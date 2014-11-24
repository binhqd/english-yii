<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
class GNYahooMailConnector extends GNContactConnector
{
	private $_client;
	public $application_name;
	public $app_id;
	public $consumer_key;
	public $consumer_secret;
	public $connected_path;
	public $re_url;
	
	public function getClient() {
		return $this->_client;
	}
	
	public function __construct() {
		
	}
	
	public function init() {
		$path = Yii::getPathOfAlias('application.modules.social.components.Yahoo.sdk');
		
		require_once ($path . '/config.php');
		require_once ($path . '/Yahoo.inc');
		
		
		
		if (YahooSession::hasSession($this->consumer_key, $this->consumer_secret, $this->app_id)) {
			$sessionStore = new NativeSessionStore();
			$verifier = null;
			$this->_client = YahooSession::initSession($this->consumer_key, $this->consumer_secret, $this->app_id, FALSE, NULL, $sessionStore, $verifier);
		} else {
			$this->_client = null;
		}
	}
	
	public function getAccessToken() {
		/*if (isset(Yii::app()->session['yahoo-token'])) {
			$this->_client->setAccessToken(Yii::app()->session['yahoo-token']);
		}*/
		
		return is_null($this->_client) ? null : $this->_client->getAccessToken();
	}
	/**
	 * This method is used to get all contacts of current user
	*/
	public function getAllContacts() {
		
	}
	
	public function getIsConnected() {
		return YahooSession::hasSession($this->consumer_key, $this->consumer_secret, $this->app_id);;
	}
	
	public function revoke() {
		//unset(Yii::app()->session['gmail-token']);
		//$this->_client->revokeToken();
	}
	
	public function getAuthUrl() {
		return YahooSession::createAuthorizationUrl($this->consumer_key, $this->consumer_secret, GNRouter::createAbsoluteUrl('/invites/yahoo/connect'));
		//return GNRouter::createAbsoluteUrl('/invites/yahoo/connect');
	}
	
	public function connect() {
		$path = Yii::getPathOfAlias('application.modules.social.components.Yahoo.sdk');
		
		require_once ($path . '/config.php');
		require_once ($path . '/Yahoo.inc');
		
		$this->_client = YahooSession::requireSession($this->consumer_key, $this->consumer_secret, $this->app_id);
		
		if (YahooSession::hasSession($this->consumer_key, $this->consumer_secret, $this->app_id)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getSessionedUser() {
		if (!$this->isConnected) return null;
		return $this->_client->getSessionedUser();
	}
	public function getContacts($offset = 0, $limit = 1000) {
		if (!$this->isConnected) return array();
		
		$user = $this->_client->getSessionedUser();
		$profile = $user->getProfile();
		
		$name = $profile->nickname; // Getting user name
		$guid = $profile->guid; // Getting Yahoo ID
		
		$contacts = $user->getContacts(0, 1000)->contacts;
		return $contacts;
	}
}