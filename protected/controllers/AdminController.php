<?php
Yii::import('application.components.widgets.feedback.models.*');
class AdminController extends JLController
{
	public $layout = '//layouts/admin/default';

	public function allowedActions() {
		return '*';
	}
	
	/**
	 * @return array action filters
	 */
// 	public function filters()
// 	{
// 		return array(
// 			'rights',
// 		);
// 	}
	
// 	public function allowedActions()
// 	{
// 		return '';
// 	}
	
// 	/**
// 	 * Specifies the access control rules.
// 	 * This method is used by the 'accessControl' filter.
// 	 * @return array access control rules
// 	 */
// 	public function accessRules()
// 	{
// 		return array();
// 	}
	
	
	// Dashboard
	public function actionIndex() {
		$this->render('index');
	}
	
	// load videos
	public function actionVideos($dataStatus=null) {
		$videos = ZoneResourceVideo::model()->getAllVideos($dataStatus);
		$this->render('videos', compact('videos', 'dataStatus'));
	}
	
	// load album
	public function actionAlbum($dataStatus=null) {
		$album = ZoneResourceAlbum::model()->getAllAlbum($dataStatus);
		$this->render('album', compact('album', 'dataStatus'));
	}
	
	// load photos
	public function actionPhotos($dataStatus=null) {
		$photos = ZoneResourceImage::model()->getAllPhotos($dataStatus);
		$this->render('photos', compact('photos','dataStatus'));
	}
	
	// load article
	public function actionArticles($dataStatus=null) {
		$articles = ZoneArticle::model()->getAllArticle($dataStatus);
		$this->render('articles', compact('articles','dataStatus'));
	}
	
	// load feedback
	public function actionFeedback($statusView=null){
		$feedbacks = ZoneFeedback::model()->getAllFeedback($statusView);
		$this->render('feedback', compact('feedbacks','statusView'));
	}
}