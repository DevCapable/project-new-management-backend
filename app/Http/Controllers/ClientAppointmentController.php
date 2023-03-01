<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\appointmentFormRequest;
use App\Models\AppointmentInvoicePayments;
use App\Models\ClientAppointment;
use App\Models\InvoicePayment;
use App\Models\PaymentLists;
use App\Models\Project;
use App\Models\RegistrationPayment;
use App\Models\Stage;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Unicodeveloper\Paystack\Facades\Paystack;

class ClientAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = '')
    {
        $objUser = Auth::user();
        $currentWorkspace = $this->_getworkspace($slug);


        if ($objUser->getGuard() == 'client') {

            $projects = ClientAppointment::select('client_appointments.*')->where('client_appointments.client_id', '=', $objUser->id)->get();
        } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id)->get();
        }

        $stages = Stage::where('workspace_id', '=', $currentWorkspace->id)->orderBy('order')->get();
        $users = User::select('users.*')->join('user_workspaces', 'user_workspaces.user_id', '=', 'users.id')->where('user_workspaces.workspace_id', '=', $currentWorkspace->id)->get();


        return view('clients.appointments.index', compact('currentWorkspace', 'stages', 'users', 'projects'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create($slug)
    {

        $objUser = $client = Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        $check_chance_left = RegistrationPayment::where('user_id', $objUser->id)->first();
        $warning = 'Opps! you cant request appointment this time, not until you subscribe again';

        $info = 'Note that you are eligible to create appointment three times free, while you are required to subscribe
                subsequently thanks, please! proceed!';
        $payment_data = AppointmentInvoicePayments::where('user_id', $objUser->id)->first();
        if ($payment_data === null) {
            $notice = ' You have 0 chance appointment tokens free,  and' . $check_chance_left->appointment_chance . 'chance(s) left';
        } else {
            $notice = ' You have ' . $check_chance_left->appointment_chance . ' chance(s) appointment tokens free,  and  ' . $payment_data->chance . ' chance(s) left';
        }
        return view('clients.appointments.create', compact('currentWorkspace', 'payment_data', 'check_chance_left', 'info', 'warning', 'notice'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(appointmentFormRequest $request, $slug)
    {
        $currentWorkspace = $this->_getworkspace($slug);

        $objUser = Auth::user();
        $appointment = new ClientAppointment();

        $appointment->client_id = $objUser->id;
        $appointment->date_schedule = $request->date_schedule;
        $appointment->title = $request->title;
        $appointment->description = $request->description;
        if ($request->action == 'submit') {
            $msg = 'Appointment has been scheduled successfully';
            $appointment->status = get_appointment_status(1);
            $check = $this->_checkIfHasChance();
            $appointment->is_zoom_link = 1;
            if (!$check) {
                $msg = 'You dont have any tokens to submit this appointment please subscribe';
                return redirect()->back()->with('error', $msg);
            } else {
                $appointment->save();
                return redirect()->back()->with('success', $msg);
            }
        } else {
            $msg = 'Appointment has been saved successfully';
            $appointment->status = get_appointment_status(3);
            $appointment->save();
            return redirect()->back()->with('success', $msg);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($slug, $id)
    {
        $currentWorkspace = $this->_getworkspace($slug);
        $appointment = ClientAppointment::where('id', $id)->first();
        return view('clients.appointments.show', compact('currentWorkspace', 'appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($slug, $id)
    {
        $danger = 'Please always confirm the date before submission thanks';
        $currentWorkspace = $this->_getworkspace($slug);
        $appointment = ClientAppointment::where('id', $id)->first();
        return view('clients.appointments.edit', compact('currentWorkspace', 'appointment', 'danger'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(appointmentFormRequest $request, $slug, $id)
    {
        $objUser = Auth::user();
        $appointment = ClientAppointment::find($id);
        if ($request->action === 'submit') {
            $msg = 'Appointment has been scheduled successfully';

            $appointment->status = get_appointment_status(1);
            $check = $this->_checkIfHasChance();
            $appointment->is_zoom_link = 1;
            if (!$check) {
                $msg = 'You dont have any tokens to submit this appointment please subscribe';
                return redirect()->back()->with('error', $msg);
            } else {
                $appointment->update($request->all());
                return redirect()->back()->with('success', $msg);
            }
        } else {
            $msg = 'Appointment successfully updated';
            $appointment->status = get_appointment_status(3);
            $new_data = $appointment->update($request->all());
            if ($new_data) {
                return redirect()->back()->with('success', $msg);
            }
            return redirect()->back()->with('error', 'Something went wrong');
        }
//        $new_data = $appointment->update($request->all());
//        if ($new_data) {
//            return redirect()->back()->with('success', $msg);
//        }
//        return redirect()->back()->with('error', 'Something went wrong');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($slug, $appointment_id)
    {

        $objUser = Auth::user();
        $appointment = ClientAppointment::find($appointment_id);

        if ($appointment->client_id == $objUser->id) {
//            ProjectFile::where('appointment_id', '=', $appointment_id)->delete();
            $appointment->delete();

            return redirect()->route('client-appointment-index', $slug)->with('success', __('Appointment Deleted Successfully!'));
        } else {
            return redirect()->route('client-appointment-index', $slug)->with('error', __("You can't Delete Appointment!"));
        }
    }

    public function ajax_data(Request $request, $slug)
    {

        $objUser = Auth::user();
        $currentWorkspace = $this->_getworkspace($slug);
        if ($objUser->getGuard() == 'client') {
            $projects = ClientAppointment::where('client_id', '=', $objUser->id);
        } else {
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', $currentWorkspace->id);
        }
        if ($request->all_users) {
            unset($projects);
            $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->where('user_projects.user_id', '=', $request->all_users)->where('projects.workspace', '=', $currentWorkspace->id);
        }

        if ($request->status) {
            $projects->where('status', '=', $request->status);
        }

        if ($request->date_schedule) {

            $projects->where('date_schedule', 'LIKE', '%' . $request->date_schedule . '%')->get();
        }

        if ($request->end_date) {

            $projects->where('end_date', '=', $request->end_date);

        }

        $client_keyword = Auth::user()->getGuard() == 'client' ? 'client.' : '';
        $projects = $projects->get();
        $data = [];
        foreach ($projects as $project) {
            $tmp = [];

            $tmp['title'] = $project->title;
            $tmp['description'] = $project->description;

            if ($objUser->getGuard() == 'client') {
                if ($project->is_zoom_link == 1 && $project->zoom_link != null) {
                    $tmp['zoom_link'] = $project->zoom_link;
                } else {
                    $tmp['zoom_link'] = 'N/A';
                }
            }


            $tmp['date_schedule'] = $project->date_schedule;

            $tmp['status'] = get_appointment_status_label($project->status);

            if (Auth::user()->getGuard() != 'client') {

                $tmp['action'] = '
                <a  class="action-btn btn-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('view Appointment') . '" data-size="lg" data-title="' . __('show') . '" href="' . route(
                        $client_keyword . 'project_report.show', [
                            $currentWorkspace->slug,
                            $project->id,
                        ]
                    ) . '"><i class="ti ti-eye"></i></a>



                <a href="#" class="action-btn btn-info  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('Edit Project') . '" data-ajax-popup="true" data-size="lg" data-title="' . __('Edit') . '" data-url="' . route(
                        'projects.edit', [
                            $currentWorkspace->slug,
                            $project->id,
                        ]
                    ) . '"><i class="ti ti-pencil"></i></a>';
            } else {

                $tmp['action'] = '';
                $tmp['action'] .= '
                <a  class="action-btn btn-warning  btn btn-sm d-inline-flex align-items-center" data-toggle="popover"  title="' . __('view Appointment') . '" data-size="lg" data-title="' . __('show') . '" href="' . route(
                        'client-appointment-show', [
                            $currentWorkspace->slug,
                            $project->id,
                        ]
                    ) . '"><i class="ti ti-eye"></i></a>';
                $status = ClientAppointment::where('id', $project->id)->where('status', 'Submitted')->first();
                if ($status) {
//                    $tmp['action'] .= get_appointment_status_label('Submitted');
                } else {
                    $tmp['action'] .= '<a href="#"
                                                   class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                   data-ajax-popup="true" data-size="lg"
                                                   data-toggle="popover" title="' . __('Update') . '"
                                                   data-title="' . __('Update Appointment') . '"
                                                   data-url="' . route('client-edit-appointment', [$currentWorkspace->slug, $project->id]) . '"><i
                                                        class="ti ti-edit"></i></a>';
                }


                $tmp['action'] .= '
                <a  class="action-btn btn-danger  btn btn-sm d-inline-flex align-items-center delete-me"  data-toggle="popover"  title="' . __('Delete') . '"  Onclick="return ConfirmDelete();"' . '" data-size="lg" data-title="' . __('Delete') . '" href="' . route(

                        'client-appointment-delete', [
                            $currentWorkspace->slug,
                            $project->id,
                        ]
                    ) . '"><i class="ti ti-trash"></i></a>';

            }

            $data[] = array_values($tmp);

        }

        return response()->json(['data' => $data], 200);
    }


    function _getworkspace($slug)
    {
        return Utility::getWorkspaceBySlug($slug);

    }

    public function renewAppointment($slug)
    {
        $lang = '';
        if ($lang == '') {
            $lang = env('DEFAULT_LANG') ?? 'en';
        }

        \App::setLocale($lang);
        $currentWorkspace = $this->_getworkspace($slug);
        $payment_data = PaymentLists::where('slug', 'appointment_payment')->first();
        $client = Auth::user();
        return view('clients.appointments._client_renew_appointment_page', compact('currentWorkspace', 'payment_data', 'client', 'lang'));

    }

    public function redirectToGateway(Request $request)
    {
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();
        } catch (\Exception $e) {
            return Redirect::back()->withMessage(['msg' => 'The paystack token has expired. Please refresh the page and try again.', 'type' => 'error']);
        }
    }

    public function verifyPay(Request $response)
    {
        try {
            if ($response['data']['status'] === "successful") {

                $payment_info = new InvoicePayment();
                $payment_info->order_id = generate_code('APPOINT');
                $payment_info->invoice_id = generate_code('APPOINT');

                $payment_info->txn_id = $response['data']['tx_ref'];
                $payment_info->amount = $response['data']['amount'];
                $payment_info->payment_type = 'Appointment';
                $payment_info->status = $response['data']['flw_ref'];
                $payment_info->currency = $response['data']['currency'];
                $payment_info->receipt = $response['data']['transaction_id'];
                $payment_info->client_id = Auth::user()->id;
                $payment_info->save();
//                getAppointmentPayment($response);
                return true;
//            return redirect()->to('/client/login')->with('success', 'Congratulations you can now access your portal!');
            } else {
                // Inform the customer their payment was unsuccessful
                return false;
                return redirect()->to('/client/login')->with('error', 'Oops sorry your payment was not successful');
            }
        } catch (\Exception $e) {
            throw $e;
        }

    }

    public function handleGatewayCallback()
    {
        try {
            $paymentDetails = Paystack::getPaymentData();

            $status = $paymentDetails['data']['status']; // Getting the status of the transaction

            if ($status == "success") { //Checking to Ensure the transaction was succesful
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
        } catch (\Exception $e) {
            throw  $e;
        }
    }


    function _checkIfHasChance()
    {
        $objUser = Auth::user();
        $check_chance_left = AppointmentInvoicePayments::where('user_id', $objUser->id)->first();
        $check_appoint_chance = RegistrationPayment::where('user_id', $objUser->id)->first();


        if ($check_chance_left !== null) {
            if ($check_chance_left->chance === 0) {
                return false;
//                return redirect()->to('/client/appointments/{$currentWorkspace->slug}')->with('error', 'ERROR');

            } else {
                $check_chance_left->update(['chance' => ($check_chance_left->chance - 1)]);
                return true;
            }
//                return redirect()->to('/client/appointments/{$currentWorkspace->slug}')->with('error', 'ERROR');
        } elseif ($check_appoint_chance !== null) {

            $check_appoint_chance->update(['appointment_chance' => ($check_appoint_chance->appointment_chance - 1)]);
            return true;
        } else {
            return redirect()->to('/client/appointments/{$currentWorkspace->slug}')->with('error', 'error');
        }
    }

}
