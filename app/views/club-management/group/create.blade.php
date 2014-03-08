@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Member Groups :: Create Memeber Group ::
    @parent
@stop

@section('scripts')
    <link href="{{ asset(theme_path('css/bootstrap-slider.css')) }}" rel="stylesheet" type="text/css"/>
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Member Groups <small>Create Member Group</small></h1>

    {{ Form::open(array('method' => 'POST', 'route' => 'group.store', 'class' => 'form')) }}

        @include(theme_view('group/_form'))
    
        <div class="well">
            {{ link_to_route('group.index', 'Cancel') }}
            {{ Form::button('Reset', array('type' => 'reset', 'class' => 'btn btn-default')) }}
            {{ Form::submit('Create and Add New', array('class' => 'btn btn-info', 'name' => 'create_and_add', 'value' => '1')) }}
            {{ Form::submit('Create and Exit', array('class' => 'btn btn-success', 'name' => 'create_and_exit', 'value' => '1')) }}
        </div>
    
    {{ Form::close() }}
    
@stop

@section('scripts')
    <script src="{{ asset(theme_path('js/bootstrap-slider.js')) }}" type="text/javascript"></script>
@stop