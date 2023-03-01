<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Payment;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Obydul\LaraSkrill\SkrillClient;
use Obydul\LaraSkrill\SkrillRequest;

class SkrillPaymentController extends Controller
{
    public $email;
    public $is_enabled;
    public $currancy;

    public function setPaymentDetail()
    {
        $user             = Auth::user();
        $payment_setting  = Utility::getPaymentSetting($user->currentWorkspace->id);
        $this->currancy   = (isset($user->currentWorkspace->currency_code)) ? $user->currentWorkspace->currency_code : 'USD';
        $this->email      = isset($payment_setting['skrill_email']) ? $payment_setting['skrill_email'] : '';
        $this->is_enabled = isset($payment_setting['is_skrill_enabled']) ? $payment_setting['is_skrill_enabled'] : 'off';
    }

    public function invoicePayWithSkrill(Request $request, $slug, $invoice_id)
    {
        $this->setPaymentDetail();

        $validatorArray = [
            'amount' => 'required',
        ];
        $validator      = Validator::make(
            $request->all(), $validatorArray
        );

        if($validator->fails())
        {
            return redirect()->back()->with('error', __($validator->errors()->first()));
        }

        $invoice = Invoice::find($invoice_id);
        if($invoice->getDueAmount() < $request->amount)
        {
            return redirect()->route(
                'client.invoices.show', [
                                          $slug,
                                          $invoice_id,
                                      ]
            )->with('error', __('Invalid amount.'));
        }

        $tran_id             = md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id');
        $skill               = new SkrillRequest();
        $skill->pay_to_email = $this->email;
        $skill->return_url   = route(
            'client.invoice.skrill', [
                                       $slug,
                                       encrypt($invoice->id),
                                       'tansaction_id=' . MD5($tran_id),
                                   ]
        );
        $skill->cancel_url   = route(
            'client.invoice.skrill', [
                                       $slug,
                                       encrypt($invoice->id),
                                   ]
        );

        // create object instance of SkrillRequest
        $skill->transaction_id  = MD5($tran_id); // generate transaction id
        $skill->amount          = $request->amount;
        $skill->currency        = $this->currancy;
        $skill->language        = 'EN';
        $skill->prepare_only    = '1';
        $skill->merchant_fields = 'site_name, customer_email';
        $skill->site_name       = Auth::user()->name;
        $skill->customer_email  = Auth::user()->email;

        // create object instance of SkrillClient
        $client = new SkrillClient($skill);
        $sid    = $client->generateSID(); //return SESSION ID

        // handle error
        $jsonSID = json_decode($sid);
        if($jsonSID != null && $jsonSID->code == "BAD_REQUEST")
        {
            return redirect()->back()->with('error', $jsonSID->message);
        }

        // do the payment
        $redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url
        if($tran_id)
        {
            $data = [
                'amount' => $request->amount,
                'trans_id' => MD5($request['transaction_id']),
                'currency' => $this->currancy,
            ];
            session()->put('skrill_data', $data);
        }

        return redirect($redirectUrl);
    }

    public function getInvoicePaymentStatus($slug, $invoice_id, Request $request)
    {
        $this->setPaymentDetail();

        if(!empty($invoice_id))
        {
            $invoice_id = decrypt($invoice_id);
            $invoice    = Invoice::find($invoice_id);
            if($invoice)
            {
                $user             = Auth::user();
                $currentWorkspace = Utility::getWorkspaceBySlug($slug);

                try
                {
                    if(session()->has('skrill_data') && $request->has('tansaction_id'))
                    {
                        $get_data = session()->get('skrill_data');
                        $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->order_id       = $order_id;
                        $invoice_payment->invoice_id     = $invoice->id;
                        $invoice_payment->currency       = $currentWorkspace->currency_code;
                        $invoice_payment->amount         = isset($get_data['amount']) ? $get_data['amount'] : 0;
                        $invoice_payment->payment_type   = 'Skrill';
                        $invoice_payment->receipt        = '';
                        $invoice_payment->client_id      = $user->id;
                        $invoice_payment->txn_id         = '';
                        $invoice_payment->payment_status = 'succeeded';
                        $invoice_payment->save();

                        if(($invoice->getDueAmount() - $invoice_payment->amount) == 0)
                        {
                            $invoice->status = 'paid';
                            $invoice->save();
                        }

                        session()->forget('skrill_data');

                        return redirect()->route(
                            'client.invoices.show', [
                                                      $slug,
                                                      $invoice_id,
                                                  ]
                        )->with('success', __('Invoice paid Successfully!'));
                    }
                    else
                    {
                        return redirect()->route(
                            'client.invoices.show', [
                                                      $slug,
                                                      $invoice_id,
                                                  ]
                        )->with('error', __('Transaction fail'));
                    }
                }
                catch(\Exception $e)
                {
                    return redirect()->route(
                        'client.invoices.show', [
                                                  $slug,
                                                  $invoice_id,
                                              ]
                    )->with('error', __('Something went wrong.'));
                }
            }
            else
            {
                return redirect()->route(
                    'client.invoices.show', [
                                              $slug,
                                              $invoice_id,
                                          ]
                )->with('error', __('Invoice not found.'));
            }
        }
        else
        {
            return redirect()->route('client.invoices.index', $slug)->with('error', __('Invoice not found.'));
        }
    }
}
