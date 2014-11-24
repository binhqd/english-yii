<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
Yii::import('application.modules.social.components.Facebook.sdk.Facebook');
Yii::import('greennet.modules.social.components.*');
class GNFacebookProfilePhotoConnector extends GNFacebookConnector implements IGNPhotoConnector
{
	public $appId;
	public $secret;
	public $cookie;
	
	
// 	public function getClient() {
// 		return $this->_client;
// 	}
// 	public function __construct() {
// 		parent::__construct();
// 	}
	
// 	public function init() {
// 		$this->_client = new Facebook(array(
// 			'appId'		=> $this->appId,
// 			'secret'	=> $this->secret,
// 			'cookie'	=> $this->cookie
// 		));
// 	}
	
	/**
	 * This method is used to get all contacts of current user
	*/
	public function getAllPhotos() {
		
	}
	
	public function getAuthUrl($params = array()) {
		$_default = array(
			'redirect_uri'	=> GNRouter::createAbsoluteUrl('/facebook/checkPhotos')
		);
		$params = CMap::mergeArray($_default, $params);
		return $this->_client->getLoginUrl($params);
	}
	
	public function getPhotos($offset = 0, $limit = 1000) {
		if (!$this->isConnected) return array();
		$user = $this->client->getUser();

		if ($user) {
			try {
				$photos = $this->client->api("/{$user}/photos?limit={$limit}&offset={$offset}&fields=source");

				if (count($photos) < $limit) {
					// Reset photos
					$photos = array();
					$albums = $this->client->api("/{$user}/albums?limit={$limit}&fields=name,id,privacy");
					foreach ($albums['data'] as $album) {
						$albumPhotos = $this->client->api("/{$album['id']}/photos?limit={$limit}&fields=source");
						
						$photos = $photos + $albumPhotos['data'];
						if (count($photos) >= $limit) break;
					}
					return $photos;
				}
				//$friends = $this->_client->api('/'.$user.'?fields=id,name,friends.fields(name,first_name,last_name,picture)');
				return $photos['data'];
			} catch (FacebookApiException $e) {
				$user = null;
				return array();
			}
		} else {
			return array();
		}
	}
	
	public function getFBAccountInfo($userID) {
		if (!$this->isConnected) return array();
		
		$user = $this->_client->getUser();
		
		if ($user) {
			try {
				$info = $this->_client->api('/'.$userID.'?fields=id,name,first_name,last_name,email,picture');
				return $info;
			} catch (FacebookApiException $e) {
				debug($e);
				$user = null;
			}
		} else {
			return array();
		}
	}
}