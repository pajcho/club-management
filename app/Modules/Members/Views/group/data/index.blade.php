@extends('layouts/default')

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
            <a class="btn btn-default" href="{{ route('group.index') }}"><i class="fa fa-icon fa-list text-info"></i> Groups listing</a>
        </div>
    </h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Month</th>
                    <th width="100" class="text-center">Payed?</th>
                    <th width="100">Actions</th>
                    <th width="170">Documents</th>
                </tr>
            </thead>
            <tbody>
                @if($months->count())
                    @foreach($months as $key => $month)
                        <tr class="{{ $today->year == $month->year && $today->month == $month->month ? 'success bold' : '' }}">
                            <td>{{ $months->firstItem() + $key }}</td>
                            <td>
                                {!! link_to_route('group.data.show', $month->format('Y, F'), [$memberGroup->id, $month->year, $month->month], ['class' => 'naked-link']) !!}

                                @if($today->year == $month->year && $today->month == $month->month)
                                    <span class="label label-primary">Current month</span>
                                @endif
                            </td>
                            <td class="text-center ajax-content" data-url="{{route('group.data.get.payment-data', [$memberGroup->id, $month->year, $month->month])}}"></td>
                            {{--<td class="text-center">{{ $memberGroup->data($month->year, $month->month) ? $memberGroup->payedString($month->year, $month->month) : '' }}</td>--}}
                            <td>
                                {!! Html::decode(link_to_route('group.data.show', '<i class="fa fa-icon fa-pencil text-success"></i> Update', [$memberGroup->id, $month->year, $month->month], ['class' => 'btn btn-xs btn-default'])) !!}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#" class="btn btn-xs btn-default"><i class="fa fa-paperclip"></i> Documents <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li>{!! Html::decode(link_to_route('group.payments', '<i class="fa fa-print"></i> Payments', [$memberGroup->id, $month->year, $month->month], ['target' => '_blank', 'title' => 'Print Payments PDF', 'data-placement' => 'left'])) !!}</li>

                                        @if($memberGroup->total_monthly_time)
                                            <li>{!! Html::decode(link_to_route('group.attendance', '<i class="fa fa-print"></i> Attendance', [$memberGroup->id, $month->year, $month->month], ['target' => '_blank', 'title' => 'Print Attendance PDF', 'data-placement' => 'left'])) !!}</li>
                                        @endif

                                        <li class="divider"></li>
                                        <li>{!! Html::decode(link_to_route('group.payments', '<i class="fa fa-download"></i> Payments', [$memberGroup->id, $month->year, $month->month, 'download'], ['title' => 'Save Payments PDF', 'data-placement' => 'left'])) !!}</li>

                                        @if($memberGroup->total_monthly_time)
                                            <li>{!! Html::decode(link_to_route('group.attendance', '<i class="fa fa-download"></i> Attendance', [$memberGroup->id, $month->year, $month->month, 'download'], ['title' => 'Save Attendance PDF', 'data-placement' => 'left'])) !!}</li>
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
            @include('paginator/club', ['paginator' => $months->appends(Input::except('page', '_pjax'))])
        @endif
    </div>

@stop