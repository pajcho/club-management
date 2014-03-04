@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Members</h1>
<!--    <h2 class="sub-header">Members</h2>-->

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Subscribed</th>
                    <th>Active</th>
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
                            <td>{{ $member->active ? 'Yes' : 'No' }}</td>
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

@stop