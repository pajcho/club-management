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
                <th colspan="{{ 3 + count($memberGroup->training_days) }}" class="no-border">{{ Config::get('settings.club_name') }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($memberGroup->training_days) }}" class="no-border">{{ $memberGroup->location }} - {{ $memberGroup->name }}</th>
            </tr>

            <tr>
                <th colspan="{{ 3 + count($memberGroup->training_days) }}">
                    {{ Lang::has('documents.attendance.title') ? Lang::get('documents.attendance.title') : 'Monthly group attendance list' }}
                </th>
            </tr>

            <tr>
                <th colspan="2">
                    {{ Lang::has('documents.attendance.month') ? Lang::get('documents.attendance.month') : 'Month' }}
                </th>
                <th colspan="{{ count($memberGroup->training_days) }}">
                    <strong>
                        {{ Lang::has('dates.month.' . Carbon\Carbon::now()->month) ? Lang::get('dates.month.' . Carbon\Carbon::now()->month) : Carbon\Carbon::now()->format('F') }}
                    </strong>
                </th>
                <th><strong>{{ $memberGroup->name }}</strong></th>
            </tr>

            <tr>
                <th colspan="2">
                    {{ Lang::has('documents.attendance.name') ? Lang::get('documents.attendance.name') : 'Full Name' }}
                </th>

                @foreach($memberGroup->training_days as $day)
                    <th width="35">{{ $day->day }}</th>
                @endforeach

                <th>
                    {{ Lang::has('documents.attendance.phone') ? Lang::get('documents.attendance.phone') : 'Phone' }}
                </th>
            </tr>
        
        </thead>
        <tbody>
            @if($members->count())
                @foreach($members as $key => $member)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td style="text-align: left;">{{ $member->full_name }}</td>

                        @foreach($memberGroup->training_days as $day)
                            <td>&nbsp;</td>
                        @endforeach

                        <td>{{ $member->phone }}</td>
                    </tr>
                @endforeach

                {{-- Fill rest of the page with empty rows, so new members can be hand written --}}
                @for($i = 0; $i < 35-(($key+1)%36); $i++)
                    <tr>
                        <td>{{ $key+$i+2 }}</td>
                        <td>&nbsp;</td>

                        @foreach($memberGroup->training_days as $day)
                            <td>&nbsp;</td>
                        @endforeach

                        <td>&nbsp;</td>
                    </tr>
                @endfor

            @endif
        </tbody>
    </table>

@stop