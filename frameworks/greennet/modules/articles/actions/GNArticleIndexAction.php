<?php
/**
 * This action is used to display profile home of user
 */
//Yii::import('');
class GNArticleIndexAction extends GNAction {
	public $viewFile = 'greennet.modules.articles.views.list';
	
	private $_uploadPath;
	public $indexUri;
	public $createUri;
	public $editUri;
	public $viewUri;
	public $deleteUri;
	public $bulkDeleteUrl;
	
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
	
	public function run()
	{
		$controller = $this->controller;
		
		$model = $this->_model;
// 		// mass action
// 		if (isset($_POST['selectedItems']) && is_array($_POST['selectedItems'])) {
// 			$mass_action = $_POST['DNTArticle']['mass_action'];
// 			// delete all
// 			if ($mass_action == 'delete') {
// 				$controller->__massDelete($_POST['selectedItems']);
// 			// highlight all
// 			} elseif ($mass_action == 'highlight') {
// 				$controller->__massHighlight($_POST['selectedItems']);
// 			}
// 		}
// 		// unmass hightlight
// 		if (isset($_POST['selectedMassHighlight']) && is_array($_POST['selectedMassHighlight'])) {
// 			$mass_action = $_POST['DNTArticle']['mass_action'];
// 			// un mass highlight all
// 			if ($mass_action == 'unhighlight') 
// 				$controller->__unmassHighlight($_POST['selectedMassHighlight']);
// 		}
		
// 		$model=new DNTArticle('search');
// 		$model->unsetAttributes();  // clear any default values
		$modelName = $this->_model->name;
		
		if (!isset($_GET[$modelName]))
			$_GET[$modelName] = array();
		
		if(isset($modelName))
			$model->setAttributes($_GET[$modelName], false);

		$criteria=new CDbCriteria;
		
		$controller->render($this->viewFile, array(
			'model'		=> $this->_model

		));
	}
}