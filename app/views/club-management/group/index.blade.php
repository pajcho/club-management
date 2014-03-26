@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Member Groups ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        Member Groups
    </h1>
<!--    <h2 class="sub-header">Members</h2>-->

    <div id="pjax-container">
        <div class="table-responsive">
            <table class="table table-striped table-condensed table-hover">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Hours this month</th>
                        <th width="210">Documents</th>
                        <th>Members</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($memberGroups->count())
                        @foreach($memberGroups as $key => $memberGroup)
                            <tr>
                                <td>{{ $memberGroups->getFrom() + $key }}</td>
                                <td>{{ $memberGroup->name }}</td>
                                <td>{{ $memberGroup->location }}</td>
                                <td>{{ $memberGroup->total_monthly_time }} hours</td>
                                <td>
                                    {{ HTML::decode(link_to_route('group.payments', '<i class="glyphicon glyphicon-print"></i> Payments', array('id' => $memberGroup->id), array('class' => 'btn btn-xs btn-link', 'title' => 'Generate Payments PDF'))) }}

                                    @if($memberGroup->total_monthly_time)
                                        {{ HTML::decode(link_to_route('group.attendance', '<i class="glyphicon glyphicon-print"></i> Attendance', array('id' => $memberGroup->id), array('class' => 'btn btn-xs btn-link', 'title' => 'Generate Attendance PDF'))) }}
                                    @endif
                                </td>
                                <td>
                                    {{ HTML::decode(link_to_route('member.index', '<i class="glyphicon glyphicon-user"></i> View Members', array('group_id' => $memberGroup->id), array('class' => 'btn btn-xs btn-link'))) }}
                                </td>
                                <td width="150">
                                    {{ link_to_route('group.show', 'Update', array($memberGroup->id), array('class' => 'btn btn-xs btn-success')) }}
                                    {{ Form::delete(route('group.destroy', array($memberGroup->id)), 'Delete', array('class' => 'btn btn-xs'), array('class' => 'btn btn-xs btn-danger')) }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" align="center">
                                There are no member groups <br/>
                                {{ link_to_route('group.create', 'Create new member group', null, array('class' => 'btn btn-xs btn-info')) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="pjax-pagination">
            @if($memberGroups->count())
                {{ $memberGroups->appends(Input::except('page', '_pjax'))->links(theme_view('paginator.club')) }}
            @endif
        </div>
    </div>

@stop