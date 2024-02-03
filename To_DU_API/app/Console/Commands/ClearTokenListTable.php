<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearTokenListTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:tokenlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the token_lists table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::table('token_lists')->truncate();
        $this->info('Token list table cleared successfully.');
    }
}
