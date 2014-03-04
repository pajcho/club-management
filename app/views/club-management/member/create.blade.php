@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members :: Create Memeber ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Members <small>Create Member</small></h1>

    {{ Form::open(array('method' => 'POST', 'route' => 'member.store', 'class' => 'form')) }}

        @include(theme_view('member/_form'))
    
        <div class="well">
            {{ link_to_route('member.index', 'Cancel') }}
            {{ Form::button('Reset', array('type' => 'reset', 'class' => 'btn btn-default')) }}
            {{ Form::submit('Create', array('class' => 'btn btn-success')) }}
        </div>
    
    {{ Form::close() }}
    
@stop