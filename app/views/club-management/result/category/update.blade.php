@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Result Categories :: Update Result Category ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Result Category <small>{{ $resultCategory->name }}</small></h1>

    {{ Former::open()->method('PUT')->action(route('result.category.update', $resultCategory->id)) }}

        {{ Former::populate($resultCategory) }}

        @include(theme_view('result/category/_form'))
    
        <div class="well">
            {{
                Former::actions(
                    Former::link('Cancel', route('result.category.index')),
                    Former::default_reset('Reset'),
                    Former::success_submit('Update')
                )
            }}
        </div>
    
    {{ Former::close() }}
    
@stop