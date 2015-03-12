@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Result Categories :: Create Result Category ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-bars"></i> Result Categories <small>Create Result Category</small></h1>

    {!! Former::open()->action(route('result.category.store')) !!}

        @include(theme_view('result/category/_form'))

        <div class="well">
            {!!
                Former::actions(
                    Former::link('Cancel', route('result.category.index')),
                    Former::default_reset('Reset'),
                    Form::button('Create and Add New', array('type' => 'submit', 'class' => 'btn btn-info', 'name' => 'create_and_add', 'value' => '1')),
                    Form::button('Create and Exit', array('type' => 'submit', 'class' => 'btn btn-success', 'name' => 'create_and_exit', 'value' => '1'))
                )
            !!}
        </div>

    {!! Former::close() !!}

@stop