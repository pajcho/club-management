@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Settings</h1>

    {{ Former::open()->action(route('settings.store')) }}

        @foreach($settings as $setting)

            {{ Former::text('settings[' . $setting->id . '][value]')->value($setting->value)->label($setting->title)->help($setting->description) }}

        @endforeach

        <div class="well">
            {{
                Former::actions(
                    Former::link('Cancel', route('settings.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Save Settings')
                )
            }}
        </div>

    {{ Former::close() }}

@stop