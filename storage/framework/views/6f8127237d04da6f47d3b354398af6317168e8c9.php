<div class="container">
    <div class="row my-4">
        <div class="col-lg-12 mx-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h4> Add Task(s) </h4>
                    <?php echo $__env->make('partials._notifications', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </div>
                <div class="card-body" id="table_field">

                    <div id="input_field" class="table-responsive">
                        <?php if(isset($project)): ?>
                            <a href="<?php echo e(route($route,[$currentWorkspace->slug,$project->project_id])); ?>"><i class="ti ti-arrow-back"
                                                                                                          style="float: right; font-size: 40px; padding-right: 10px"></i></a>
                        <?php else: ?>
                            <a href="javascript:history.back()"><i class="ti ti-arrow-back" style="float: right; font-size: 40px; padding-right: 10px"></i></a>
                        <?php endif; ?>

                         <table class="table responsive">
                            <tr><th colspan="4">Attachment</th>
                            </tr>

                            <tr>
                                <td colspan="4">
                                    <div class="dropzone" id="dropzonewidget">
                                        <input hidden name="documents[]" id="documents" type="text" />
                                    </div>
                                </td>
                            </tr>
                            <tr><th colspan="4">Title<input type="hidden" value="<?php echo e(generate_project_id('TASK')); ?>" name="task_id"></th>

                            <tr>
                                <td colspan="4"><input class="form-control" type="text" name="title[]">
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2">Start Date</th>
                                <th colspan="2">Deadline</th>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="date" class="form-control form-control-light" id="start_date"
                                           name="start_date[]" required autocomplete="off">
                                </td>
                                <td colspan="2"><input type="date" class="form-control form-control-light" id="due_date"
                                           name="due_date[]" required autocomplete="off">
                                </td>
                            </tr>
                            <tr>
                                <th colspan="4">Description</th>
                            </tr>
                            <tr>
                                <td colspan="4" rowspan="5"><textarea class="form-control form-control-light"
                                                                      id="description"
                                                                      rows="4"
                                                                      name="description[]"></textarea>
                                </td>
                            </tr>










                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/heritage/PROJECTS/NEW/management/resources/views/projects/task/_admin_task_form.blade.php ENDPATH**/ ?>