<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\V2\Eid;

class EidPartner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:eid-partner {year?} {--type=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile summary tables for eid partners';

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
        $type = $this->option('type');

        if($type==1){
            $output .= $eid->update_partners($year);
            $output .= $eid->update_partners_yearly($year);
        }

        else{
            $output .= $eid->update_partners_yearly($year);
        }

        


        $this->info($output);
    }
}
