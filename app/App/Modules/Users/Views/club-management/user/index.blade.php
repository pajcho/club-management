@extends(theme_view('layouts/default'))

{{-- Page title --}}
@section('title')
    Users ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-male"></i> Users</h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Groups</th>
                    <th width="120">Type</th>
                    <th width="80">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($users->count())
                    @foreach($users as $key => $user)
                        <tr>
                            <td>{{ $users->getFrom() + $key }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ implode(', ', $user->groups->lists('name')) }}</td>
                            <td>{{ ucfirst($user->type) }}</td>
                            <td>
                                {{ HTML::decode(link_to_route('user.show', '<i class="fa fa-pencil"></i>', array($user->id), array('class' => 'btn btn-xs btn-success', 'title' => 'Update this item'))) }}
                                {{ HTML::decode(Form::delete(route('user.destroy', array($user->id)), '<i class="fa fa-remove"></i>', array('class' => 'btn btn-xs btn-danger', 'title' => 'Delete this item', 'data-modal-text' => 'delete this user?'))) }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" align="center">
                            There are no users <br/>
                            {{ link_to_route('user.create', 'Create new user', null, array('class' => 'btn btn-xs btn-info')) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($users->count())
            {{ $users->appends(Input::except('page', '_pjax'))->links(theme_view('paginator.club')) }}
        @endif
    </div>

@stop