<?php
/**
 * GNUserSocial
 *
 * @author BinhQD
 * @version 1.0
 * @created 16-Mar-2013 10:30:25 AM
 * @modified 16-Mar-2013 10:58:25 AM
 */
Yii::import('greennet.modules.social.models.*');
class GNUserSocial extends CActiveRecordBehavior
{
	private $_network;
	private $_owner;
	private $_parent;
	
	/**
	 * Constructor of GNUserSocial
	 * 
	 * @param string $networkAlias
	 * @param string $parent
	 */
	public function __construct($networkAlias = '', $parent = null) {
		if (!empty($networkAlias)) {
			$network = GNNetwork::model()->find('alias=:alias', array(
				':alias'	=> $networkAlias
			));
			
			if (!empty($network)) {
				$this->_network = $network;
				$this->_owner = $parent->owner;
				$this->_parent = $parent;
			}
		}
	}
	
	/**
	 * This method is used to return the data for current social connection
	 * 
	 * @param unknown $socialAlias
	 * @return LinkedObject
	 */
	public function getConnectionData($socialAlias) {
		if (empty($this->_network)) throw new Exception("Invalid network");
		
		$owner = $this->_owner;
		$linkedAccount = GNLinkedAccount::model()->find('user_id=:user_id and network_id=:network_id', array(
			':user_id'		=> $owner->id,
			':network_id'	=> $this->_network->id
		));
		
		return $linkedAccount;
	}
	
	/**
	 * This method is used to save the social data corresponse with the user
	 * 
	 * @param string $networkAccountID
	 * @param string $networkData
	 * @throws Exception
	 */
	public function saveConnectionData($networkAccountID, $networkData = "") {
		if (empty($this->_network)) throw new Exception(Yii::t("greennet", "Invalid network"));
		
		$owner = $this->_owner;
		$linkedAccount = GNLinkedAccount::model()->find('user_id=:user_id and network_id=:network_id', array(
			':user_id'		=> $owner->id,
			':network_id'	=> $this->_network->id
		));
		
		if (empty($linkedAccount)) {
			$linkedAccount = new GNLinkedAccount();

			$linkedAccount->user_id = $owner->id;
			$linkedAccount->network_id = $this->_network->id;
		}
		
		$linkedAccount->network_account_id = $networkAccountID;
		$linkedAccount->network_account_data = serialize($networkData);
		
		// This will create new if linked data not exist or update if that data existed
		$linkedAccount->save();
	}
	
	/**
	 * This method is used to remove current linked data of user 
	 * 
	 * @throws Exception
	 */
	public function removeLinkedData() {
		if (empty($this->_network)) throw new Exception(Yii::t("greennet", "Invalid network"));
		
		$owner = $this->_owner;
		$linkedAccount = GNLinkedAccount::model()->find('user_id=:user_id and network_id=:network_id', array(
			':user_id'		=> $owner->id,
			':network_id'	=> $this->_network->id
		));
		
		// remove 
		if (!empty($linkedAccount)) {
			$linkedAccount->delete();
		}
	}
	
	
	/**
	 * This method will return an instance of current class corresponse with the Facebook
	 * 
	 * @return Facebook Connection Object
	 */
	public function getFacebook() {
		$class = __CLASS__;
		return new $class('facebook', $this);
	}
	
	/**
	 * This method will return an instance of current class corresponse with the Facebook
	 *
	 * @return Facebook Connection Object
	 */
	public function getGoogle() {
		$class = __CLASS__;
		return new $class('google', $this);
	}
	
	/**
	 * This method will return current network
	 * 
	 * @return Connection Object
	 */
	public function getNetwork() {
		return $this->_network;
	}
	
	public function syncFacebookPhoto($fb) {
		
	}
}