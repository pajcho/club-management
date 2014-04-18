@extends(theme_view('layouts/default'))

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
                    {{ HTML::decode(link_to_route('group.payments', '<i class="glyphicon glyphicon-print"></i> Payments', array($memberGroup->id, $year, $month), array('class' => 'btn btn-xs btn-link', 'title' => 'Generate Payments PDF'))) }}

                    @if($memberGroup->total_monthly_time)
                        {{ HTML::decode(link_to_route('group.attendance', '<i class="glyphicon glyphicon-print"></i> Attendance', array($memberGroup->id, $year, $month), array('class' => 'btn btn-xs btn-link', 'title' => 'Generate Attendance PDF'))) }}
                    @endif
                </div>
            </span>
        </div>

        <div class="clearfix"></div>
    </h1>

<div class="row">
    <div class="col-lg-8 col-md-12">
        {{ Former::open()->method('PUT')->action(route('group.details.update', array($memberGroup->id, $year, $month))) }}

            <table class="table table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th colspan="3">&nbsp;</th>
                        <th colspan="{{ count($memberGroup->trainingDays($year, $month)) }}" class="text-center">
                            <strong>
                                {{ \Carbon\Carbon::createFromDate($year, $month)->format('Y, F') }}
                            </strong>
                        </th>
                    </tr>

                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th class="text-center">Payment</th>
                        @foreach($memberGroup->trainingDays($year, $month) as $day)
                            <th width="35" class="text-center">{{ $day->day }}</th>
                        @endforeach
                    </tr>

                </thead>
                <tbody>
                    @if($members->count())
                        @foreach($members as $key => $member)
                            <tr>
                                <td width="50">{{ $key+1 }}</td>
                                <td style="text-align: left;">{{ $member->full_name }}</td>
                                <td class="text-center">
                                    @if(!$member->freeOfChargeOnDate($year, $month))
                                        <label for="payment_{{$member->id}}" style="width: 100%; height: 100%;">
                                            {{ Form::hidden('payment[' . $member->id . ']', 0) }}
                                            {{ Form::checkbox('payment[' . $member->id . ']', 1, $memberGroup->details($year, $month) ? $memberGroup->details($year, $month)->details('payment.' . $member->id) : false, array('id' => 'payment_' . $member->id)) }}
                                        </label>
                                    @else
                                        <label for="payment_{{$member->id}}" style="width: 100%; height: 100%;" title="Free of charge"><i class="glyphicon glyphicon-star"></i></label>
                                    @endif
                                </td>
                                @foreach($memberGroup->trainingDays($year, $month) as $day)
                                    <td class="text-center">
                                        <label for="attendance_{{$member->id}}_{{$day->day}}" style="width: 100%; height: 100%;">
                                            {{ Form::hidden('attendance[' . $member->id . '][' . $day->day . ']', 0) }}
                                            {{ Form::checkbox('attendance[' . $member->id . '][' . $day->day . ']', 1, $memberGroup->details($year, $month) ? $memberGroup->details($year, $month)->details('attendance.' . $member->id . '.' . $day->day) : false, array('id' => 'attendance_' . $member->id . '_' . $day->day)) }}
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="well">
                {{
                    Former::actions(
                        Former::link('Cancel', route('group.details.index', array($memberGroup->id))),
                        Former::default_reset('Reset'),
                        Former::success_submit('Update')
                    )
                }}
            </div>

        {{ Former::close() }}
    </div>
</div>


@stop