@extends('layouts/pdf')

{{-- Page content --}}
@section('content')

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th colspan="{{ 3 + count($memberGroup->trainingDays($year, $month)) }}" class="no-border">{{ app('config')->get('settings.club_name') }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($memberGroup->trainingDays($year, $month)) }}" class="no-border">{{ $memberGroup->location }} - {{ $memberGroup->name }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($memberGroup->trainingDays($year, $month)) }}">
                    {{ Lang::has('members::documents.attendance.title') ? Lang::get('members::documents.attendance.title') : 'Monthly group attendance list' }}
                </th>
            </tr>

            <tr>
                <th colspan="2">
                    {{ Lang::has('members::documents.attendance.month') ? Lang::get('members::documents.attendance.month') : 'Month' }}
                </th>
                <th colspan="{{ count($memberGroup->trainingDays($year, $month)) }}">
                    <strong>
                        {{ Lang::has('dates.month.' . $month) ? Lang::get('dates.month.' . $month) : Carbon\Carbon::create($year, $month, 1)->format('F') }} {{ $year }}
                    </strong>
                </th>
                <th><strong>{{ $memberGroup->name }}</strong></th>
            </tr>

            <tr>
                <th width="35">#</th>
                <th style="text-align: left;">
                    {{ Lang::has('members::documents.attendance.name') ? Lang::get('members::documents.attendance.name') : 'Full Name' }}
                </th>

                @foreach($memberGroup->trainingDays($year, $month) as $day)
                    <th width="35">{{ $day->day }}</th>
                @endforeach

                <th>
                    {{ Lang::has('members::documents.attendance.phone') ? Lang::get('members::documents.attendance.phone') : 'Phone' }}
                </th>
            </tr>
        
        </thead>
        <tbody>
            @if($members->count())
                @foreach($members as $key => $member)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td style="text-align: left;">{{ $member->full_name }}</td>

                        @foreach($memberGroup->trainingDays($year, $month) as $day)
                            <td {{ ($year == $member->dos->year && $month == $member->dos->month && $day->day == $member->dos->day ) ? 'style="border-left: solid 2px black !important;"' : '' }}>
                                {{ $memberGroup->data($year, $month, $member->id) ? ($memberGroup->data($year, $month, $member->id)->attendance($day->day) ? '+' : '&nbsp;') : '&nbsp;' }}
                            </td>
                        @endforeach

                        <td>{{ $member->phone }}</td>
                    </tr>
                @endforeach

                {{-- Fill rest of the page with empty rows, so new members can be hand written --}}
                @for($i = 0; $i < 34-(($key+1)%36); $i++)
                    <tr>
                        <td>{{ $key+$i+2 }}</td>
                        <td>&nbsp;</td>

                        @foreach($memberGroup->trainingDays($year, $month) as $day)
                            <td>&nbsp;</td>
                        @endforeach

                        <td>&nbsp;</td>
                    </tr>
                @endfor

            @endif
        </tbody>
    </table>

@stop