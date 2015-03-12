@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members :: Payments and Attendance ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        <i class="fa fa-user"></i> Members <small>{{ $member->full_name }}</small>
        <div class="pull-right">
            <a class="btn btn-info" href="{{ route('member.show', array($member->id)) }}"><i class="fa fa-arrow-left"></i> Back to member</a>
        </div>
    </h1>


    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($data as $year => $groups)
                <li class="{{$groups === reset($data) ? 'active' : ''}}"><a href="#{{$year}}" role="tab" data-toggle="tab">{{$year}}</a></li>
            @endforeach
        </ul>
    </div>

    {!! Former::open()->method('PUT')->action(route('member.payments.update', array($member->id))) !!}
    <div class="tab-content">
        @foreach($data as $year => $details)
            <div role="tabpanel" class="tab-pane {{$details === reset($data) ? 'active' : ''}}" id="{{$year}}">
                <div class="row">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-8 col-md-11">
                        <div class="alert alert-info">
                            <strong>New!!</strong> All data will be saved each time you submit a form. No need to submit
                            one by one any more, just fill everything you want and click any
                            {!! Form::submit('Update', array('class' => 'btn btn-xs btn-success')) !!} button, including this one.
                        </div>
                        @foreach($details as $key => $dataItem)
                            {!! Form::hidden('data[' . implode('-', [$year, $key]) . '][year]', $dataItem->year) !!}
                            {!! Form::hidden('data[' . implode('-', [$year, $key]) . '][month]', $dataItem->month) !!}

                            <?php
                            // Defaults to a member that was inactive on a month that was not payed
                            $itemClass = 'info';

                            // If member payed for this month
                            if($member->freeOfChargeOnDate($dataItem->year, $dataItem->month, $member->freeOfCharge) || $dataItem->payed)
                            {
                                $itemClass = 'success';
                            }
                            // If member was active on this month but not payed yet
                            elseif($member->activeOnDate($dataItem->year, $dataItem->month, $member->active))
                            {
                                $itemClass = 'warning';
                            }
                            ?>

                            <table class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th colspan="{{ count($dataItem->group->trainingDays($dataItem->year, $dataItem->month)) + 2 }}" class="{{$itemClass}}">
                                        {!!
                                            link_to_route(
                                                'group.data.show',
                                                \Carbon\Carbon::createFromDate($dataItem->year, $dataItem->month, 1)->format('F, Y'),
                                                array($dataItem->group_id, $dataItem->year, $dataItem->month, 'highlight' => $member->id),
                                                array('class' => 'btn btn-xs btn-' . $itemClass)
                                            )
                                        !!}

                                        @if(($dataItem->year . '.' . $dataItem->month) === date('Y.n', time()))
                                            <span class="label label-primary">Current month</span>
                                        @endif

                                        @if(!$member->activeOnDate($dataItem->year, $dataItem->month, $member->active))
                                            <small class="text-danger">Inactive!!</small>
                                        @endif
                                        {!!
                                            link_to_route(
                                                'group.data.index',
                                                $dataItem->group->name,
                                                array($dataItem->group_id),
                                                array('class' => 'pull-right btn btn-xs btn-' . ($member->group_id == $dataItem->group_id ? 'primary' : 'danger'))
                                            )
                                        !!}
                                    </th>
                                </tr>
                                <tr>
                                    <th width="100" class="text-center">Payment</th>
                                    <th>&nbsp;</th>
                                    @foreach($dataItem->group->trainingDays($dataItem->year, $dataItem->month) as $day)
                                        <th width="35" class="text-center">{{ $day->day }}</th>
                                    @endforeach
                                </tr>

                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">
                                        @if(!$member->freeOfChargeOnDate($dataItem->year, $dataItem->month, $member->freeOfCharge))
                                            <label for="payment_{{ $dataItem->member_id }}_{{ $dataItem->group_id }}_{{ $dataItem->month }}" style="width: 100%; height: 100%;">
                                                {!! Form::hidden('data[' . implode('-', [$year, $key]) . '][data][' . $dataItem->group_id . '][payed]', 0) !!}
                                                {!! Form::checkbox('data[' . implode('-', [$year, $key]) . '][data][' . $dataItem->group_id . '][payed]', 1, $dataItem->payed == '1' ? true : false, array('id' => 'payment_' . $dataItem->member_id . '_' . $dataItem->group_id . '_' . $dataItem->month)) !!}
                                            </label>
                                        @else
                                            <label for="payment_{{ $dataItem->group_id }}" style="width: 100%; height: 100%;" title="Free of charge"><i class="fa fa-star small"></i></label>
                                        @endif
                                    </td>
                                    <th>&nbsp;</th>
                                    @foreach($dataItem->group->trainingDays($dataItem->year, $dataItem->month) as $day)
                                        <td class="text-center">
                                            <label for="attendance_{{ $dataItem->member_id }}_{{ $dataItem->group_id }}_{{ $dataItem->month }}_{{ $day->day }}" style="width: 100%; height: 100%;">
                                                {!! Form::hidden('data[' . implode('-', [$year, $key]) . '][data][' . $dataItem->group_id . '][attendance][' . $day->day . ']', 0) !!}
                                                {!! Form::checkbox('data[' . implode('-', [$year, $key]) . '][data][' . $dataItem->group_id . '][attendance][' . $day->day . ']', 1, $dataItem->attendance($day->day) == '1' ? true : false, array('id' => 'attendance_' . $dataItem->member_id . '_' . $dataItem->group_id . '_' . $dataItem->month . '_' . $day->day)) !!}
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="{{ count($dataItem->group->trainingDays($dataItem->year, $dataItem->month)) + 2 }}" class="text-right">
                                        @if($dataItem->id)
                                            {!! Html::decode(Form::delete(route('member.payments.destroy', array($dataItem->member_id, $dataItem->group_id, $dataItem->year, $dataItem->month)), 'Delete?', array('class' => 'pull-left btn btn-xs btn-link', 'title' => 'Delete data for this month', 'data-modal-text' => 'delete data for this month?'))) !!}
                                        @endif
                                        {!! Form::submit('Update', array('class' => 'btn btn-xs btn-success')) !!}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {!! Former::close() }}

@stop