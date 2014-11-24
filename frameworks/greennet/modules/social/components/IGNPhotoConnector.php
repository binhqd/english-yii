<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
interface IGNPhotoConnector
{
	/**
	 * This method is used to get all contacts of current user
	 */
	public function getAllPhotos();
	
	public function getPhotos($offset = 0, $limit = 100);
	
	public function getAuthUrl();
	
}