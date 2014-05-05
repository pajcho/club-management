@extends(theme_view('layouts/pdf'))

{{-- Page content --}}
@section('content')

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th colspan="{{ 3 + count($months) }}" class="no-border">{{ Config::get('settings.club_name') }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($months) }}" class="no-border">{{ $memberGroup->location }} - {{ $memberGroup->name }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($months) }}">
                    {{ Lang::has('documents.payments.title') ? Lang::get('documents.payments.title') : 'Group payments' }}
                    (
                    {{ Lang::has('dates.month.' . $first_month) ? substr(Lang::get('dates.month.' . $first_month), 0, 3) : Carbon\Carbon::createFromDate($year, $first_month, 1)->format('M') }}
                    {{ head($months) }}
                    -
                    {{ Lang::has('dates.month.' . $last_month) ? substr(Lang::get('dates.month.' . $last_month), 0, 3) : Carbon\Carbon::createFromDate($year, $last_month, 1)->format('M') }}
                    {{ last($months) }}
                    )
                </th>
            </tr>

            <tr>
                <th width="35">#</th>
                <th style="text-align: left;">
                    {{ Lang::has('documents.payments.name') ? Lang::get('documents.payments.name') : 'Full Name' }}
                </th>

                @foreach($months as $tmp_month => $tmp_year)
                    <th width="40">
                        {{ Lang::has('dates.month.' . $tmp_month) ? substr(Lang::get('dates.month.' . $tmp_month), 0, 3) : Carbon\Carbon::createFromDate($tmp_year, $tmp_month, 1)->format('M') }}
                    </th>
                @endforeach

                <th>
                    {{ Lang::has('documents.payments.phone') ? Lang::get('documents.payments.phone') : 'Phone' }}
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

                            {{-- Mark month of subscription with border so we know when did member subscribed --}}
                            <td {{ ($tmp_year == $member->dos->year && $tmp_month == $member->dos->month) ? 'style="border-left: solid 2px black !important;"' : '' }}>

                                {{-- Write day of subscription in top left corner of field --}}
                                @if($tmp_year == $member->dos->year && $tmp_month == $member->dos->month)
                                    <div style="position: relative;">
                                        <span style="font-size: 7px; position: absolute; top: -4px; left: -3px;">{{ $member->dos->day }}</span>
                                    </div>
                                @endif

                                @if(!$member->freeOfChargeOnDate($tmp_year, $tmp_month, $member->freeOfCharge))
                                    @if($member->activeOnDate($tmp_year, $tmp_month, $member->active))
                                        {{ $memberGroup->details($tmp_year, $tmp_month) ? ($memberGroup->details($tmp_year, $tmp_month)->details('payment.' . $member->id) ? '+' : '&nbsp;') : '&nbsp;' }}
                                    @else
                                        <!-- member is inactive this month -->
                                        /
                                    @endif
                                @else
                                    <!-- member is free of charge this month -->
                                    <i class="glyphicon glyphicon-star"></i>
                                @endif
                            </td>

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