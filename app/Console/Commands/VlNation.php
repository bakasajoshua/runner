<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Vl;

class VlNation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:vl-nation {year?} {--month=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for viralload nation';

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
        $year = $this->argument('year');
        $month = $this->option('month');

        $vl = new Vl;

        $output = $vl->update_nation($month, $year);

        $this->info($output);
    }
}
