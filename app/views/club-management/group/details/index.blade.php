@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Member Groups :: Payments & Attendance ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        Payments & Attendance <small>{{ $memberGroup->name }}</small>
        <div class="pull-right">
            <a class="btn btn-info" href="{{ route('group.index') }}"><i class="glyphicon glyphicon-circle-arrow-left"></i> Back to groups</a>
        </div>
    </h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Month</th>
                    <th width="210" class="text-center">Payed?</th>
                    <th width="70">Actions</th>
                    <th width="100">Documents</th>
                </tr>
            </thead>
            <tbody>
                @if($months->count())
                    @foreach($months as $key => $month)
                        <tr>
                            <td>{{ $months->getFrom() + $key }}</td>
                            <td>{{ $month->format('Y, F') }}</td>
                            <td class="text-center">{{ $memberGroup->details($month->year, $month->month) ? $memberGroup->details($month->year, $month->month)->payed() : '' }}</td>
                            <td>
                                {{ link_to_route('group.details.show', 'Update', array($memberGroup->id, $month->year, $month->month), array('class' => 'btn btn-xs btn-success')) }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#" class="btn btn-xs btn-default"><i class="glyphicon glyphicon-print"></i> Print <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>{{ HTML::decode(link_to_route('group.payments', 'Payments', array($memberGroup->id, $month->year, $month->month), array('class' => '', 'title' => 'Generate Payments PDF'))) }}</li>

                                        @if($memberGroup->total_monthly_time)
                                            <li>{{ HTML::decode(link_to_route('group.attendance', 'Attendance', array($memberGroup->id, $month->year, $month->month), array('class' => '', 'title' => 'Generate Attendance PDF'))) }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" align="center">
                            There is no data to display
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($months->count())
            {{ $months->appends(Input::except('page', '_pjax'))->links(theme_view('paginator.club')) }}
        @endif
    </div>

@stop