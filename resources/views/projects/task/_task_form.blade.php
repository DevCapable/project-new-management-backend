<div class="container">
    <div class="row my-4">
        <div class="col-lg-12 mx-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h4> Add Task(s) </h4>
                    @include('partials._notifications')

                </div>
                <div class="card-body">

                    <div id="input_field" class="table-responsive">
                        <a href="javascript:history.back()" ><i class="fa fa-reply" style="float: right; font-size: 40px; padding-right: 10px" ></i></a>

                        <table id="table_field" class="table table-bordered">

                            @auth('web')
                                <tr><th>Assign to</th></tr>
                                <tr>
                                    <td colspan="6">
                                        <select class=" form-control multi-select" id="assign_to" name="assign_to[]"
                                                 data-toggle="select2" multiple="multiple"
                                                 data-placeholder="{{ __('Select Users ...') }}" required>
                                            @foreach($users as $u)
                                                <option value="{{$u->name}} - {{$u->email}}">{{$u->name}} - {{$u->email}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            @endauth
                            <tr><th colspan="3">Attachment</th>
                            </tr>

                            <tr>
                                <td colspan="3">
                                    <div class="dropzone" id="dropzonewidget">
                                        <input hidden name="documents[]" id="documents" type="text" />
                                    </div>
                                </td>
                            </tr>
                            <tr><th colspan="3">Title<input type="hidden" value="{{generate_project_id('TASK')}}" name="task_id[]"></th>

                            <tr>
                                <td colspan="3"><input class="form-control" type="text" name="title[]">
                                </td>
                            </tr>
                            <tr>
                                <th colspan="2">Start Date</th>
                                <th colspan="2">Deadline</th>
                            </tr>
                            <tr>
                                <td colspan="1"><input type="date" class="form-control form-control-light" id="start_date"
                                                       name="start_date[]" required autocomplete="off">
                                </td>
                                <td colspan="2"><input type="date" class="form-control form-control-light" id="due_date"
                                                       name="due_date[]" required autocomplete="off">
                                </td>
                            </tr>
                            <tr>
                                <th colspan="3">Description</th>
                            </tr>
                            <tr>
                                <td colspan="3" rowspan="5"><textarea class="form-control form-control-light"
                                                                      id="description"
                                                                      rows="4"
                                                                      name="description[]"></textarea>
                                </td>
                            </tr>

                            <tr>
                                <td width="10px" colspan="1"><input class="btn btn-primary" type="button" name="add" value="Add"
                                                                    id="add">
                                </td>
                            </tr>
                            <tr>
                                <hr style="color: red">
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
