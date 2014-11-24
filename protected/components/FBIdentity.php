<?php
/**
 * Facebook Identity
 * Author : dunghd
 * Store information about current facebook user
 *
 **/
class FBIdentity extends CUserIdentity{

	private $_id;

	public function authenticate(){
		$this->_id = $this->password;
		return $this->errorCode==self::ERROR_NONE;
	}
	
	public function storeInformation($infor)
	{
		$user = CJSON::decode(base64_decode( urldecode($infor)));		
		
		// fix for account without username		
		if(!isset($user['username']))
		{
			$user['username'] = substr($user['email'], 0, strpos($user['email'],'@'));
		}
		
		// remove special character
		$user['username'] = str_replace(' ','',$user['username']);
		$user['username'] = str_replace('.','',$user['username']);
		$user['username'] = str_replace('@','',$user['username']);
		$user['username'] = str_replace(';','',$user['username']);
		$user['username'] = str_replace('#','',$user['username']);
		$user['username'] = str_replace('!','',$user['username']);
		$user['username'] = str_replace('$','',$user['username']);
		$user['username'] = str_replace('%','',$user['username']);
		$user['username'] = str_replace('^','',$user['username']);
		$user['username'] = str_replace('&','',$user['username']);
		$user['username'] = str_replace('*','',$user['username']);
		$user['username'] = str_replace(')','',$user['username']);
		$user['username'] = str_replace('(','',$user['username']);
		
		Yii::app()->session->add('fbUser',$user);
		return $user['id'];
	}

	public function getId()
	{
		return $this->_id;
	}
}
