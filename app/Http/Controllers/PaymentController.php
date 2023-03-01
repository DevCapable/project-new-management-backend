<?php

namespace App\Http\Controllers;

use App\Models\AppointmentInvoicePayments;
use App\Models\Client;
use App\Models\InvoicePayment;
use App\Models\RegistrationPayment;
use App\Models\Utility;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\Student;

// Student Model
use App\Payment;

// Payment Model
use App\User;
use Unicodeveloper\Paystack\Facades\Paystack;

// User model
class PaymentController extends Controller
{

    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway(Request $request)
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return Redirect::back()->withMessage(['msg' => 'The paystack token has expired. Please refresh the page and try again.', 'type' => 'error']);
        }
    }

    /**
     * Obtain Paystack payment information
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGatewayCallback()
    {
        try {

            $paymentDetails = Paystack::getPaymentData();

            $status = $paymentDetails['data']['status']; // Getting the status of the transaction
            $currentWorkspace = Utility::getWorkspaceBySlug($slug = 'default');
            if (isset($paymentDetails['data']['metadata']['payment_type']) && $paymentDetails['data']['metadata']['payment_type'] == 'appointment') {
                $this->getAppointmentPay($paymentDetails, $currentWorkspace);
//               dd($create_appointment);
//               if ($create_appointment){
//                return redirect()->to('/client/appointments/{$currentWorkspace->slug}')->with('success', 'Congratulations you can now access your portal!');

                $chek_info = AppointmentInvoicePayments::where('user_id',$paymentDetails['data']['metadata']['user_id'])->first();
                if (!$chek_info) {
                    getAppointmentPayment($paymentDetails, $currentWorkspace);
                    return redirect()->to('/client/appointments/{$currentWorkspace->slug}')->with('success', 'You have successfully obtained appointment tokens! you can now proceed');
                }else if($chek_info->chance == 0){
                    $chek_info->update(['chance' => 3]);
                    return redirect()->to('/client/appointments/{$currentWorkspace->slug}')->with('success', 'You have successfully obtained appointment tokens! you can now proceed');
                }else {
                    $chek_info->update(['chance' => 3]);
                    return redirect()->to('/client/appointments/{$currentWorkspace->slug}')->with('success', 'Your already have tokens created please proceed on new appointment!');
                }

//               }
            } else {
//                if ($status == "success") { //Checking to Ensure the transaction was succesful
                    $payment = new RegistrationPayment();
                    $payment->user_id = $paymentDetails['data']['metadata']['user_id'];
                    $payment->email = $paymentDetails['data']['metadata']['email'];
                    $payment->reference = $paymentDetails['data']['reference'];
                    $payment->ip_address = $paymentDetails['data']['ip_address'];
                    $payment->amount = $paymentDetails['data']['amount'];
                    $payment->payment_date = $paymentDetails['data']['created_at'];
                    $payment->channel = $paymentDetails['data']['channel'];
                    $payment->currency = $paymentDetails['data']['currency'];
                    $payment->appointment_chance = 3;
                    $create = $payment->save();
                    if ($create) {
                        return redirect()->to('/client/login')->with('success', 'Congratulations you can now access your portal!');
                    }
                    return redirect()->to('/client/login')->with('error', 'Oops sorry your payment was not successful');
            }
//            if ($paymentDetails['data']['metadata']['payment_type'] == 'appointment') {
            return view('clients.appointments.create', compact('currentWorkspace'));
//            }else{

//            }
        } catch (\Exception $e) {
            throw  $e;
        }
    }

    function getAppointmentPay($response, $currentWorkspace)
    {
        $payment_info = new InvoicePayment();
        $payment_info->order_id = generate_code('APPOINT');
        $payment_info->invoice_id = generate_code('APPOINT');

        $payment_info->txn_id = $response['data']['reference'];
        $payment_info->amount = $response['data']['amount'];
        $payment_info->payment_type = 'Appointment';
        $payment_info->payment_status = 'paid';
        $payment_info->currency = $response['data']['currency'];
        $payment_info->receipt = 'Appointment payment with card';
        $payment_info->client_id = $response['data']['metadata']['user_id'];
        $payment_info->save();


    }

//    flutter
    public function verifyPay(Request $response)
    {
        if ($response['data']['status'] === "successful") {
            $payment = new RegistrationPayment();
            $payment->user_id = $response->user_id;
            $payment->email = $response['data']['customer']['email'];
            $payment->reference = $response['data']['tx_ref'];
            $payment->ip_address = $response['data']['transaction_id'];
            $payment->amount = $response['data']['amount'];
            $payment->payment_date = $response['data']['created_at'];
            $payment->channel = $response['data']['flw_ref'];
            $payment->currency = $response['data']['currency'];
            $payment->save();
            return true;
//            return redirect()->to('/client/login')->with('success', 'Congratulations you can now access your portal!');
        } else {
            // Inform the customer their payment was unsuccessful
            return false;
            return redirect()->to('/client/login')->with('error', 'Oops sorry your payment was not successful');
        }
    }
}
