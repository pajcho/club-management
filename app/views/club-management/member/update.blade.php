@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members :: Create Memeber ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Members <small>{{ $member->full_name }}</small></h1>

    {{ Form::model($member, array('method' => 'PUT', 'route' => array('member.update', $member->id))) }}

        @include(theme_view('member/_form'))
    
        <div class="well">
            {{ link_to_route('member.index', 'Cancel') }}
            {{ Form::button('Reset', array('type' => 'reset', 'class' => 'btn btn-default')) }}
            {{ Form::button('Update', array('type' => 'submit', 'class' => 'btn btn-success')) }}
        </div>
    
    {{ Form::close() }}
    
@stop