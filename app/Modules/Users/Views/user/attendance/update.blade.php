@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Users :: Current Attendance ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        Users <small>{{ $user->full_name }}</small>
        <div class="pull-right">
            <a class="btn btn-default" href="{{ route('user.show', [$user->id]) }}"><i class="fa fa-icon fa-pencil text-info"></i> User details</a>
            <a class="btn btn-default" href="{{ route('user.attendance.index', [$user->id]) }}"><i class="fa fa-icon fa-list text-info"></i> Full Attendance</a>
        </div>
    </h1>

    {!! Former::open()->method('PUT')->action(route('user.attendance.update', [$user->id])) !!}
    <div class="row">
        @foreach($data as $groupId => $groupData)

            <div class="col-lg-6 col-md-12 col-sm-12">
                <div class="col-md-12">
                    <h4> {{ $groupData['group']->name }} </h4>
                </div>
                <div class="col-md-12">
                    @foreach($groupData['data'] as $key => $dataItem)

                        {!! Form::hidden('data[' . implode('-', [$dataItem->year, $groupId, $key]) . '][year]', $dataItem->year) !!}
                        {!! Form::hidden('data[' . implode('-', [$dataItem->year, $groupId, $key]) . '][month]', $dataItem->month) !!}

                        <table class="table table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th colspan="{{ count($dataItem->group->trainingDays($dataItem->year, $dataItem->month)) + 1 }}">
                                    {!!
                                        link_to_route(
                                            'group.data.show',
                                            $today->format('F, Y'),
                                            [$dataItem->group_id, $dataItem->year, $dataItem->month, 'highlight' => $user->id],
                                            ['class' => 'btn btn-xs btn-success']
                                        )
                                    !!}

                                    {!!
                                        link_to_route(
                                            'group.data.index',
                                            $dataItem->group->name,
                                            [$dataItem->group_id],
                                            ['class' => 'pull-right btn btn-xs btn-primary']
                                        )
                                    !!}

                                </th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                @foreach($dataItem->group->trainingDays($dataItem->year, $dataItem->month) as $day)
                                    <th width="35" class="text-center {!! $day->year($dataItem->year)->month($dataItem->month)->isToday() ? 'info' : '' !!}">{{ $day->day }}</th>
                                @endforeach
                            </tr>

                            </thead>
                            <tbody>
                            <tr>
                                <th>&nbsp;</th>
                                @foreach($dataItem->group->trainingDays($dataItem->year, $dataItem->month) as $day)
                                    <td class="text-center {!! $day->year($dataItem->year)->month($dataItem->month)->isToday() ? 'info' : '' !!}">
                                        <label for="attendance_{{ $dataItem->user_id }}_{{ $dataItem->group_id }}_{{ $dataItem->month }}_{{ $day->day }}" style="width: 100%; height: 100%;">
                                            {!! Form::hidden('data[' . implode('-', [$dataItem->year, $groupId, $key]) . '][data][' . $dataItem->group_id . '][attendance][' . $day->day . ']', 0) !!}
                                            {!! Form::checkbox('data[' . implode('-', [$dataItem->year, $groupId, $key]) . '][data][' . $dataItem->group_id . '][attendance][' . $day->day . ']', 1, $dataItem->attendance($day->day) == '1' ? true : false, ['id' => 'attendance_' . $dataItem->user_id . '_' . $dataItem->group_id . '_' . $dataItem->month . '_' . $day->day]) !!}
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td colspan="{{ count($dataItem->group->trainingDays($dataItem->year, $dataItem->month)) + 1 }}" class="text-right">
                                    {!! Form::submit('Update', ['class' => 'btn btn-xs btn-success']) !!}
                                </td>
                            </tr>
                            </tfoot>
                        </table>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    {!! Former::close() !!}
@stop