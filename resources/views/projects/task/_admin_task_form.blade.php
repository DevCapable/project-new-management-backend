<div class="container">
    <div class="row my-4">
        <div class="col-lg-12 mx-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h4> Add Task(s) </h4>
                    @include('partials._notifications')

                </div>
                <div class="card-body" id="table_field">

                    <div id="input_field" class="table-responsive">
                        @if(isset($project))
                            <a href="{{route($route,[$currentWorkspace->slug,$project->project_id])}}"><i class="ti ti-arrow-back"
                                                                                                          style="float: right; font-size: 40px; padding-right: 10px"></i></a>
                        @else
                            <a href="javascript:history.back()"><i class="ti ti-arrow-back" style="float: right; font-size: 40px; padding-right: 10px"></i></a>
                        @endif

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
                            <tr><th colspan="4">Title<input type="hidden" value="{{generate_project_id('TASK')}}" name="task_id"></th>

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

{{--                            <tr>--}}
{{--                                <td width="10px" colspan="1"><input class="btn btn-primary" type="button" name="add" value="Add"--}}
{{--                                                       id="add">--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <hr style="color: red">--}}
{{--                            </tr>--}}

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
