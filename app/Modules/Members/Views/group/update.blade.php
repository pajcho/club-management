@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Member Groups :: Update Memeber Group ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        <i class="fa fa-users"></i> Member Groups <small>{{ $memberGroup->name }}</small>
        <div class="pull-right">
            <a class="btn btn-sm btn-default" href="{{ route('group.data.index', [$memberGroup->id]) }}"><i class="fa fa-clock-o text-info"></i> Payments and Attendance</a>
        </div>
    </h1>

    {!! Former::open()->method('PUT')->action(route('group.update', $memberGroup->id)) !!}

        {!! Former::populate($memberGroup) !!}
        {!! Former::populateField('trainers[]', $memberGroup->trainer_ids) !!}

        @include('group/_form')

        <div class="well">
            {!!
                Former::actions(
                    Former::link('Cancel', route('group.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Update')
                )
            !!}
        </div>

    {!! Former::close() !!}

@stop
