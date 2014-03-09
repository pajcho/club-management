@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Settings</h1>

    {{ Form::open(array('method' => 'POST', 'route' => 'settings.store', 'class' => 'form')) }}

        @foreach($settings as $setting)
            <div class="form-group">
                {{ Form::label('settings[' . $setting->id . ']', $setting->title, array('class' => 'control-label')) }}
                {{ Form::text('settings[' . $setting->id . '][value]', $setting->value, array('class' => 'form-control')) }}
                <span class="help-block">{{ $setting->description }}</span>
            </div>
        @endforeach

        <div class="well">
            {{ link_to_route('settings.index', 'Cancel') }}
            {{ Form::button('Reset', array('type' => 'reset', 'class' => 'btn btn-default')) }}
            {{ Form::button('Save Settings', array('type' => 'submit', 'class' => 'btn btn-success')) }}
        </div>

    {{ Form::close() }}

@stop