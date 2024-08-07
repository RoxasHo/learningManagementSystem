<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TransferCounters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'counters:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer data from the test table to the crontest table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch data from the original table
        $counters = DB::table('test')->get();

        // Insert or update data in the backup table
        foreach ($counters as $counter) {
            DB::table('crontest')->updateOrInsert(
                ['name' => $counter->name],
                ['counter' => $counter->counter]
            );
        }

        $this->info('Counters have been successfully transferred to the backup table.');
        return 0;
    }
}
