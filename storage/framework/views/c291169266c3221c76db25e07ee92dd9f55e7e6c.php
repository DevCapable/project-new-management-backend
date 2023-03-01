<style>
    .modal-backdrop {
        z-index: -1;
    }
</style>
<?php if(isset($client)): ?>
    <?php if(!isset($client->payment_policy)): ?>
        <div id="app" class="container py-2">
            <div>
                <p class="lead"></p>
            </div>
            <div class="py-2">
                <div class="modal fade in" id="policyModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <?php ($terms = App\Models\TermsAndCondition::where('slug','client_payment_policy')->first()); ?>
                                <h5 class="modal-title"><?php echo e($terms->name); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><?php echo e($terms->info); ?></p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="<?php echo e(route('client.update.payment.policy')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="user_id" value="<?php if(isset($client->id)): ?><?php echo e(($client->id)); ?><?php endif; ?>">
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
<?php endif; ?>
<?php /**PATH /home/heritage/SHINE/LATEST/projectmanagement/resources/views/partials/_payment_terms.blade.php ENDPATH**/ ?>