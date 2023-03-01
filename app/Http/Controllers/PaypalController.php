<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Utility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use App\Models\Workspace;
class PaypalController extends Controller
{
    private $_api_context;
    public function setApiContext()
     {  
         
        $user = Auth::user();
   
        $paypal_conf = config('paypal');
 
        $paypal_conf['settings']['mode'] = $user->currentWorkspace->paypal_mode;

        $paypal_conf['client_id']        = $user->currentWorkspace->paypal_client_id;
        $paypal_conf['secret_key']       = $user->currentWorkspace->paypal_secret_key;

        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'], $paypal_conf['secret_key']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function withOutAuthsetApiContext($workspace_id)
    { 

        $workspace = Workspace::find($workspace_id);
        $paypal_conf = config('paypal');
        $paypal_conf['settings']['mode'] = $workspace->paypal_mode;
        $paypal_conf['client_id']        = $workspace->paypal_client_id;
        $paypal_conf['secret_key']       = $workspace->paypal_secret_key;

        $this->_api_context = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'], $paypal_conf['secret_key']
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }


    public function clientPayWithPaypal(Request $request, $slug, $invoice_id)
    {
         $user = Auth::user();
        
        

        $invoice = Invoice::find($invoice_id);

        $workspace = Workspace::find($invoice->workspace_id);
       

       $get_amount = $request->amount;

        $request->validate(['amount' => 'required|numeric|min:0']);

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if($currentWorkspace)
        {
            
            if($invoice)
            {
                if($get_amount > $invoice->getDueAmount())
                {
                    return redirect()->back()->with('error', __('Invalid amount.'));
                }
                else
                {
                    if(Auth::check()){
                        self::setApiContext($invoice_id);
                    }else{
                        self::withOutAuthsetApiContext($invoice->workspace_id);
                    }
                   

                    $name = $currentWorkspace->name . " - " . Utility::invoiceNumberFormat($invoice->invoice_id);

                    $payer = new Payer();
                    $payer->setPaymentMethod('paypal');

                    $item_1 = new Item();
                    $item_1->setName($name)->setCurrency($currentWorkspace->currency_code)->setQuantity(1)->setPrice($get_amount);

                    $item_list = new ItemList();
                    $item_list->setItems([$item_1]);

                    $amount = new Amount();
                    $amount->setCurrency($currentWorkspace->currency_code)->setTotal($get_amount);

                    $transaction = new Transaction();
                    $transaction->setAmount($amount)->setItemList($item_list)->setDescription($name);

                    $redirect_urls = new RedirectUrls();
                    $redirect_urls->setReturnUrl(
                        route(
                            'client.get.payment.status', [
                            $currentWorkspace->slug,
                            $invoice->id,
                        ]
                        )
                    )->setCancelUrl(
                        route(
                            'client.get.payment.status', [
                            $currentWorkspace->slug,
                            $invoice->id,
                        ]
                        )
                    );

                    $payment = new Payment();
                    $payment->setIntent('Sale')->setPayer($payer)->setRedirectUrls($redirect_urls)->setTransactions([$transaction]);

                    try
                    {

                        $payment->create($this->_api_context);
                    }
                    catch(\PayPal\Exception\PayPalConnectionException $ex) //PPConnectionException
                    {
                        if(\Config::get('app.debug'))
                        {
                            return redirect()->route(
                                'client.invoices.show', [
                                $currentWorkspace->slug,
                                $invoice_id,
                            ]
                            )->with('error', __('Connection timeout'));
                        }
                        else
                        {
                            return redirect()->route(
                                'client.invoices.show', [
                                $currentWorkspace->slug,
                                $invoice_id,
                            ]
                            )->with('error', __('Some error occur, sorry for inconvenient'));
                        }
                    }
                    foreach($payment->getLinks() as $link)
                    {
                        if($link->getRel() == 'approval_url')
                        {
                            $redirect_url = $link->getHref();
                            break;
                        }
                    }
                    Session::put('paypal_payment_id', $payment->getId());
                    if(isset($redirect_url))
                    {
                        return Redirect::away($redirect_url);
                    }

                    return redirect()->route(
                        'client.invoices.show', [
                        $currentWorkspace->slug,
                        $invoice_id,
                    ]
                    )->with('error', __('Unknown error occurred'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }

    public function clientGetPaymentStatus(Request $request, $slug, $invoice_id)
    {
         // $user = Auth::user();

        $invoice = Invoice::find($invoice_id);
        $user = User::where('id',$invoice->workspace_id)->first();

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        if($currentWorkspace && $invoice)
        {
            if(Auth::check()){
                        self::setApiContext($invoice_id);
                    }else{
                        self::withOutAuthsetApiContext($invoice->workspace_id);
                    }
                   

            $payment_id = Session::get('paypal_payment_id');

            Session::forget('paypal_payment_id');

            if(empty($request->PayerID || empty($request->token)))
            {
                return redirect()->route(
                    'client.invoices.show', [
                    $currentWorkspace->slug,
                    $invoice_id,
                ]
                )->with('error', __('Payment failed'));
            }

            $payment = Payment::get($payment_id, $this->_api_context);

            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);

             try
            {
                $result = $payment->execute($execution, $this->_api_context)->toArray();

                $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

                $status = ucwords(str_replace('_', ' ', $result['state']));

                if($result['state'] == 'approved')
                {
                    $amount = $result['transactions'][0]['amount']['total'];
                }
                else
                {
                    $amount = isset($result['transactions'][0]['amount']['total']) ? $result['transactions'][0]['amount']['total'] : '0.00';
                }

                $invoice_payment                 = new InvoicePayment();
                $invoice_payment->order_id       = $order_id;
                $invoice_payment->invoice_id     = $invoice->id;
                $invoice_payment->currency       = $currentWorkspace->currency_code;
                $invoice_payment->amount         = $amount;
                $invoice_payment->payment_type   = 'PAYPAL';
                $invoice_payment->receipt        = '';
                $invoice_payment->client_id      = $user->id;
                $invoice_payment->txn_id         = $payment_id;
                $invoice_payment->payment_status = $result['state'];

                $invoice_payment->save();

                if($result['state'] == 'approved')
                {
                    if(($invoice->getDueAmount() - $invoice_payment->amount) == 0)
                    {
                        $invoice->status = 'paid';
                        $invoice->save();
                    }

                   
 
                                    return redirect()->route( 'client.invoices.show', [ $currentWorkspace->slug,$invoice_id,
                                    ])->with('success', __('Payment added Successfully'));
                             

                   
                }
                else
                {

                                     return redirect()->route( 'client.invoices.show', [ $currentWorkspace->slug,$invoice_id,
                                       ] )->with('error', __('Transaction has been ' . $status));
                              
                   
                }

            }
             catch(\Exception $e)
            {

               
                                     return redirect()->route('client.invoices.show', [$currentWorkspace->slug, $invoice_id,  ] )->with('error', __('Transaction has been failed!'));
                              
                
            }
        }
        else
        {        
       return redirect()->back()->with('error', __('Permission denied.'));                                       
        }
    }
}
