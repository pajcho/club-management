@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Members :: Update Memeber ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        <i class="fa fa-user"></i> Members <small>{{ $member->full_name }}</small>
        <div class="pull-right">
            <a class="btn btn-sm btn-default" href="{{ route('member.payments.index', array($member->id)) }}"><i class="fa fa-icon fa-clock-o text-info"></i> Payments and Attendance</a>
        </div>
    </h1>

    {!! Former::open()->method('PUT')->action(route('member.update', $member->id)) !!}

        {!! Former::populate($member) !!}

        @include('member/_form')
    
        <div class="well">
            {!!
                Former::actions(
                    Former::link('Cancel', route('member.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Update')
                )
            !!}
        </div>
    
    {!! Former::close() !!}
    
@stop