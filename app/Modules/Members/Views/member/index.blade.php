@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    <h1 class="page-header"><i class="fa fa-user"></i> Members</h1>

    @include('member/_search_form')

    <h1 class="page-header"></h1>

    <div class="table-responsive">
        <table class="table table-striped table-condensed table-hover">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th class="hidden-md hidden-sm hidden-xs" width="30"></th>
                    <th>Full Name</th>
                    <th class="hidden-md hidden-sm hidden-xs">Date of Birth</th>
                    <th class="hidden-md hidden-sm hidden-xs">Subscribed</th>
                    <th>Email</th>
                    <th class="hidden-md hidden-sm hidden-xs">&nbsp;</th>
                    <th>Phone</th>
                    <th width="80">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($members->count())
                    @foreach($members as $key => $member)
                        <tr>
                            <td>{{ $members->firstItem() + $key }}</td>
                            <td class="hidden-md hidden-sm hidden-xs">
                                <span class="btn btn-xs btn-circle {{$member->active ? 'btn-link' : 'btn-danger'}}" title="{{$member->active ? '' : 'Inactive member'}}">&nbsp;</span>
                                <span class="btn btn-xs btn-circle {{$member->freeOfCharge ? 'btn-success' : 'btn-link'}}" title="{{$member->freeOfCharge ? 'Free of charge member' : ''}}">&nbsp;</span>
                            </td>
                            <td>{{ $member->full_name }}</td>
                            <td class="hidden-md hidden-sm hidden-xs">{{ $member->dob->format('d.m.Y') }}</td>
                            <td class="hidden-md hidden-sm hidden-xs">{{ $member->dos->format('d.m.Y') }} ({{ $member->dos->diffForHumans() }})</td>
                            <td>{{ $member->email }}</td>
                            <td class="hidden-md hidden-sm hidden-xs">
                                <span class="btn btn-xs btn-circle {{ $member->getMedicalExaminationClass() }}" title="{{ $member->getMedicalExaminationTitle() }}">&nbsp;</span>
                            </td>
                            <td>{{ $member->phone }}</td>
                            <td>
                                {!! Html::decode(link_to_route('member.show', '<i class="fa fa-pencil"></i>', array($member->id), array('class' => 'btn btn-xs btn-success', 'title' => 'Update this item'))) !!}
                                {!! Html::decode(Form::delete(route('member.destroy', array($member->id)), '<i class="fa fa-remove"></i>', array('class' => 'btn btn-xs btn-danger', 'title' => 'Delete this item', 'data-modal-text' => 'delete this member?'))) !!}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" align="center">
                            There are no members <br/>
                            {!! link_to_route('member.create', 'Create new member', null, array('class' => 'btn btn-xs btn-info')) !!}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="pjax-pagination">
        @if($members->count())
            @include('paginator/club', ['paginator' => $members->appends(Input::except('page', '_pjax'))])
        @endif
    </div>

@stop

@section('styles')
    <style>

    </style>
@endsection