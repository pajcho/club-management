@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Member Groups ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        <i class="fa fa-users"></i> Member Groups
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
                    <th width="80">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($memberGroups->count())
                    @foreach($memberGroups as $key => $memberGroup)
                        <tr>
                            <td>{{ $memberGroups->firstItem() + $key }}</td>
                            <td>{{ $memberGroup->name }}</td>
                            <td>{{ $memberGroup->location }}</td>
                            <td>{{ $memberGroup->total_monthly_time }} hours</td>
                            <td>
                                {!! Html::decode(link_to_route('member.index', '<i class="fa fa-user"></i> View Members', array('group_id' => $memberGroup->id), array('class' => 'btn btn-xs btn-link'))) !!}
                            </td>
                            <td>
                                {!! link_to_route('group.data.index', 'Payments & Attendance', array($memberGroup->id), array('class' => 'btn btn-xs btn-info')) !!}
                            </td>
                            <td>
                                {!! Html::decode(link_to_route('group.show', '<i class="fa fa-pencil"></i>', array($memberGroup->id), array('class' => 'btn btn-xs btn-success', 'title' => 'Update this item'))) !!}
                                @if($currentUser->isAdmin())
                                    {!! Html::decode(Form::delete(route('group.destroy', array($memberGroup->id)), '<i class="fa fa-remove"></i>', array('class' => 'btn btn-xs btn-danger', 'title' => 'Delete this item', 'data-modal-text' => 'delete this member group?'))) !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ $currentUser->isAdmin() ? 7 : 6 }}" align="center">
                            There are no member groups
                            @if($currentUser->isAdmin())
                                <br/>{!! link_to_route('group.create', 'Create new member group', null, array('class' => 'btn btn-xs btn-info')) !!}
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($memberGroups->count())
            @include(theme_view('paginator/club'), ['paginator' => $memberGroups->appends(Input::except('page', '_pjax'))])
        @endif
    </div>

@stop