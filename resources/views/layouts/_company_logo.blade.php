@php($company_logo = App\Models\Utility::get_logo())
<img src="{{asset('assets/logo/'.$company_logo)}}"  alt="logo">
