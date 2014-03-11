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
                <th colspan="{{ 3 + count($memberGroup->training_days) }}">Evidencija pohadjanja treninga</th>
            </tr>

            <tr>
                <th colspan="2">{{ Config::get('settings.att_doc_month_translation', 'Month') }}</th>
                <th colspan="{{ count($memberGroup->training_days) }}"><strong>{{ Carbon\Carbon::now()->format('F'); }}</strong></th>
                <th><strong>{{ $memberGroup->name }}</strong></th>
            </tr>

            <tr>
                <th colspan="2">{{ Config::get('settings.att_doc_name_translation', 'Full Name') }}</th>

                @foreach($memberGroup->training_days as $day)
                    <th width="35">{{ $day->day }}</th>
                @endforeach

                <th>{{ Config::get('settings.att_doc_phone_translation', 'Phone') }}</th>
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