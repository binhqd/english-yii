<?php

class ZoneExperienceForm extends CFormModel
{

	public $name;
	public $title;
	public $location;
	public $period;
	public $description;
	
	/**
	* @return array customized attribute labels (name=>label)
	*/

	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name,title,location,period', 'required'),
			// array('email', 'email'),
			
			// array('email', 'check'),
			// email has to be a valid email address
			//array('email', 'email'=>UsersModule::t(' Email failed')),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function attributeLabels()
	{
		return array(
			'name' => 'Company name',
			'title' => 'Title',
			'location' => 'Location',
			'period' => 'Time period',
			
		);
	}
	

}