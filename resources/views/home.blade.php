@extends('layouts.admin')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('content')

    <section class="section">
        @if ($currentWorkspace)
            <div class="row">
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <div class="row mt-3">
                        <div class="col-xl-3 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="fas fa-tasks bg-primary text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm"></p>
                                    <h6 class="">{{ __('Total Project') }}</h6>
                                    <h3 class="mb-0">{{ $totalProject ?? '' }} <span
                                            class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-info">
                                        <i class="fas fa-tag bg-info text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm "></p>
                                    <h6 class="">{{ __('Total Task') }}</h6>
                                    <h3 class="mb-0">{{ $totalTask ?? '' }} <span
                                            class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-danger">
                                        <i class="fas fa-bug bg-danger text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm"></p>
                                    <h6 class="">{{ __('Total Issue') }}</h6>
                                    <h3 class="mb-0">{{ $totalBugs ?? '' }} <span
                                            class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-success">
                                        <i class="fas fa-users bg-success text-white"></i>
                                    </div>
                                    <p class="text-muted text-sm"></p>
                                    <h6 class="">{{ __('Total User') }}</h6>
                                    <h3 class="mb-0">{{ $totalMembers ?? '' }} <span
                                            class="text-success text-sm"></span></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card ">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-9">
                                    <h5 class="">
                                        {{ __('Tasks') }}
                                    </h5>
                                </div>
                                <div class="col-auto d-flex justify-content-end">
                                    <div class="">
                                        <small><b>{{ $completeTask ?? '' }}</b> {{ __('Tasks completed out of') }}
                                            {{ $totalTask ?? '' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body ">
                            <div class="table-responsive">
                                <table class="table table-centered table-hover mb-0 animated">
                                    <tbody>
                                        @foreach ($tasks ?? '' as $task)
                                            <tr>
                                                <td>
                                                    <div class="font-14 my-1"><a
                                                            href="{{ route('projects.task.board', [$currentWorkspace->slug, $task->project_id]) }}"
                                                            class="text-body">{{ $task->title }}</a></div>

                                                    @php($due_date = '<span class="text-' . ($task->due_date < date('Y-m-d') ? 'danger' : 'success') . '">' . date('Y-m-d', strtotime($task->due_date)) . '</span> ')

                                                    <span class="text-muted font-13">{{ __('Due Date') }} :
                                                        {!! $due_date !!}</span>
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13">{{ __('Status') }}</span> <br />
                                                    @if ($task->complete == '1')
                                                        <span
                                                            class="status_badge_dash badge bg-success p-2 px-3 rounded">{{ __($task->status) }}</span>
                                                    @else
                                                        <span
                                                            class="status_badge_dash badge bg-primary p-2 px-3 rounded">{{ __($task->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted font-13">{{ __('Project') }}</span>
                                                    <div class="font-14 mt-1 font-weight-normal">{{ $task->project->name }}
                                                    </div>
                                                </td>
                                                @if ($currentWorkspace->permission == 'Owner' || Auth::user()->getGuard() == 'client')
                                                    <td>
                                                        <span class="text-muted font-13">{{ __('Assigned to') }}</span>
                                                        <div class="font-14 mt-1 font-weight-normal">
                                                            @foreach ($task->users() as $user)
                                                                <span
                                                                    class="badge p-2 px-2 rounded bg-secondary">{{ isset($user->name) ? $user->name : '-' }}</span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="col-lg-5 col-md-5 col-sm-5">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Tasks Overview') }}</h5>
                            <div class="text-end"><small class=""></small></div>
                        </div>
                        <div class="card-body">
                            <div id="task-area-chart"></div>
                        </div>
                    </div>



                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                        class=""></i></a>
                            </div>
                            <h5>Project Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center px-3">
                                <div class="col-6">
                                    <div id="projects-chart"></div>
                                </div>
                                <div class="col-6 pb-5 px-3">

                                    <div class="col-6">
                                        <span class="d-flex align-items-center mb-2">
                                            <i class="f-10 lh-1 fas fa-circle" style="color:#6095c1;"></i>
                                            <span class="ms-2 text-sm">On Going</span>
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <span class="d-flex align-items-center mb-2">
                                            <i class="f-10 lh-1 fas fa-circle" style="color: #545454;"></i>
                                            <span class="ms-2 text-sm">On Hold</span>
                                        </span>
                                    </div>
                                    <div class="col-6">
                                        <span class="d-flex align-items-center mb-2">
                                            <i class="f-10 lh-1 fas fa-circle" style="color: #3cb8d9; "></i>
                                            <span class="ms-2 text-sm">Finished</span>
                                        </span>
                                    </div>

                                </div>

                                <div class="row text-center">

                                    @foreach ($arrProcessPer as $index => $value)
                                        <div class="col-4">
                                            <i class="fas fa-chart {{ $arrProcessClass[$index] }}  h3"></i>
                                            <h6 class="font-weight-bold">
                                                <span>{{ $value }}%</span>
                                            </h6>
                                            <p class="text-muted">{{ __($arrProcessLabel[$index]) }}</p>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-0 mt-3 text-center text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                {{ __('There is no active Workspace. Please create Workspace from right side menu.') }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

@endsection


@push('scripts')
    <script src="{{ asset('custom/js/apexcharts.min.js') }}"></script>

    @if (Auth::user()->type == 'admin')
        <script>
            var taskAreaOptions = {
                series: [{
                    name: '{{ __('Order') }}',
                    data: {!! json_encode($chartData['data']) !!}
                }, ],
                chart: {
                    height: 350,
                    type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#37b37e'],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: {!! json_encode($chartData['label']) !!},
                    title: {
                        text: '{{ __('Days') }}'
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __('Orders') }}'
                    },

                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            };

            setTimeout(function() {
                var taskAreaChart = new ApexCharts(document.querySelector(""), taskAreaOptions);
                taskAreaChart.render();
            }, 100);
        </script>
    @elseif(isset($currentWorkspace) && $currentWorkspace)
        <script>
            (function() {
                var options = {
                    chart: {
                        height: 210,
                        type: 'donut',
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                            }
                        }
                    },
                    series: {!! json_encode($arrProcessPer) !!},




                    colors: {!! json_encode($chartData['color']) !!},
                    labels: {!! json_encode($chartData['label']) !!},
                    grid: {
                        borderColor: '#e7e7e7',
                        row: {
                            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                            opacity: 0.5
                        },
                    },
                    markers: {
                        size: 1
                    },
                    legend: {
                        show: false
                    }
                };
                var chart = new ApexCharts(document.querySelector("#projects-chart"), options);
                chart.render();
            })();


            setTimeout(function() {
                var taskAreaChart = new ApexCharts(document.querySelector(""), taskAreaOptions);
                taskAreaChart.render();
            }, 100);

            var projectStatusOptions = {
                series: {!! json_encode($arrProcessPer) !!},

                chart: {
                    height: '350px',
                    width: '450px',
                    type: 'pie',
                },
                colors: ["#00B8D9", "#36B37E", "#2359ee"],
                labels: {!! json_encode($arrProcessLabel) !!},

                plotOptions: {
                    pie: {
                        dataLabels: {
                            offset: -5
                        }
                    }
                },
                title: {
                    text: ""
                },
                dataLabels: {},
                legend: {
                    display: false
                },

            };
            var projectStatusChart = new ApexCharts(document.querySelector("#project-status-chart"), projectStatusOptions);
            projectStatusChart.render();
        </script>
    @endif


    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    @if (Auth::user()->type == 'admin')
        <script>
            (function() {
                var options = {
                    chart: {
                        height: 150,
                        type: 'area',
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    series: [{
                        name: 'Refferal',
                        data: [20, 50, 30, 60, 40, 50, 40]
                    }, {
                        name: 'Organic search',
                        data: [40, 20, 60, 15, 50, 65, 20]
                    }],
                    xaxis: {
                        categories: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    },
                    colors: ['#ffa21d', '#FF3A6E'],

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },
                    markers: {
                        size: 4,
                        colors: ['#ffa21d', '#FF3A6E'],
                        opacity: 0.9,
                        strokeWidth: 2,
                        hover: {
                            size: 7,
                        }
                    },


                };
                var chart = new ApexCharts(document.querySelector("#task-area-chart"), options);
                chart.render();
            })();
        </script>
    @elseif(isset($currentWorkspace) && $currentWorkspace)
        <script>
            (function() {
                var options = {
                    chart: {
                        height: 150,
                        type: 'line',
                        toolbar: {
                            show: false,
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 2,
                        curve: 'smooth'
                    },
                    series: [
                        @foreach ($chartData['stages'] as $id => $name)
                            {
                                name: "{{ __($name) }}",
                                data: {!! json_encode($chartData[$id]) !!}
                            },
                        @endforeach
                    ],
                    xaxis: {
                        categories: {!! json_encode($chartData['label']) !!},
                        title: {
                            text: '{{ __('Days') }}'
                        }
                    },
                    colors: {!! json_encode($chartData['color']) !!},

                    grid: {
                        strokeDashArray: 4,
                    },
                    legend: {
                        show: false,
                    },
                    markers: {
                        size: 4,
                        colors: ['#ffa21d', '#FF3A6E'],
                        opacity: 0.9,
                        strokeWidth: 2,
                        hover: {
                            size: 7,
                        }
                    },
                    yaxis: {
                        tickAmount: 3,
                        min: 10,
                        max: 70,
                    },
                    title: {
                        text: '{{ __('Tasks') }}'
                    },
                };
                var chart = new ApexCharts(document.querySelector("#task-area-chart"), options);
                chart.render();
            })();
        </script>
    @endif
@endpush
