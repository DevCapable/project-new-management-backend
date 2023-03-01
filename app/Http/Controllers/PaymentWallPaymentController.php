<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Utility; 
use App\Models\User;
use App\Models\Plan;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaymentWallPaymentController extends Controller
{

    public function index(Request $request){
        $data = $request->all();
    
        $admin_payment_setting = Utility::getAdminPaymentSetting();

        $plan_id= Crypt::decrypt($data['plan_id']);
        $plandata=Plan::find($plan_id);


        if($request->paymentwall_payment_frequency == 'annual'){
            $plandatas['price']= $plandata->annual_price;
        }

        if($request->paymentwall_payment_frequency == 'monthly'){
            $plandatas['price']= $plandata->monthly_price;
        }

        $plandatas['name']= $plandata->name;
      

        return view('plans.paymentwall',compact('data','admin_payment_setting','plandatas'));
    }

    public function paymenterror(Request $request,$flag){
        if($flag == 1){
            return redirect()->route("plans.index")->with('error', __('Transaction has been Successfull! '));
        }else{
                return redirect()->route("plans.index")->with('error', __('Transaction has been failed! '));
        }
        //return redirect()->route('plans.index')->with('error', __('Transaction has been failed.'));
    }

    public function planPayWithPaymentwall(Request $request,$plan_id)
    {
        //$res['msg'] = __("Plan successfully upgraded.");
        //return $res;
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);
        $plan    = Plan::find($planID);
        $user    = Auth::user();
        $result  = array();

        if(\Auth::user()->type == 'company')
        {
            $payment_setting = Utility::getAdminPaymentSetting();
        }
        else
        {
            $payment_setting = Utility::getCompanyPaymentSetting();
        }
        
        if($plan)
        {
            try
            {
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                \Paymentwall_Config::getInstance()->set(array(
                    'private_key' => $payment_setting['paymentwall_private_key']
                ));

                $parameters = $_POST;

                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => $plan->price,
                    'currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );

                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(),true);
                $response = $charge->getPublicData();
                if ($charge->isSuccessful() AND empty($responseData['secure'])) {
                        if ($charge->isCaptured()) {
                            $price = $plan->price;
                        if(isset($request->coupon) && !empty($request->coupon))
                        {
                            $request->coupon = trim($request->coupon);
                            $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                            if(!empty($coupons))
                            {
                                $usedCoupun             = $coupons->used_coupon();
                                $discount_value         = ($price / 100) * $coupons->discount;
                                $plan->discounted_price = $price - $discount_value;
                                $coupons_id             = $coupons->id;
                                if($usedCoupun >= $coupons->limit)
                                {
                                    return redirect()->back()->with('error', __('This coupon code has expired.'));
                                }
                                $price = $price - $discount_value;
                            }
                            else
                            {
                                return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                            }
                        }
                        $user->plan = $plan->id;
                        $user->save();

                        $assignPlan = $authuser->assignPlan($plan->id);
                        $orderID = time();
                        PlanOrder::create(
                            [
                                'order_id' => $orderID,
                                'name' => null,
                                'email' => null,
                                'card_number' => null,
                                'card_exp_month' => null,
                                'card_exp_year' => null,
                                'plan_name' => $plan->name,
                                'plan_id' => $plan->id,
                                'price' => $plan->price == null ? 0 : $plan->price,
                                'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                                'txn_id' => '',
                                'payment_type' => __('Paymentwall'),
                                'payment_status' => 'succeeded',
                                'receipt' => null,
                                'user_id' => $user->id,
                            ]
                        );
                        $assignPlan = $user->assignPlan($plan->id);
                        if($assignPlan['is_success'])
                        {
                            $res['flag'] = 1;
                            return $res;
                        }
                        else
                        {
                            $res['flag'] = 2;
                            return $res;
                        }
                        } 
                    elseif($charge->isUnderReview()) {
                        $res['flag'] = 2;
                        return $res;
                    }
                }else {
                   $res['flag'] = 2;
                    return $res;
                }

               
            }
            catch(\Exception $e)
            {
                $res['flag'] = 2;
                    return $res;
            }
        }
        else
        {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }

    }
    public function invoiceindex(Request $request,$slug,$invoice_id){
        $data = $request->all();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $payment_detail = Utility::getPaymentSetting($currentWorkspace->id);
        $invoice          = Invoice::find($invoice_id);

        return view('invoices.paymentwall',compact('data','payment_detail','invoice_id','invoice','slug'));
    }

    public function invoicePayWithPaymentwall(Request $request,$slug,$invoice_id){

        

        if(!empty($invoice_id))
        {
            $user             = Auth::user();
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            $payment_detail = Utility::getPaymentSetting($currentWorkspace->id);
            $invoice      = Invoice::find($invoice_id);
            if($invoice && !empty($invoice_data))
            {
                $result = array();
                //The parameter after verify/ is the transaction reference to be verified
                
                \Paymentwall_Config::getInstance()->set(array(
                    'private_key' => $payment_detail['paymentwall_private_key']
                ));

                $parameters = $_POST;
                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => isset($request->amount) ? $request->amount : 0,
                    'currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );

                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(),true);
                $response = $charge->getPublicData();

                if ($charge->isSuccessful() AND empty($responseData['secure']))
                {
                    if($charge->isCaptured()) {
                        $order_id = strtoupper(str_replace('.', '', uniqid('', true)));

                        $invoice_payment                 = new InvoicePayment();
                        $invoice_payment->order_id       = $order_id;
                        $invoice_payment->invoice_id     = $invoice->id;
                        $invoice_payment->currency       = $currentWorkspace->currency_code;
                        $invoice_payment->amount         = isset($request->amount) ? $request->amount : 0;
                        $invoice_payment->payment_type   = 'Paymentwall';
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

                        $res['flag'] = 1;
                        $res['slug'] = $slug;
                        $res['invoice_id'] = $invoice_id;
                        return $res;
                        /*return redirect()->route(
                            'client.invoices.show', [
                                                      $slug,
                                                      $invoice_id,
                                                  ]
                        )->with('success', __('Invoice paid Successfully!'));*/
                    }
                    elseif($charge->isUnderReview()) {
                        $res['flag'] = 2;
                        $res['slug'] = $slug;
                        $res['invoice_id'] = $invoice_id;
                        return $res;
                    }else {
                        $res['flag'] = 2;
                        $res['slug'] = $slug;
                        $res['invoice_id'] = $invoice_id;
                        return $res;
                    }    
                }
                else
                {
                    $res['flag'] = 2;
                    $res['slug'] = $slug;
                    $res['invoice_id'] = $invoice_id;
                    return $res;
                }
            }
            else
            {
                $res['flag'] = 2;
                $res['slug'] = $slug;
                $res['invoice_id'] = $invoice_id;
                return $res;
            }
        }
        else
        {
            $res['flag'] = 2;
            $res['slug'] = $slug;
            $res['invoice_id'] = $invoice_id;
            return $res;
        }
    }

    public function orderpaymenterror(Request $request,$flag,$slug,$invoice_id){
        if($flag == 1){
            return redirect()->route("client.invoices.show",[$slug,$invoice_id])->with('error', __('Transaction has been Successfull! '));
        }else{
                return redirect()->route("client.invoices.show",[$slug,$invoice_id])->with('error', __('Transaction has been failed! '));
        }
    }

}
