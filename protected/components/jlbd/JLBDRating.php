<?php
/**
 * Widget is used to register library JLBD
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 2013-02-01 4:15 PM
 */
class JLBDRating extends CWidget
{
	/**
	 * This method is used to initial widget
	 */
	public function init()
	{
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));

		GNAssetHelper::setBase('application.components.jlbd.assets');
		GNAssetHelper::cssFile('jlbd.rating', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('jlbd.rating', CClientScript::POS_END);
	}

	/**
	 * This method is used to run widget
	 */
	public function run()
	{
		$this->render('ratingTemplate');
	}
}