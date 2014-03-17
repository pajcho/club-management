@extends(theme_view('layouts/pdf'))

@section('styles')
    <style>
        table thead th, table tbody tr {
            text-align: center;
        }
        table thead tr th.no-border {
            border: none !important;
        }
    </style>
@stop

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
                </th>
            </tr>

            <tr>
                <th colspan="2">
                    {{ Lang::has('documents.payments.name') ? Lang::get('documents.payments.name') : 'Full Name' }}
                </th>

                @foreach($months as $month => $year)
                    <th width="40">
                        {{ Lang::has('dates.month.' . $month) ? substr(Lang::get('dates.month.' . $month), 0, 3) : Carbon\Carbon::createFromDate($year, $month, 1)->format('M') }}
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

                        @foreach($months as $month => $year)

                            {{-- Mark month of subscription with border so we know when did member subscribed --}}
                            <td {{ ($year == $member->dos->year && $month == $member->dos->month) ? 'style="border-left: solid 2px black !important;"' : '' }}>

                                {{-- Write day of subscription in top left corner of field --}}
                                @if($year == $member->dos->year && $month == $member->dos->month)
                                    <div style="position: relative;">
                                        <span style="font-size: 7px; position: absolute; top: -4px; left: -3px;">{{ $member->dos->day }}</span>
                                    </div>
                                @endif
                                &nbsp;
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

                        @foreach($months as $month)
                            <td>&nbsp;</td>
                        @endforeach

                        <td>&nbsp;</td>
                    </tr>
                @endfor

            @endif
        </tbody>
    </table>

@stop