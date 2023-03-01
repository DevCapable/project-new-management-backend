<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MolliePaymentController extends Controller
{
    public $api_key;
    public $profile_id;
    public $partner_id;
    public $is_enabled;
    public $currancy;

    public function setPaymentDetail()
    {
        $user = Auth::user();
        if($user->getGuard() == 'client')
        {
            $payment_setting = Utility::getPaymentSetting($user->currentWorkspace->id);
            $this->currancy  = (isset($user->currentWorkspace->currency_code)) ? $user->currentWorkspace->currency_code : 'USD';
        }
        else
        {
            $payment_setting = Utility::getAdminPaymentSetting();
            $this->currancy  = !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD';
        }

        $this->api_key    = isset($payment_setting['mollie_api_key']) ? $payment_setting['mollie_api_key'] : '';
        $this->profile_id = isset($payment_setting['mollie_profile_id']) ? $payment_setting['mollie_profile_id'] : '';
        $this->partner_id = isset($payment_setting['mollie_partner_id']) ? $payment_setting['mollie_partner_id'] : '';
        $this->is_enabled = isset($payment_setting['is_mollie_enabled']) ? $payment_setting['is_mollie_enabled'] : 'off';
    }

    public function invoicePayWithMollie(Request $request, $slug, $invoice_id)
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

        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $invoice          = Invoice::find($invoice_id);

        if($invoice->getDueAmount() < $request->amount)
        {
            return redirect()->route(
                'invoices.show', [
                                   $slug,
                                   $invoice_id,
                               ]
            )->with('error', __('Invalid amount.'));
        }

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($this->api_key);

        $payment = $mollie->payments->create(
            [
                "amount" => [
                    "currency" => $this->currancy,
                    "value" => number_format($request->amount, 2),
                ],
                "description" => "payment for invoice",
                "redirectUrl" => route(
                    'client.invoice.mollie', [
                                               $slug,
                                               encrypt($invoice_id),
                                           ]
                ),
            ]
        );

        session()->put('mollie_payment_id', $payment->id);

        return redirect($payment->getCheckoutUrl())->with('payment_id', $payment->id);
    }

    public function getInvoicePaymentStatus($slug, $invoice_id, Request $request)
    {
        $this->setPaymentDetail();

        if(!empty($invoice_id))
        {
            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($this->api_key);

            $invoice_id = decrypt($invoice_id);
            $invoice    = Invoice::find($invoice_id);
            if($invoice && session()->has('mollie_payment_id'))
            {
                $user             = Auth::user();
                $currentWorkspace = Utility::getWorkspaceBySlug($slug);

                try
                {
                    $payment = $mollie->payments->get(session()->get('mollie_payment_id'));

                    if($payment->isPaid())
                    {
                        $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->order_id       = $order_id;
                        $invoice_payment->invoice_id     = $invoice->id;
                        $invoice_payment->currency       = $currentWorkspace->currency_code;
                        $invoice_payment->amount         = isset($payment->amount->value) ? $payment->amount->value : 0;
                        $invoice_payment->payment_type   = 'Mollie';
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
