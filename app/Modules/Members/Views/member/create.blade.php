@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Members :: Create Memeber ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-user"></i> Members <small>Create Member</small></h1>

    {!! Former::open()->action(route('member.store')) !!}

        @include('member/_form')

        <div class="well">
            {!!
                Former::actions(
                    Former::link('Cancel', route('member.index')),
                    Former::default_reset('Reset'),
                    Form::button('Create and Add New', array('type' => 'submit', 'class' => 'btn btn-info', 'name' => 'create_and_add', 'value' => '1')),
                    Form::button('Create and Exit', array('type' => 'submit', 'class' => 'btn btn-success', 'name' => 'create_and_exit', 'value' => '1'))
                )
            !!}
        </div>

    {!! Former::close() !!}

@stop