@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    {{ Form::open(array('method' => 'POST', 'route' => 'member.store')) }}
    
        <div>
            {{ Form::label('first_name', 'First Name') }}
            {{ Form::text('first_name') }}
            @if($errors->has('first_name'))
                {{ $errors->first('first_name') }}
            @endif
        </div>

        <div>
            {{ Form::label('last_name', 'Last Name') }}
            {{ Form::text('last_name') }}
            @if($errors->has('last_name'))
                {{ $errors->first('last_name') }}
            @endif
        </div>

        <div>
            {{ Form::label('dob', 'Date of Birth') }}
            {{ Form::text('dob') }}
            @if($errors->has('dob'))
                {{ $errors->first('dob') }}
            @endif
        </div>

        <div>
            {{ Form::label('dos', 'Date of Subscription') }}
            {{ Form::text('dos') }}
            @if($errors->has('dos'))
                {{ $errors->first('dos') }}
            @endif
        </div>
    
        <div>
            {{ Form::submit('Create') }}
        </div>
    
    {{ Form::close() }}
    
@stop