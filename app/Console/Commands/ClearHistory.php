<?php

namespace App\Console\Commands;

use App\Modules\History\Models\History;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-history {--months=2}';

    /**
     * The console command description.
     *
     * @var	string
     */
    protected $description = 'Clear items from history database table that are older that supplied number of months';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = History::where('created_at', '<', Carbon::now()->subMonths($this->option('months')))->count();

        echo json_encode([
            'status'  => 'OK',
            'message' => sprintf('Deleted %d items', $response),
        ]);
    }
}
