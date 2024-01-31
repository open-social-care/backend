<?php

namespace App\Console\Commands\Populate;

use Illuminate\Console\Command;

class Populate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate';

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
     * @return mixed
     */
    public function handle()
    {
        $this->info('Populating data!');

        $this->call('populate:organization-with-users');

        $this->info('Finished data population!');
    }
}
