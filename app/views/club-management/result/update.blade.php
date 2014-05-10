@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Results :: Create Memeber ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Results <small>{{ $result->member->full_name }}</small></h1>

    {{ Former::open()->method('PUT')->action(route('result.update', $result->id)) }}

        {{ Former::populate($result) }}

        @include(theme_view('result/_form'))
    
        <div class="well">
            {{
                Former::actions(
                    Former::link('Cancel', route('result.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Update')
                )
            }}
        </div>
    
    {{ Former::close() }}
    
@stop