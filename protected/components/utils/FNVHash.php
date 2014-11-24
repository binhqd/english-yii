<?php

/**
 * FNVHash - Helper chứa các phương thức phục vụ việc sử dụng UUID
 * 
 * @ingroup utils
 * @author dunghd
 * @version 1.0
 */
 
/*
*       FNV_PRIME:
*       32 bit FNV_prime = 224 + 28 + 0x93 = 16777619
*       64 bit FNV_prime = 240 + 28 + 0xb3 = 1099511628211
*       128 bit FNV_prime = 288 + 28 + 0x3b = 309485009821345068724781371
*       OFFSET_BASIS:
*       32 bit offset_basis = 2166136261
*       64 bit offset_basis = 14695981039346656037
*       128 bit offset_basis = 144066263297769815596495629667062367629  
*
*       Source: http://www.isthe.com/chongo/tech/comp/fnv/
*/

define('MAX_BASE', 256);

define ("FNV_prime_32", 16777619);
define ("FNV_prime_64", 1099511628211);
define ("FNV_prime_128", 309485009821345068724781371);

define ("FNV_offset_basis_32", 2166136261);
define ("FNV_offset_basis_64", 14695981039346656037);
define ("FNV_offset_basis_128", 144066263297769815596495629667062367629);

 
class FNVHash
{	
	function fnv1a_64($txt) {
		
	 $binary_string = pack("H*" , $txt);			
	 $hash = "14695981039346656037";
	 foreach (str_split($binary_string) as $chr) {
	  $mod = bcmod($hash,"256");
	  $hash = bcmod(bcmul(bcadd($hash, ord(chr($mod) ^ $chr) - $mod), "1099511628211"),"18446744073709551616");
	 }
	 if ( bcadd($hash, 1) <= 1 || bccomp($hash,"9223372036854775807") >= 0)
	  $hash = bcsub($hash, "18446744073709551616");
	 return $hash;
	 
	}
	
}
