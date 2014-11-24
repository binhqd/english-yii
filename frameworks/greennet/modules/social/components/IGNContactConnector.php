<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
abstract class IGNContactConnector extends IGNSocialConnector
{
	/**
	 * This method is used to get all contacts of current user
	 */
	public abstract function getAllContacts();
	
	public abstract function getContacts($offset = 0, $limit = 100);
	
	public function getFBAccountInfo($userID);
}