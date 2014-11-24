<?php

class ZoneRegisterForm extends CFormModel {

	public $firstname;
	public $lastname;
	public $password;
	public $confirmPassword;
	public $confirmEmail;
	public $email;
	public $location;
	public $daybirth;
	public $monthbirth;
	public $yearbirth;
	public $birth;
	public $gender;
	public $verifyCode;

	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function rules() {
		return array(
			// array('email, confirmEmail', 'email'),
			array('email', 'email'),
			array('email', 'required'),
			// array('email, confirmEmail', 'required'),
			// array('confirmEmail', 'compare','compareAttribute'=>'email'),
			array('email', 'greennet.modules.users.components.validators.CheckEmailExistingValidator'),
			array('password', 'length', 'min' => 6),
			array('password,confirmPassword', 'required'),
			array('confirmPassword', 'compare', 'compareAttribute' => 'password'),
			array('firstname, lastname', 'required'),
			array('firstname', 'length', 'max' => 30),
			array('lastname', 'length', 'max' => 20),
			array('monthbirth, daybirth, yearbirth, birth, location, gender', 'safe'),
			array('birth', 'dateValid'),
			array('gender', 'genderValid'),
			array('location', 'locationValid'),
			array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements(), 'on' => 'Registration'),
			// array('firstname', 'greennet.modules.users.components.validators.FirstnameValidator'),
			// array('lastname', 'greennet.modules.users.components.validators.LastnameValidator'),
		);
	}
	
	public function genderValid($attribute , $params){
		if(isset($this->gender) && !in_array($this->gender, array(0,1))){
			$this->addError('gender', UsersModule::t('Gender invalid'));
		}
	}


	public function dateValid($attribute, $params) {
		if (!checkdate(intval($this->monthbirth), intval($this->daybirth), intval($this->yearbirth))) {
			$this->addError('birth', UsersModule::t('Birthday invalid'));
		} else {
			$time = strtotime(intval($this->yearbirth) . '-' . intval($this->monthbirth) . '-' . intval($this->daybirth));
			if ($time > time()) {
				$this->addError('birth', UsersModule::t('Birthday invalid'));
			}
		}
	}

	public function locationValid($attribute) {
		//exit($attribute);
		if (!$this->location) {
			return;
		}
		$locations = $this->getLocations();
		//debug($locations[$this->location]);
		if (!isset($locations[$this->location])) {
			$this->addError($attribute, UsersModule::t('The location is not found.'));
		}
	}

	public function attributeLabels() {
		return array(
			'firstname' => UsersModule::t('First Name'),
			'lastname' => UsersModule::t('Last Name'),
			'password' => UsersModule::t('Password'),
			'confirmPassword' => UsersModule::t('Confirm Password'),
			'confirmEmail' => UsersModule::t('Confirm Email'),
			'email' => UsersModule::t('Email'),
		);
	}

	public static function getYears() {
		$years = array(0 => 'Year');
		for ($i = date("Y"); $i >= 1901; $i--)
			$years[$i] = $i;
		return $years;
	}

	public static function getDays() {
		$days = array(0 => 'Day');
		for ($i = 1; $i <= 31; $i++)
			$days[count($days)] = $i;
		return $days;
	}

	public static function getMonths() {
		$months = array(0 => 'Month');
		for ($i = 1; $i <= 12; $i++)
			$months[$i] = date("F", strtotime(date('Y-' . $i . '-01')));
		return $months;
	}

	public static function getLocations() {
		$nodes = ZoneInstanceRender::search(null, 300, 0, array(
					"/location/country" => '*'
		));
		$results = array();
		foreach ($nodes as $node) {
			$results[$node['zone_id']] = $node['name'];
		}
		array_multisort($results);
		return $results;
	}

}