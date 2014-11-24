<?php
/**
 * Class using to proccess activities of user
 * 
 * @author minhnc
 * @version 1.0
 * @created 19-Jun-2012 11:17:20 AM
 */
class ActivitiesHelper
{
	/**
	 * Method using to set map activities for user
	 * 
	 * @return multitype:string
	 */
	public static function setActivitiesMaps() {
		$activitiesMap = array(
				'claim'		=>'Claim business',
				'review'	=>'Write a new review',
				'list'		=>'Create and share list',
				'site'		=>'Create site',
				'addfriend'	=>'Add a new friend',
				'comment'	=>'Comment a review'
			);
		if (!empty($activitiesMap)) {
			return $activitiesMap;
		}
		return array();
	}
	/**
	 * Method using to comparing between privacy_settings and reviewer_settings
	 * 
	 * @param unknown_type $privacy_settings
	 * @param unknown_type $reviewer_settings
	 * @return string
	 */
	public static function comparingPrivacy($privacy_settings = NULL, $reviewer_settings = NULL) {
		if (isset($privacy_settings) && isset($reviewer_settings)) {
			
			$binary_privacy_settings = BitHelper::numberToBinary($privacy_settings);
			$binary_reviewer_settings = BitHelper::numberToBinary($reviewer_settings);
			
			$result = BitHelper::andBinary($binary_privacy_settings, $binary_reviewer_settings);
			
			$newBit = new Bitfield($result);
			$number = $newBit->toNumber();
			
			$comparingActivities = BitHelper::bitToArray($number);

// 			debug($comparingActivities);
			return $comparingActivities;
		}
		return array();
	}
	/**
	 * Method using to convert value string of array to value type integer
	 * 
	 * @param unknown_type $arrString
	 */
	public static function resetArrStringToNumber($arrString = array()) {
		
		if (!empty($arrString)) {
			$arrNumber = array();
			foreach ($arrString as $key=>$items) {
				if ($items === '1') {
					$arrNumber["{$key}"] = $key;
				} else {
					$arrNumber["{$key}"] = 'Null';
				}
			}
			
			return $arrNumber;
 		}
 		
		return $arrString;
	}
	public static function array_sort($array, $on, $order='SORT_ASC') {
		$new_array = array();
		$sortable_array = array();
	
		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $on) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}
	
			switch ($order) {
				case 'SORT_ASC':
					asort($sortable_array);
				break;
				case 'SORT_DESC':
					arsort($sortable_array);
				break;
			}
	
			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}
	
		return $new_array;
	}
	
}
?>