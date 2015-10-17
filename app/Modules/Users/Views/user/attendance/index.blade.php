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
            <a class="btn btn-info" href="{{ route('user.show', array($user->id)) }}">User details</a>
            <a class="btn btn-info" href="{{ route('user.attendance.show', array($user->id, $today->year, $today->month)) }}">Current Attendance</a>
        </div>
    </h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Month</th>
                    <th width="70">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($months->count())
                    @foreach($months as $key => $month)
                        <tr class="{{ $today->year == $month->year && $today->month == $month->month ? 'success bold' : '' }}">
                            <td>{{ $months->firstItem() + $key }}</td>
                            <td>
                                {{ $month->format('Y, F') }}

                                @if($today->year == $month->year && $today->month == $month->month)
                                    <span class="label label-primary">Current month</span>
                                @endif
                            </td>
                            <td>
                                {!! link_to_route('user.attendance.show', 'Update', array($user->id, $month->year, $month->month), array('class' => 'btn btn-xs btn-success')) !!}
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