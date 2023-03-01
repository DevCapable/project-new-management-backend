<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RazorpayPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;
    public $currancy;

    public function setPaymentDetail()
    {
        $user             = Auth::user();
       
        $payment_setting  = Utility::getPaymentSetting($user->currentWorkspace->id);
        $this->currancy   = (isset($user->currentWorkspace->currency_code)) ? $user->currentWorkspace->currency_code : 'USD';
        $this->secret_key = isset($payment_setting['razorpay_secret_key']) ? $payment_setting['razorpay_secret_key'] : '';
        $this->public_key = isset($payment_setting['razorpay_public_key']) ? $payment_setting['razorpay_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_razorpay_enabled']) ? $payment_setting['is_razorpay_enabled'] : 'off';
    }

    public function invoicePayWithRazorpay(Request $request)
    {
        $validatorArray = [
            'amount' => 'required',
        ];
        $validator      = Validator::make(
            $request->all(), $validatorArray
        );

        if($validator->fails())
        {
            return response()->json(
                [
                    'message' => $validator->errors()->first(),
                ], 401
            );
        }

        $this->setPaymentDetail();

        $invoice = Invoice::find($request->invoice_id);
        if($invoice->getDueAmount() < $request->amount)
        {
            return response()->json(
                [
                    'message' => __('Invalid amount.'),
                ], 401
            );
        }

        $res_data['email']       = Auth::user()->email;
        $res_data['total_price'] = $request->amount;
        $res_data['currency']    = $this->currancy;
        $res_data['flag']        = 1;
        $res_data['invoice_id']  = $invoice->id;
        $request->session()->put('invoice_data', $res_data);
        $this->pay_amount = $request->amount;

        return $res_data;
    }

    public function getInvoicePaymentStatus($slug, $pay_id, $invoice_id, Request $request)
    {
        $this->setPaymentDetail();

        if(!empty($invoice_id) && !empty($pay_id))
        {
            $user             = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);

            $invoice_id   = decrypt($invoice_id);
            $invoice      = Invoice::find($invoice_id);
            $invoice_data = $request->session()->get('invoice_data');

            if($invoice && !empty($invoice_data))
            {
                try
                {
                    $ch = curl_init('https://api.razorpay.com/v1/payments/' . $pay_id . '');
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch, CURLOPT_USERPWD, $this->public_key . ':' . $this->secret_key); // Input your Razorpay Key Id and Secret Id here
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = json_decode(curl_exec($ch));

                    if($response->status == 'authorized')
                    {
                        $order_id                        = strtoupper(str_replace('.', '', uniqid('', true)));
                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->order_id       = $order_id;
                        $invoice_payment->invoice_id     = $invoice->id;
                        $invoice_payment->currency       = $currentWorkspace->currency_code;
                        $invoice_payment->amount         = isset($invoice_data['total_price']) ? $invoice_data['total_price'] : 0;
                        $invoice_payment->payment_type   = 'Razorpay';
                        $invoice_payment->receipt        = '';
                        $invoice_payment->client_id      = $user->id;
                        $invoice_payment->txn_id         = $pay_id;
                        $invoice_payment->payment_status = 'succeeded';
                        $invoice_payment->save();

                        if(($invoice->getDueAmount() - $invoice_payment->amount) == 0)
                        {
                            $invoice->status = 'paid';
                            $invoice->save();
                        }

                        $request->session()->forget('invoice_data');

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
                        )->with('error', __('Transaction has been failed.'));
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
