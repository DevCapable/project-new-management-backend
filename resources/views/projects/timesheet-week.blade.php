<div class="table-responsive ">
    <table id="dt-all-checkbox" class="table data-table" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th class="th-sm">
                <span class="pr-1 text-left">{{ __('Task') }}</span> <i class="fas fa-sort-alt"></i>
            </th>
            @foreach ($days['datePeriod'] as $key => $perioddate)
                <th class="th-sm">
                    <div class="day-name">
                        <p class="m-0">{{ $perioddate->format('l d') }}</p><small>{{ $perioddate->format('F') }}</small>
                    </div>
                </th>
            @endforeach
            <th class="th-sm">
                <span class="pr-1">{{ __('Total') }}</span>
            </th>
        </tr>
        </thead>
        <tbody>

        @if(isset($allProjects) && $allProjects == true)

            @foreach ($timesheetArray as $key => $timesheet)

                <tr class="">
                    <td colspan="9"><span class="project-name pad_row">{{ $timesheet['project_name'] }}</span></td>
                </tr>

                @foreach ($timesheet['taskArray'] as $key => $taskTimesheet)

                    <tr>
                        <td colspan="9">
                            <div class="task-name  ml-3 pad_row">
                                {{ $taskTimesheet['task_name'] }}
                            </div>
                        </td>
                    </tr>

                    @foreach ($taskTimesheet['dateArray'] as $dateTimeArray)

                        <tr>
                            <td>
                                <div class="task blue ml-5">
                                    {{ $dateTimeArray['user_name'] }}
                                </div>
                            </td>

                            @foreach ($dateTimeArray['week'] as $dateSubArray)

                                <td>
                                    @auth('client')
                                        <div class="day-time">{{ $dateSubArray['time'] != '00:00' ? $dateSubArray['time'] : '-' }}</div>
                                    @elseauth
                                        <div class="day-time" title="{{ $dateSubArray['type'] == 'edit' ? __('Click to Edit/Delete Timesheet') : __('Click to Add Timesheet') }}" data-ajax-timesheet-popup="true" data-type="{{ $dateSubArray['type'] }}" data-user-id="{{ $dateTimeArray['user_id'] }}" data-project-id="{{ $timesheet['project_id'] }}" data-task-id="{{ $taskTimesheet['task_id'] }}" data-date="{{ $dateSubArray['date'] }}"
                                             data-url="{{ $dateSubArray['url'] }}">{{ $dateSubArray['time'] != '00:00' ? $dateSubArray['time'] : '-' }}</div>
                                    @endauth
                                </td>

                            @endforeach
                            <td>
                                <div class="total day-time">
                                    {{ $dateTimeArray['totaltime'] }}
                                </div>
                            </td>
                        </tr>
                    @endforeach

                @endforeach

            @endforeach

        @else
            @foreach ($timesheetArray as $key => $timesheet)
                <tr>
                    <td>
                        <div class="task-name ml-3">
                            {{ $timesheet['task_name'] }}
                        </div>
                    </td>

                    @foreach ($timesheet['dateArray'] as $day => $datetime)

                        <td>
                            @auth('client')
                                <div class="day-time">{{ $datetime['time'] != '00:00' ? $datetime['time'] : '-' }}</div>
                            @elseauth
                                <div class="day-time" title="{{ $datetime['type'] == 'edit' ? __('Click to Edit/Delete Timesheet') : __('Click to Add Timesheet') }}" data-ajax-timesheet-popup="true" data-type="{{ $datetime['type'] }}" data-task-id="{{ $timesheet['task_id'] }}" data-date="{{ $datetime['date'] }}" data-url="{{ $datetime['url'] }}">{{ $datetime['time'] != '00:00' ? $datetime['time'] : '-' }}</div>
                            @endauth
                        </td>

                    @endforeach

                    <td>
                        <div class="total day-time">
                            {{ $timesheet['totaltime'] }}
                        </div>
                    </td>
                </tr>

            @endforeach

        @endif

        </tbody>
        <tfoot>
        <tr class="footer-total">
            <td>{{ __('Total') }}</td>

            @foreach ($totalDateTimes as $key => $totaldatetime)
                <td>
                    <div class="value" style="padding: 3px 19px !important;">
                        {{ $totaldatetime != '00:00' ? $totaldatetime : '-' }}
                    </div>
                </td>
            @endforeach
            <td>
                <div class="total-value" style="padding: 3px 19px !important;">
                    {{ $calculatedtotaltaskdatetime }}
                </div>
            </td>
        </tr>
        </tfoot>

    </table>
</div>

<style type="text/css">
    .task-name{
    padding: 1.5rem 1.5rem !important;
}



    .table thead th {
    border-bottom: 1px solid #000 !important;
  
     background: #fff !important;
 }

    .day-time, .total-value, .value {
    /* display: inline-block; */
    border: 1px solid #000 !important;
   /* padding: 3px 19px !important;*/
    border-radius: 30px !important;
    width: 80px !important; 
    text-align: center !important;
}
</style>
