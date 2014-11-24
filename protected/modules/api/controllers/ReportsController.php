<?php

/**
 * Users Controller
 * 
 * @author HungVT
 * @version 1.0
 */
class ReportsController extends ApiController {

	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions() {
		return 'stat,post';
	}

	public function actions() {
		return array(
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
				'testLimit' => 0,
			),
			'stat'	=> array(
				'class'	=> 'application.modules.reports.actions.StatReportConcernAction'
			),
			'post'	=> array(
				'class'	=> 'application.modules.reports.actions.PostReportConcernAction'
			),
		);
	}
}