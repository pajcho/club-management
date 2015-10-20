@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Results ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-trophy"></i> Results</h1>

    @include('result/_search_form')

    <h1 class="page-header"></h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Full Name</th>
                    <th>Place</th>
                    <th>Type</th>
                    <th>Year</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th width="80">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($results->count())
                    @foreach($results as $key => $result)
                        <tr>
                            <td>{{ $results->firstItem() + $key }}</td>
                            <td>{{ $result->member->full_name }}</td>
                            <td>{{ $result->place }}</td>
                            <td>{{ $result->type }}</td>
                            <td>{{ $result->year }}</td>
                            <td>{{ $result->category->name }}</td>
                            <td>{{ $result->subcategory }}</td>
                            <td>
                                {!! Html::decode(link_to_route('result.show', '<i class="fa fa-pencil text-success"></i>', array($result->id), array('class' => 'btn btn-xs btn-default', 'title' => 'Update this item'))) !!}
                                {!! Html::decode(Form::delete(route('result.destroy', array($result->id)), '<i class="fa fa-trash-o text-danger"></i>', array('class' => 'btn btn-xs btn-default', 'title' => 'Delete this item', 'data-modal-text' => 'delete this result?'))) !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" align="center">
                            There are no results <br/>
                            {!! link_to_route('result.create', 'Create new result', null, array('class' => 'btn btn-xs btn-info')) !!}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($results->count())
            @include('paginator/club', ['paginator' => $results->appends(Input::except('page', '_pjax'))])
        @endif
    </div>

@stop