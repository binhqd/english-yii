<?php
/*
 *
 */
class GNWyswyg extends CInputWidget
{
	public $options = array(
		'width' => 'auto',
	);

	public function run()
	{
		list($name,$id)=$this->resolveNameID();

		if(isset($this->htmlOptions['id']))
			$id=$this->htmlOptions['id'];
		else
			$this->htmlOptions['id']=$id;

		if(isset($this->htmlOptions['name']))
			$name=$this->htmlOptions['name'];
		else
			$this->htmlOptions['name']=$name;

		if($this->hasModel())
			$this->widget('application.extensions.cleditor.ECLEditor', array(
				'model'=>$this->model,
				'attribute'=>$this->attribute, //Model attribute name. Nome do atributo do modelo.
				'options'=>$this->options,
				'htmlOptions'=>$this->htmlOptions,
			));
		else
			$this->widget('application.extensions.cleditor.ECLEditor', array(
				'name'=>$name,
				'value'=>$this->value,
				'options'=>$this->options,
				'htmlOptions'=>$this->htmlOptions,
			));
		
	}
}