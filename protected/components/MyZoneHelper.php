<?php

class MyZoneHelper
{
	
	public static function formatHeightValue($h,$name){
		$output = $h;
		$h = (float) $h;
		
		switch($name){
			case "Height":
				$out = preg_replace("/(\d).(\d)/", "$1'$2\"", round($h * 3.28084, 1));
				$ext = str_replace($h,"",$output);
				return $out." ({$h}m)";
			break;
			case "Weight":
				// $out = round($h / 0.453592,1);
				return $h." kg";
			break;
			default:
				return $output;
			break;
		}
		return "error";
	}
	
	public static function formatLocation($location = null)
	{
		$location = ZoneArticleNamespace::model()->nodeInfo($location);
		if(!empty($location))
			return CHtml::link($location['name'],ZoneRouter::createUrl('/zone/pages/detail',array('id'=>$location['zone_id'])));
		else
			return $location;
	}

	public static function isValidURL($url){

		if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
		{
			return false;
		}else{
			return true;
		}
	}
	
	public static function strDate($day) { 
		if($day == "Present") return date("Y-m-d");
		$day = explode("-",$day);
		$strDate = "";
		switch(count($day)){
			case 3:
				// TODO:
			break;
			case 2:
				$strDate = $day[0]."-".$day[1]."-01";
				
			break;
			case 1:
				$strDate = $day[0]."-01-01";
			break;
		}
		return $strDate;
	}
	
	
	public static function checkDate($mydate) { 
		$arrDate = explode("-",$mydate);
		if(count($arrDate) == 3){
			list($yy,$mm,$dd)=explode("-",$mydate); 
			if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd)) 
			{ 
				return checkdate($mm,$dd,$yy); 
			}else{
				return false;
			}
			
		}else{
			return false;
		}
		
	} 
}
