<?php
class GNGalleryWidget extends CWidget {
	public $ref;
	public $_model;
	public $uri;
	public $assetPath = 'greennet.modules.gallery.assets';
	public $assetUrl;
	public $deleteUrl;
	public $width;
	public $height;
	public $showDeleteButton = false;
	/**
	 * This method is used to set model to current action
	 *
	 * @param unknown $model
	 */
	public function setModel($model){
		// import model
		if (is_string($model)) {
			Yii::import($model);
			$config = array(
				'class'	=> $model
			);
			$this->_model = Yii::createComponent($config);
		} else {
			$this->_model = $model;
		}
	}
	public function init() {
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));
		$this->assetUrl = GNAssetHelper::setBase($this->assetPath);
	}
	public function run() {
		if ($this->ref != "") {
			$items = $this->_model->findAllByAttributes(array(
				'object_id'	=> $this->ref
			));
		} else {
			$items = array();
		}
		$this->render('gallery', array(
			'items'		=> $items,
			'width'		=> $this->width,
			'height'	=> $this->height
		));
	}
}