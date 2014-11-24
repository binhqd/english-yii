<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
interface IGNSocialConnector
{
	public function getAccessToken();
	/**
	 * This method is used to get all contacts of current user
	 */

	public function getIsConnected();
	
	public function revoke();
	
	public function getAuthUrl();
	
	public function connect();
	
	public function getUserInfo();
}