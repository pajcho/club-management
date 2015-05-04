@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Users :: Attendance ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        Users <small>{{ $user->full_name }}</small>
        <div class="pull-right">
            <a class="btn btn-info" href="{{ route('user.show', array($user->id)) }}"><i class="fa fa-arrow-left"></i> Back to user</a>
        </div>
    </h1>

    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($data as $year => $groups)
                <li class="{{$groups === reset($data) ? 'active' : ''}}"><a href="#{{$year}}" role="tab" data-toggle="tab">{{$year}}</a></li>
            @endforeach
        </ul>
    </div>

    {!! Former::open()->method('PUT')->action(route('user.attendance.update', array($user->id))) !!}
    <div class="tab-content">
    @foreach($data as $year => $groups)
        <div role="tabpanel" class="tab-pane {{$groups === reset($data) ? 'active' : ''}}" id="{{$year}}">

            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col-lg-8 col-md-11">
                    <div class="alert alert-info">
                        <strong>New!!</strong> All data will be saved each time you submit a form. No need to submit
                        one by one any more, just fill everything you want and click any
                        {!! Form::submit('Update', array('class' => 'btn btn-xs btn-success')) !!} button, including this one.
                    </div>
                </div>

                @foreach($groups as $groupId => $groupData)

                    <div class="col-md-12"><h2>{{ $groupData['name'] }}</h2></div>
                    <div class="col-lg-8 col-md-11">
                        @foreach($groupData['data'] as $key => $dataItem)
                            {!! Form::hidden('data[' . implode('-', [$year, $groupId, $key]) . '][year]', $dataItem->year) !!}
                            {!! Form::hidden('data[' . implode('-', [$year, $groupId, $key]) . '][month]', $dataItem->month) !!}

                            <table class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th colspan="{{ count($dataItem->group->trainingDays($dataItem->year, $dataItem->month)) + 1 }}">
                                        {!!
                                            link_to_route(
                                                'group.data.show',
                                                \Carbon\Carbon::createFromDate($dataItem->year, $dataItem->month, 1)->format('F, Y'),
                                                array($dataItem->group_id, $dataItem->year, $dataItem->month, 'highlight' => $user->id),
                                                array('class' => 'btn btn-xs btn-success')
                                            )
                                        !!}

                                        @if(($dataItem->year . '.' . $dataItem->month) === date('Y.n', time()))
                                            <span class="label label-primary">Current month</span>
                                        @endif

                                        {!!
                                            link_to_route(
                                                'group.data.index',
                                                $dataItem->group->name,
                                                array($dataItem->group_id),
                                                array('class' => 'pull-right btn btn-xs btn-primary')
                                            )
                                        !!}
                                    </th>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                    @foreach($dataItem->group->trainingDays($dataItem->year, $dataItem->month) as $day)
                                        <th width="35" class="text-center">{{ $day->day }}</th>
                                    @endforeach
                                </tr>

                                </thead>
                                <tbody>
                                <tr>
                                    <th>&nbsp;</th>
                                    @foreach($dataItem->group->trainingDays($dataItem->year, $dataItem->month) as $day)
                                        <td class="text-center">
                                            <label for="attendance_{{ $dataItem->user_id }}_{{ $dataItem->group_id }}_{{ $dataItem->month }}_{{ $day->day }}" style="width: 100%; height: 100%;">
                                                {!! Form::hidden('data[' . implode('-', [$year, $groupId, $key]) . '][data][' . $dataItem->group_id . '][attendance][' . $day->day . ']', 0) !!}
                                                {!! Form::checkbox('data[' . implode('-', [$year, $groupId, $key]) . '][data][' . $dataItem->group_id . '][attendance][' . $day->day . ']', 1, $dataItem->attendance($day->day) == '1' ? true : false, array('id' => 'attendance_' . $dataItem->user_id . '_' . $dataItem->group_id . '_' . $dataItem->month . '_' . $day->day)) !!}
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="{{ count($dataItem->group->trainingDays($dataItem->year, $dataItem->month)) + 1 }}" class="text-right">
                                        {!! Form::submit('Update', array('class' => 'btn btn-xs btn-success')) !!}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    </div>
    {!! Former::close() !!}
@stop