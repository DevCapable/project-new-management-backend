<?php if(Auth::user()->accept_terms == null): ?>)
<div id="app" class="container py-2">
    <div>
        <p class="lead"></p>
    </div>
    <div class="py-2">
        <div class="modal fade in" id="policyModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <?php ($terms = App\Models\TermsAndCondition::where('slug','client_terms_and_policy')->first()); ?>
                        <h5 class="modal-title"><?php echo e($terms->name?:'NULL'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><?php echo e($terms->info); ?></p>
                    </div>
                    <div class="modal-footer">
                        <form method="POST" action="<?php echo e(route('client.update.terms')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="user_id" value="<?php echo e(Auth::user()->id); ?>">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Continue</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php /**PATH /home/heritage/PROJECTS/NEW/management/resources/views/partials/_welcome_modal.blade.php ENDPATH**/ ?>