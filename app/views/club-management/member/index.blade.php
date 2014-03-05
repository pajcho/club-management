@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">
        Members
        <div class="btn-group pull-right">
            {{ link_to_route('member.index', 'All members', null, array('class' => 'btn btn-md btn-info')) }}
            {{ link_to_route('member.index', 'Active members', array('active' => '1'), array('class' => 'btn btn-md btn-success')) }}
            {{ link_to_route('member.index', 'Inactive members', array('active' => '0'), array('class' => 'btn btn-md btn-warning')) }}
        </div>
    </h1>
<!--    <h2 class="sub-header">Members</h2>-->

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Subscribed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($members->count())
                    @foreach($members as $member)
                        <tr class="{{ $member->active ? '' : 'danger' }}">
                            <td>{{ $member->id }}</td>
                            <td>{{ $member->full_name }}</td>
                            <td>{{ $member->dob->format('F j, Y') }}</td>
                            <td>{{ $member->dos->format('F j, Y') }} ({{ $member->dos->diffForHumans() }})</td>
                            <td>
                                {{ link_to_route('member.show', 'Update', array($member->id), array('class' => 'btn btn-xs btn-success pull-left2')) }}
                                {{ Form::delete(route('member.destroy', array($member->id)), 'Delete', array('class' => 'btn btn-xs pull-left2'), array('class' => 'btn btn-xs btn-danger')) }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" align="center">
                            There are no members <br/>
                            {{ link_to_route('member.create', 'Create new member', null, array('class' => 'btn btn-xs btn-info')) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($members->count())
        {{ $members->appends(Input::except('page'))->links(theme_view('paginator.club')) }}
    @endif

@stop