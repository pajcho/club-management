@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Users :: Update User ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        <i class="fa fa-male"></i> Users <small>{{ $user->full_name }}</small>
        <div class="pull-right">
            <a class="btn btn-default" href="{{ route('user.attendance.show', [$user->id, $today->year, $today->month]) }}"><i class="fa fa-icon fa-clock-o text-info"></i> Current Attendance</a>
        </div>
    </h1>

    {!! Former::open()->method('PUT')->action(route('user.update', $user->id)) !!}

        {!! Former::populate($user) !!}
        {!! Former::populateField('groups[]', $user->group_ids) !!}

        @include('user/_form', array('form' => 'update'))

        <div class="well">
            {!!
                Former::actions(
                    Former::link('Cancel', route('user.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Update')
                )
            !!}
        </div>
    
    {!! Former::close() !!}
    
@stop