<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
Yii::import('application.modules.social.components.Facebook.sdk.Facebook');
class GNFacebookContactConnector extends GNFacebookConnector implements IGNContactConnector
{
	private $_client;
	
	public $appId;
	public $secret;
	public $cookie;
	
	
	public function getClient() {
		return $this->_client;
	}
	public function __construct() {
		
	}
	
	public function init() {
		$this->_client = new Facebook(array(
			'appId'		=> $this->appId,
			'secret'	=> $this->secret,
			'cookie'	=> $this->cookie
		));
	}
	
	/**
	 * This method is used to get all contacts of current user
	*/
	public function getAllContacts() {
		
	}
	
// 	public function getAuthUrl($params = array()) {
// 		$_default = array(
// 			'redirect_uri'	=> GNRouter::createAbsoluteUrl('/invites/facebook/connect')
// 		);
// 		$params = CMap::mergeArray($_default, $params);
// 		return $this->_client->getLoginUrl($params);
// 	}
	
// 	public function connect() {
// 		$this->_client->authenticate();
// 		$token = $this->_client->getAccessToken();
		
// 		if (!empty($token)) {
// 			Yii::app()->session['gmail-token'] = $token;
// 			return true;
// 		} else {
// 			return false;
// 		}
// 	}
	
// 	public function getUserInfo() {
// 		if (!$this->isConnected) return array();
		
// 		$user = $this->_client->getUser();
		
// 		if ($user) {
// 			try {
// 				$userInfo = $this->_client->api('/'.$user.'?fields=id,name,picture.width(140)');
// 				return $userInfo;
// 			} catch (FacebookApiException $e) {
// 				$user = null;
// 			}
// 		} else {
// 			return array();
// 		}
// 	}
	
	public function getContacts($offset = 0, $limit = 1000) {
		if (!$this->isConnected) return array();
		
		$user = $this->_client->getUser();
		
		if ($user) {
			try {
				$friends = $this->_client->api('/'.$user.'?fields=id,name,friends.fields(name,first_name,last_name,picture)');
				return $friends['friends']['data'];
			} catch (FacebookApiException $e) {
				$user = null;
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