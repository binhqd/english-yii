<?php
/**
 * @author BinhQD
 * @version 1.0
 * @created 26-Feb-2013 9:47:44 AM
 */
Yii::import('application.modules.social.components.Google.sdk.src.Google_Client');
class GNGmailConnector extends GNGmailConnector implements IGNContactConnector
{
	private $_client;
	public $application_name;
	public $oauth2_client_id;
	public $oauth2_client_secret;
	public $oauth2_redirect_uri = 'http://core.greennet.com/gmail/connect';
	public $site_name;
	public $scope = "http://www.google.com/m8/feeds/";
	
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
	
	/**
	 * This method is used to get all contacts of current user
	*/
	public function getAllContacts() {
		
	}
	
	
	public function getContacts($offset = 0, $limit = 1000) {
		if (!$this->isConnected) return array();
		
		$req = new Google_HttpRequest("https://www.google.com/m8/feeds/contacts/default/thin?max-results={$limit}&alt=json");
		$val = $this->_client->getIo()->authenticatedRequest($req);

		$response = $val->getResponseBody();
		
		// Update access token
		Yii::app()->session['gmail-token'] = $this->_client->getAccessToken();
		
		$return = json_decode($response, true);
		return $return['feed']['entry'];
	}
	
	public function getContactPhoto($link) {
		if (!$this->isConnected) return array();
		
		$req = new Google_HttpRequest($link, 'GET');
		$val = $this->_client->getIo()->authenticatedRequest($req);
		$headers = $val->getResponseHeaders();

		if (substr($headers['content-type'], 0, 5) != "image") {
			header("Content-type: image/jpg");
			echo file_get_contents(Yii::getPathOfAlias('jlwebroot') . "/upload/user-photos/user-thumb-default-male.png");
			exit;
		}
		
		foreach ($headers as $header => $value) {
			header("{$header}: {$value}");
			echo $val->getResponseBody();
		}
	}
}