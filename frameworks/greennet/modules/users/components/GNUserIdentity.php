<?php
/**
 * GNUserIdentity - This class used to representing identities that are authenticated based on a username and a password.
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 25-Jan-2013 9:37:29 AM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNUserIdentity extends CUserIdentity
{

	const ERROR_EMAIL_INVALID = 3;
	const ERROR_STATUS_NOTACTIV = 4;
	const ERROR_STATUS_BAN = 5;
	const INVALID_REMEMBER_HASH = 6;

	private $_id;
	private $_user = null;

	/**
	 * This method is used to authenticate
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$modelUser = GNUser::model()->findByEmail($this->username);
		if($modelUser === null)
			$this->errorCode = self::ERROR_EMAIL_INVALID;
		else if($modelUser->password !== GNUser::encryptPassword($this->password, $modelUser->saltkey))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_user = $modelUser;
			$this->_id = $modelUser->id;
			$this->errorCode = self::ERROR_NONE;
		}

		return !$this->errorCode;
	}

	/**
	 * This method is used to set authenticate
	 * @param unknown $info
	 */
	public function setAuthenticate($info) {
		$this->_id = $info->id;
		$this->errorCode = self::ERROR_NONE;
	}

	/**
	 * This method is used to get user
	 */
	public function getUser()
	{
		return $this->_user;
	}

}