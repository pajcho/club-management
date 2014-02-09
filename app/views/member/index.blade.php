@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Members ::
    @parent
@stop

{{-- Page content --}}
@section('content')

    @if($members->count())
        <ul>
            @foreach($members as $member)
                <li>{{ $member->fullName() }}</li>
            @endforeach
        </ul>
    @else
        <h3>There are no members</h3>
    @endif
    
@stop