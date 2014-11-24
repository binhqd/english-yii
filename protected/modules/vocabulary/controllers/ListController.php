<?php
/**
 * ListController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Aug 27, 2013 2:09:19 PM
 */
//Yii::import('import something here');
class ListController extends GNController {
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
	
	public function actionIndex() {
		$id = Yii::app()->request->getParam('id');
		
		$criteria = new CDbCriteria();
		$criteria->condition = "user_data_id=:id";
		$criteria->order = "RAND()";
// 		$criteria->select = '';
		$criteria->params = array(':id' => IDHelper::uuidToBinary($id));
		
		$words = GNWord::model()->findAll($criteria);
		
		$data = UserData::model()->find('id=:id', array(
			':id'	=> IDHelper::uuidToBinary($id)
		));
		
		$isOwner = $data->user_id == currentUser()->id;
		
		$model = new GNWord();
		$this->render('list', compact('words', 'id', 'data', 'isOwner', 'model'));
	}
}