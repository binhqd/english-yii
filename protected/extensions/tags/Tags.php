<?php

class Tags{
	public $updateHtml = array();
	public $name	=	"";
	public $id		=	"";
	public $text	=	"";
	public $value	=	"";
	public function init(){
		
		$headScript = '
			$(document).ready(function(e) {
				$("#jl'.$this->id.'").tagsInput({width:"auto",defaultText:"'.$this->text.'"});
			});
		';
		Yii::app()->getClientScript()->registerScript('registerGlobalVariables', $headScript, CClientScript::POS_END);
		
		$cs=Yii::app()->getClientScript();
		$assets = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, true);
		$cs->registerScriptFile($assets . '/js/jquery.tagsinput.js');
		$cs->registerCssFile($assets . '/css/jquery.tagsinput.css');
		
		
	}
	public function run(){
		echo '<input id="jl'.$this->id.'" name="'.$this->name.'" type="text" class="" value="'.$this->value.'"/>';
	}
	

}
