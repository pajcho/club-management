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

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Hours this month</th>
                    <th>Attendance</th>
                    <th>Payments</th>
                    <th>Members</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($memberGroups->count())
                    @foreach($memberGroups as $memberGroup)
                        <tr>
                            <td width="50">{{ $memberGroup->id }}</td>
                            <td>{{ $memberGroup->name }}</td>
                            <td>{{ $memberGroup->location }}</td>
                            <td>{{ $memberGroup->total_monthly_time }} hours</td>
                            <td>
                                @if($memberGroup->total_monthly_time)
                                    {{ link_to_route('group.attendance', 'Attendance PDF', array('id' => $memberGroup->id), array('class' => 'btn btn-xs btn-warning')) }}
                                @endif
                            </td>
                            <td>
                                {{ link_to_route('group.payments', 'Payments PDF', array('id' => $memberGroup->id), array('class' => 'btn btn-xs btn-warning')) }}
                            </td>
                            <td>
                                {{ link_to_route('member.index', 'View Members', array('group_id' => $memberGroup->id), array('class' => 'btn btn-xs btn-info')) }}
                            </td>
                            <td width="150">
                                {{ link_to_route('group.show', 'Update', array($memberGroup->id), array('class' => 'btn btn-xs btn-success')) }}
                                {{ Form::delete(route('group.destroy', array($memberGroup->id)), 'Delete', array('class' => 'btn btn-xs'), array('class' => 'btn btn-xs btn-danger')) }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" align="center">
                            There are no member groups <br/>
                            {{ link_to_route('group.create', 'Create new member group', null, array('class' => 'btn btn-xs btn-info')) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($memberGroups->count())
        {{ $memberGroups->appends(Input::except('page'))->links(theme_view('paginator.club')) }}
    @endif

@stop