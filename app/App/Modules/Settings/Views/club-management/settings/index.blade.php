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

        @foreach($settings as $key => $setting)

            {{-- Wrap settings fields  --}}
            @if(Config::get('settings.per_row', 2) > 1)
                @if(($key+1)%Config::get('settings.per_row', 2) === 1)
                    <div class="row">
                @endif
                    <div class="col-md-{{ 12/Config::get('settings.per_row', 2) }}">
            @endif

            {{-- Display month settings differently --}}
            @if(in_array($setting->key, array('season_starts', 'season_ends')))
                {{ Former::select('settings[' . $setting->id . '][value]')->options(Lang::get('dates.month'))->value($setting->value)->label($setting->title)->help($setting->description) }}
            @else
                {{ Former::text('settings[' . $setting->id . '][value]')->value($setting->value)->label($setting->title)->help($setting->description) }}
            @endif

            {{-- Wrap settings fields  --}}
            @if(Config::get('settings.per_row', 2) > 1)
                    </div>
                @if(($key+1)%Config::get('settings.per_row', 2) === 0 || ($key+1) === count($settings))
                    </div>
                @endif
            @endif
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