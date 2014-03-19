@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header">Members</h1>

    @include(theme_view('member/_search_form'))

    <h1 class="page-header"></h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Subscribed</th>
                    <th>&nbsp;</th>
                    <th>Phone</th>
                    <th width="150">Actions</th>
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
                                <span class="btn btn-xs {{ $member->getMedicalExaminationClass() }}" title="{{ $member->getMedicalExaminationTitle() }}">&nbsp;</span>
                            </td>
                            <td>{{ $member->phone }}</td>
                            <td>
                                {{ link_to_route('member.show', 'Update', array($member->id), array('class' => 'btn btn-xs btn-success')) }}
                                {{ Form::delete(route('member.destroy', array($member->id)), 'Delete', array('class' => 'btn btn-xs'), array('class' => 'btn btn-xs btn-danger')) }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" align="center">
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