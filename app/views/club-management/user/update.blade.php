@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Users :: Create User ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Users <small>{{ $user->full_name }}</small></h1>

    {{ Former::open()->method('PUT')->action(route('user.update', $user->id)) }}

        {{ Former::populate($user) }}
        {{ Former::populateField('groups[]', $user->group_ids) }}

        @include(theme_view('user/_form'), array('form' => 'update'))

        <div class="well">
            {{
                Former::actions(
                    Former::link('Cancel', route('user.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Update')
                )
            }}
        </div>
    
    {{ Former::close() }}
    
@stop