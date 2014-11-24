<?php
class DashboardController extends JLController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';
	private $outputDir = "";
	public $defaultAction = "index";
	//List data type of jl attributes

	public function allowedActions() {
		return '*';
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionIndex()
	{
		$this->render('index',array(
			//'model'	=> $model
		));
	}
}