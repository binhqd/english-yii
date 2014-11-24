<?php
/**
 * This action is used to display profile home of user
 */
//Yii::import('');
class GNCreateArticleAction extends GNAction {
	public $viewFile = 'greennet.modules.articles.views.create';
	public $successUrl = '';
	public $errorUrl = '';
	
	private $_uploadPath;
	public $indexUri;
	public $createUri;
	public $editUri;
	public $viewUri;
	public $deleteUri;
	
	/**
	 * This method is used to set upload path
	 * 
	 * @param string $uploadPath
	 */
	public function setUploadPath($uploadPath) {
		$this->_uploadPath	= $uploadPath;
	}
	
	public function run()
	{
		$controller = $this->controller;
		$model = $this->_model;
		$modelName = $this->_model->name;
		
		if(isset($_POST[$modelName]))
		{
			unset($_POST[$modelName]['image']);
			unset($_POST[$modelName]['created']);
			unset($_POST[$modelName]['alias']);
			
			$model->setAttributes($_POST[$modelName], false);

			$model->created = date("Y-m-d H:i:s");
			
			// Validate
			$validateModel = false;
			try {
				$validate = $model->validate();
			} catch (Exception $ex) {
				$strMessage = $ex->getMessage();
				
				if ($controller->isJsonRequest) {
					ajaxOut(array(
						'error'		=> true,
						'type'		=> 'error',
						'message'	=> $strMessage,
						'url'		=> GNRouter::createUrl($this->errorUrl),
					));
				} else {
					Yii::app()->jlbd->dialog->notify(array(
						'error'		=> true,
						'type'		=> 'error',
						'autoHide'	=> true,
						'message'	=> $strMessage,
					));
					$controller->redirect($this->errorUrl);
				}
			}
			
			if ($validate) {
				try {
					$strContent = $model->content;
					$description = GNStringHelper::htmlPurify($strContent);
					
					$model->description = $description;
					
					if($model->save()) {
						/* Upload image */
						$config = array(
							'class'	=> 'greennet.extensions.GNUploader.components.GNSingleUploadComponent',
							'uploadPath'	=> $this->_uploadPath
						);
						
						$uploader = Yii::createComponent($config);
						$image = $uploader->upload($model,'image');
						
						if (!empty($image)) {
							$model->image = $image['filename'];
							$model->save();
						}
						
						// Save associated data
						$belongsTo = $model->belongsTo;
						foreach ($belongsTo as $name => $instance) {
							$className = get_class($instance);
							
							if (isset($_POST[$className])) {
								$holderIDs = $_POST[$className];
								if (is_array($holderIDs)) {
									foreach ($holderIDs as $value) {
										$obj = new $className;
										$obj->article_id = $model->id;
										$obj->holder_id = IDHelper::uuidToBinary($value);
										
										$obj->save();
									}
								}
							}
						}
						
						$strMessage = Yii::t("greennet", "Article has been created successful");
						
						if ($controller->isJsonRequest) {
							ajaxOut(array(
								'error'		=> false,
								'type'			=> 'success',
								'message'		=> $strMessage,
								'urlRedirect'	=> GNRouter::createUrl($this->successUrl),
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
						
					}
				} catch (Exception $ex) {
					$strMessage = $ex->getMessage();
					
					if ($controller->isJsonRequest) {
						ajaxOut(array(
							'error'			=> true,
							'type'			=> 'error',
							'message'		=> $strMessage,
							'url'			=> GNRouter::createUrl($this->errorUrl),
						));
					} else {
						Yii::app()->jlbd->dialog->notify(array(
							'error'		=> true,
							'type'		=> 'error',
							'autoHide'	=> false,
							'message'	=> $strMessage,
						));
						$controller->redirect($this->errorUrl);
					}
				}
			}
		}
		
		$controller->render($this->viewFile, array(
			'model'	=> $this->_model,
		));
	}
}