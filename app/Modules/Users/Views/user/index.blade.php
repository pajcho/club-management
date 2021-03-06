@extends('layouts/default')

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
                            <td>{{ $users->firstItem() + $key }}</td>
                            <td>
                                <img class="img-circle" width="20" height="20" src="{{ $user->gravatar }}" />
                                {{ $user->full_name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ implode(', ', $user->groups->lists('name')->all()) }}</td>
                            <td>{{ ucfirst($user->type) }}</td>
                            <td>
                                {!! Html::decode(link_to_route('user.show', '<i class="fa fa-pencil text-success"></i>', [$user->id], ['class' => 'btn btn-xs btn-default', 'title' => 'Update this item'])) !!}
                                {!! Html::decode(Form::delete(route('user.destroy', [$user->id]), '<i class="fa fa-trash-o text-danger"></i>', ['class' => 'btn btn-xs btn-default', 'title' => 'Delete this item', 'data-modal-text' => 'delete this user?'])) !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" align="center">
                            There are no users <br/>
                            {!! link_to_route('user.create', 'Create new user', null, ['class' => 'btn btn-xs btn-info']) !!}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($users->count())
            @include('paginator/club', ['paginator' => $users->appends(Input::except('page', '_pjax'))])
        @endif
    </div>

@stop