<?php

namespace Theanik\LaravelMoreCommand\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old log data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        exec('rm -f ' . storage_path('logs/*.log'));

        $this->info("Logs have been cleared!");

        Log::info("Log Cleared at ".date('l jS \of F Y h:i:s A'));
    }
}
