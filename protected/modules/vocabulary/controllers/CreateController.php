<?php
/**
 * CreateController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Aug 26, 2013 1:52:45 PM
 */
//Yii::import('import something here');
class CreateController extends GNController {
	public $layout = "//../views/themes/bootstrap/views/layouts/main";
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}

	public function actions(){
		return array(
			
		);
	}
	
	public function actionDataset() {
		$model = new UserData();
		
		if (!empty($_POST['UserData'])) {
			$model->title = $_POST['UserData']['title'];
			$model->year = date("Y");
			$model->created = date("Y-m-d H:i:s");
			$model->user_id = currentUser()->id;
			
			if ($model->save()) {
				Yii::app()->user->setFlash("success", "Dataset has been saved successful");
				$this->redirect('/');
			} else {
				$errors = $model->errors;
				$error = current($errors);
				
				$backUrl = GNRouter::createUrl('/vocabulary/create/dataset');
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'message'	=> $error,
						'url'		=> $backUrl
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type' 		=> 'error',
						'autoHide'	=> true,
						'message'	=> $error,
					));
				
					$this->redirect($backUrl);
				}
			}
		} else {
			$this->render('create-dataset', compact('model'));
		}
	}
	
	public function actionWord() {
		if (!empty($_POST['GNWord'])) {
			$model = new GNWord();
			
			$_POST['GNWord']['word'] = trim($_POST['GNWord']['word']);
			
			$id = $_POST['GNWord']['user_data_id'];
			
			$model->setAttributes($_POST['GNWord'], false);
			$model->user_data_id = IDHelper::uuidToBinary($id);
			$model->created = date("Y-m-d H:i:s");
			
			if ($model->save()) {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> false,
					'type' 		=> 'success',
					'autoHide'	=> true,
					'message'	=> "Word has been save successful",
				));
				
				$this->redirect('/vocabulary/list?id='.$id);
			} else {
				$errors = $model->errors;
				dump($errors);
				$error = current($errors);
				throw new Exception($error);
			}
		} else {
			$this->redirect('/');
		}
	}
}