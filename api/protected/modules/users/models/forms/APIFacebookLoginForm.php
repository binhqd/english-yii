<?php

/**
 * Facebook Login Form
 * @author huytbt <huytbt@gmail.com>
 * @version 2.0
 */
class APIFacebookLoginForm extends CFormModel
{
	/**
	 * @var string Facebook Id
	 */
	public $fbId;

	/**
	 * @var string Facebook access token
	 */
	public $fbToken;

	/**
	 * @var array Information of facebook account
	 */
	private $_info = null;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('fbId, fbToken', 'required'),
				array('fbToken', 'checkToken'),
		);
	}

	/**
	 * Validation check token
	 * @param array $attributes
	 * @param array $params
	 * @return void
	 */
	public function checkToken($attributes, $params)
	{
		$url = sprintf('https://graph.facebook.com/%s?access_token=%s', $this->fbId, $this->fbToken);
		list($code, $response) = InstanceCrawler::transport($url);
		$result = @json_decode($response, true);
		if ($code != 200 || empty($result) || !empty($result['error'])) {
			$this->addError($attributes, Yii::t("Youlook", 'Can not connect to facebook with your id and access token.'));
		}
		if (empty($result['email'])) {
			$this->addError($attributes, Yii::t("Youlook", 'We could not get your email address from Facebook response. Make sure your email address in your Facebook account is valid or being verified by Facebook.'));
		}
		$this->_info = $result;
	}

	/**
	 * Retrieve Facebook account information
	 * @return array Facebook account information
	 */
	public function getUserInfo()
	{
		return $this->_info;
	}
}