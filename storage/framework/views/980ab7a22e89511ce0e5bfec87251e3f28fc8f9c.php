<form class="" method="post" action="<?php echo e(route('clients.store',$currentWorkspace->slug)); ?>">
    <?php echo csrf_field(); ?>
    <div class="modal-body">
    <div class="row">
        <div class="form-group ">
            <label for="name" class="col-form-label"><?php echo e(__('Name')); ?></label>
            <input class="form-control" type="text" id="name" name="name" required="" placeholder="<?php echo e(__('Enter Name')); ?>">
        </div>
        <div class="form-group ">
            <label for="email" class="col-form-label"><?php echo e(__('Email')); ?></label>
            <input class="form-control" type="email" id="email" name="email" required="" placeholder="<?php echo e(__('Enter Email')); ?>">
        </div>
        <div class="form-group ">
            <label for="password" class="col-form-label"><?php echo e(__('Password')); ?></label>
            <input class="form-control" type="text" id="password" name="password" required="" placeholder="<?php echo e(__('Enter Password')); ?>">
        </div>
         </div>
          </div>
        <div class="modal-footer">
            <div class="row">
                <?php ($rg_fee = \App\Models\PaymentLists::where('slug','registration_payment')->first()); ?>
                <div class="col-md-12">
                    <div class="form-group " style="background-color: #959694; padding: 5px; border-radius: 10px; font-weight: bold">
                        <h3>Registration fee</h3>
                        <label for="is_paid" class="col-form-label"><?php echo e(__('Paid')); ?></label>
                        <input  type="radio" id="reg_fee" name="reg_fee" value="paid">
                        <label for="is_paid" class="col-form-label"><?php echo e(__('Not paid')); ?></label>
                        <input  type="radio" id="reg_fee" name="reg_fee" value="not_paid">
                        <div class="row">
                            <div class="col-md-6">
                                <input class="form-control"  type="text" id="amount" name="amount" value="<?php echo e($rg_fee->amount); ?>">
                            </div>
                            <div class="col-md-6">
                                <select class="form-control"  type="text" id="currency" name="currency">
                                    <option class="form-control" value="NGN">NGN</option>
                                    <option class="form-control" value="USD">USD</option>
                                </select>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="col-md-12" >
                    <button style="float: right" type="button" class="btn  btn-light" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    <input style="float: right"  type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn  btn-primary btn-right">
                </div>
            </div>




        </div>

</form>

<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/clients/create.blade.php ENDPATH**/ ?>