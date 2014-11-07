@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Member Groups :: Payments & Attendance ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        Payments & Attendance <small>{{ $memberGroup->name }}</small>
        <div class="pull-right">
            <a class="btn btn-info" href="{{ route('group.index') }}"><i class="fa fa-arrow-left"></i> Back to groups</a>
        </div>
    </h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Month</th>
                    <th width="210" class="text-center">Payed?</th>
                    <th width="70">Actions</th>
                    <th width="100">Documents</th>
                </tr>
            </thead>
            <tbody>
                @if($months->count())
                    @foreach($months as $key => $month)
                        <tr class="{{ $today->year == $month->year && $today->month == $month->month ? 'success bold' : '' }}">
                            <td>{{ $months->getFrom() + $key }}</td>
                            <td>
                                {{ $month->format('Y, F') }}

                                @if($today->year == $month->year && $today->month == $month->month)
                                    <span class="label label-primary">Current month</span>
                                @endif
                            </td>
                            <td class="text-center ajax-content" data-url="{{route('group.data.get.payment-data', array($memberGroup->id, $month->year, $month->month))}}"></td>
                            <td>
                                {{ link_to_route('group.data.show', 'Update', array($memberGroup->id, $month->year, $month->month), array('class' => 'btn btn-xs btn-success')) }}
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a data-toggle="dropdown" href="#" class="btn btn-xs btn-default"><i class="fa fa-paperclip"></i> Documents <span class="caret"></span></a>
                                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                        <li>{{ HTML::decode(link_to_route('group.payments', '<i class="fa fa-print"></i> Payments', array($memberGroup->id, $month->year, $month->month), array('target' => '_blank', 'title' => 'Print Payments PDF', 'data-placement' => 'left'))) }}</li>

                                        @if($memberGroup->total_monthly_time)
                                            <li>{{ HTML::decode(link_to_route('group.attendance', '<i class="fa fa-print"></i> Attendance', array($memberGroup->id, $month->year, $month->month), array('target' => '_blank', 'title' => 'Print Attendance PDF', 'data-placement' => 'left'))) }}</li>
                                        @endif

                                        <li class="divider"></li>
                                        <li>{{ HTML::decode(link_to_route('group.payments', '<i class="fa fa-download"></i> Payments', array($memberGroup->id, $month->year, $month->month, 'download'), array('title' => 'Save Payments PDF', 'data-placement' => 'left'))) }}</li>

                                        @if($memberGroup->total_monthly_time)
                                            <li>{{ HTML::decode(link_to_route('group.attendance', '<i class="fa fa-download"></i> Attendance', array($memberGroup->id, $month->year, $month->month, 'download'), array('title' => 'Save Attendance PDF', 'data-placement' => 'left'))) }}</li>
                                        @endif

                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" align="center">
                            There is no data to display
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($months->count())
            {{ $months->appends(Input::except('page', '_pjax'))->links(theme_view('paginator.club')) }}
        @endif
    </div>

@stop

@section('scripts')
    @parent

    <script type="text/javascript">

        $(function(){

            $('.ajax-content').each(function(){
                var element = $(this).html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax($(this).data('url')).done(function(response){
                    element.html(response);
                });
            });

        });

    </script>

@stop