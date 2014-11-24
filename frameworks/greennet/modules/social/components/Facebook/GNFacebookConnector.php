<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
Yii::import('greennet.modules.social.components.Facebook.sdk.Facebook');
Yii::import('greennet.modules.social.components.IGNSocialConnector');
class GNFacebookConnector extends CComponent implements IGNSocialConnector
{
	protected $_client;
	
	public $appId;
	public $secret;
	public $cookie;
	
	/**
	 * This method will return client object for current Facebook connection
	 * @return Facebook
	 */
	public function getClient() {
		return $this->_client;
	}
	
	/**
	 * 
	 * @param Facebook $client
	 */
	public function setClient($client) {
		// TODO: Check if $client is a valid Facebook client
		$this->_client = $client;
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
	
	public function getAccessToken() {
		return $this->_client->getAccessToken();
	}
	
	public function getIsConnected() {
		$user = $this->_client->getUser();
		return !empty($user);
	}
	
	public function revoke() {
		if ($this->isConnected) {
			$this->_client->api('/me/permissions', 'DELETE');
		}
	}
	
	public function getAuthUrl($params = array()) {
		$_default = array(
			'redirect_uri'	=> GNRouter::createAbsoluteUrl('/facebook/connect')
		);
		$params = CMap::mergeArray($_default, $params);
		return $this->_client->getLoginUrl($params);
	}
	
	public function connect() {
// 		$this->_client->authenticate();
// 		$token = $this->_client->getAccessToken();
		
// 		if (!empty($token)) {
// 			Yii::app()->session['gmail-token'] = $token;
// 			return true;
// 		} else {
// 			return false;
// 		}
	}
	
	public function getUserInfo() {
		if (!$this->isConnected) return array();
		
		$user = $this->_client->getUser();
		
		if ($user) {
			try {
				// Check more info here: http://developers.facebook.com/docs/reference/rest/users.getInfo/
				$userInfo = $this->_client->api('/'.$user.'?fields=id,name,first_name,last_name,birthday,email,locale,picture.width(140)');
				return $userInfo;
			} catch (FacebookApiException $e) {
				$user = null;
			}
		} else {
			return array();
		}
	}
	
	public function getFriends() {
		if (!$this->isConnected) return array();
		$user = $this->client->getUser();
		
		if ($user) {
			try {
				$friends = $this->_client->api('/'.$user.'?fields=id,name,friends.fields(name,first_name,last_name,picture)');
				return $friends['friends'];
			} catch (FacebookApiException $e) {
				$user = null;
			}
		} else {
			return array();
		}
	}
}