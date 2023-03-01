{{--<div class="row">--}}
{{--    <div id="newinput"></div>--}}
{{--</div>--}}
{{--<div class="row">--}}
{{--    <div class="col-md-3">--}}
{{--        <div id="newinput"></div>--}}
{{--        <button id="rowAdder" type="button" style="float: left"--}}
{{--                class="btn btn-dark">--}}
{{--						<span class="bi bi-plus-square-dotted">--}}
{{--						</span> ADD--}}
{{--        </button>--}}
{{--    </div>--}}
{{--</div>--}}


<div class="container">
    <div class="row my-4">
        <div class="col-lg-12 mx-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h4> Add Task(s) </h4>
                </div>
                <div class="card-body">

                        <div id="input_field">
                            <table id="table_field" class="table table-bordered">
                                <tr>
                                    <th>Title</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th> Priority</th>
                                    <th>Description
                                    <th>Action</th>

                                </tr>
                                <tr>
                                    <td><input class="form-control" type="text" name="title[]"></td>
                                    <td><input type="date" class="form-control form-control-light" id="start_date"
                                               name="start_date[]" required autocomplete="off">
                                        </td>
                                    <td><input type="date" class="form-control form-control-light" id="due_date"
                                               name="due_date[]" required autocomplete="off">
                                     </td>

                                    <td> <select class="form-control form-control-light select2" name="priority[]" id="task-priority" required>
                                            <option value="Low">{{ __('Low')}}</option>
                                            <option value="Medium">{{ __('Medium')}}</option>
                                            <option value="High">{{ __('High')}}</option>
                                        </select></td>

                                    <td class="col-2"><textarea class="form-control form-control-light" id="description" rows="3"
                                                  name="description[]"></textarea>
                                    </td>
                                    <td><input class="btn btn-primary" type="button" name="add" value="Add" id="add">
                                    </td>
                                </tr>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
