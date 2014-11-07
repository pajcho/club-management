@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Users :: Create User ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-male"></i> Users <small>Create User</small></h1>

    {{ Former::open()->action(route('user.store')) }}

        @include(theme_view('user/_form'), array('form' => 'create'))

        <div class="well">
            {{
                Former::actions(
                    Former::link('Cancel', route('user.index')),
                    Former::default_reset('Reset'),
                    Form::button('Create and Add New', array('type' => 'submit', 'class' => 'btn btn-info', 'name' => 'create_and_add', 'value' => '1')),
                    Form::button('Create and Exit', array('type' => 'submit', 'class' => 'btn btn-success', 'name' => 'create_and_exit', 'value' => '1'))
                )
            }}
        </div>

    {{ Former::close() }}

@stop