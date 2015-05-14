<?php

    use App\Modules\History\Models\History;
    use App\Modules\Members\Models\DateHistory;
    use App\Modules\Members\Models\Member;
    use Carbon\Carbon;

    get('/', ['middleware' => 'auth', function () {
        Session::reflash();

        return redirect(route('member.index'));
    }]);

    /**
     * This will clear history database table from old entries because this
     * table is filling fast and we don't actually need all that data
     */
    get('/cron/clear-history/{months}', [function ($months) {
        $response = History::where('created_at', '<', Carbon::now()->subMonths($months))->delete();

        echo json_encode([
            'status'  => 'OK',
            'message' => sprintf('Deleted %d items', $response),
        ]);

    }]);

    /**
     * This will create new entries in date-history database table for existing members because
     * this is new functionality and it requires members to have this value in database
     */
    get('/cron/update-members-group-date-history', [function () {
        $members = Member::all();

        foreach ($members as $member) {
            $memberHistory = $member->dateHistory('group_id')->orderBy('date', 'asc')->get();

            if (!$memberHistory->count()) {
                // There are no history for this member and we need to create one
                $history = new DateHistory([
                    'date'  => $member->created_at,
                    'value' => $member->group_id,
                    'type'  => 'group_id',
                ]);

                $member->dateHistory()->save($history);
            } elseif ($memberHistory->count()) {
                $memberHistory->first()->update([
                    'date'  => $member->dos,
                    'value' => $member->group_id,
                    'type'  => 'group_id',
                ]);
            }
        }

    }]);