<?php
/**
 * GNIUserCacheBehavior
 *
 * @author BinhQD
 * @version 1.0
 * @created 09-Mar-2013 10:43:18 AM
 * @modified 09-Mar-2013 10:57:57 AM
 */
interface GNIUserCacheBehavior
{
	/**
	 * 
	 * @param strUserID
	 */
	public function loadFromCache($strUserID);

	public function saveToCache($user);

	/**
	 * 
	 * @param arrFields
	 */
	public function updatePartial($arrFields);

}