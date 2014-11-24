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
class ManageUserController extends GNController
{
	/**
	 * Thiết lập layout cho Controller
	 */
	public $layout = '//layouts/admin';
	public $baseScriptUrl = "";
	
	public function allowedActions()
	{
		return '*';
	}
	
	public function init() {
		parent::init();
		$this->baseScriptUrl = Yii::app()->assetManager->publish(dirname(__FILE__) . '/../assets', false, -1, true);
	}
	

	/* index action **/
	
	public function actionIndex($username=NULL,$email=NULL)
	{
		Yii::import('application.components.jl_bd.helpers.*');
	
		/**
		 *Kiểm tra xem biến $_GET['type'] có tồn tại hay không
		 *	- kiểm tra loại type và khai báo tên lớp Model tương ứng
		**/
		if (isset($_GET['type']) && $_GET['type']!="" && $_GET['type']!=="face_user" && $_GET['type']!=="face_tmp_user" && $_GET['type']!=="all" && $_GET['type']!=="tmp_user_expiry_date") {
			$user = $_GET['type']::model();
			$type_core_user = $_GET['type'];
		} else {
			$user =GNUser::model();
			$type_core_user = 'GNUser';
		}
	
		/**
		 * Khai báo sử dụng lớp phân trang
		 * @var unknown_type
		 */
		$pages=new CPagination();
		$pages->pageSize = 20;
	
		$criteria = new CDbCriteria();
	
	
	
	
		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		$criteria->order = 'created DESC';
	
		/**
		 * Khai báo  và khởi gán các giá trị lọc theo thời gian
		 */
		$dateFrom = "01-".date("m")."-".date("Y")." ".date("G:i:s");
		$dateTo = date("d-m-Y G:i:s");
		$date_From = strtotime($dateFrom);
		$date_To = strtotime($dateTo);
	
	
	
		/**
		 * Hiển thị danh sách tất cả các user đăng ký qua facebook : Bao gồ các user đã kích hoạt và chưa kích hoạt
		*/
		if (isset($_GET['type']) && ($_GET['type']==="face_user" || $_GET['type']==="face_tmp_user")) {
			if ($_GET['type']==="face_user") {
				$core_user = GNUser::model()->tableName();
				$type_core_user = 'GNUser';
			} else {
				$core_user = GNUserTmp::model()->tableName();
				$type_core_user = 'GNUserTmp';
			}
				
			//Get pages to limit/offset
			$page_current = (isset($_GET['page']) && $_GET['page']!=='') ? $_GET['page'] : 0;
				
			/**
			 * Kiểm tra xem chế độ xem có chọn hay không
			 * 	- Nếu chọn thì xem nếu khác kiểu khác all thì truy vấn
			 */
			if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all') {
				$filter_conditions = "created >= :fromday AND created <= :today AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
				$filter_params = array(
					':fromday'	=> $date_From,
					':today'	=> $date_To
				);
				/**
				 * Nếu all thì :
				*/
			} else {
				$filter_conditions = "username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
				$filter_params = array();
			}
			/**
			 * Tiến hành truy vấn dữ liệu và count kết quả
			 * @var unknown_type
			 */
			$user = Yii::app()->db->createCommand()
			->select('a.id, a.username, a.displayname, a.lastname, a.firstname, a.email, a.created')
			->from($core_user.' a')
			//->join(FacebookMapped::model()->tableName().' b', 'a.email=b.fbemail')
			->where($filter_conditions, $filter_params)
			->limit($pages->pageSize)
			->offset(($pages->pageSize) * $page_current - 1)
			->queryAll();
				
			$count = Yii::app()->db->createCommand()
			->select('count(a.id) as count_user')
			->from($core_user.' a')
			//->join(FacebookMapped::model()->tableName().' b', 'a.email=b.fbemail')
			->where($filter_conditions, $filter_params)
			->queryAll();
			$pages->itemCount = $count['0']['count_user'];
	
			$result = array();
				
			/**
			 * Nếu có kết quả thị lặp và sắp xếp kết quả vào một array()
			*/
			if (!empty($user)) {
				foreach ($user as $sub_item) {
// 					$user_Facebook = FacebookMapped::model()->findByAttributes(array('fbemail'=>$sub_item['email']));
					$result[] = CMap::mergeArray(
						$sub_item,
						array(
							'is_activation'	=> ($type_core_user==="GNUser") ? 'true' : 'false',
// 							'source'		=> !empty($user_Facebook) ? 'facebook' : 'form'
							'source'		=> 'form'
						));
				}
			}
		} else {
			/**
			 * Hiển thị danh sách các user đã đăng ký và đã hết thời hạn kích hoạt
			 */
			if (isset($_GET['type']) && ($_GET['type']==="tmp_user_expiry_date")) {
				$time_day = strtotime(date("Y-m-d H:i:s", strtotime("-1 day")));
	
				if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all') {
					$criteria->condition = "created >= :fromday AND created <= :today AND created <:time_day AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
					$criteria->params = array(
						':time_day'	=> $time_day,
						':fromday'	=> $date_From,
						':today'	=> $date_To,
					);
				} else {
					$criteria->condition = "created <:time_day AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
					$criteria->params = array(
						':time_day'	=> $time_day,
					);
				}
	
				$data = GNUserTmp::model()->findAll($criteria);
	
				$pages->itemCount = GNUserTmp::model()->count($criteria);
				$pages->applyLimit($criteria);
	
				$result = array();
	
				if (!empty($data)) {
					foreach ($data as $items) {
						//$user_Facebook = FacebookMapped::model()->findByAttributes(array('fbemail'=>$items->attributes['email']));
						$result[] = CMap::mergeArray(
							$items->attributes,
							array(
								'is_activation'	=> 'false',
								//'source'		=> !empty($user_Facebook) ? 'facebook' : 'form'
								'source'		=> 'form'
							));
					};
				}
			} else {
				/**
				 * Hiển thị danh sách các user đăng ký qua form : Bao gồm đã kích hoạt và chưa kích hoạt.
				 */
				if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all') {
					$criteria->condition = " created >= :fromday AND created <= :today AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
					$criteria->params = array (
						':fromday' => $date_From,
						':today' => $date_To,
					);
				} else {
					//$criteria->condition = "username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
					$criteria->addSearchCondition('username',$username);
					$criteria->addSearchCondition('displayname',$displayname);
					$criteria->addSearchCondition('email',$email);
				}
	
				$pages->itemCount = $user->count($criteria);
				$pages->applyLimit($criteria);
					
				$data = $user->findAll($criteria);
				$result = array();
				foreach ($data as $items) {
// 					$user_Facebook = FacebookMapped::model()->findByAttributes(array('fbemail'=>$items->attributes['email']));
					$result[] = CMap::mergeArray(
						$items->attributes,
						array(
							'is_activation'	=> ($type_core_user==="GNUser") ? 'true' : 'false',
// 							'source'		=> !empty($user_Facebook) ? 'facebook' : 'form'
							'source'		=> 'form'
						));
				}
			}
		}
	
		/**
		 * Định nghĩa tất cả các kiểu dữ liệu lấy về
		 * @var unknown_type
		 */
		$arrType = array(
			'0' => array(
				'key' => 'GNUser',
				'name'=> '-- Core User',
				'description' => 'Truy xuất các user đã đăng ký và đã kích hoạt.'
			),
			'1' => array(
				'key' => 'GNUserTmp',
				'name'=> '-- Tmp User',
				'description' => 'Truy xuất các user đã đăng ký nhưng chưa kích hoạt.'
			),
			'2' => array(
				'key' => 'face_user',
				'name'=> '-- Facebook User',
				'description' => 'Truy xuất các user đăng ký bằng Facebook và đã kích hoạt.'
			),
			'3' => array(
				'key' => 'face_tmp_user',
				'name'=> '-- Facebook Tmp User',
				'description' => 'Truy xuất các user đăng ký Facebook và chưa kích hoạt.'
			),
			'4' => array(
				'key' => 'tmp_user_expiry_date',
				'name'=> '-- Tmp User Expiry Date',
				'description' => 'Truy xuất các user đăng ký và chưa kích hoạt nhưng đã hết thời hạn kích hoạt.'
			),
		);
		/**
		 * Khai báo các kiểu xem
		 * @var unknown_type
		*/
		$view_mode = array(
			'5' => array(
				'value' => 'all',
				'name'	=> '-- All'
			),
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
				'username'	=> $username,
				'displayname'	=> $displayname,
				'email'	=> $email,
				'typeName_core' => isset($_GET['type']) ? $_GET['type'] : 'GNUser',
				'viewMode'	=> $view_mode
			)
		);
	}
	
	/**
	 * Action using to created  User
	 */
	public function actionCreated() {
		$model = new GNRegistrationInfoForm();
		
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'admin_created_user') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		
		if (isset($_POST['GNRegistrationInfoForm'])) {
			$model->attributes = $_POST['GNRegistrationInfoForm'];
			$model->verifyPassword = $model->textPassword;
			$model->verifyEmail = $model->email;
			$model->firstname = ucfirst(strtolower($model->firstname));
			$model->lastname = ucfirst(strtolower($model->lastname));
			$model->displayname = $model->firstname . " " . substr($model->lastname, 0, 1) . ".";
			
			if (!isset($_POST['GNRegistrationInfoForm']['username']) || empty($_POST['GNRegistrationInfoForm']['username'])) {
				$username = Sluggable::slug($_POST['GNRegistrationInfoForm']['email']);
				$username = preg_replace("/@/", '.', $username);
				$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);
				
				$model->username = $username;
			}
			$model->encryptPassword($model->textPassword);
			if ($model->validate()) {
				if ($model->save()) {
					/**
					 * Send mail to user account
					 */
					Rights::assign(Yii::app()->params['roles']['MEMBER'], $model->id);

					$subject = "Congratulations, you registration has been successful.";
					$urlLogin = GNRouter::createAbsoluteUrl('/user/activation/loginUser/email/'.$model->email.'/key/'.IDHelper::uuidFromBinary($model->id, true));
					$data = array(
							'username'	=> $model->username,
							'displayname'	=> $model->displayname,
							'firstname'	=> $model->firstname,
							'lastname'	=> $model->lastname,
							'email'		=> $model->email,
							'location'	=> '',
							'url'		=> $urlLogin
					);
					$view = "register-activation-code";
					$this->_sendMail($model->email, $subject, $view, $data);
					
					Yii::app()->user->setFlash('success', "Created new account was successful !");
					$this->redirect(GNRouter::createAbsoluteUrl('/admin_manage/manageUser/created'));
				}
			} 
		}
		$this->render('created', array('model' => $model));
	}
	
	/**
	 * Method using to send link reset pasword for user
	 */
	
	public function actionResetPassword($binUser) {
		$model = new GNForgotPasswordForm;

		$data = GNUser::model()->findByAttributes(array('id'=>IDHelper::uuidToBinary($binUser)));
		$model->id = IDHelper::uuidToBinary($binUser);
		$model->email = $data->email;
		$model->attributes = $model;
		$model->username = $data->username;
		$model->displayname = $data->displayname;

		if ($binUser) {
			$model->id = IDHelper::uuidToBinary($binUser);
			GNUserActivation::model()->deleteAllByAttributes(array(
					'user_id' => $model->id,
					'type' => GNUserActivation::TYPE_FORGOTPASS,
			));
			
			$codeForgotPassword = GNUserActivation::createCode($model->id, GNUserActivation::TYPE_FORGOTPASS);

			if ($codeForgotPassword != '') {
				/**
				 * @todo Change message email
				 */
				$strForgotPasswordUrl = GNRouter::createAbsoluteUrl('/user/recovery/changepassword', array("code" => $codeForgotPassword, "email" => $model->email));
			//	$strForgotPasswordUrl = MailHelper::resetUrl($strForgotPasswordUrl);
				$subject = "JustLook.vn - First step of reset password was successful.";
				$strMsgHTML = "<h3>JustLook.vn - First step of reset password was successful</h3>";
				$strMsgHTML = $strMsgHTML."<p>You are recovery password in JustLook systems was successful. To confirm and changed old password, please click in link to input new password. </p>";
				$strMsgHTML = $strMsgHTML."Click here to input new password : <a href='{$strForgotPasswordUrl}'>{$strForgotPasswordUrl}</a>";
				$strMsgHTML = $strMsgHTML."<p>Thank you!</p>";

				MailHelper::Send($subject, $strMsgHTML, array(array($model->email, $model->displayname)));
				
				Yii::app()->user->setFlash('success', "Look.vn System send a email to address {$model->email} of user account {$model->displayname}!");
				$this->redirect(GNRouter::createAbsoluteUrl('/admin_manage/manageUser/listUser'));
			}
			
			Yii::app()->user->setFlash('error', "The JustLook attributes has been saved");
			$this->redirect(GNRouter::createAbsoluteUrl('/admin_manage/manageUser/listUser'));
		}
	}
	
	/**
	 * Method using to view list include infomation of all user
	 */
	public function actionListUser() {
		$user = GNUser::model();
		$pages=new CPagination();
		$pages->pageSize = 30;
		
		$criteria = new CDbCriteria();
		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		$criteria->order = 'created DESC';
		
		$pages->itemCount = $user->count($criteria);
		$pages->applyLimit($criteria);
		
		$models = $user->findAll($criteria);

		$this->render('listUser', array(
			'model'=>$models,
			'count'=>$pages->itemCount,
			'item_count'=>$pages->pageSize,
			'pages' => $pages,
			'cnn'=>'0'
		));
	}
	
	/**
	 * Method using to delete a user with binIDUser
	 * @param unknown_type $binIDUser
	 */
	
	public function actionDelete($binIDUser = null) {
		//debug($binIDUser);
		if (isset($binIDUser)) {
			$binIDUser = IDHelper::uuidToBinary($binIDUser);
			
			$getUser = GNUser::model()->deleteUser($binIDUser);
			
			if ($getUser) {
				$uri = '/upload/user-photos/' . IDHelper::uuidFromBinary($binIDUser);
				$filePath = Yii::getPathOfAlias('webroot') . $uri . "/";
			
				if (file_exists($filePath)) {
					@unlink($filePath);
				}
				
				// TODO: Delete image from mongoDB too
			
				GNUserProfile::model()->deleteAllByAttributes(array(
						'user_id'=>$binIDUser
				));
				
				Yii::app()->user->setFlash('success', "Delete account user was successful !");
				$this->redirect(GNRouter::createAbsoluteUrl('/admin_manage/manageUser/listUser'));
			}
		} else {
			Yii::app()->user->setFlash('error', "Delete account user was fail !");
			$this->redirect(GNRouter::createAbsoluteUrl('/admin_manage/manageUser/listUser'));
		}
		
	}
	
	/**
	 * Method using to upload photo for a user
	 * @param unknown_type $binIDUser
	 */
	public function actionUploadPhoto($uuid = NULL) {
		if (isset($uuid)) {
			
			$fileUpload			=	CUploadedFile::getInstanceByName('UserPhoto');
			
			$userInfo = GNUser::model()->getUserInfo(IDHelper::uuidToBinary($uuid));
			// Thiết lập đường dẫn upload
			$uri = '/upload/user-photos/' . $uuid;
		
			Yii::import('application.modules.user.models.GNUserAvatar');
			$fs = GNUserAvatar::model();
			
			$fs->owner_id = $uuid;
			$info = $fs->saveFile($fileUpload);
			
			// Save info to database
			$filename = $info['basename'];
			
			$photoID = $info['filename'];
			
			$imageSize = getimagesize("{$info['dirname']}/{$info['basename']}");
			$userInfo->updateCache(array('avatar'));
			// Print result
			$out = array(
				array(
					'name'		=> $filename,
					'filename' => $filename,
					'content_type' => $fileUpload->type,
					'size' => $fileUpload->size,
					'url' => 'javascript:void(0)',
					//'thumbnail_url'	=> GNRouter::createUrl("{$uri}/fill/{$_GET['size']}/{$filename}"),
					'image_url'		=> GNRouter::createUrl("{$uri}/{$filename}"),
					'delete_url' 	=> GNRouter::createUrl('/user/managePhoto/deletePhoto', array('photoID' => $photoID)),
					'delete_type'	=> 'DELETE',
					'photo_id'		=> $photoID,
					'detailLink'	=> GNRouter::createUrl("/user/photos/showGallery?uid=".$fs->owner_id."&imgID={$photoID}"),
					'isExisted'		=> GNUserAvatar::model()->checkDuplicate($photoID),
					'width'			=> $imageSize[0],
					'height'		=> $imageSize[1]
				)
			);
	
			jsonOut($out);
		}
	}
	
	/**
	 * Method using to view deital of upload photo
	 * @param unknown_type $binIDUser
	 */
	public function actionPhotoUser($binIDUser = NULL) {
		if (isset($binIDUser)) {
			$uuid =  $strBizID = str_replace("-", "", $binIDUser);;
			$binIDUser = IDHelper::uuidToBinary($binIDUser);
			$dataUser = GNUser::getUserInfo($binIDUser);
			$photo = GNUserPhoto::model()->findAll('user_id = :binIDUser', array(
					':binIDUser'=>$binIDUser
					));
			
			$arrUserInfo = array(
					'uuid'=>IDHelper::uuidFromBinary($dataUser->id, TRUE),
					'username'=>$dataUser->username,
					'displayname'=>$dataUser->displayname
				);
			
			
			$maxUploadFiles = 99;
			//Yii::app()->getClientScript()->registerScriptFile($this->baseScriptUrl . '/js/jlbd.manage-photo.js', CClientScript::POS_END);
			
			/*$userPhotos = currentUser()->avatars;
			
			$photos = array();
			if (!empty($userPhotos['primary'])) {
			$photos = CMap::mergeArray(array($userPhotos['primary']), $userPhotos['others']);
			} else {
			$photos = $userPhotos['others'];
			}*/
			$photos = GNUserAvatar::model()->getUserAvatars($uuid, array(), $limit = 1000);
			
			Yii::import("application.modules.photo.models.GNContributePhoto");
			$contributePhotos = GNContributePhoto::model()->getAllContributedPhotosByUser($uuid, array('owner_id', 'biz_id', 'rating', 'metadata'));
			
		//	debug($arrUserInfo);
			$this->render('photoUser', array(
					'model'=>$dataUser,
					'UserInfo'=>$arrUserInfo,
					'userPhotos'		=> $photos,
					'contributePhotos'	=> $contributePhotos,
			));
			
		}
	}
	
	/**
	 * Method using to search user account
	 * @param unknown_type $name_username
	 * @param unknown_type $name_fullname
	 * @param unknown_type $name_email
	 */
	public function actionSearchAdvanced($name_username = NULL, $name_fullname = NULL, $name_email = NULL) {
		$user = GNUser::model();
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
		$criteria->order = 'created DESC';
		
		$pages->itemCount = $user->count($criteria);
		$pages->applyLimit($criteria);
		
		$models = $user->findAll($criteria);
		
// 		$models = new CActiveDataProvider('GNUser', array(
// 				'criteria'=>$criteria,
// 				'pagination' => array(
// 						'pageSize' => $pages->pageSize
// 				),
// 		));
		
		//debug($models);
		$this->render('listUser', array(
			'model'=>$models,
			'count'=>$pages->itemCount,
			'item_count'=>$pages->pageSize,
			'pages' => $pages,
			'cnn'=>0
		));
	}
	
	public function actionDeletePhotoUser($binIDUser = NULL, $photoID = NULL) {
		if (isset($binIDUser)) {
			//debug($binIDUser.' '.$photoID);
			$binIDUser = IDHelper::uuidToBinary($binIDUser);
			
			$photo = GNUserPhoto::model()->find('user_id = :user_id and id=:id', array(
					':user_id'	=> $binIDUser,
					':id'		=> IDHelper::uuidToBinary($photoID)
			));
			//debug(array(currentUser()->id, IDHelper::uuidToHex($_GET['photoID'])));
			if (!empty($photo)) {
				$uri = '/upload/user-photos/' . IDHelper::uuidFromBinary($binIDUser);
				$uploadPath = Yii::getPathOfAlias('webroot') . $uri . "/";
			
				$filePath = $uploadPath . "{$photo->filename}";
				if (file_exists($filePath)) {
					@unlink($filePath);
					@unlink($uploadPath . "thumbs/64-64/{$photo->filename}");
					@unlink($uploadPath . "thumbs/200-200/{$photo->filename}");
					@unlink($uploadPath . "thumbs/400-400/{$photo->filename}");
				}
			
				$photo->delete();
			
				$arr = array(
						"error"		=> false,
						"message"	=> "Photo has been delete successful"
				);
			} else {
				$arr = array(
						"error"		=> true,
						"message"	=> "Your request is invalid. Can't delete this photo"
				);
			}
			
			jsonOut($arr);
		}
	}
	
	/**
	 * Method using to make primary of photo
	 */
	public function actionMakePhotoUser($binIDUser = NULL, $binIDPhoto = NULL) {
		if (isset($binIDUser) && isset($binIDPhoto)) {
			$binIDUser = IDHelper::uuidToBinary($binIDUser);
			$binIDPhoto = IDHelper::uuidToBinary($binIDPhoto);
			
			$photo = GNUserPhoto::model()->find('user_id = :user_id and id=:id', array(
					':user_id'	=> $binIDUser,
					':id'		=> $binIDPhoto
			));
			
			$photo->is_primary = 1;
			$photo->modified = date("Y/m/d H:i:s");
			
			GNUserPhoto::model()->updateAll(array(
					'is_primary'	=> 0
			));
			
			if ($photo->save()) {
				$arr = array(
						"error"		=> false,
						"message"	=> "Photo has been mark as primary successful"
				);
			} else {
				$arr = array(
						"error"		=> true,
						"message"	=> "Can't mark this photo as primary"
				);
			}
			
			jsonOut($arr);
		}
	}
	public function actionEdit($binUserID = NULL) {
		
		if (isset($binUserID)) {
			$user = GNUser::model()->findByPk(IDHelper::uuidToBinary($binUserID));
			if (!empty($_POST['GNUser'])) {

				$model = new GNChangeEmailForm;
				$email  = trim($_POST['GNUser']['email']);
				$model->email = $email;
				$model->verifyEmail = $email;
				$oldMail = $user->email;
				
				if (!empty($_POST['GNUser']['firstname'])) $user->firstname = $_POST['GNUser']['firstname'];
				if (!empty($_POST['GNUser']['lastname'])) $user->lastname = $_POST['GNUser']['lastname'];
				
				$user->firstname		= strtoupper(substr($user->firstname, 0, 1)).substr($user->firstname, 1, strlen($user->firstname));
				$user->lastname		= strtoupper(substr($user->lastname, 0, 1)).substr($user->lastname, 1, strlen($user->lastname));
				$user->username		= $user->firstname . " " . substr($user->lastname, 0, 1) . ".";
				
				if (!empty($_POST['GNUser']['email'])) {
					if ($user->email !== $email) {
						if ($model->validate()) {
							$user->email = $email;
						} else {
// 							jsonOut(json_decode(CActiveForm::validate($model))->GNChangeEmailForm_email[0]);
							$confirn = array(
									'error'=>true,
									'message'=> json_decode(CActiveForm::validate($model))->GNChangeEmailForm_email[0]
								);
							jsonOut($confirn);
						}
					}
				}

				if (!empty($user->firstname) && !empty($user->lastname)) {
					$user->username = ucfirst($user->firstname) . " " . strtoupper(mb_substr($user->lastname, 0, 1, 'UTF-8')) . ".";
				} else {
					$confirn = array(
							'error'=>true,
							'message'=>'Firstname or lastname not true'
						);
					jsonOut($confirn);
				}
				
				if ($user->validate()) {
					$user->isNewRecord = false;
					$update = $user->update(array('firstname', 'lastname', 'username', 'email'));
				} else {
					$confirn = array(
							'error'=>true,
							'message'=>json_decode(CActiveForm::validate($user))->GNUser_username[0]
					);
					jsonOut($confirn);
				}
				
				if ($update) {
					$confirn = array(
							'error'=>false,
							'message'=>'Update info of user was successful.'
						);
					jsonOut($confirn);
				} else {
					$confirn = array(
							'error'=>true,
							'message'=>'Can not update info of current user.'
						);
				}
				jsonOut($confirn);
			}
			$return = array(
					'user_id'=>IDHelper::uuidFromBinary($user->id, true),
					'username'=>$user->username,
					'firstname'=>$user->firstname,
					'lastname'=>$user->lastname,
					'email'=>$user->email
			);
			jsonOut($return);
		}
		
	}
	public function actionChangedPassword($binUserID = NULL) {
		if (isset($binUserID)) {
			$user = GNUser::model()->findByPk(IDHelper::uuidToBinary($binUserID));
			if (!empty($user)) {
				$model = new GNChangePasswordForm;
				if (isset($_POST['GNChangePasswordForm'])) {
					$model->attributes = $_POST['GNChangePasswordForm'];
				
					if ($model->validate()) {
						$user->encryptPassword($model->password);
				
						if ($user->save()) {
							jsonOut(array(
										'error'=>false,
										'message'=>'Changed password for current user was successful.'
									), false);
							$subject = "Hi {$user->firstname} {$user->lastname}, we have a  email to you.";
							
							Yii::import('application.extensions.yii-mail.*');
							
							try {
								$message = new YiiMailMessage;
								$message->view = 'changed-password-user';
								$message->setSubject($subject);
								$message->setBody(
										array(
												'data'	=> array(
														'firstname'	=> $user->firstname,
														'lastname'	=> $user->lastname,
														'username'	=> $user->username,
														'password'	=> $model->password,
												),
										),
										'text/html'
								);
								$message->addTo($user->email);
								if(empty($from)) $from = Yii::app()->params->mailer['username'];
								$message->from = $from;
							
								Yii::app()->mail->send($message);
							} catch (Exception $ex) {
							
								return array(
										'error'			=> true,
										'msg'			=> $ex->getMessage()
								);
							}
						} else {
							jsonOut(array(
										'error'=>true,
										'message'=>'Can not changed password for currrent user.'
									));
						}
					} else {
						jsonOut(array(
								'error'=>true,
								'message'=>'Password is not rules.'
						));
					}
				}
			} else {
				jsonOut(array(
						'error'=>true,
						'message'=>'User is not acount.'
					));
			}
		}
	}
	/**
	 * Code for thong ke user
	 */
	public function actionTest() {
		$dateTo = time(date("Y-m-d G:i:s"));
// 		$dateFrom = date("Y-m-d G:i:s", strtotime("-1 week"));
// 		$dateFrom = date("Y-m-d G:i:s", strtotime("-1 month"));
		$dateFrom = strtotime(date("Y-m-d", strtotime("-1 day"))." 00:00:00");
		debug($dateFrom);
	}
	public function actionMonitor() {
		Yii::import('application.components.jl_bd.helpers.*');
		
		/**
		 *Kiểm tra xem biến $_GET['type'] có tồn tại hay không
		 *	- kiểm tra loại type và khai báo tên lớp Model tương ứng
		 **/
		if (isset($_GET['type']) && $_GET['type']!="" && $_GET['type']!=="face_user" && $_GET['type']!=="face_tmp_user" && $_GET['type']!=="all" && $_GET['type']!=="tmp_user_expiry_date") {
			$user = $_GET['type']::model();
			$type_core_user = $_GET['type'];
		} else {
			$user =GNUser::model();
			$type_core_user = 'GNUser';
		}
		
		/**
		 * Khai báo sử dụng lớp phân trang
		 * @var unknown_type
		 */
		$pages=new CPagination();
		$pages->pageSize = 30;
		
		$criteria = new CDbCriteria();
		$username = '';
		$email = '';
	
		/**
		 * Kiểm tra xem có $_GET username hay email hay không
		 * 	- Nếu có thì khai báo condition trong criteria
		 */
		if(isset($_GET['name_username']) && $_GET['name_username']!="") {
			$username = $_GET['name_username'];
		}
		if(isset($_GET['name_email']) && $_GET['name_email']!="") {
			$email = $_GET['name_email'];
		}
		
		$criteria->limit = $pages->limit;
		$criteria->offset = $pages->offset;
		$criteria->order = 'created DESC';
		
		/**
		 * Khai báo  và khởi gán các giá trị lọc theo thời gian
		 */
		$dateFrom = "01-".date("m")."-".date("Y")." ".date("G:i:s");
		$dateTo = date("d-m-Y G:i:s");
		$date_From = strtotime($dateFrom);
		$date_To = strtotime($dateTo);
		
		/**
		 * Cấu hình thời gian lọc theo điều kiện được chọn
		 */
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
		
		/**
		 * Hiển thị danh sách tất cả các user đăng ký qua facebook : Bao gồ các user đã kích hoạt và chưa kích hoạt
		 */
		if (isset($_GET['type']) && ($_GET['type']==="face_user" || $_GET['type']==="face_tmp_user")) {
			if ($_GET['type']==="face_user") {
				$core_user = GNUser::model()->tableName();
				$type_core_user = 'GNUser';
			} else {
				$core_user = GNUserTmp::model()->tableName();
				$type_core_user = 'GNUserTmp';
			}
			
			//Get pages to limit/offset
			$page_current = (isset($_GET['page']) && $_GET['page']!=='') ? $_GET['page'] : 0;
			
			/**
			 * Kiểm tra xem chế độ xem có chọn hay không
			 * 	- Nếu chọn thì xem nếu khác kiểu khác all thì truy vấn
			 */
			if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all') {
				$filter_conditions = "created >= :fromday AND created <= :today AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
				$filter_params = array(
							':fromday'	=> $date_From,
							':today'	=> $date_To
						);
			/**
			 * Nếu all thì :
			 */
			} else {
				$filter_conditions = "username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
				$filter_params = array();
			}
			/**
			 * Tiến hành truy vấn dữ liệu và count kết quả
			 * @var unknown_type
			 */
			$user = Yii::app()->db->createCommand()
				->select('a.id, a.username, a.lastname, a.firstname, a.email, a.created')
				->from($core_user.' a')
// 				->join(FacebookMapped::model()->tableName().' b', 'a.email=b.fbemail')
				->where($filter_conditions, $filter_params)
				->limit($pages->pageSize)
				->offset(($pages->pageSize) * $page_current - 1)
				->queryAll();
			
			$count = Yii::app()->db->createCommand()
				->select('count(a.id) as count_user')
				->from($core_user.' a')
// 				->join(FacebookMapped::model()->tableName().' b', 'a.email=b.fbemail')
				->where($filter_conditions, $filter_params)
				->queryAll();
			$pages->itemCount = $count['0']['count_user'];

			$result = array();
			
			/**
			 * Nếu có kết quả thị lặp và sắp xếp kết quả vào một array()
			 */
			if (!empty($user)) {
				foreach ($user as $sub_item) {
// 					$user_Facebook = FacebookMapped::model()->findByAttributes(array('fbemail'=>$sub_item['email']));
					$result[] = CMap::mergeArray(
							$sub_item,
							array(
									'is_activation'	=> ($type_core_user==="GNUser") ? 'true' : 'false',
// 									'source'		=> !empty($user_Facebook) ? 'facebook' : 'form'
								'source'			=> 'form'
							));
				}
			}
		} else {
			/**
			 * Hiển thị danh sách các user đã đăng ký và đã hết thời hạn kích hoạt
			 */
			if (isset($_GET['type']) && ($_GET['type']==="tmp_user_expiry_date")) {
				$time_day = strtotime(date("Y-m-d H:i:s", strtotime("-1 day")));
				
				if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all') {
					$criteria->condition = "created >= :fromday AND created <= :today AND created <:time_day AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
					$criteria->params = array(
						':time_day'	=> $time_day,
						':fromday'	=> $date_From,
						':today'	=> $date_To,
					);
				} else {
					$criteria->condition = "created <:time_day AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
					$criteria->params = array(
						':time_day'	=> $time_day,
					);
				}
				
				$data = GNUserTmp::model()->findAll($criteria);
				
				$pages->itemCount = GNUserTmp::model()->count($criteria);
				$pages->applyLimit($criteria);
				
				$result = array();
				
				if (!empty($data)) {
					foreach ($data as $items) {
// 						$user_Facebook = FacebookMapped::model()->findByAttributes(array('fbemail'=>$items->attributes['email']));
						$result[] = CMap::mergeArray(
							$items->attributes,
							array(
								'is_activation'	=> 'false',
// 								'source'		=> !empty($user_Facebook) ? 'facebook' : 'form'
								'source'		=> 'form'
							));
					};
				}
			} else {
			/**
			 * Hiển thị danh sách các user đăng ký qua form : Bao gồm đã kích hoạt và chưa kích hoạt.
			 */
				if (isset($_GET['filter_give']) && $_GET['filter_give']!=='all') {
					$criteria->condition = " created >= :fromday AND created <= :today AND username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
					$criteria->params = array (
							':fromday' => $date_From,
							':today' => $date_To,
					);
				} else {
					$criteria->condition = "username LIKE '%{$username}%' AND email LIKE '%{$email}%'";
				}
				
				$pages->itemCount = $user->count($criteria);
				$pages->applyLimit($criteria);
			
				$data = $user->findAll($criteria);
				$result = array();
				foreach ($data as $items) {
// 					$user_Facebook = FacebookMapped::model()->findByAttributes(array('fbemail'=>$items->attributes['email']));
					$result[] = CMap::mergeArray(
						$items->attributes,
						array(
								'is_activation'	=> ($type_core_user==="GNUser") ? 'true' : 'false',
// 								'source'		=> !empty($user_Facebook) ? 'facebook' : 'form'
							'source'			=> 'form'
						));
				}
			}
		}
		
		/**
		 * Định nghĩa tất cả các kiểu dữ liệu lấy về
		 * @var unknown_type
		 */
		$arrType = array(
			'0' => array(
				'key' => 'GNUser',
				'name'=> '-- Core User',
				'description' => 'Truy xuất các user đã đăng ký và đã kích hoạt.'
			),
			'1' => array(
				'key' => 'GNUserTmp',
				'name'=> '-- Tmp User',
				'description' => 'Truy xuất các user đã đăng ký nhưng chưa kích hoạt.'
			),
			'2' => array(
				'key' => 'face_user',
				'name'=> '-- Facebook User',
				'description' => 'Truy xuất các user đăng ký bằng Facebook và đã kích hoạt.'
			),
			'3' => array(
				'key' => 'face_tmp_user',
				'name'=> '-- Facebook Tmp User',
				'description' => 'Truy xuất các user đăng ký Facebook và chưa kích hoạt.'
			),
			'4' => array(
				'key' => 'tmp_user_expiry_date',
				'name'=> '-- Tmp User Expiry Date',
				'description' => 'Truy xuất các user đăng ký và chưa kích hoạt nhưng đã hết thời hạn kích hoạt.'
			),
		);
		/**
		 * Khai báo các kiểu xem
		 * @var unknown_type
		 */
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
		/**
		 * Render to views file
		 */
		$this->render('monitor', array(
			'model'=>$result,
			'count'=>$pages->itemCount,
			'item_count'=>$pages->pageSize,
			'pages' => $pages,
			'cnn'=>'0',
			'dateFrom'=>$dateFrom,
			'dateTo'=>$dateTo,
			'type'	=> $arrType,
			'typeName_core' => isset($_GET['type']) ? $_GET['type'] : 'GNUser',
			'viewMode'	=> $view_mode
		));
	}
	/**
	 * Phương thức dùng để xử lý khi chọn check box (all or từng item)
	 * - Tùy theo action chọn mà delete hay resend email
	 */
	public function actionMonitorAction($binUserID = NULL, $models = NULL) {
		//Kiểm tra biến POST lên
		if (!empty($_POST) && !empty($_POST['Action_Monitor']['check']) && !empty($_POST['Action_Monitor']['type_action'])) {
			//Biến các giá trị chọn checkbox
			$data_check = $_POST['Action_Monitor']['check'];
			//Biến chọn các action thực hiện : Delete | Send email
			$type_action = $_POST['Action_Monitor']['type_action'];
		}
			
		//Biến của class models cần thực hiện
		$type_core = isset($_POST['Action_Monitor']['type_core']) ? $_POST['Action_Monitor']['type_core'] : '';
		//Kiểm tra biến models và gán biến models
		if (!empty($type_core) && $type_core!=="tmp_user_expiry_date" && $type_core!=="face_tmp_user" && $type_core!=="GNUserTmp") {
			$type_core = "GNUser";
		} else {
			$type_core = "GNUserTmp";
		}

		//Kiểm tra nếu tồn tại biến binUserID thì tiến hành cho xóa 1 user
		if (isset($binUserID) && $binUserID!="" && isset($models) && $models!='') {
			$type_action = "deleteByID";
			$binUserID = IDHelper::uuidToBinary($binUserID);
			
			if($models=="face_tmp_user" || $models=="tmp_user_expiry_date" || $models=="GNUserTmp") {
				$type_core = "GNUserTmp";
			} else {
				$type_core = "GNUser";
			}
		}

		if (isset($type_core) && isset($type_action)) {
			try {
				switch($type_action) {
					case 'delete':
						if (!empty($data_check)) {
							foreach ($data_check as $items) {
								$binUserID = IDHelper::uuidToBinary($items);
								$type_core::model()->deleteUser($binUserID);
							}
						} else {
							throw new Exception("Invalid user data");
						}
						break;
					case 'deleteByID':
						if (isset($binUserID) && $binUserID!='') {
							$type_core::model()->deleteUser($binUserID);
						} else {
							throw new Exception("Invalid user data");
						}
						break;
					case 'resendEmailPass':
						break;
					default:
						break;
				}
			} catch (Exception $e) {
				Yii::app()->user->setFlash('error', $e->getMessage());
				$this->redirect($_SERVER['HTTP_REFERER']);
			}
			
			Yii::app()->user->setFlash('success', "User has been deleted successfully!");
			$this->redirect($_SERVER['HTTP_REFERER']);
		}
		Yii::app()->user->setFlash('error', "There's an error while deleting account!");
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
			$subject = isset($subject) ? $subject : "We have a confirmation email to you.";
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
			$user = GNUserTmp::model()->findByPk(IDHelper::uuidToBinary($binUserID,true));
	
			if (!empty($user)) {
				$deleteActivaCode = GNUserActivation::model()->deleteAllByAttributes(array(
						'user_id' => $user->id,
						'type' => GNUserActivation::TYPE_ACTIVATION,
				));
	
				$codeActivation = GNUserActivation::createCode($user->id, GNUserActivation::TYPE_ACTIVATION);
	
				if ($codeActivation != '') {
					jsonOut(array(
							'error'		=> false,
							'message'	=> "<b>Email has been sent to {$user->email}</b>"
						), false);
					/**
					 * @todo Change message email
					 */
					$strActivationUrl = GNRouter::createAbsoluteUrl('/user/activation/activate', array("activekey" => $codeActivation, "email" => $user->email));
	
					//Send email forr usser
					$subject = "Register new account in JustLook.";
					$data = array(
							'url'	=> $strActivationUrl,
							'email'	=> $user->email
					);
					$view = "register-user-first-step";
					$this->_sendMail($user->email, $subject, $view, $data);
	
				} else {
					jsonOut(array(
							'error'		=> true,
							'message'	=> "JustLook Error!", "Can't create activation code"
					));
				}
			}
		}
	}
}