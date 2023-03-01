@if(isset(Auth::user()->from_admin))
@if(Auth::user()->from_admin == 1 && Auth::user()->is_password_reset == null))
<div id="app" class="container py-2">
    <div class="py-2">
        <div class="modal fade in"  id="changePassword">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update your password!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>CHANGE PASS AS ACCOUNT WILL EXPIRE IN THE NEXT 1 MINUTE.</p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Update Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif
