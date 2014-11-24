<?php

class ZoneEducationForm extends CFormModel
{

	public $school;
	public $datesAttended;
	public $degree;
	public $fieldOfStudy;
	public $grade;
	public $activitiesAndSocieties;
	public $description;
	
	/**
	* @return array customized attribute labels (name=>label)
	*/

	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('school,datesAttended,degree,fieldOfStudy,grade,activitiesAndSocieties,description', 'required'),
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
			'school' => 'School',
			'datesAttended' => 'Dates Attended',
			'degree' => 'Degree',
			'fieldOfStudy' => 'Field Of Study',
			'grade' => 'Grade',
			'activitiesAndSocieties' => 'Activities And Societies',
			'description' => 'Description'
			
		);
	}
	

}