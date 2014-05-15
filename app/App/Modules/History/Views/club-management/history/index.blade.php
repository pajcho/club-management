@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    History ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">History</h1>

    @include(theme_view('history/_search_form'))

    <h1 class="page-header"></h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th width="300">Date</th>
                    <th>History</th>
                </tr>
            </thead>
            <tbody>
                @if($history->count())
                    @foreach($history as $key => $item)
                        <tr>
                            <td>{{ $history->getFrom() + $key }}</td>
                            <td>{{ $item->created_at->diffForHumans() }}</td>
                            <td>{{ $item->message }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" align="center">
                            There is no history for now
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($history->count())
            {{ $history->appends(Input::except('page', '_pjax'))->links(theme_view('paginator.club')) }}
        @endif
    </div>

@stop