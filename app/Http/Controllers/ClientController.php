<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientProject;
use App\Models\ClientWorkspace;
use App\Models\Mail\SendClientLoginDetail;
use App\Exports\clientsExport;
use App\Imports\clientsImport;
use App\Models\RegistrationPayment;
use App\Models\Workspace;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Plan;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ClientController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function clientLogout(Request $request)
    {
        \Auth::guard('client')->logout();

        $request->session()->invalidate();

        return redirect()->route('client.login');
    }

    public function index($slug)
    {
        $this->middleware('auth');
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        if ($currentWorkspace->creater->id == \Auth::user()->id) {
            $clients = Client::select(
                [
                    'clients.*',
                    'client_workspaces.is_active',
                ]
            )->join('client_workspaces', 'client_workspaces.client_id', '=', 'clients.id')->where('client_workspaces.workspace_id', '=', $currentWorkspace->id)->get();

            return view('clients.index', compact('currentWorkspace', 'clients'));
        } else {
            return redirect()->route('home');
        }
    }

    public function create($slug)
    {
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        return view('clients.create', compact('currentWorkspace'));
    }

    public function store($slug, Request $request)
    {
        $objUser = \Auth::user();
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $registerClient = Client::where('email', '=', $request->email)->first();
        if (!$registerClient) {
            $arrUser['name'] = $request->name;
            $arrUser['email'] = $request->email;
            $arrUser['password'] = Hash::make($request->password);
            $arrUser['verification_code'] = sha1(time());
            $arrUser['from_admin'] = 1;
            $arrUser['email_verified_at'] = now();

            $registerClient = Client::create($arrUser);
            if ($request->reg_fee == 'paid'){
                $payment_info = new RegistrationPayment();
                $payment_info->user_id = $registerClient->id;
                $payment_info->email = $registerClient->email;
                $payment_info->reference = $objUser->email;
                $payment_info->payment_date = now();
                $payment_info->amount = $request->amount;
                $payment_info->channel = 'admin';
                $payment_info->currency = $request->currency;
                $payment_info->ip_address = $request->ip();
                $payment_info->save();
                $update = Client::where('email', $registerClient->email)->first();
                $update->payment_policy = 1;
                $update->is_verified = 1;
                $update->save();

            }
            try {
                $registerClient->password = $request->password;
                $user = Client::where('email', '=', $request->email)->first();
                $uArr = [
                    'user_name' => $request->name,
                    'app_name' => env('APP_NAME'),
                    'email' => $request->email,
                    'password' => $request->password,
                    'value' => $user->verification_code,
                    'app_url' => env('APP_URL'),
                ];

//                           dd($uArr);

                // Send Email
                $resp = Utility::sendclientEmailTemplate('New Client', $user->id, $uArr);
                 Mail::to($request->email)->send(new SendClientLoginDetail($registerClient));
            } catch (\Exception $e) {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }
        }
        $checkClient = ClientWorkspace::where('client_id', '=', $registerClient->id)->where('workspace_id', '=', $currentWorkspace->id)->first();
        if (!$checkClient) {
            ClientWorkspace::create(
                [
                    'client_id' => $registerClient->id,
                    'workspace_id' => $currentWorkspace->id,
                    'permission' => 'Owner',

                ]
            );

            Workspace::create(['created_by' => $registerClient->id, 'name' => 'NEW-FROM-ADMIN', 'currency_code' => 'USD', 'paypal_mode' => 'sandbox']);

        }

        return redirect()->route('clients.index', $currentWorkspace->slug)->with('success', __('Client Created Successfully!') . ((isset($smtp_error)) ? ' <br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
    }


    public function export()
    {
        $name = 'clients_' . date('Y-m-d i:h:s');
        $data = Excel::download(new clientsExport(), $name . '.xlsx');

        return $data;
    }


    public function importFile($slug)
    {

        return view('clients.import', compact('slug'));
    }


    public function import(Request $request)
    {
        $slug = $request->slug;


        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $Users = (new clientsImport())->toArray(request()->file('file'))[0];

        $totalCustomer = count($Users) - 1;
        $errorArray = [];


        for ($i = 1; $i <= count($Users) - 1; $i++) {
            $user = $Users[$i];

            $userByEmail = Client::where('email', $user[1])->first();


            if (!empty($userByEmail)) {
                $userData = $userByEmail;
            } else {
                $userData = new Client();
                // $userData->id = $this->UserNumber();

            }
            $userData->name = $user[0];
            $userData->email = $user[1];
            $userData->password = Hash::make($user[2]);

            if (empty($userData)) {
                $errorArray[] = $userData;
            } else {
                $userData->save();
            }

            $clientByEmail = ClientWorkspace::where('client_id', $userData->id)->first();
            if (!$clientByEmail) {
                $objWorkspace = new ClientWorkspace();
                $objWorkspace->client_id = $userData->id;
                $objWorkspace->workspace_id = $currentWorkspace->id;
                if (empty($objWorkspace)) {
                    $errorArray[] = $objWorkspace;
                } else {
                    $objWorkspace->save();
                }

            } else {
                return redirect()->back()->with("error", __("client already exits."));
            }

        }

        $errorRecord = [];
        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg'] = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg'] = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);

            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }


    public function edit($slug, $id)
    {
        $client = Client::find($id);
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);

        return view('clients.edit', compact('client', 'currentWorkspace'));
    }

    public function update($slug, $id, Request $request)
    {
        $client = Client::find($id);
        if ($client) {
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            $client->name = $request->name;
            if ($request->password) {
                $client->password = Hash::make($request->password);
            }
            $client->save();
            return redirect()->route('clients.index', $currentWorkspace->slug)->with('success', __('Client Updated Successfully!'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function destroy($slug, $id)
    {
        $client = Client::find($id);

        if ($client) {
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            ClientWorkspace::where('client_id', '=', $client->id)->delete();
            ClientProject::where('client_id', '=', $client->id)->delete();
            $client->delete();

            return redirect()->route('clients.index', $currentWorkspace->slug)->with('success', __('Client Deleted Successfully!'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function updateBilling(Request $request)
    {
        $objUser = \Auth::user();
        $objUser->address = $request->address;
        $objUser->city = $request->city;
        $objUser->state = $request->state;
        $objUser->zipcode = $request->zipcode;
        $objUser->country = $request->country;
        $objUser->telephone = $request->telephone;
        $objUser->save();
        return redirect()->back()->with('success', __('Billing Details Updated Successfully!'));
    }

    public function resetPassword($slug, $id)
    {
        $client = Client::find($id);
        $currentWorkspace = Utility::getWorkspaceBySlug($slug);
        return view('clients.reset_password', compact('client', 'currentWorkspace'));
    }

    public function changePassword($slug, $id, Request $request)
    {
        $request->validate(
            [
                'password' => 'required|same:password',
                'password_confirmation' => 'required|same:password',
            ]
        );
        $client = Client::find($id);
        if ($client) {
            $currentWorkspace = Utility::getWorkspaceBySlug($slug);
            if ($request->password) {
                $client->password = Hash::make($request->password);
            }
            $client->save();
            return redirect()->route('clients.index', $currentWorkspace->slug)->with('success', __('Client Updated Successfully!'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }

    }
}
