<?php

/**
 * This controller is uses to manage Concern
 *
 * @author	huytbt
 * @date	2012-08-27 10:13 AM
 * @version	1.0
 */
class ConcernController extends JLController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/admin';
	public $defaultAction = "report";
	//List data type of jl attributes

	public function allowedActions() {
		return '*';
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionReport()
	{
		Yii::import('application.models.JLConcern');
		$model = JLConcern::model()->getReports();
		
		$this->render('report',array(
			'model' => $model
		));
	}

	/**
	 * This action is used to check a report concern
	 */
	public function actionCheck($report_id)
	{
		$binReportID = IDHelper::uuidToBinary($report_id);
		Yii::import('application.models.JLConcern');
		$model = JLConcern::model()->findByPk($binReportID);
		
		if (empty($model)) throw new Exception('Your request is invalid.');
		
		if ($model->checked != JLConcern::CHECKED) {
			$model->checked = JLConcern::CHECKED;
			$model->save();
		}
		
		// Redirect for check content report
		if ($model->object_type == JLConcern::TYPE_REVIEW) {
			Yii::import('application.modules.reviews.models.JLReview');
			$review = JLReview::model()->findByPk($model->object_id);
			if (empty($review)) throw new Exception('Review does not exists.');
			Yii::import('application.modules.businesses.models.JLBusiness');
			$bizInfo = JLBusiness::model()->getInfo(str_replace('-', '', IDHelper::uuidFromBinary($review->business_id)));
			if (empty($bizInfo)) throw new Exception('Business does not exists.');
			$strReviewID = IDHelper::uuidFromBinary($review->id);
			$this->redirect('/business/'.$bizInfo['alias'].'?rid='.$strReviewID.'#/section/?highlightreview='.$strReviewID);
		} elseif ($model->object_type == JLConcern::TYPE_BUSINESS) {
			Yii::import('application.modules.businesses.models.JLBusiness');
			$bizInfo = JLBusiness::model()->getInfo(str_replace('-', '', IDHelper::uuidFromBinary($model->object_id)));
			if (empty($bizInfo)) throw new Exception('Business does not exists.');
			$this->redirect('/business/'.$bizInfo['alias']);
		} else {
		}
	}

	/**
	 * This action is used to delete a report concern
	 */
	public function actionDelete($report_id)
	{
		$binReportID = IDHelper::uuidToBinary($report_id);
		Yii::import('application.models.JLConcern');
		$model = JLConcern::model()->findByPk($binReportID);
		if (empty($model)) throw new Exception('Your request is invalid.');
		if (!$model->delete())
			throw new Exception('Cannot delete this report concern.');
		$this->redirect('/admin_manage/concern');
	}

	/**
	 * This method is used to show information in grid view
	 */
	public function showInfo($data, $row)
	{
		$msg = '';
		$info = CJSON::decode($data->info);
		if ($info['isGuest']) {
			if (isset($info['info']['email']) && !empty($info['info']['email']))
				$msg .= '<a target="_blank" href="mailto:'.$info['info']['email'].'">';
			$msg .= 'Guest';
			if (isset($info['info']['name']) && !empty($info['info']['name']))
				$msg .= ' ('.CHtml::encode($info['info']['name']).')';
			if (isset($info['info']['name']) && !empty($info['info']['name']))
				$msg .= '</a>';
		} else {
			$user = JLUser::model()->getUserInfo(IDHelper::uuidToBinary($info['user_id']));
			if ($user)
				$msg .= '<a target="_blank" href="'.JLRouter::createUrl('/dashboard?u='.$info['user_id']).'">'.CHtml::encode($user->displayname).'</a>';
			else
				$msg .= '[User deleted]';
		}
		return $msg;
	}
}