@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block">
       <i class="fa fa-check-circle-o"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong>{{ $message }}</strong>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul type="disc">
            @foreach ($errors->all() as $error)

                <li><i class="fa fa-asterisk"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(isset($warning))
    <div class="alert alert-warning alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong>{!! $warning !!}</strong>
    </div>
@endif

@if(isset($danger))
    <div class="alert alert-danger alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong>{{ $danger }}</strong>
    </div>
@endif
@if(isset($info))
    <div class="alert alert-info alert-block">
        <i class="fa fa-check-circle-o"></i>
        <strong>{{ $info }}</strong>
    </div>
@endif
