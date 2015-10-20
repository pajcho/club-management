@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Member Groups :: Payments & Attendance ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        <div class="col-lg-8 col-md-12">
            <span class="col-md-8">
                Payments & Attendance <small>{{ $memberGroup->name }}</small>
            </span>

            <span class="col-md-4">
                <div class="pull-right">
                    {!! Html::decode(link_to_route('group.payments', '<i class="fa fa-print"></i> Payments', [$memberGroup->id, $year, $month], ['class' => 'btn btn-xs btn-link', 'target' => '_blank', 'title' => 'Print Payments PDF'])) !!}

                    @if($memberGroup->total_monthly_time)
                        {!! Html::decode(link_to_route('group.attendance', '<i class="fa fa-print"></i> Attendance', [$memberGroup->id, $year, $month], ['class' => 'btn btn-xs btn-link', 'target' => '_blank', 'title' => 'Print Attendance PDF'])) !!}
                    @endif
                </div>
            </span>
        </div>

        <div class="clearfix"></div>
    </h1>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            {!! Former::open()->method('PUT')->action(route('group.data.update', [$memberGroup->id, $year, $month])) !!}

                <table class="table table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th colspan="3">&nbsp;</th>
                            <th colspan="{{ count($memberGroup->trainingDays($year, $month)) + 1 }}" class="text-center">
                                <strong>
                                    {{ \Carbon\Carbon::create($year, $month, 1)->format('Y, F') }}
                                </strong>
                            </th>
                        </tr>

                        <tr>
                            <th>#</th>
                            <th>Full Name</th>
                            <th class="text-center">Payment</th>
                            @foreach($memberGroup->trainingDays($year, $month) as $day)
                                <th width="35" class="text-center {!! \Carbon\Carbon::create($year, $month, $day->day)->isSameDay(\Carbon\Carbon::now()) ? 'info' : '' !!}">{{ $day->day }}</th>
                            @endforeach
                            <th class="text-center"></th>
                        </tr>

                    </thead>
                    <tbody>
                        @if($members->count())
                            @foreach($members as $key => $member)
                                <tr {!! ($highlight == $member->id) ? 'class="warning"' : '' !!}>
                                    <td width="50">{{ $key+1 }}</td>
                                    <td style="text-align: left;">
                                        {!! link_to_route('member.show', $member->full_name, [$member->id], ['class' => 'naked-link']) !!}
                                    </td>
                                    <td class="text-center">
                                        @if(!$member->freeOfChargeOnDate($year, $month, $member->freeOfCharge))
                                            <label for="payment_{{$member->id}}" style="width: 100%; height: 100%;">
                                                {!! Form::hidden('data[' . $member->id . '][payed]', 0) !!}
                                                {!! Form::checkbox('data[' . $member->id . '][payed]', 1, $memberGroup->data($year, $month, $member->id) ? $memberGroup->data($year, $month, $member->id)->payed : false, array('id' => 'payment_' . $member->id)) !!}
                                            </label>
                                        @else
                                            <label for="payment_{{$member->id}}" style="width: 100%; height: 100%;" title="Free of charge"><i class="fa fa-star small"></i></label>
                                        @endif
                                    </td>
                                    @foreach($memberGroup->trainingDays($year, $month) as $day)
                                        <td class="text-center {!! \Carbon\Carbon::create($year, $month, $day->day)->isSameDay(\Carbon\Carbon::now()) ? 'info' : '' !!}">
                                            <label for="attendance_{{$member->id}}_{{$day->day}}" style="width: 100%; height: 100%;">
                                                {!! Form::hidden('data[' . $member->id . '][attendance][' . $day->day . ']', 0) !!}
                                                {!! Form::checkbox('data[' . $member->id . '][attendance][' . $day->day . ']', 1, $memberGroup->data($year, $month, $member->id) ? $memberGroup->data($year, $month, $member->id)->attendance($day->day) : false, ['id' => 'attendance_' . $member->id . '_' . $day->day]) !!}
                                            </label>
                                        </td>
                                    @endforeach
                                    <td class="text-center {{ round(count(array_filter($memberGroup->data($year, $month, $member->id) ? $memberGroup->data($year, $month, $member->id)->attendance() : [], function($value){ return $value == 1; })) / count($memberGroup->trainingDays($year, $month)) * 100) >= 50 ? 'success' : 'warning' }}">
                                        {{ round(count(array_filter($memberGroup->data($year, $month, $member->id) ? $memberGroup->data($year, $month, $member->id)->attendance() : [], function($value){ return $value == 1; })) / count($memberGroup->trainingDays($year, $month)) * 100) }}%
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <div class="well">
                    {!!
                        Former::actions(
                            Former::link('Cancel', route('group.data.index', [$memberGroup->id])),
                            Former::default_reset('Reset'),
                            Former::success_submit('Update')
                        )
                    !!}
                </div>

            {!! Former::close() !!}
        </div>
    </div>


@stop