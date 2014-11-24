<?php
/**
 * Helper class to proccess string work changed between binary, number, array() 
 * 
 * @author minhnc
 * @version 1.0
 * @created 19-Jun-2012 11:17:20 AM
 */
class BitHelper
{
	/**
	 * Method using to changed from a string Binay to a array with $key is number auto from 0 to ..., $value is value of string bit to split 1,1
	 * 
	 * @param unknown_type $number
	 * @return multitype:Ambigous <string>
	 */
	public static function bitToArray($number = NULL) {
		
		$newBit = new Bitfield(decbin($number));
		
		$str = $newBit->toString();

		$mapActivities = ActivitiesHelper::setActivitiesMaps();
		
		$strBase = $newBit->toBaseBit($str,count($mapActivities));
		$arrbase = $newBit->toBaseArray($strBase);
		$newActivities = array();
		$count = 0;
		foreach ($mapActivities as $key=>$items) {
			$newActivities["{$key}"] = $arrbase["{$count}"];
			$count++;
		}
// 		debug($strBase);
		return $newActivities;
	}
	/**
	 * Method to using to changed from a array() to a string binary
	 * 
	 * @param unknown_type $binArray
	 * @return number
	 */
	public static function arrayToBit($binArray = array()) {
		
		$arr = Bitfield::fromArray($binArray);
		$newBit = new Bitfield($arr);
		$number = $newBit->toNumber();
		
		return $number;
	}
	/**
	 * Method using to changed a number to binary with condition is length of array() Activities to config in file ActivitiesHelper.php
	 * 
	 * @param unknown_type $number
	 */
	public static function numberToBinary($number = NULL) {
		$strBinary = '';
		if (isset($number)) {
			$newBit = new Bitfield(decbin($number));
			
			$strNumber = $newBit->toString();
			
			$mapActivities = ActivitiesHelper::setActivitiesMaps();
			
			$strBinary = $newBit->toBaseBit($strNumber,count($mapActivities));
		}
// 		debug($strBinary);
		return $strBinary;
	}
	/**
	 * Method using to And a binary A with binary B, return is a string type Binary
	 * 
	 * @param unknown_type $binaryA
	 * @param unknown_type $binaryB
	 * @return boolean
	 */
	public static function andBinary($binaryA = NULL, $binaryB = NULL) {
		if (isset($binaryA) && isset($binaryB)) {
			return $binaryA & $binaryB;
		}
	}
	
}
