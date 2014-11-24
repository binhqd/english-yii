<?php
/**
 * @author : Chu Tieu
 * @version 1.0
 */
class ReportController extends JLController
{
	/**
	 * Thiết lập layout cho Controller
	 */
	// public $layout = '//layouts/dashboard';
	public $defaultAction = "index";
	
	/**
	 * @return string Trả về các action (cách nhau bằng dấu phẩy) cho phép truy cập mà không cần xác thực quyền
	 */
	public function allowedActions() {
		return '*';
	}
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
				'testLimit' => 0,
			),
			'concern'	=> array(
				'class'	=> 'application.modules.reports.actions.PostReportConcernAction'
			),
		);
	}
}