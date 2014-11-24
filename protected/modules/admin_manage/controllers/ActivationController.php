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
class ActivationController extends JLController
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
	public function init() {
		parent::init();
		$this->baseScriptUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../assets', false, -1, true);
	}
	
	/**
	 * Method using to delete a user with binIDUser
	 * @param unknown_type $binIDUser
	 */
	
	public function actionDelete($binActiveID = null) {
		if (isset($binActiveID)) {
			$activate = JLUserActivation::model()->findByPk(IDHelper::uuidToBinary($binActiveID));
			
			if (!empty($activate)) {
				if ($activate->delete()) {
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
	public function actionIndex() {
		Yii::import('application.components.jl_bd.helpers.*');
		
		$activation =JLUserActivation::model();
		
		$pages=new CPagination();
		$pages->pageSize = 30;
		
		$criteria = new CDbCriteria();

		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		$criteria->order = 'created DESC';
		
		if (isset($_GET['type']) && $_GET['type']!=="" && $_GET['type']!=="all") {
			$criteria->condition = "type=:TYPE_GET";
			$criteria->params = array(':TYPE_GET'=>$_GET['type']);
		}
		
		$pages->itemCount = $activation->count($criteria);
		$pages->applyLimit($criteria);
	
		$data = $activation->findAll($criteria);
		
		$result = array();
		foreach ($data as $items) {
			//Kiểm tra code đã hết hạn hay chưa
			if ($items->type==="forgot_password") {
				$tmpUser = JLUser::model()->findByPk($items->user_id);
				$expiry_date = strtotime(date("Y-m-d H:i:s", strtotime("-2 day")));
			} else {
				$tmpUser = JLUserTmp::model()->findByPk($items->user_id);
				$expiry_date = strtotime(date("Y-m-d H:i:s", strtotime("-1 day")));
			}
			$created = strtotime($items->created);
			
			//Thiết lập status : Đã hết hạn thì false, còn không thì true
			if ($created < $expiry_date) {
				$status = "true";
			} else {
				$status = "false";
			}
			//Nếu có user tồn tại của code activation trong tmpUser hay JLUser thì hiển thị email, còn không hiển thị "No User"
			if (!empty($tmpUser)) {
				$name = $tmpUser->email;
			} else {
				$name = "No user";
			}
			if (isset($_GET['filter_expiry'])) {
				switch ($_GET['filter_expiry']) {
					case 'false':
						if ($created >= $expiry_date) {
							$result[] = CMap::mergeArray(
								$items->attributes,
								array(
									'name' => $name,
									'status' => $status
								));
						}
						break;
					case 'true':
						if ($created < $expiry_date) {
							$result[] = CMap::mergeArray(
								$items->attributes,
								array(
									'name' => $name,
									'status' => $status
								));
						}
						break;
					case 'nouser':
						if (empty($tmpUser)) {
							$result[] = CMap::mergeArray(
								$items->attributes,
								array(
									'name' => $name,
									'status' => $status
								));
						}
						break;
					default : 
						$result[] = CMap::mergeArray(
							$items->attributes,
							array(
								'name' => $name,
								'status' => $status
							));
						
				}
			} else {
				$result[] = CMap::mergeArray(
					$items->attributes,
					array(
						'name' => $name,
						'status' => $status
					));
			}
		}

		$arrType = array(
			'0' => array(
				'key' => 'active_account',
				'name'=> 'Active Account'
			),
			'1' => array(
				'key' => 'forgot_password',
				'name'=> 'Active Forgot Password'
			),
			'2' => array(
				'key' => 'change_email',
				'name'=> 'Active Changed Email'
			),
			'3' => array(
				'key' => 'all',
				'name'=> 'All'
			),
		);
	
		$expiry = array(
			'0' => array(
				'value' => 'true',
				'name' => 'True'
			),
			'1' => array(
				'value' => 'false',
				'name' => 'False'
			),
			'2' => array(
				'value' => 'nouser',
				'name' => 'No User'
			),
			'3' => array(
				'value' => 'all',
				'name' => 'All'
			)
		);
		$this->render('index', array(
			'model'=>$result,
			'count'=>$pages->itemCount,
			'item_count'=>$pages->pageSize,
			'pages' => $pages,
			'cnn'=>'0',
			'type'	=> $arrType,
			'expiry'	=> $expiry
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
					$current = JLUserActivation::model()->findByPk(IDHelper::uuidToBinary($items));
					
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