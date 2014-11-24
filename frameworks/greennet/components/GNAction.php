<?php
class GNAction extends CAction {
	protected $_model;
	// 	public function create
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


}