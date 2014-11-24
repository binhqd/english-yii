<?php
class CareerController extends JLController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	// public $layout='//layouts/admin';
	private $outputDir = "";
	public $defaultAction = "index";
	//List data type of jl attributes

	public function allowedActions() {
		return '';
	}
	
	public function init() {
		parent::init();
		Yii::import('application.modules.feedback.models.JLFeedback');
		$this->outputDir = Yii::getPathOfAlias('feedback.data');
		//Yii::import('feedback.models');
	}
	
	public function actionIndex()
	{
		$datas = Recruitment::model()->getCareer();
		$models = $datas['data'];
		$pages = $datas['pages'];
		$this->render('index', array(
			'models' => $models,
			'pages'	=> $pages
		));
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id);
		$files = CVFile::model()->findAllByAttributes(array('recruitment_id'=>$model->id));
		$this->render('view', array(
			'model'	=> $model,
			'files'	=> $files
		));
	}
	
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		$this->redirect('/admin_manage/career');
	}
	
	public function loadModel($id)
	{
		$model=Recruitment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}