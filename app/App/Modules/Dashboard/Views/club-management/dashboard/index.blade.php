@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Dashboard ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-line-chart"></i> Dashboard</h1>

    <div class="row">
        <div class="col-md-6">
            <div class="alert alert-link text-center"><h4><i class="fa fa-arrow-down"></i> New members per month</h4> </div>
            <hr/>
            <div id="monthly-members-chart"></div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-link text-center"><h4><i class="fa fa-arrow-down"></i> New members per year</h4></div>
            <hr/>
            <div id="yearly-members-chart"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <hr/>
            <div class="alert alert-link text-center"><h4><i class="fa fa-arrow-down"></i> Member years grouped by year of subscription</h4></div>
            <hr/>
            <div id="members-year-of-birth-chart"></div>
        </div>
        <div class="col-md-6">
            <hr/>
            <div class="alert alert-link text-center"><h4><i class="fa fa-arrow-down"></i> Member years in percents</h4></div>
            <hr/>
            <div id="members-year-of-birth-pie-chart"></div>
        </div>
    </div>

@stop

@section('scripts')

    <script>
        $(function(){

            var data = {{ $data }};

            var chart1 = c3.generate({
                bindto: '#monthly-members-chart',
                data: {
                    x: 'x',
                    xFormat: '%Y-%m-%d %H:%M:%S',
                    columns: data.monthlyMembers
                },
                axis: {
                    x: {
                        label: 'Month in year',
                        type: 'timeseries',
                        tick: {
                            culling: true,
                            fit: true,
                            format: '%b \'%y'
                        }
                    },
                    y: {
                        label: 'Number of new members'
                    }
                },
                grid: {
                    y: {
                        show: true,
                        lines: true
                    }
                }
            });
            var chart2 = c3.generate({
                bindto: '#yearly-members-chart',
                data: {
                    x: 'x',
                    xFormat: '%Y-%m-%d %H:%M:%S',
                    columns: data.yearlyMembers
                },
                axis: {
                    x: {
                        label: 'Year',
                        type: 'timeseries',
                        tick: {
                            format: '%Y'
                        }
                    },
                    y: {
                        label: 'Number of new members'
                    }
                },
                grid: {
                    y: {
                        show: true,
                        lines: true
                    }
                }
            });
            var chart3 = c3.generate({
                bindto: '#members-year-of-birth-chart',
                data: {
                    xs: data.memberYearsOfBirthAxes,
                    columns: data.membersYearOfBirth
                },
                axis: {
                    x: {
                        label: 'Years old'
                    },
                    y: {
                        label: 'Number of new members'
                    }
                },
                grid: {
                    y: {
                        show: true,
                        lines: true
                    }
                }
            });
            var chart4 = c3.generate({
                bindto: '#members-year-of-birth-pie-chart',
                data: {
                    columns: data.membersYearOfBirthPie,
                    type: 'pie'
                }
            });
        });
    </script>

@stop