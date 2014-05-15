@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members :: Update Memeber ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Members <small>{{ $member->full_name }}</small></h1>

    {{ Former::open()->method('PUT')->action(route('member.update', $member->id)) }}

        {{ Former::populate($member) }}

        @include(theme_view('member/_form'))
    
        <div class="well">
            {{
                Former::actions(
                    Former::link('Cancel', route('member.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Update')
                )
            }}
        </div>
    
    {{ Former::close() }}
    
@stop