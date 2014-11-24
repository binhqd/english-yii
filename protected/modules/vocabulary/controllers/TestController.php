<?php
/**
 * ListController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Aug 27, 2013 2:09:19 PM
 */
//Yii::import('import something here');
class TestController extends GNController {
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
		
		$arr = array();
		foreach ($words as $item) {
			$arr[] = array(
				'en'		=> trim($item->word),
				'vi'		=> $item->vietnamese,
				'read'		=> $item->phonetic,
				'sentence'	=> "{$item->meaning}<br/>Eg: <i>{$item->example}</i>",
				'image'		=> $item->image
			);
		}
		
		$data = UserData::model()->find('id=:id', array(
			':id'	=> IDHelper::uuidToBinary($id)
		));
		
		$this->render('test', compact('words', 'id', 'data', 'arr'));
	}
}