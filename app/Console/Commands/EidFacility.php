<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Eid;


class EidFacility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eid-facility {year?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for eid facilities';

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

        $eid = new Eid;
        $output="";

        $output .= $eid->update_facilities_yearly($year);  
        $output .= $eid->update_facilities($year);      

        $this->info($output);
    }
}
