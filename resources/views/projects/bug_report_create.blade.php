@if($project && $currentWorkspace)

    <form class="" method="post" action="@auth('web'){{ route('projects.bug.report.store',[$currentWorkspace->slug,$project->id]) }}@elseauth{{ route('client.projects.bug.report.store',[$currentWorkspace->slug,$project->id]) }}@endauth">
        @csrf
         <div class="modal-body">
            <div class="row">
            <div class="form-group col-md-8">
                <label class="col-form-label">{{ __('Title')}}</label>
                <input type="text" class="form-control" id="task-title" placeholder="{{ __('Enter Title')}}" name="title" required>
            </div>

            <div class="form-group col-md-4">
                <label for="task-priority" class="col-form-label">{{ __('Priority')}}</label>
                <select class="form-control select2" name="priority" id="task-priority" required>
                    <option value="Low">{{ __('Low')}}</option>
                    <option value="Medium">{{ __('Medium')}}</option>
                    <option value="High">{{ __('High')}}</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="assign_to" class="col-form-label">{{ __('Assign To')}}</label>
                <select class="form-control select2" id="assign_to" name="assign_to" required>
                    @foreach($users as $u)
                        <option value="{{$u->id}}">{{$u->name}} - {{$u->email}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="status" class="col-form-label">{{ __('Status')}}</label>
                <select class="form-control select2" id="status" name="status" required>
                    @foreach($arrStatus as $id => $status)
                        <option value="{{$id}}">{{__($status)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-12 mb-0">
                <label for="task-description" class="col-form-label">{{ __('Description')}}</label>
                <textarea class="form-control" id="task-description" rows="3" name="description"></textarea>
            </div>
        </div>
    </div>
            <div class=" modal-footer">
               <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
               <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
            </div>
       
    </form>

@else
    <div class="container mt-5">
        <div class="card">
            <div class="card-body p-4">
                <div class="page-error">
                    <div class="page-inner">
                        <h1>404</h1>
                        <div class="page-description">
                            {{ __('Page Not Found') }}
                        </div>
                        <div class="page-search">
                            <p class="text-muted mt-3">{{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")}}</p>
                            <div class="mt-3">
                                <a class="btn-return-home badge-blue" href="{{route('home')}}"><i class="fas fa-reply"></i> {{ __('Return Home')}}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
