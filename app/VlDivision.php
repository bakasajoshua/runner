<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BaseModel;

class VlDivision extends Model
{
    //Control Tests
	public function control_samples($year, $start_month){

    	$data = DB::connection('vl')
		->table('worksheets_vl')
		->select('worksheets_vl.lab', DB::raw("COUNT(*) as totals, month(daterun) as month"))
		->whereYear('daterun', $year)
		->whereMonth('daterun', '>', $start_month)
		->groupBy('month', 'worksheets_vl.lab')
		->get();

		return $data;
	}

    //Mapping
	public function lab_county_tests($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month, viralsamples.labtestedin"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->where('viralsamples.facility', '!=', 7148)
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division, 'viralsamples.labtestedin')
		->get();

		return $data;
	}
	
    //Mapping
	public function lab_mapping_sites($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.facility) as totals, month(datetested) as month, viralsamples.labtestedin"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->where('viralsamples.facility', '!=', 7148)
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division, 'viralsamples.labtestedin')
		->get();

		return $data;
	}

    //National rejections
	public function national_rejections($year, $start_month, $division='view_facilitys.county', $rejected_reason){

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereYear('datereceived', $year)
		->whereMonth('datereceived', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;
	}

	//National eqa
	public function get_eqa_tests($year, $start_month, $division='view_facilitys.county'){

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				// return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->where('facility', 7148)
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get(); 

		return $data;
	}
	

    public function getalltestedviraloadsamples($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				// return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallactualpatients($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.patient,viralsamples.facility) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallreceivediraloadsamples($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datereceived', $year)
		->whereMonth('datereceived', '>', $start_month)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamples($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datereceived', $year)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->whereMonth('datereceived', '>', $start_month)
		->where('viralsamples.receivedstatus', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetSupportedfacilitysFORViralLoad($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.facility) as totals, month(datereceived) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datereceived', $year)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->whereMonth('datereceived', '>', $start_month)
		->where('viralsamples.facility', '!=', 0)
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get();

		return $data;
    } 

    public function GetNationalConfirmed2VLs($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }


    public function GetNationalConfirmedFailure($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [3, 4])
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }
    

    public function false_confirmatory($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->where('viralsamples.previous_nonsuppressed', 1)
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalBaseline($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalBaselineFailure($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [3, 4])
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallrepeattviraloadsamples($year, $start_month, $division='view_facilitys.county'){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.receivedstatus', 3)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetails($year, $start_month, $division='view_facilitys.county', $sampletype, $routine=true){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->when($routine, function($query) use ($routine){
			return $query
			->where('viralsamples.justification', '!=', 2)
			->where('viralsamples.justification', '!=', 10);
		})
		->when($sampletype, function($query) use ($sampletype){
			if($sampletype == 2){
				return $query->whereIn('viralsamples.sampletype', [3, 4]);
			}
			else if($sampletype == 3){
				return $query->where('viralsamples.sampletype', 2);
			}
			else{
				return $query->where('viralsamples.sampletype', 1);
			}				
		})
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadbygender($year, $start_month, $division='view_facilitys.county', $sex){

    	$b = new BaseModel;
		$gender = $b->get_gender($sex);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->where('viralpatients.gender', $gender)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyage($year, $start_month, $division='view_facilitys.county', $age, $all=false){

    	$b = new BaseModel;
		$age_band = $b->get_vlage($age);

		$age_column = 'viralsamples.age2';
		$sql = "COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month";

		if($age < 6){
			$age_column = 'viralsamples.age';
		}

		if($all){
			$sql = "COUNT(viralsamples.ID) as totals, month(datetested) as month";
		}

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->where($age_column, $age)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsbyresult($year, $start_month, $division='view_facilitys.county', $result){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($result, function($query) use ($result){
			if($result != 5){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10);
			}
		})
		->where('viralsamples.rcategory', $result)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNatTATs($year, $start_month, $div_array, $division='view_facilitys.county', $col='county')
	{
		// $sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		$sql = "datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		ini_set("memory_limit", "-1");

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw($sql))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('viralsamples.datecollected', '>', 1980)
		->whereYear('viralsamples.datereceived', '>', 1980)
		->whereYear('viralsamples.datetested', '>', 1980)
		->whereYear('viralsamples.datedispatched', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->get(); 



		$return;
		$b = new BaseModel;

		$place = 0;

		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;

			for ($iterator=0; $iterator < count($div_array); $iterator++) { 
				$c = $div_array[$iterator];
				
				$d = $data->where('month', $month)->where($col, $c);

				if($d->isEmpty()){
					$return[$place]['tat1'] = 0;
					$return[$place]['tat2'] = 0;
					$return[$place]['tat3'] = 0;
					$return[$place]['tat4'] = 0;
					$return[$place]['division'] = $c;
					$return[$place]['month'] = $month;
					continue;
				}

				$tat1 = $tat2 = $tat3 = $tat4 = 0;
				$rows = $d->count();

				$holidays = $b->getTotalHolidaysinMonth($month);

				foreach ($d as $key => $value) {
					
					$tat1 += $b->get_days($value->datecollected, $value->datereceived, $holidays);
					$tat2 += $b->get_days($value->datereceived, $value->datetested, $holidays);
					$tat3 += $b->get_days($value->datetested, $value->datedispatched, $holidays);
					$tat4 += $b->get_days($value->datecollected, $value->datedispatched, $holidays);

				}

				$return[$place]['tat1'] = floor($tat1 / $rows);
				$return[$place]['tat2'] = floor($tat2 / $rows);
				$return[$place]['tat3'] = floor($tat3 / $rows);
				$return[$place]['tat4'] = floor($tat4 / $rows);
				$return[$place]['county'] = $c;
				$return[$place]['month'] = $month;

				$place++;

			}


		}

		return $return;
	}

	public function get_tat($year, $start_month, $division='view_facilitys.county'){
    	$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw($sql))
		->when($division, function($query) use ($division){
			if($division == "viralsamples.labtestedin" || $division == "viralsamples.facility"){
				return $query->where('viralsamples.facility', '!=', 7148);
			}
			else{
				return $query->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID');
			}
		})
		->whereYear('viralsamples.datecollected', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('datetested', $year)
		->whereYear('viralsamples.datecollected', '>', 1980)
		->whereYear('viralsamples.datereceived', '>', 1980)
		->whereYear('viralsamples.datetested', '>', 1980)
		->whereYear('viralsamples.datedispatched', '>', 1980)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get(); 

		return $data;
	}



    public function compare_alltestedviralloadsamples(){
    	$age = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND repeatt=0 and Flag=1 AND $colmn ='$age' and viralsamples.rcategory BETWEEN 1 AND 4";

    	$gender = "select count(viralsamples.ID)  as numsamples from viralsamples, viralpatients where  viralsamples.patientid=viralpatients.AutoID AND MONTH(datetested)='$month' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND viralpatients.gender='$gender' and viralsamples.rcategory BETWEEN 1 AND 4";

    	$regimen = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND prophylaxis='$regimen' and (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2";

    	$justification = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND justification='$justification'";

    	$sampletype = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND sampletype BETWEEN '$stype' AND '$ttype'";
    }

    public function getalltestedviraloadsamplesbydash($year, $start_month, $division='view_facilitys.county', $type, $param){

		$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}					
		})
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallreceivediraloadsamplesbydash($year, $start_month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->whereMonth('datereceived', '>', $start_month)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.Flag', 1)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getallrejectedviraloadsamplesbydash($year, $start_month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->whereMonth('datereceived', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalConfirmed2VLsbydash($year, $start_month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalConfirmedFailurebydash($year, $start_month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->whereIn('viralsamples.rcategory', [3, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function GetNationalBaselinebydash($year, $start_month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereIn('viralsamples.rcategory', [1, 2, 3, 4])
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=10 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year'   AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1 and rcategory between 1 and 4";
    }

    public function GetNationalBaselineFailurebydash($year, $start_month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereIn('viralsamples.rcategory', [3, 4])
		->whereIn('viralsamples.sampletype', [1, 2, 3, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=10 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND viralsamples.rcategory BETWEEN 3 AND 4  AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1";
    }

    public function getallrepeattviraloadsamplesbydash($year, $start_month, $division='view_facilitys.county', $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 3)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $start_month, $division='view_facilitys.county', $type, $param, $sampletype){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}					
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->when($sampletype, function($query) use ($sampletype){
			if($sampletype == 2){
				return $query->whereIn('viralsamples.sampletype', [3, 4]);
			}
			else if($sampletype == 3){
				return $query->where('viralsamples.sampletype', 2);
			}
			else{
				return $query->where('viralsamples.sampletype', 1);
			}				
		})
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $start_month, $division='view_facilitys.county', $type, $param, $gender){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);
		$sex = $b->get_gender($gender);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}					
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralpatients.gender', $sex)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $start_month, $division='view_facilitys.county', $type, $param, $age){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$age_column = 'viralsamples.age2';

		if($age < 6){
			$age_column = 'viralsamples.age';
		}

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}
			if($type == 4){
				return $query->whereIn('viralsamples.rcategory', [1, 2, 3, 4]);
			}					
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where($age_column, $age)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $start_month, $division='view_facilitys.county', $type, $param, $result){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select($division, DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->when($type, function($query) use ($type){
			if($type == 2 || $type == 6){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10);
			}				
		})
		->whereYear('datetested', $year)
		->whereMonth('datetested', '>', $start_month)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 4 && $p['param'] == 3){
				return $query->whereIn($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.rcategory', $result)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month', $division)
		->get();

		return $data;
    }

    public function supp($year=null){

    	$sql = 'SELECT v.facility, v.rcategory, month(datetested) AS month ';
    	$sql .= 'FROM viralsamples v ';
    	$sql .= 'INNER JOIN ';
    	$sql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
    	$sql .= 'FROM viralsamples ';
    	$sql .= 'WHERE year(datetested)={$year} ';
    	$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
    	$sql .= 'GROUP BY patient, facility) gv ';
    	$sql .= 'ON v.ID=gv.ID AND gv.maxdate=v.datetested ';

		$newsql = 'SELECT tb.facility, tb.month, tb.rcategory, count(*) as tests ';
		$newsql .= 'FROM ';
		$newsql .= '(SELECT v.facility, v.rcategory, month(datetested) AS month ';
		$newsql .= 'FROM viralsamples v ';
		$newsql .= 'INNER JOIN ';
		$newsql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
		$newsql .= 'FROM viralsamples ';
		$newsql .= 'WHERE year(datetested)={$year} ';
		$newsql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
		$newsql .= 'GROUP BY patient, facility) gv ';
		$newsql .= 'ON v.ID=gv.ID AND gv.maxdate=v.datetested) tb ';
		$newsql .= 'GROUP BY tb.facility, tb.month, tb.rcategory ';
		$newsql .= 'ORDER BY tb.facility, tb.month, tb.rcategory ';

		// $data = DB::connection('vl')->select($newsql);

		// return $data;
    }

    public function suppression(){
    	ini_set("memory_limit", "-1");
    	// SELECT facility, rcategory, count(*) as totals
		// FROM
		// (SELECT v.ID, v.facility, v.rcategory 
		// FROM viralsamples v 
		// RIGHT JOIN 
		// (SELECT ID, patient, facility, max(datetested) as maxdate
		// FROM viralsamples
		// WHERE ( (year(datetested) = 2016 AND month(datetested) > 9) || (year(datetested) = 2017 AND month(datetested) < 10) ) 
		// AND flag=1 AND repeatt=0 AND rcategory between 1 AND 4 
		// AND justification != 10 AND facility != 7148
		// GROUP BY patient, facility) gv 
		// ON v.ID=gv.ID) tb
		// GROUP BY facility, rcategory 
		// ORDER BY facility, rcategory;

    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$sql = 'SELECT facility, rcategory, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.ID, v.facility, v.rcategory ';
		$sql .= 'FROM viralsamples v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 AND 4 ';
		$sql .= 'AND justification != 10 AND facility != 7148 ';
		$sql .= 'GROUP BY patient, facility) gv ';
		$sql .= 'ON v.ID=gv.ID) tb ';
		$sql .= 'GROUP BY facility, rcategory ';
		$sql .= 'ORDER BY facility, rcategory ';

		$data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month]);

		return $data;
    }

    public function current_age_suppression($age, $suppression=true){
    	ini_set("memory_limit", "-1"); 

    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$sql = 'SELECT facility, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.ID, v.facility, v.rcategory, v.age2 ';
		$sql .= 'FROM viralsamples v ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT ID, patient, facility, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
		$sql .= 'AND justification != 10 and facility != 7148 ';
		$sql .= 'GROUP BY patient, facility) gv ';
		$sql .= 'ON v.ID=gv.ID) tb ';
		if($suppression){
			$sql .= 'WHERE rcategory between 1 and 2 ';
		}
		else{
			$sql .= 'WHERE rcategory between 3 and 4 ';
		}
		$sql .= 'AND age2 = ? ';
		$sql .= 'GROUP BY facility ';
		$sql .= 'ORDER BY facility';

		$data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month, $age]);

		return collect($data);
    }

    public function current_gender_suppression($sex, $suppression=true){
    	ini_set("memory_limit", "-1"); 

    	$r = $this->current_range();

    	$year = $r[0];
    	$prev_year = $r[1];
    	$month = $r[2];
    	$prev_month = $r[3];

    	$b = new BaseModel;
		$gender = $b->get_gender($sex);

    	$sql = 'SELECT facility, count(*) as totals ';
		$sql .= 'FROM ';
		$sql .= '(SELECT v.ID, v.facility, v.rcategory, viralpatients.gender ';
		$sql .= 'FROM viralsamples v JOIN viralpatients ON v.patientid=viralpatients.AutoID ';
		$sql .= 'RIGHT JOIN ';
		$sql .= '(SELECT viralsamples.ID, patient, facility, max(datetested) as maxdate ';
		$sql .= 'FROM viralsamples ';
		$sql .= 'WHERE ( (year(datetested) = ? and month(datetested) > ?) || (year(datetested) = ? and month(datetested) < ?) ) ';
		$sql .= "AND patient != '' AND patient != 'null' AND patient is not null ";
		$sql .= 'AND flag=1 AND repeatt=0 AND rcategory between 1 and 4 ';
		$sql .= 'AND justification != 10 and facility != 7148 ';
		$sql .= 'GROUP BY patient, facility) gv ';
		$sql .= 'ON v.ID=gv.ID) tb ';
		if($suppression){
			$sql .= 'WHERE rcategory between 1 and 2 ';
		}
		else{
			$sql .= 'WHERE rcategory between 3 and 4 ';
		}
		$sql .= 'AND gender = ? ';		
		$sql .= 'GROUP BY facility ';
		$sql .= 'ORDER BY facility ';

		$data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month, $gender]);
		// $data = DB::connection('vl')->select($sql, [$prev_year, $prev_month, $year, $month]);

		return collect($data);
    }

    private function current_range(){

    	$year = ((int) Date('Y'));
    	$prev_year = ((int) Date('Y')) - 1;
    	$month = ((int) Date('m'));
    	$prev_month = ((int) Date('m')) - 1;

    	// $year = 2017;
    	// $prev_year = 2016;
    	// $month = 10;
    	// $prev_month = 9;

    	return [$year, $prev_year, $month, $prev_month];
    }

    public function update_patients()
    {
    	ini_set("memory_limit", "-1");

		$sql = "viralsamples.ID, viralsamples.facility, viralsamples.patient, viralsamples.batchno, view_facilitys.name,
		 view_facilitys.facilitycode, view_facilitys.DHIScode, viralpatients.age, viralpatients.gender,
		  viralpatients.prophylaxis, viralsamples.justification, viralsamples.datecollected,
		   viralsamples.receivedstatus, viralsamples.sampletype, viralsamples.rejectedreason,
		    viralsamples.reason_for_repeat, viralsamples.datereceived, viralsamples.datetested,
		     viralsamples.result, viralsamples.datedispatched, viralsamples.labtestedin, month(datetested) as month";

		$raw = "ID, patient, facility, datetested";		

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw($sql))
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->join('view_facilitys', 'viralsamples.facility', '=', 'view_facilitys.ID')
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->where('viralsamples.synched', 0)
		->get();

		$today=date('Y-m-d');

		$b = new BaseModel;

		$p=0;

		foreach ($data as $key => $value) {
			$data_array = array(
				'labid' => $value->ID, 'FacilityMFLcode' => $value->facilitycode, 
				'FacilityDHISCode' => $value->DHIScode, 'batchno' => $value->batchno,
				'patientID' => $value->patient, 'Age' => $value->age, 'Gender' => $value->gender,
				'Regimen' => $value->prophylaxis,	'datecollected' => $value->datecollected,
				'SampleType' => $value->sampletype, 'Justification' => $value->justification, 
				'receivedstatus' => $value->receivedstatus, 'result' => $value->result, 
				'rejectedreason' => $value->rejectedreason, 
				'reason_for_repeat' => $value->reason_for_repeat,
				'datereceived' => $value->datereceived, 'datetested' => $value->datetested,
				'datedispatched' => $value->datedispatched, 'labtestedin' => $value->labtestedin
			);

			// DB::table('patients')->insert($data_array);

			$holidays = $b->getTotalHolidaysinMonth($value->month);

			$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
			$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
			$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
			$tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);
			// $tat4 = $tat1 + $tat2 + $tat3;

			$update_array = array('synched' => 1, 'datesynched' => $today, 'tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);

			if ($value->justification == 2) {

		    	$d = DB::connection('vl')
				->table("viralsamples")
				->select(DB::raw($raw))
				->where('facility', $value->facility)
				->where('patient', $value->patient)
				->whereDate('datetested', '<', $value->datetested)
				->whereIn('rcategory', [3, 4])
				->where('repeatt', 0)
				->where('Flag', 1)
				->where('facility', '!=', 7148)
				->first();

				if($d == null){
					$update_array = array_merge($update_array, ['previous_nonsuppressed' => 1]);
				}
			}
			// $update_array = array('synched' => 0, 'datesynched' => $today);

			DB::connection('vl_wr')->table('viralsamples')->where('ID', $value->ID)->update($update_array);
			$p++;
		}


		echo "\n {$p} vl patients synched.";
	}
}
