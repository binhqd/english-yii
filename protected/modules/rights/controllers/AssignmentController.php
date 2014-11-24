<?php
/**
* Rights assignment controller class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.1
*/
class AssignmentController extends GNController
{
	/**
	* @property RAuthorizer
	*/
	private $_authorizer;
	public $layout='//layouts/admin';
	public function allowedActions()
	{
		return '*';
	}
	
	/**
	* Initializes the controller.
	*/
	public function init()
	{
		$this->_authorizer = $this->module->getAuthorizer();
		//debug($this->module);
		$this->layout = $this->module->layout;
		$this->defaultAction = 'view';

		// Register the scripts
		$this->module->registerScripts();
	}

	/**
	* @return array action filters
	*/
	public function filters()
	{
		return array('rights');
	}

	/**
	* Specifies the access control rules.
	* This method is used by the 'accessControl' filter.
	* @return array access control rules
	*/
	public function accessRules()
	{
		return array(
			array('allow', // Allow superusers to access Rights
				'actions'=>array(
					'view',
					'user',
					'revoke',
				),
				'users'=>$this->_authorizer->getSuperusers(),
			),
			array('deny', // Deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	* Displays an overview of the users and their assignments.
	*/
	public function actionView()
	{
		/**
		 * @edited Thêm chức năng lọc User theo Role
		 * @author huytbt
		 * @date 2011-06-04
		 */
		$model = new AuthItem;

		$userClass = Yii::app()->getModule('rights')->userClass;
		$modelUser = new $userClass;

		$criteria = new CDbCriteria;

		if (isset($_POST['AuthItem']))
		{
			$model->attributes = $_POST['AuthItem'];
			$modelUser->attributes = $_POST[$userClass];

			if (isset($_POST['AuthItem']['name']) && $_POST['AuthItem']['name']!='*')
			{
				$criteria->join = 'INNER JOIN ' . Yii::app()->authManager->assignmentTable . ' AuthAssignmentTable  ON t.id = AuthAssignmentTable.userid';
				$criteria->compare('AuthAssignmentTable.itemname',$_POST['AuthItem']['name']);
			}

			if (isset($_POST[$userClass]))
			{
				if ($criteria->join)
				{
					$criteria->join = 'INNER JOIN (' . $modelUser->tableName() . ' UserTable INNER JOIN ' . Yii::app()->authManager->assignmentTable . ' AuthAssignmentTable  ON UserTable.id = AuthAssignmentTable.userid) ON t.id = UserTable.id';
				} else
					$criteria->join = 'INNER JOIN ' . $modelUser->tableName() . ' UserTable  ON t.id = UserTable.id';
				if (isset($_POST[$userClass]['username']))
					$criteria->compare('UserTable.username',$_POST[$userClass]['username'],true);
				if (isset($_POST[$userClass]['email']))
					$criteria->compare('UserTable.email',$_POST[$userClass]['email'],true);
				if (isset($_POST[$userClass]['firstname']))
					$criteria->compare('UserTable.firstname',$_POST[$userClass]['firstname'],true);
				if (isset($_POST[$userClass]['lastname']))
					$criteria->compare('UserTable.lastname',$_POST[$userClass]['lastname'],true);
			}
		}

		// Create a data provider for listing the users
		$dataProvider = new RAssignmentDataProvider(array(
			'criteria'=>$criteria, // huytbt edited
			'pagination'=>array(
				'pageSize'=>50,
			),
		));

		// Render the view
		$this->render('view', array(
			'dataProvider'=>$dataProvider,
			'model'=>$model, // huytbt edited
			'modelUser'=>$modelUser,
		));
	}

	/**
	* Displays the authorization assignments for an user.
	*/
	public function actionUser()
	{
		// Create the user model and attach the required behavior
		$userClass = $this->module->userClass;
		$model = CActiveRecord::model($userClass)->findByPk(IDHelper::uuidToBinary($_GET['id']));
		$this->_authorizer->attachUserBehavior($model);

		$assignedItems = $this->_authorizer->getAuthItems(null, $model->getId());
		$assignments = array_keys($assignedItems);

		// Make sure we have items to be selected
		$assignSelectOptions = Rights::getAuthItemSelectOptions(null, $assignments);
		if( $assignSelectOptions!==array() )
		{
			$formModel = new AssignmentForm();

			// Form is submitted and data is valid, redirect the user
			if( isset($_POST['AssignmentForm'])===true )
			{
				$formModel->attributes = $_POST['AssignmentForm'];
				if( $formModel->validate()===true )
				{
					// Update and redirect
					$this->_authorizer->authManager->assign($formModel->itemname, $model->getId());
					$item = $this->_authorizer->authManager->getAuthItem($formModel->itemname);
					$item = $this->_authorizer->attachAuthItemBehavior($item);

					Yii::app()->user->setFlash($this->module->flashSuccessKey,
						Rights::t('core', 'Permission :name assigned.', array(':name'=>$item->getNameText()))
					);

					$this->redirect(array('assignment/user', 'id'=>IDHelper::uuidFromBinary($model->getId())));
				}
			}
		}
		// No items available
		else
		{
		 	$formModel = null;
		}

		// Create a data provider for listing the assignments
		$dataProvider = new RAuthItemDataProvider('assignments', array(
			'userId'=>$model->getId(),
		));

		// Render the view
		$this->render('user', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
			'formModel'=>$formModel,
			'assignSelectOptions'=>$assignSelectOptions,
		));
	}

	/**
	* Revokes an assignment from an user.
	*/
	public function actionRevoke()
	{
		// We only allow deletion via POST request
		if( Yii::app()->request->isPostRequest===true )
		{
			$itemName = $this->getItemName();

			// Revoke the item from the user and load it
			$this->_authorizer->authManager->revoke($itemName, IDHelper::uuidToBinary($_GET['id']));
			$item = $this->_authorizer->authManager->getAuthItem($itemName);
			$item = $this->_authorizer->attachAuthItemBehavior($item);

			// Set flash message for revoking the item
			Yii::app()->user->setFlash($this->module->flashSuccessKey,
				Rights::t('core', 'Permission :name revoked.', array(':name'=>$item->getNameText()))
			);

			// if AJAX request, we should not redirect the browser
			if( isset($_POST['ajax'])===false )
				$this->redirect(array('assignment/user', 'id'=>$_GET['id']));
		}
		else
		{
			throw new CHttpException(400, Rights::t('core', 'Invalid request. Please do not repeat this request again.'));
		}
	}

	/**
	* @return string the item name or null if not set.
	*/
	public function getItemName()
	{
		return isset($_GET['name'])===true ? urldecode($_GET['name']) : null;
	}
}
