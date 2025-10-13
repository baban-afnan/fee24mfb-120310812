<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SendMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sendMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the Laravel queue worker to process mail jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing mail jobs...');

        Artisan::call('queue:work', [
            '--stop-when-empty' => true, // stop after processing all pending jobs
        ]);

        $this->info('All pending mail jobs have been processed.');
    }
}
