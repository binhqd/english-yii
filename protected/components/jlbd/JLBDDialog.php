<?php
/**
 * Widget is used to register library JLBD dialog
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 2013-02-01 4:15 PM
 */
class JLBDDialog extends CApplicationComponent
{
	/**
	 * This method is used to register script, css
	 */
	public function register()
	{
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));

		GNAssetHelper::cssFile('jlbd.dialog');
		GNAssetHelper::cssFile('jlbd.notify');
		GNAssetHelper::scriptFile('jlbd.dialog', CClientScript::POS_END);
		GNAssetHelper::scriptFile('jlbd.notify', CClientScript::POS_END);
	}

	public function alert($title = 'null', $message = 'null', $callback = 'null', $options = '{}')
	{
		$title = addslashes($title);
		$message = addslashes($message);
		Yii::app()->session['jlbd_command'] = "jlbd.dialog.alert('$title', '$message', $callback, $options);";
	}

	public function confirm($title = 'null', $message = 'null', $callback = 'null', $options = '{}')
	{
		$title = addslashes($title);
		$message = addslashes($message);
		Yii::app()->session['jlbd_command'] = "jlbd.dialog.confirm('$title', '$message', $callback, $options);";
	}

	public function prompt($title = 'null', $message = 'null', $defaultVal = 'null', $callback = 'null', $options = '{}', $loadFunction = 'null')
	{
		$title = addslashes($title);
		$message = addslashes($message);
		Yii::app()->session['jlbd_command'] = "jlbd.dialog.prompt('$title', '$message', '$defaultVal', $callback, $options, $loadFunction);";
	}

	public function notify($options = array())
	{
		Yii::app()->session['jlbd_command'] = "jlbd.dialog.notify(".CJSON::encode($options).");";
	}
}