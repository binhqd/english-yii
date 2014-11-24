<?php
/**
 * 
 *
 * @author minhnc
 * @version 1.0
 * @created 03-May-2012 5:25:30 PM
 * @modified 03-May-2012 5:55:13 PM
 */
Yii::import('application.modules.users.models.*');
class NewsLettersController extends JLController
{
	/**
	 * Thiết lập layout cho Controller
	 */
	public $layout = '//layouts/back_end';
	public $baseScriptUrl = "";
	/**
	 * @return string Trả về các action (cách nhau bằng dấu phẩy) cho phép truy cập mà không cần xác thực quyền
	 */
	/* public function allowedActions() {
		return '*';
	} */
	public function actions() {
		return (isset($_POST['ajax']) && $_POST['ajax'] === 'registration-form') ? array() : array(
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF,
			),
		);
	}
	
	public function init() {
		parent::init();
		$this->baseScriptUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../assets', false, -1, true);
	}
	
	/**
	 * Method using to delete a user with binIDUser
	 * @param unknown_type $binIDUser
	 */
	
	public function actionDelete($binUserID = null) {
		if (isset($binUserID)) {
			$newsletter = JLUserNewsLetter::model()->findByPk(IDHelper::uuidToBinary($binUserID));
			
			if (!empty($newsletter)) {
				if ($newsletter->delete()) {
					Yii::app()->user->setFlash('success', "Delete record was successful !");
					$this->redirect($_SERVER['HTTP_REFERER']);
				}
			}
		}	
		Yii::app()->user->setFlash('error', "Delete record was fail !");
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
	
	/**
	 * Method using to search user account
	 * @param unknown_type $name_username
	 * @param unknown_type $name_fullname
	 * @param unknown_type $name_email
	 */
	public function actionSearchAdvanced($name_username = NULL, $name_fullname = NULL, $name_email = NULL) {
		$user = JLUser::model();
		$criteria = new CDbCriteria();
		
		if (isset($name_username)) {
			$criteria->compare('username',$name_username,true);
		}
		if (isset($name_fullname)) {
			$criteria->addCondition("firstname LIKE '%{$name_fullname}%' OR lastname LIKE '%{$name_fullname}%'");
		}
		if (isset($name_email)) {
			$criteria->compare('email',$name_email,true);
		}
		
		$pages=new CPagination();
		$pages->pageSize = 30;
		
		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		$criteria->order = 'createtime DESC';
		
		$pages->itemCount = $user->count($criteria);
		$pages->applyLimit($criteria);
		
		$models = $user->findAll($criteria);
		
		$this->render('listUser', array(
			'model'=>$models,
			'count'=>$pages->itemCount,
			'item_count'=>$pages->pageSize,
			'pages' => $pages,
			'cnn'=>0
		));
	}
	/**
	 * Phương thức dùng để thự hiện một hành động đồng loạt cho các activation
	 * 	- Xóa
	 */
	public function actionMonitorAction() {
		if (!empty($_POST) && !empty($_POST['Action_Monitor']['check'])) {
			if (!empty($_POST['Action_Monitor']['type_action']) && $_POST['Action_Monitor']['type_action']==="delete") {
				$transaction = Yii::app()->db->beginTransaction();
				foreach ($_POST['Action_Monitor']['check'] as $items) {
					$current = JLUserNewsLetter::model()->findByPk(IDHelper::uuidToBinary($items));
	
					if (!empty($current)) {
						$delete = $current->delete();
						if (!$delete) {
							$transaction->rollBack();
							Yii::app()->user->setFlash('error', "Delete account user was fail !");
							$this->redirect($_SERVER['HTTP_REFERER']);
						}
					}
				}
				$transaction->commit();
				Yii::app()->user->setFlash('success', "Delete account user was successful !");
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
		}
		Yii::app()->user->setFlash('error', "Delete account user was fail !");
		$this->redirect($_SERVER['HTTP_REFERER']);
	}
	/**
	 * Phương thức dùng để hiển thị kết quả monitor
	 */
	public function actionIndex() {
		Yii::import('application.components.jl_bd.helpers.*');
		
		if (isset($_GET['type']) && $_GET['type']!="") {
			$user = $_GET['type']::model();
		} else {
			$user =JLUserNewsLetter::model();
		}
		
		$pages=new CPagination();
		$pages->pageSize = 30;
		
		$criteria = new CDbCriteria();
		$username = '';
		$email = '';

		if(isset($_GET['name_email']) && $_GET['name_email']!="") {
			$email = $_GET['name_email'];
		}
		
		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		$criteria->order = 'created DESC';
		
		$dateFrom = "01-".date("m")."-".date("Y")." ".date("G:i:s");
		$dateTo = date("d-m-Y G:i:s");
		$date_From = strtotime($dateFrom);
		$date_To = strtotime($dateTo);
		
		//Check value of date input Get
		if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all' && $_GET['filter_give']!=='' && $_GET['filter_give']!=='today') {
			$date_To = strtotime(date("d-m-Y 00:00:00"));
			$date_From = strtotime(date("d-m-Y 00:00:00", strtotime("-1 {$_GET['filter_give']}")));
			$dateTo = date("d-m-Y 00:00:00");
			$dateFrom = date("d-m-Y 00:00:00", strtotime("-1 {$_GET['filter_give']}"));
		} else {
			if (isset($_GET['filter_give']) && $_GET['filter_give']==='today') {
				$date_To = strtotime(date("d-m-Y G:i:s"));
				$date_From = strtotime(date("d-m-Y")." 0:00:00");
				$dateTo = date("d-m-Y G:i:s");
				$dateFrom = date("d-m-Y")." 0:00:00";
			} else {
				if(isset($_GET['dateFrom']) && $_GET['dateFrom']!="") {
					$date_From = strtotime($_GET['dateFrom']);
					$dateFrom = $_GET['dateFrom'];
				}
				if(isset($_GET['dateTo']) && $_GET['dateTo']!="") {
					$date_To = strtotime($_GET['dateTo']);
					$dateTo = $_GET['dateTo'];
				}
			}
		}
		
		if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all') {
			$criteria->condition = " created>= :fromday AND created<= :today AND email LIKE '%{$email}%'";
			$criteria->params = array (
					':fromday' => $date_From,
					':today' => $date_To,
			);
		} else {
			$criteria->condition = "email LIKE '%{$email}%'";
		}
		
		
		$pages->itemCount = $user->count($criteria);
		$pages->applyLimit($criteria);
	
		$data = $user->findAll($criteria);
		$result = array();
		foreach ($data as $items) {
			$newsletters = $items->attributes;
			$newsletters['is_validate'] = (isset($newsletters['is_validate']) && ($newsletters['is_validate'] == 1)) ? 'true' : 'false'; 
			$result[] = $newsletters;
		}
		
		$arrType = array(
			'0' => array(
				'key' => 'JLUserNewsLetter',
				'name'=> 'News Letters User'
			)
		);
		$view_mode = array(
			'0' => array(
				'value' => '',
				'name'	=> '-- Give Filter'
			),
			'1' => array(
				'value' => 'today',
				'name'	=> '-- Current day'
			),
			'2' => array(
				'value' => 'day',
				'name'	=> '-- Before day'
			),
			'3' => array(
				'value' => 'week',
				'name'	=> '-- Before week'
			),
			'4' => array(
				'value' => 'month',
				'name'	=> '-- Before month'
			),
			'5' => array(
				'value' => 'all',
				'name'	=> '-- All'
			)
		);
		$this->render('index', array(
				'model'=>$result,
				'count'=>$pages->itemCount,
				'item_count'=>$pages->pageSize,
				'pages' => $pages,
				'cnn'=>'0',
				'dateFrom'=>$dateFrom,
				'dateTo'=>$dateTo,
				'type'	=> $arrType,
				'typeName_core' => isset($_GET['type']) ? $_GET['type'] : '',
				'viewMode'	=> $view_mode
		));
	}
	/**
	 * Method using to send email for user before user activation code is success
	 * @param unknown_type $email
	 */
	private function _sendMail($email = NULL, $subject = NULL, $view = NULL, $data = NULL) {
		if (isset($email) && isset($view)) {
			/**
			 * @todo Change message email
			 */
			$subject = isset($subject) ? $subject : "we have a confirmation email to you.";
			Yii::import('application.extensions.yii-mail.*');
	
			try {
				$message = new YiiMailMessage;
				$message->view = $view;
				$message->setSubject($subject);
				$message->setBody(
						array(
								'data'	=> isset($data) ? $data : array()
						),
						'text/html'
				);
	
				$message->addTo($email);
				if(empty($from)) $from = Yii::app()->params->mailer['username'];
				$message->from = $from;
	
				Yii::app()->mail->send($message);
			} catch (Exception $ex) {
				return array(
						'error'			=> true,
						'msg'			=> $ex->getMessage()
				);
			}
		}
	}
	/**
	 * Method using to re_send mail for user :
	 * 	- Trường hợp User đã đăng ký email nhưng chưa kích hoạt tài khoản
	 *  - Hàm có chức năng gửi lại email chứa link kích hoạt tài khoản cho user
	 * @param unknown_type $email
	 */
	public function actionReSendEmail($binUserID = NULL, $all = NULL) {
		if (isset($binUserID)) {
			$user = JLUserNewsLetter::model()->findByPk(IDHelper::uuidToBinary($binUserID,true));
	
			if (!empty($user)) {
				jsonOut(array(
					'error'		=> false,
					'message'	=> "<p>Re-Send Email Activate your subscription to JustLook's Weekly NewsLetters was successful.</p>"
				), false);
				/**
				 * @todo Change message email
				 */
				//Send email forr usser
				$subject = "Activate your subscription to JustLook's Weekly Newsletters.";
				$view = "register-user-newsletters";
				$urlDetach = JLRouter::createAbsoluteUrl('/user/newsLetters/unsubscribe/email/'.$user->email.'/key/'.IDHelper::uuidFromBinary($user->id, true));
				$data = array(
						'email'	=> $user->email,
						'url'	=> $urlDetach
					);
				$this->_SendMail($user->email, $subject, $view, $data);
	
			} else {
				jsonOut(array(
						'error'		=> true,
						'message'	=> "JustLook Error!", "Can't create activation code"
				));
			}
		}
	}
}