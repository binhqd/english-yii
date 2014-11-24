<?php
/**
 * CreateController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Aug 26, 2013 1:52:45 PM
 */
//Yii::import('import something here');
class EditController extends GNController {
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
	
// 	public function actionDataset() {
// 		$model = new UserData();
		
// 		if (!empty($_POST['UserData'])) {
// 			$model->title = $_POST['UserData']['title'];
// 			$model->year = date("Y");
// 			$model->created = date("Y-m-d H:i:s");
// 			$model->user_id = currentUser()->id;
			
// 			if ($model->save()) {
// 				Yii::app()->user->setFlash("success", "Dataset has been saved successful");
// 				$this->redirect('/');
// 			} else {
// 				$errors = $model->errors;
// 				$error = current($errors);
				
// 				$backUrl = GNRouter::createUrl('/vocabulary/create/dataset');
// 				if ($controller->isJsonRequest) {
// 					ajaxOut(array(
// 						'error'		=> true,
// 						'message'	=> $error,
// 						'url'		=> $backUrl
// 					));
// 				} else {
// 					Yii::app()->jlbd->dialog->notify(array(
// 						'error'		=> true,
// 						'type' 		=> 'error',
// 						'autoHide'	=> true,
// 						'message'	=> $error,
// 					));
				
// 					$this->redirect($backUrl);
// 				}
// 			}
// 		} else {
// 			$this->render('create-dataset', compact('model'));
// 		}
// 	}
	
	public function actionWord() {
		if (!empty($_POST['GNWord'])) {
			$model = new GNWord();
			
			$_POST['GNWord']['word'] = trim($_POST['GNWord']['word']);
			unset($_POST['GNWord']['user_data_id']);
			unset($_POST['GNWord']['created']);
			
			$id = $_POST['GNWord']['id'];
			$id = IDHelper::uuidToBinary($id);
			unset($_POST['GNWord']['id']);
			
			$model = GNWord::model()->find('id=:id', array(
				':id'	=> $id
			));
			if (empty($model)) {
				throw new Exception("Invalid ID");
			}
			
			$model->setAttributes($_POST['GNWord'], false);
			
			//dump($model->attributes);
			//$model->user_data_id = IDHelper::uuidToBinary($id);
			//$model->created = date("Y-m-d H:i:s");
			
			$oldImage = $model->image;
			
			$config = array(
				'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
				'uploadPath'	=> "upload/english/",
				'width'			=> 300,
				'height'		=> 300
			);
			
			$uploader = Yii::createComponent($config);
			
			$image = $uploader->upload($model, 'image');
				
			// If file has been saved
			if (!empty($image)) {
				$model->image = $image['filename'];
			}
			
			if ($model->save()) {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> false,
					'type' 		=> 'success',
					'autoHide'	=> true,
					'message'	=> "Word has been save successful",
				));
				
				if (!empty($image) && !empty($oldImage)) {
					//$this->_uploader->remove($oldImage, true);
				}
				
				$this->redirect('/vocabulary/list?id='.IDHelper::uuidFromBinary($model->user_data_id, true));
			} else {
				$errors = $model->errors;
				$error = current($errors);
				throw new Exception($error);
			}
		} else {
			$id = Yii::app()->request->getParam('id');
			
			$model = GNWord::model()->find('id=:id', array(
				':id'	=> IDHelper::uuidToBinary($id)
			));
			
			$data = $model->dataset;
			
			$isOwner = $data->user_id == currentUser()->id;
			
			$this->render('word', compact('model', 'isOwner', 'id', 'data'));
		}
	}
	
	public function actionRemoveImage() {
		$id = Yii::app()->request->getParam('id');
		
		$model = GNWord::model()->find('id=:id', array(
				':id'	=> IDHelper::uuidToBinary($id)
		));
		
		$image = $model->image;
		if (!empty($image)) {
			$webroot = Yii::getPathOfAlias("jlwebroot");
			
			@unlink("{$webroot}/upload/english/{$image}");
			$model->image = "";
			
			$model->save();
		}
		
		$this->redirect('/vocabulary/edit/word?id=' . $id);
	}
}