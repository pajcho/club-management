<?php

use App\Modules\History\Models\History;
use Carbon\Carbon;

Route::get('/', array('before' => 'auth', function()
{
    Session::reflash();
    return Redirect::route('member.index');
}));

Route::get('/cron/clear-history/{months}', array(function($months)
{
    $response = History::where('created_at', '>', Carbon::now()->subMonths($months))->delete();

    echo json_encode([
        'status' => 'OK',
        'message' => sprintf('Deleted %d items', $response),
    ]);

}));