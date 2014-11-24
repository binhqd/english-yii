<?php
class GNScriptPacker extends CWidget {
	public $id;
	public $type;
	public $position = CClientScript::POS_READY;
	public function init() {
		parent::init();
		ob_start();
	}
	
	public function run() {
		$script = ob_get_clean();
		if ($this->type == "js") {
			Yii::app()->clientScript->registerScript($this->id, $script, $this->position);
		} else {
			Yii::app()->clientScript->registerCss($this->id, $script);
		}
		parent::run();
	}
}
