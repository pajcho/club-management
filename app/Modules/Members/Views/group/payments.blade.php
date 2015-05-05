@extends('layouts/pdf')

{{-- Page content --}}
@section('content')

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th colspan="{{ 3 + count($months) }}" class="no-border">{{ app('config')->get('settings.club_name') }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($months) }}" class="no-border">{{ $memberGroup->location }} - {{ $memberGroup->name }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($months) }}">
                    {{ Lang::has('members::documents.payments.title') ? Lang::get('members::documents.payments.title') : 'Group payments' }}
                    (
                    {{ Lang::has('dates.month.' . $firstMonth) ? substr(Lang::get('dates.month.' . $firstMonth), 0, 3) : Carbon\Carbon::create($year, $firstMonth, 1)->format('M') }}
                    {{ head($months) }}
                    -
                    {{ Lang::has('dates.month.' . $lastMonth) ? substr(Lang::get('dates.month.' . $lastMonth), 0, 3) : Carbon\Carbon::create($year, $lastMonth, 1)->format('M') }}
                    {{ last($months) }}
                    )
                </th>
            </tr>

            <tr>
                <th width="35">#</th>
                <th style="text-align: left;">
                    {{ Lang::has('members::documents.payments.name') ? Lang::get('members::documents.payments.name') : 'Full Name' }}
                </th>

                @foreach($months as $tmp_month => $tmp_year)
                    <th width="40">
                        {{ Lang::has('dates.month.' . $tmp_month) ? substr(Lang::get('dates.month.' . $tmp_month), 0, 3) : Carbon\Carbon::create($tmp_year, $tmp_month, 1)->format('M') }}
                    </th>
                @endforeach

                <th>
                    {{ Lang::has('members::documents.payments.phone') ? Lang::get('members::documents.payments.phone') : 'Phone' }}
                </th>
            </tr>
        
        </thead>
        <tbody>
            @if($members->count())
                @foreach($members as $key => $member)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td style="text-align: left;">{{ $member->full_name }}</td>

                        @foreach($months as $tmp_month => $tmp_year)

                            {{-- We don't want anything displayed if user was not even subscribed --}}
                            @if(Carbon\Carbon::create($tmp_year, $tmp_month, 1)->endOfMonth()->lt($member->dos))
                                <td></td>
                            @else
                                {{-- Mark month of subscription with border so we know when did member subscribed --}}
                                <td {{ ($tmp_year == $member->dos->year && $tmp_month == $member->dos->month) ? 'style="border-left: solid 2px black !important;"' : '' }}>

                                    {{-- Write day of subscription in top left corner of field --}}
                                    @if($tmp_year == $member->dos->year && $tmp_month == $member->dos->month)
                                        <div style="position: relative;">
                                            <span style="font-size: 7px; position: absolute; top: -4px; left: -3px;">{{ $member->dos->day }}</span>
                                        </div>
                                    @endif

                                    @if($member->activeOnDate($tmp_year, $tmp_month, $member->active))
                                        @if(!$member->freeOfChargeOnDate($tmp_year, $tmp_month, $member->freeOfCharge))
                                            {{ $memberGroup->data($tmp_year, $tmp_month, $member->id) ? ($memberGroup->data($tmp_year, $tmp_month, $member->id)->payed ? '+' : '&nbsp;') : '&nbsp;' }}
                                        @else
                                            <!-- member is free of charge this month -->
                                            <i class="fa fa-star small"></i>
                                        @endif
                                    @else
                                        <!-- member is inactive this month -->
                                        /
                                    @endif
                                </td>
                            @endif

                        @endforeach

                        <td>{{ $member->phone }}</td>
                    </tr>
                @endforeach

                {{-- Fill rest of the page with empty rows, so new members can be hand written --}}
                @for($i = 0; $i < 35-(($key+1)%36); $i++)
                    <tr>
                        <td>{{ $key+$i+2 }}</td>
                        <td>&nbsp;</td>

                        @foreach($months as $tmp_month => $tmp_year)
                            <td>&nbsp;</td>
                        @endforeach

                        <td>&nbsp;</td>
                    </tr>
                @endfor

            @endif
        </tbody>
    </table>

@stop