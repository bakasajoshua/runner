<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BaseModel;

class VlNation extends Model
{
    
	//National rejections
	public function national_rejections($year, $rejected_reason){

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->where('receivedstatus', 2)
		->where('rejectedreason', $rejected_reason)
		->whereYear('datereceived', $year)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get(); 

		return $data;
	}

    //
    public function getalltestedviraloadsamples($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;


    	// $sql="select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' and Flag=1 and viralsamples.repeatt=0 AND viralsamples.rcategory  BETWEEN 1 AND 4";
    }

    public function getallactualpatients($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.patient,viralsamples.facility) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		$sql = "select COUNT(DISTINCT viralsamples.patient,viralsamples.facility)  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.rcategory  BETWEEN 1 AND 4";

    	// $sql = "select COUNT(DISTINCT viralsamples.patient,viralsamples.facility)  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2	 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.rcategory  BETWEEN 1 AND 4";
    }

    public function getallreceivediraloadsamples($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->where('viralsamples.Flag', 1)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where   MONTH(datereceived)='$month' and YEAR(datereceived)='$year'   AND ((parentid=0)||(parentid IS NULL)) and Flag=1";
    }

    public function getallrejectedviraloadsamples($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->where('viralsamples.receivedstatus', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where   MONTH(datereceived)='$month' and YEAR(datereceived)='$year'  and viralsamples.receivedstatus=2 AND repeatt=0 and Flag=1";
    }

    public function GetSupportedfacilitysFORViralLoad($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.facility) as totals, month(datereceived) as month"))
		->whereYear('datereceived', $year)
		->where('viralsamples.facility', '!=', 0)
		->where('viralsamples.Flag', 1)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(facility))  as numsamples from viralsamples where  MONTH(datereceived)='$month' and YEAR(datereceived)='$year'  and viralsamples.Flag=1 and viralsamples.facility !=0";
    }

    public function GetNationalConfirmed2VLs($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=2 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year'   AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1 and rcategory between 1 and 4";
    }

    public function GetNationalConfirmedFailure($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [3, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=2 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND viralsamples.rcategory BETWEEN 3 AND 4  AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1";
    }

    public function GetNationalBaseline($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [1, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=10 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year'   AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1 and rcategory between 1 and 4";
    }

    public function GetNationalBaselineFailure($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.rcategory', [3, 4])
		->whereBetween('viralsamples.sampletype', [1, 4])
		->where('viralsamples.justification', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where viralsamples.justification=10 AND  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND viralsamples.rcategory BETWEEN 3 AND 4  AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1";
    }


    public function getallrepeattviraloadsamples($year){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->where('viralsamples.receivedstatus', 3)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND receivedstatus=3";
    }

    public function getalltestedviraloadsamplesbytypedetails($year, $sampletype){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralsamples.sampletype', $sampletype)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 and viralsamples.Flag=1 AND viralsamples.sampletype='$sampletype' and viralsamples.rcategory BETWEEN 1 AND 4";
    }

    public function getalltestedviraloadbygender($year, $sex){

    	$b = new BaseModel;
		$gender = $b->get_gender($sex);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where('viralpatients.gender', $gender)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 and viralsamples.rcategory BETWEEN 1 AND 4";
    }

    public function getalltestedviraloadsbyage($year, $age, $all=false){

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
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->whereBetween('viralsamples.rcategory', [1, 4])
		->where($age_column, $age)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		

    	// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.age='$age' and viralsamples.rcategory BETWEEN 1 AND 4";

    	// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.age2='$age' and viralsamples.rcategory BETWEEN 1 AND 4";
    }

    public function getalltestedviraloadsbyresult($year, $result){

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->whereYear('datetested', $year)
		->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
		->where('viralsamples.rcategory', $result)
		->where('viralsamples.justification', '!=', 2)
		->where('viralsamples.justification', '!=', 10)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.rcategory ='$rcategory'";
    }

    public function GetNatTATs($year){
    	$sql = "datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
    	ini_set("memory_limit", "-1");

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw($sql))
		->whereYear('viralsamples.datecollected', '>', 1980)
		->whereYear('viralsamples.datereceived', '>', 1980)
		->whereYear('viralsamples.datetested', '>', 1980)
		->whereYear('viralsamples.datedispatched', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('viralsamples.datetested', $year)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->get(); 

		// return $data;

		$return = null;
		$b = new BaseModel;

		for ($i=0; $i < 12; $i++) { 
			$month = $i + 1;
			$d = $data->where('month', $month);

			if($d->isEmpty()){
				break;
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

			$return[$i]['tat1'] = floor($tat1 / $rows);
			$return[$i]['tat2'] = floor($tat2 / $rows);
			$return[$i]['tat3'] = floor($tat3 / $rows);
			$return[$i]['tat4'] = floor($tat4 / $rows);
			$return[$i]['month'] = $month;

			

		}

		return $return;
    }

    public function get_tat($year){
    	$sql = "AVG(tat1) AS tat1, AVG(tat2) AS tat2, AVG(tat3) AS tat3, AVG(tat4) AS tat4, month(datetested) as month";

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw($sql))
		->whereYear('viralsamples.datecollected', '>', 1980)
		->whereYear('viralsamples.datereceived', '>', 1980)
		->whereYear('viralsamples.datetested', '>', 1980)
		->whereYear('viralsamples.datedispatched', '>', 1980)
		->whereColumn([
			['datecollected', '<=', 'datereceived'],
			['datereceived', '<=', 'datetested'],
			['datetested', '<=', 'datedispatched']
		])
		->whereYear('viralsamples.datetested', $year)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
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

    public function getalltestedviraloadsamplesbydash($year, $type, $param){

		$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

    	$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(viralsamples.ID) as totals, month(datetested) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->whereYear('datetested', $year)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		// $sql = "select count(ID)  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND repeatt=0 and Flag=1 AND $colmn ='$age' and viralsamples.rcategory BETWEEN 1 AND 4";
    }

    public function getallreceivediraloadsamplesbydash($year, $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->whereRaw("((parentid=0) || (parentid IS NULL))")
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.Flag', 1)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where   MONTH(datereceived)='$month' and YEAR(datereceived)='$year'   AND ((parentid=0)||(parentid IS NULL)) and Flag=1 AND $colmn='$age'";
    }

    public function getallrejectedviraloadsamplesbydash($year, $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datereceived) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datereceived', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where   MONTH(datereceived)='$month' and YEAR(datereceived)='$year'  and viralsamples.receivedstatus=2 AND repeatt=0 and Flag=1 AND $colmn='$age'";
    }

    public function GetNationalConfirmed2VLsbydash($year, $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.sampletype', [1, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

    	// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where  viralsamples.justification=2  AND  MONTH(datetested)='$month' and YEAR(datetested)='$year'   AND viralsamples.sampletype BETWEEN 1 and 4  AND repeatt=0 and Flag=1 AND $colmn='$age'";
    }

    public function GetNationalConfirmedFailurebydash($year, $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->whereBetween('viralsamples.sampletype', [1, 4])
		->whereBetween('viralsamples.rcategory', [3, 4])
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.justification', 2)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where  viralsamples.justification=2  AND  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND viralsamples.rcategory BETWEEN 3 AND 4 AND viralsamples.sampletype BETWEEN 1 AND 4  AND repeatt=0 and Flag=1 AND $colmn='$age'";
    }

    public function getallrepeattviraloadsamplesbydash($year, $type, $param){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.receivedstatus', 3)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		// $sql = "select count(DISTINCT(ID))  as numsamples from viralsamples where  MONTH(datetested)='$month' and YEAR(datetested)='$year' AND repeatt=0 and Flag=1 AND receivedstatus=3 AND $colmn='$age'";
    }

    public function getalltestedviraloadsamplesbytypedetailsbydash($year, $type, $param, $sampletype){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.sampletype', $sampletype)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.sampletype='$sampletype' AND $colmn='$age' and viralsamples.rcategory BETWEEN 1 AND 4";

		// $sql = "select count(viralsamples.ID)  as numsamples from viralsamples, viralpatients where  viralsamples.patientid=viralpatients.AutoID AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.sampletype='$sampletype' AND viralpatients.gender='$gender' and viralsamples.rcategory BETWEEN 1 AND 4";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.sampletype='$sampletype' AND prophylaxis='$regimen' and viralsamples.rcategory BETWEEN 1 AND 4 and (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples where MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.sampletype='$sampletype' AND justification='$justification'";
    }

    public function getalltestedviraloadsamplesbygenderbydash($year, $type, $param, $gender){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);
		$sex = $b->get_gender($gender);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID')
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralpatients.gender', $sex)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.$colmn='$age' and viralsamples.rcategory BETWEEN 1 AND 4";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' and (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND viralsamples.prophylaxis='$regimen' and viralsamples.rcategory BETWEEN 1 AND 4";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND justification='$justification'";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND sampletype BETWEEN '$stype' AND '$ttype'";
    }

    public function getalltestedviraloadsamplesbyagebydash($year, $type, $param, $age){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$age_column = 'viralsamples.age2';

		if($age < 6){
			$age_column = 'viralsamples.age';
		}

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10)
				->whereBetween('viralsamples.rcategory', [1, 4]);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where($age_column, $age)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		$sql = "select count(viralsamples.ID)  as numsamples from viralsamples, viralpatients where  viralsamples.patientid=viralpatients.AutoID AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.age='$age' AND viralpatients.gender='$gender' and viralsamples.rcategory BETWEEN 1 AND 4";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' and (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND viralsamples.prophylaxis='$regimen' and viralsamples.rcategory BETWEEN 1 AND 4";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND justification='$justification'";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND sampletype BETWEEN '$stype' AND '$ttype'";
    }

    public function getalltestedviraloadsamplesbyresultbydash($year, $type, $param, $result){

    	$b = new BaseModel;
		$p = $b->get_vlparams($type, $param);

		$data = DB::connection('vl')
		->table('viralsamples')
		->select(DB::raw("COUNT(DISTINCT viralsamples.ID) as totals, month(datetested) as month"))
		->when($type, function($query) use ($type){
			if($type == 2){
				return $query->join('viralpatients', 'viralsamples.patientid', '=', 'viralpatients.AutoID');
			}			
		})
		->when($type, function($query) use ($type){
			if($type < 4){
				return $query
				->whereRaw("(viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection'))")
				->where('viralsamples.justification', '!=', 2)
				->where('viralsamples.justification', '!=', 10);
			}				
		})
		->whereYear('datetested', $year)
		->when($type, function($query) use ($type, $param, $p){
			if($type == 5 && $p['param'] == 3){
				return $query->whereBetween($p['column'], [3, 4]);
			}
			else{
				return $query->where($p['column'], $p['param']);
			}				
		})
		->where('viralsamples.rcategory', $result)
		->where('viralsamples.Flag', 1)
		->where('viralsamples.repeatt', 0)
		->groupBy('month')
		->get();

		return $data;

		// $sql = "select count(viralsamples.ID)  as numsamples from viralsamples, viralpatients where  viralsamples.patientid=viralpatients.AutoID AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1 AND viralsamples.rcategory ='$rcategory' AND viralpatients.gender='$gender'";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' and (viralsamples.receivedstatus=1  OR (viralsamples.receivedstatus=3  and  viralsamples.reason_for_repeat='Repeat For Rejection')) AND viralsamples.justification !=2 AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND viralsamples.prophylaxis='$regimen' and viralsamples.rcategory BETWEEN 1 AND 4";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND justification='$justification'";

		// $sql = "select count(DISTINCT(viralsamples.ID))  as numsamples from viralsamples,viralpatients where viralsamples.patientid=viralpatients.AutoID AND viralpatients.gender='$sex' AND MONTH(viralsamples.datetested)='$month'  AND  YEAR(viralsamples.datetested)='$year' AND viralsamples.repeatt=0 and viralsamples.Flag=1  AND sampletype BETWEEN '$stype' AND '$ttype'";
    }


    // Update samples tats	
	public function update_tats($year)
	{
		// $sql = "datediff(datereceived, datecollected) as tat1, datediff(datetested, datereceived) as tat2, datediff(datedispatched, datetested) as tat3, datediff(datedispatched, datecollected) as tat4, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		
		$sql = "viralsamples.ID, datecollected, datereceived, datetested, datedispatched, month(datetested) as month";
		$b = new BaseModel;
		
		ini_set("memory_limit", "-1");
		 
		echo "\n Begin vl samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());

		for($month=1; $month<13; $month++){

			echo "\n Begin vl samples tat update for {$year} {$month} at " . date('d/m/Y h:i:s a', time());

			$data = DB::connection('vl')
			->table('viralsamples')
			->select(DB::raw($sql))
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
			->whereMonth('datetested', $month)
			->where('viralsamples.Flag', 1)
			->where('viralsamples.repeatt', 0)
			->get(); 

			if($data->isEmpty()){
				continue;
			}	

			$holidays = $b->getTotalHolidaysinMonth($month);	

			foreach ($data as $key => $value) {

				$tat1 = $b->get_days($value->datecollected, $value->datereceived, $holidays);
				$tat2 = $b->get_days($value->datereceived, $value->datetested, $holidays);
				$tat3 = $b->get_days($value->datetested, $value->datedispatched, $holidays);
				$tat4 = $b->get_days($value->datecollected, $value->datedispatched, $holidays);

				$update_array = array('tat1' => $tat1, 'tat2' => $tat2, 'tat3' => $tat3, 'tat4' => $tat4);

				DB::connection('vl')->table('viralsamples')->where('ID', $value->ID)->update($update_array);

			}
			echo "\n Completed vl samples tat update for {$year} {$month} at " . date('d/m/Y h:i:s a', time());

		}
		echo "\n Completed vl samples tat update for {$year} at " . date('d/m/Y h:i:s a', time());
		
	}
}
