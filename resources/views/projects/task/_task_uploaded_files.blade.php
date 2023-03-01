@if(isset($uploadedFiles))
    @foreach($uploadedFiles as  $key => $file)
        @if($task->task_id === $file->type_id)

            @if($currentWorkspace->permission == 'Owner')
                <ul>
                    <li>  @auth('client')
                            <a
                                href="{{route('client-tasks-download',[$currentWorkspace->slug,$file->id,])}}"><i
                                    class="ti ti-download"></i>{{$file->name?:'N/A'}}</a>
                        @elseauth('web')
                            <a
                                href="{{route('admin-tasks-download',[$currentWorkspace->slug,$file->id,])}}"><i
                                    class="ti ti-download"></i>{{$file->name?:'N/A'}}</a>
                        @endauth

                <a href="#"
                   class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para"
                   data-confirm="{{__('Are You Sure?')}}"
                   data-toggle="popover" title="{{__('Delete')}}"
                   data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                   data-confirm-yes="delete-form1-{{$file->id}}"><i
                        class="ti ti-trash"></i></a>
                <form id="delete-form1-{{$file->id}}"
                      action="{{ route('tasks-file-destroy',[$currentWorkspace->slug,$file->id]) }}"
                      method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                    </li>
                </ul>
            @endif
        @endif
    @endforeach
@endif
