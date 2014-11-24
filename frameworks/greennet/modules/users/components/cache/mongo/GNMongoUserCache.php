<?php
/**
 * GNMongoUserCache
 *
 * @author BinhQD
 * @version 1.0
 * @created 09-Mar-2013 10:30:25 AM
 * @modified 09-Mar-2013 10:58:25 AM
 */
Yii::import("greennet.modules.users.components.cache.mongo.GNMongoUserDocument");
class GNMongoUserCache extends CActiveRecordBehavior implements GNIUserCacheBehavior
{
	public $connectionString;
	public $dbName;
	private $_document;
	private $expiryTime;
	
	public function __construct() {
		$this->_document = new GNMongoUserDocument();
	}
	
	/**
	 * 
	 * @param strUserID
	 */
	public function loadFromCache($strUserID)
	{
		// Criteria for finding user
		$criteria = new EMongoCriteria;
		$criteria->user_id = $strUserID;
		
		// find user in cache
		$cache = $this->_document->find($criteria);
		
		// If user is not cached, or cache has expired, re-cache user information
		if (!isset($cache)) {
			$cache = $this->saveToCache($strUserID);
		} else {
			if ((time() - $cache->cache_modified) > Yii::app()->params['cacheResetTime']) {
				$cache = $this->saveToCache($strUserID);
			}
		}
		
		if (!isset($cache)) return null;
		
		$binUserID = IDHelper::uuidToBinary($strUserID);
		
		$this->owner->id = $binUserID;
		
		$this->owner->hexID = $strUserID;
		$this->owner->firstname = $cache->firstname;
		$this->owner->lastname = $cache->lastname;
		$this->owner->username = $cache->username;
		$this->owner->displayname = $cache->displayname;
		$this->owner->lastvisited = $cache->lastvisited;
		$this->owner->created = $cache->created;
		$this->owner->email = $cache->email;
		$this->owner->is_activated = $cache->is_activated;
		//$this->owner->stats = $cache->stats;
		
// 		if ($this->owner->isCurrent) {
// 			$this->owner->updateCacheTime($user->cache_modified);
// 		}
		
		return $this->owner;
	}

	public function saveToCache($strUserID)
	{
		$cache = $this->_document->reset($strUserID);
		
		return $cache;
	}
	
	/**
	 * 
	 * @param arrFields
	 */
	public function updatePartial($arrFields)
	{
		
	}
	
	public function afterSave($event = null) {
		$user = $this->owner;
		$hexID = IDHelper::uuidFromBinary($user->id, true);
		
		// Invalidate user
		$this->saveToCache($hexID);
		
		$this->loadFromCache($hexID);
	}
	
	public function afterDelete() {
		
		$user = $this->owner;
		$hexID = IDHelper::uuidFromBinary($user->id, true);
	
		//xoa user
		$criteria = array(
			'conditions'	=> array(
				'user_id'	=> array('==' => $hexID)
			)
		);
		$criteria = new EMongoCriteria($criteria);
		GNMongoUserDocument::model()->deleteAll($criteria);
	}
}