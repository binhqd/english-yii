<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
Yii::import('greennet.modules.social.components.Google.sdk.src.Google_Client');
class GNGmailConnector extends CComponent implements IGNSocialConnector
{
	private $_client;
	public $application_name;
	public $oauth2_client_id;
	public $oauth2_client_secret;
	public $oauth2_redirect_uri = 'http://core.greennet.com/google/connect';
	public $site_name;
	public $scope = "http://www.google.com/m8/feeds/ https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email";
	
	public function getClient() {
		return $this->_client;
	}
	
	public function __construct() {
		$this->_client = new Google_Client();
	}
	
	public function init() {
		$this->_client->setScopes($this->scope);
		$this->_client->setClientId($this->oauth2_client_id);
		$this->_client->setClientSecret($this->oauth2_client_secret);
		$this->_client->setRedirectUri($this->oauth2_redirect_uri);
		$this->_client->setAccessType('online');
	}
	
	public function getAccessToken() {
		//$accessToken = $this->_client->getAccessToken();
		
		if (isset(Yii::app()->session['gmail-token'])) {
			$this->_client->setAccessToken(Yii::app()->session['gmail-token']);
		}
		
		return $this->_client->getAccessToken();
	}
	
	public function getIsConnected() {
		$accessToken = $this->accessToken;
		return !empty($accessToken);
	}
	
	public function revoke() {
		unset(Yii::app()->session['gmail-token']);
		$this->_client->revokeToken();
	}
	
	public function getAuthUrl() {
		return $this->_client->createAuthUrl();
	}
	
	public function connect() {
		try {
			$this->_client->authenticate();
		} catch (Google_AuthException $ex) {
			throw new Exception('Authentication is invalid.');
		}
		$token = $this->_client->getAccessToken();
		
		if (!empty($token)) {
			Yii::app()->session['gmail-token'] = $token;
			return true;
		} else {
			return false;
		}
	}
	
	public function getUserInfo() {
		if (!$this->isConnected) return array();
	
		$req = new Google_HttpRequest("https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token=" . $this->_client->getAccessToken());
		$val = $this->_client->getIo()->authenticatedRequest($req);

		$response = $val->getResponseBody();
		
		// Update access token
		Yii::app()->session['gmail-token'] = $this->_client->getAccessToken();
		
		$return = json_decode($response, true);
		return $return;
	}
}