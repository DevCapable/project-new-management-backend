<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PaytmWallet;

class PaytmPaymentController extends Controller
{
    public $currancy;

    public function setPaymentDetail()
    {
        $user            = Auth::user();
        $payment_setting = Utility::getPaymentSetting($user->currentWorkspace->id);
        $this->currancy  = (isset($user->currentWorkspace->currency_code)) ? $user->currentWorkspace->currency_code : 'USD';

        config(
            [
                'services.paytm-wallet.env' => isset($payment_setting['paytm_mode']) ? $payment_setting['paytm_mode'] : '',
                'services.paytm-wallet.merchant_id' => isset($payment_setting['paytm_merchant_id']) ? $payment_setting['paytm_merchant_id'] : '',
                'services.paytm-wallet.merchant_key' => isset($payment_setting['paytm_merchant_key']) ? $payment_setting['paytm_merchant_key'] : '',
                'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                'services.paytm-wallet.channel' => 'WEB',
                'services.paytm-wallet.industry_type' => isset($payment_setting['paytm_industry_type']) ? $payment_setting['paytm_industry_type'] : '',
            ]
        );
    }

    public function invoicePayWithPaytm(Request $request, $slug, $invoice_id)
    {
        $this->setPaymentDetail();

        $validatorArray = [
            'amount' => 'required',
            'mobile' => 'required',
        ];
        $validator      = Validator::make(
            $request->all(), $validatorArray
        )->setAttributeNames(
            [
                'mobile' => 'Mobile No.',
            ]
        );

        if($validator->fails())
        {
            return redirect()->back()->with('error', __($validator->errors()->first()));
        }

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $invoice = Invoice::find($request->invoice_id);

        if($invoice->getDueAmount() < $request->amount)
        {
            return redirect()->route(
                'client.invoices.show', [
                                          $slug,
                                          $invoice_id,
                                      ]
            )->with('error', __('Invalid amount.'));
        }

        $call_back = route(
            'client.invoice.paytm', [
                                      $slug,
                                      encrypt($invoice->id),
                                  ]
        );
        $payment   = PaytmWallet::with('receive');

        $payment->prepare(
            [
                'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                'user' => Auth::user()->id,
                'mobile_number' => $request->mobile,
                'email' => Auth::user()->email,
                'amount' => $request->amount,
                'invoice_id' => $invoice->id,
                'callback_url' => $call_back,
            ]
        );

        return $payment->receive();
    }

    public function getInvoicePaymentStatus($slug, $invoice_id, Request $request)
    {
        $this->setPaymentDetail();

        if(!empty($invoice_id))
        {
            $user             = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);

            $invoice_id = decrypt($invoice_id);
            $invoice    = Invoice::find($invoice_id);
            if($invoice)
            {
                try
                {
                    $transaction = PaytmWallet::with('receive');
                    $response    = $transaction->response();

                    if($transaction->isSuccessful())
                    {
                        $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->order_id       = $order_id;
                        $invoice_payment->invoice_id     = $invoice->id;
                        $invoice_payment->currency       = $currentWorkspace->currency_code;
                        $invoice_payment->amount         = isset($request->TXNAMOUNT) ? $request->TXNAMOUNT : 0;
                        $invoice_payment->payment_type   = 'Paytm';
                        $invoice_payment->receipt        = '';
                        $invoice_payment->client_id      = $user->id;
                        $invoice_payment->txn_id         = '';
                        $invoice_payment->payment_status = 'succeeded';
                        $invoice_payment->save();

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
            return redirect()->route(
                'client.invoices.show', [
                                          $slug,
                                          $invoice_id,
                                      ]
            )->with('error', __('Invoice not found.'));
        }
    }
}
