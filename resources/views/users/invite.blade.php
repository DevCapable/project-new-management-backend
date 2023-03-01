 <div class="modal-body">
<div class="row">
    <div class="col-md-12 col-12 text-center">
        <span class="invite-warning"></span>
    </div>
    <div class="col-md-12 col-12 form-group invite_user_div">
        {{ Form::label('username', __('Name'), ['class' => 'col-form-label']) }}
        {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => __('Please enter name')]) }}
    </div>
    <div class="col-md-12 form-group">
        {{ Form::label('invite_email', __('Email'), ['class' => 'col-form-label']) }}
        {{ Form::text('invite_email', null, ['class' => 'form-control', 'placeholder' => __('Enter email address')]) }}
    </div>
    <div class="col-md-12 col-12 form-group invite_user_div">
        {{ Form::label('userpassword', __('Password'), ['class' => 'col-form-label']) }}
        {{ Form::text('userpassword', null, ['class' => 'form-control', 'placeholder' => __('Please enter password')]) }}
    </div>
        </div>
        </div> 
    <div class=" col-md-12 modal-footer">
       <button type="button" class="btn  btn-light "  data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" class="btn  btn-primary check-invite-members">{{ __('Invite') }}</button>
    </div>



<script type="text/javascript">

    $(function () {

        $('.check-invite-members').on('click', function (e) {

            var ele = $(this);
            var emailele = $('#invite_email');

            var email = emailele.val();

            $('.email-error-message').remove();
            if (email == '') {
                emailele.focus().after('<span class="email-error-message error-message">{{ __("This field is required.") }}</span>');
                return false;
            }

            if (!isEmail(email)) {

                emailele.focus().after('<span class="email-error-message error-message">{{ __("Please enter valid email address.") }}</span>');
                return false;

            } else {

                $.ajax({
                    url: '{{ route('user.exists', '__slug') }}'.replace('__slug', '{{ $currentWorkspace->slug }}'),
                    dataType: 'json',
                    data: {
                        'email': email
                    },
                    success: function (data) {

                        if (data.code == '202') {
                            $('#commonModel').modal('hide');
                            show_toastr(data.status, data.success, 'success');
                        } else if (data.code == '200') {
                            $('#commonModel').modal('hide');
                            show_toastr(data.status, data.success, 'success');
                            location.reload();
                        } else if (data.code == '404') {
                            $('.invite_user_div').show();
                            $('.invite-warning').text(data.error).show();
                        }
                        ele.removeClass('check-invite-members').off('click').addClass('invite-members');
                    }
                });
            }
        });

        $(document).on('click', '.invite-members', function () {

            var useremail = $('#invite_email').val();
            var username = $('#username').val();
            var userpassword = $('#userpassword').val();

            $('.username-error-message').remove();
            if (username == '') {
                $('#username').focus().after('<span class="username-error-message error-message">{{ __("This field is required.") }}</span>');
                return false;
            }

            $('.userpassword-error-message').remove();
            if (userpassword == '') {
                $('#userpassword').focus().after('<span class="userpassword-error-message error-message">{{ __("This field is required.") }}</span>');
                return false;
            }

            $('.email-error-message').remove();
            if (useremail == '') {
                $('#invite_email').focus().after('<span class="email-error-message error-message">{{ __("This field is required.") }}</span>');
                return false;
            }

            if (!isEmail(useremail)) {

                $('#invite_email').focus().after('<span class="email-error-message error-message">{{ __("Please enter valid email address.") }}</span>');
                return false;

            } else {

                $.ajax({
                    url: '{{ route('users.invite.update', '__slug') }}'.replace('__slug', '{{ $currentWorkspace->slug }}'),
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        'useremail': useremail,
                        'username': username,
                        'userpassword': userpassword,
                    },
                    success: function (data) {

                        $('#commonModel').modal('hide');
                        if (data.code == '200') {
                            show_toastr(data.status, data.success, 'success');
                            location.reload();
                        } else {

                            show_toastr(data.status, data.error, 'error');
                        }
                    }
                });
            }
        });

    });
</script>
