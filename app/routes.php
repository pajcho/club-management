<?php

use App\Modules\Members\Models\MemberGroupData;
use App\Modules\Members\Models\MemberGroupDetails;

Route::get('/details-to-data', array(function()
    {
        $details = MemberGroupDetails::all();

        foreach($details as $detail)
        {
            foreach($detail->details('attendance') as $memberId => $attendance)
            {
                $data = array(
                    'group_id' => (int) $detail->group_id,
                    'member_id' => (int) $memberId,
                    'year' => (int) $detail->year,
                    'month' => (int) $detail->month,
                    'payed' => (int) $detail->details('payment.'.$memberId),
                    'attendance' => json_encode($attendance),
                );

                MemberGroupData::create($data);
            }

        }
    }));

    Route::get('/', array('before' => 'auth', function()
    {
        Session::reflash();
        return Redirect::route('member.index');
    }));