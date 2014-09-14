@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members :: Payments and Attendance ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        Members <small>{{ $member->full_name }}</small>
        <div class="pull-right">
            <a class="btn btn-info" href="{{ route('member.show', array($member->id)) }}"><i class="glyphicon glyphicon-circle-arrow-left"></i> Back to member</a>
        </div>
    </h1>

    @foreach($years as $year)
        <div class="row">
            <div class="col-md-1">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th class="info">{{ $year }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-8 col-md-11">
                @foreach($data as $key => $dataItem)
                    @if($dataItem->year == $year)

                        {{ Former::open()->method('PUT')->action(route('member.payments.update', array($member->id, $dataItem->year, $dataItem->month))) }}
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th colspan="{{ count($dataItem->attendance()) + 2 }}" class="{{ $member->freeOfChargeOnDate($dataItem->year, $dataItem->month, $member->freeOfCharge) || $dataItem->payed ? 'success' : 'warning' }}">
                                            {{
                                                link_to_route(
                                                    'group.data.show',
                                                    \Carbon\Carbon::createFromDate($dataItem->year, $dataItem->month)->format('F'),
                                                    array($dataItem->group_id, $dataItem->year, $dataItem->month, 'highlight' => $member->id),
                                                    array('class' => 'btn btn-xs btn-' . ($member->freeOfChargeOnDate($dataItem->year, $dataItem->month, $member->freeOfCharge) || $dataItem->payed ? 'success' : 'warning'))
                                                )
                                            }}
                                            {{
                                                link_to_route(
                                                    'group.data.index',
                                                    $dataItem->group->name,
                                                    array($dataItem->group_id),
                                                    array('class' => 'pull-right btn btn-xs btn-' . ($member->group_id == $dataItem->group_id ? 'success' : 'danger'))
                                                )
                                            }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th width="100" class="text-center">Payment</th>
                                        <th>&nbsp;</th>
                                        @foreach($dataItem->attendance() as $day => $attended)
                                            <th width="35" class="text-center">{{ $day }}</th>
                                        @endforeach
                                    </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">
                                            @if(!$member->freeOfChargeOnDate($dataItem->year, $dataItem->month, $member->freeOfCharge))
                                                <label for="payment_{{ $dataItem->member_id }}_{{ $dataItem->group_id }}_{{ $dataItem->month }}" style="width: 100%; height: 100%;">
                                                    {{ Form::hidden('data[' . $dataItem->group_id . '][payed]', 0) }}
                                                    {{ Form::checkbox('data[' . $dataItem->group_id . '][payed]', 1, $dataItem->payed == '1' ? true : false, array('id' => 'payment_' . $dataItem->member_id . '_' . $dataItem->group_id . '_' . $dataItem->month)) }}
                                                </label>
                                            @else
                                                <label for="payment_{{ $dataItem->group_id }}" style="width: 100%; height: 100%;" title="Free of charge"><i class="glyphicon glyphicon-star small"></i></label>
                                            @endif
                                        </td>
                                        <th>&nbsp;</th>
                                        @foreach($dataItem->attendance() as $day => $attended)
                                            <td class="text-center">
                                                <label for="attendance_{{ $dataItem->member_id }}_{{ $dataItem->group_id }}_{{ $dataItem->month }}_{{ $day }}" style="width: 100%; height: 100%;">
                                                    {{ Form::hidden('data[' . $dataItem->group_id . '][attendance][' . $day . ']', 0) }}
                                                    {{ Form::checkbox('data[' . $dataItem->group_id . '][attendance][' . $day . ']', 1, $attended == '1' ? true : false, array('id' => 'attendance_' . $dataItem->member_id . '_' . $dataItem->group_id . '_' . $dataItem->month . '_' . $day)) }}
                                                </label>
                                            </td>
                                        @endforeach
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="{{ count($dataItem->attendance()) + 2 }}" class="text-right">
                                            {{ Form::submit('Update', array('class' => 'btn btn-xs btn-success')) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        {{ Former::close() }}
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
@stop