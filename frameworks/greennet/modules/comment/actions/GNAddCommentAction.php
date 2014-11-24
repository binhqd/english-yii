<?php
class GNAddCommentAction extends GNAction {
	
	/**
	 * This property is used to set rating for comment
	 */
	public $suffix	= 'like this';
	
	/**
	 * This property is used to set rating for comment
	 */
	public $rating	= true;
	
	/**
	 * This property is used to set action rating for comment
	 */
	public $actionRate;
	
	/**
	 * This property is used to set rating for comment
	 */
	public $ratingType	= 'like';
	/**
	 * This property is used to preload comment.
	 */
	
	public function run () {
		$model	= $this->model;
		$result	= array();
		
		
		if (!empty($_POST)) {
			
			$content		= $_POST['content'];
			$content		= nl2br($content);
			// save to database
			$model->content		= $content;
			$model->object_id	= IDHelper::uuidToBinary($_POST['objectId']);
			$model->date		= date('Y-m-d H:i:s');
			$model->user_id		= currentUser()->id;
			
			$model->save();
			
			//$cntComments	= count($model::model()->findAllByAttributes(array('object_id'=>IDHelper::uuidToBinary($_POST['objectId']))));
			$cntComments = $model->countComments($_POST['objectId']);
			
			$hexUserID = IDHelper::uuidFromBinary(currentUser()->id, true);
			
			$result = array(
				'error'			=> false,
				'type'			=> 'success',
				'totalComment'	=> $cntComments,
				'id'			=> IDHelper::uuidFromBinary($model->id, true),
				'content'		=> $model->toArray()
			);
			
		} else {
			$cntComments	= $model::model()->count();
			$result = array(
				'error'		=> true,
				'type'		=> 'error',
				'message'	=> Yii::t("greennet", "Error while saving comment. Please contact administrator for more information"),
				'total'		=> $cntComments
			);
		}
		ajaxOut($result);
	}
}