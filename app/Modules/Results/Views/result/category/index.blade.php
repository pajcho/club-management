@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Result Categories ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-bars"></i> Result Categories</h1>

    @include('result/category/_search_form')

    <h1 class="page-header"></h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Name</th>
                    <th width="80">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($resultCategories->count())
                    @foreach($resultCategories as $key => $category)
                        <tr>
                            <td>{{ $resultCategories->firstItem() + $key }}</td>
                            <td>{{ $category->name }}</td>
                            <td>
                                {!! Html::decode(link_to_route('result.category.show', '<i class="fa fa-pencil text-success"></i>', [$category->id], ['class' => 'btn btn-xs btn-default', 'title' => 'Update this item'])) !!}
                                {!! Html::decode(Form::delete(route('result.category.destroy', [$category->id]), '<i class="fa fa-trash-o text-danger"></i>', ['class' => 'btn btn-xs btn-default', 'title' => 'Delete this item', 'data-modal-text' => 'delete this result category?'])) !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" align="center">
                            There are no result categories <br/>
                            {!! link_to_route('result.category.create', 'Create new result category', null, ['class' => 'btn btn-xs btn-info']) !!}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($resultCategories->count())
            @include('paginator/club', ['paginator' => $resultCategories->appends(Input::except('page', '_pjax'))])
        @endif
    </div>

@stop