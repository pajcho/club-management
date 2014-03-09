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

    <h1 class="page-header">Member Groups <small>{{ $memberGroup->name }}</small></h1>

    {{ Form::model($memberGroup, array('method' => 'PUT', 'route' => array('group.update', $memberGroup->id))) }}

        @include(theme_view('group/_form'))
    
        <div class="well">
            {{ link_to_route('group.index', 'Cancel') }}
            {{ Form::button('Reset', array('type' => 'reset', 'class' => 'btn btn-default')) }}
            {{ Form::button('Update', array('type' => 'submit', 'class' => 'btn btn-success')) }}
        </div>
    
    {{ Form::close() }}
    
@stop

@section('scripts')
    <script src="{{ asset(theme_path('js/bootstrap-slider.js')) }}" type="text/javascript"></script>
@stop