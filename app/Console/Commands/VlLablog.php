<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Vl;

class VlLablog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-lablogs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create entry for lablogs.';

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
        //

        $vl = new Vl;

        $output = $vl->finish_labs();

        $this->info($output);
    }
}
