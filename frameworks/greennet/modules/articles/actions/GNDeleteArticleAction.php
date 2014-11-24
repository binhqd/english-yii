<?php
/**
 * This action is used to display profile home of user
 */
//Yii::import('');
class GNDeleteArticleAction extends GNAction {
	public $successUrl = '';
	public $errorUrl = '';
	
	private $_uploadPath;
	public $indexUri;
	public $createUri;
	public $editUri;
	public $viewUri;
	public $deleteUri;
	
	/**
	 * This method is used to set model to current action
	 *
	 * @param unknown $model
	 */
	public function setModel($model){
		// import model
		if (is_string($model)) {
			$config = array(
				'class'	=> $model
			);
			$this->_model = Yii::createComponent($config);
		} else if (is_array($model)) {
			$this->_model = Yii::createComponent($model);
		} else {
			$this->_model = $model;
		}
	}
	
	/**
	 * Method to get current model
	 */
	public function getModel() {
		return $this->_model;
	}
	
	/**
	 * This method is used to set upload path
	 * 
	 * @param string $uploadPath
	 */
	public function setUploadPath($uploadPath) {
		$this->_uploadPath	= $uploadPath;
	}
	public function getUploadPath() {
		return $this->_uploadPath;
	}
	
	public function run()
	{
		$controller = $this->controller;
		
		if (!isset($_GET['a_id']) || empty($_GET['a_id'])) {
			$strMessage = "Invalid ID";
							
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> true,
					'type'		=> 'error',
					'message'	=> $strMessage,
					'url'		=> GNRouter::createUrl($this->editUrl, array('a_id' => $_GET['a_id'])),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=> 'error',
					'autoHide'	=> true,
					'message'	=> $strMessage,
				));
				$controller->redirect(GNRouter::createUrl($this->editUrl, array('a_id' => $_GET['a_id'])));
			}
		}
		
		$id = IDHelper::uuidToBinary($_GET['a_id']);
		$model = $this->_model->findByPk($id);
		
		$modelName = $this->_model->name;
		
		try {
			/* Remove image */
			if (!empty($model->image)) {
				$config = array(
					'class'	=> 'greennet.extensions.GNUploader.components.GNSingleUploadComponent',
					'uploadPath'	=> $this->_uploadPath
				);
				$uploader = Yii::createComponent($config);
				$uploader->remove($model->image);
			}
			
			if (empty($model)) {
				$strMessage = Yii::t("greennet", "The system can't perform your request because of invalid ID");
				$url = $this->errorUrl;
				
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'type'		=> 'error',
						'message'	=> $strMessage,
						'url'		=> GNRouter::createUrl($url),
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type'		=> 'error',
						'autoHide'	=> true,
						'message'	=> $strMessage,
					));
					$controller->redirect($url);
				}
			}
			$model->delete();
			
			$strMessage = Yii::t("greennet", "Article has been deleted successful");
			
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> false,
					'type'		=> 'success',
					'message'	=> $strMessage,
					'url'		=> GNRouter::createUrl($this->successUrl),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> false,
					'type'		=> 'success',
					'autoHide'	=> true,
					'message'	=> $strMessage,
				));
				$controller->redirect($this->successUrl);
			}
		} catch (Exception $ex) {
			
			$strMessage = $ex->getMessage();
			$url = $this->errorUrl;
			
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> true,
					'type'		=> 'error',
					'message'	=> $strMessage,
					'url'		=> GNRouter::createUrl($url),
				));
			} else {
				Yii::app()->jlbd->dialog->notify(array(
					'error'		=> true,
					'type'		=> 'error',
					'autoHide'	=> true,
					'message'	=> $strMessage,
				));
				$controller->redirect($url);
			}
		}
	}
}