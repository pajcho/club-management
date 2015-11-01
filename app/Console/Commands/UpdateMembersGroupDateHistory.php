<?php

namespace App\Console\Commands;

use App\Modules\Members\Models\DateHistory;
use App\Modules\Members\Models\Member;
use Illuminate\Console\Command;

class UpdateMembersGroupDateHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-members-group-date-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will create new entries in date-history database table for existing members' .
                            ' because this is new functionality and it requires members to have this value in database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
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
    }
}
