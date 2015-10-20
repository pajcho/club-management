@extends('layouts/default')

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
                            <td><i class="fa fa-icon fa-clock-o text-info"></i> {{ $memberGroup->total_monthly_time }} hours</td>
                            <td>
                                {!! Html::decode(link_to_route('member.index', '<i class="fa fa-user text-info"></i> View Members', ['group_id' => $memberGroup->id], ['class' => 'btn btn-xs btn-default'])) !!}
                            </td>
                            <td>
                                {!! Html::decode(link_to_route('group.data.index', '<i class="fa fa-money text-info"></i> Payments & Attendance', [$memberGroup->id], ['class' => 'btn btn-xs btn-default'])) !!}
                            </td>
                            <td>
                                {!! Html::decode(link_to_route('group.show', '<i class="fa fa-pencil text-success"></i>', [$memberGroup->id], ['class' => 'btn btn-xs btn-default', 'title' => 'Update this item'])) !!}
                                @if($currentUser->isAdmin())
                                    {!! Html::decode(Form::delete(route('group.destroy', [$memberGroup->id]), '<i class="fa fa-trash-o text-danger"></i>', ['class' => 'btn btn-xs btn-default', 'title' => 'Delete this item', 'data-modal-text' => 'delete this member group?'])) !!}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ $currentUser->isAdmin() ? 7 : 6 }}" align="center">
                            There are no member groups
                            @if($currentUser->isAdmin())
                                <br/>{!! link_to_route('group.create', 'Create new member group', null, ['class' => 'btn btn-xs btn-info']) !!}
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($memberGroups->count())
            @include('paginator/club', ['paginator' => $memberGroups->appends(Input::except('page', '_pjax'))])
        @endif
    </div>

@stop