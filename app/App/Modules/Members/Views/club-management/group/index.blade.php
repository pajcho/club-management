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

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Hours this month</th>
                    <th>Members</th>
                    <th></th>
                    @if($currentUser->isAdmin())
                        <th width="80">Actions</th>
                    @endif
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
                                {{ HTML::decode(link_to_route('member.index', '<i class="glyphicon glyphicon-user"></i> View Members', array('group_id' => $memberGroup->id), array('class' => 'btn btn-xs btn-link'))) }}
                            </td>
                            <td>
                                {{ link_to_route('group.data.index', 'Payments & Attendance', array($memberGroup->id), array('class' => 'btn btn-xs btn-info')) }}
                            </td>
                            @if($currentUser->isAdmin())
                                <td>
                                    {{ HTML::decode(link_to_route('group.show', '<i class="glyphicon glyphicon-edit"></i>', array($memberGroup->id), array('class' => 'btn btn-xs btn-success', 'title' => 'Update this item'))) }}
                                    {{ HTML::decode(Form::delete(route('group.destroy', array($memberGroup->id)), '<i class="glyphicon glyphicon-remove"></i>', array('class' => 'btn btn-xs btn-danger', 'title' => 'Delete this item', 'data-modal-text' => 'delete this member group?'))) }}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ $currentUser->isAdmin() ? 7 : 6 }}" align="center">
                            There are no member groups
                            @if($currentUser->isAdmin())
                                <br/>{{ link_to_route('group.create', 'Create new member group', null, array('class' => 'btn btn-xs btn-info')) }}
                            @endif
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

@stop