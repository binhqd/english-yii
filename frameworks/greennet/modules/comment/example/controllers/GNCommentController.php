<?php
/**
 * GNCommentController
 *
 * @author BinhQD
 * @version 1.0
 * @created Apr 15, 2013 2:46:46 PM
 */
// Yii::import('')
class GNCommentController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}
	
	public function filters()
	{
		return array(
			array(
				// Validate code if code is invalid or expired
				'greennet.modules.users.filters.ValidUserFilter + addComment',
				'out'	=> array("files" =>
					array(
						array(
							"error"			=> true,
							"message"		=> Yii::t("greennet", "You need to login before continue")
						)
					)
				)
			)
		);
	}
	
	public function actions(){
		return array(
			'addComment'	=>  array (
				'class'	=> 'greennet.modules.comment.actions.GNAddCommentAction',
				'model'	=> array(
					'class'	=> 'greennet.modules.comment.models.GNComment',
					'belongsTo'	=> array(
// 						'commentor'	=> array(
// 							'class'	=> 'greennet.modules.comment.models.GNCommentor'
// 						)
					)
				),
			),
			'deleteComment'	=> array (
				'class'		=> 'greennet.modules.comment.actions.GNDeleteCommentAction',
				'model'		=> array(
					'class'	=> 'greennet.modules.comment.models.GNComment',
					'belongsTo'	=> array(
// 						'commentor'	=> array(
// 							'class'	=> 'greennet.modules.comment.models.GNCommentor'
// 						)
					)
				)
			),
			'listComments'	=> array (
				'class'		=> 'greennet.modules.comment.actions.GNListCommentAction',
				'model'		=> array(
					'class'	=> 'greennet.modules.comment.models.GNComment',
					'belongsTo'	=> array(
// 						'commentor'	=> array(
// 						'class'	=> 'greennet.modules.comment.models.GNCommentor'
// 						)
					)
				),
			),
		);
	}
}