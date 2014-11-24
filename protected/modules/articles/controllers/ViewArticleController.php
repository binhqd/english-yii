<?php
/**
 * ViewArticleController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created May 28, 2013 9:35:43 AM
 */
//Yii::import('import something here');
class ViewArticleController extends ZoneController {
	public $layout = "//layouts/master/myzone";
	/**
	 * This method is used to allow action
	 * @return string
	 */
	public function allowedActions()
	{
		return '*';
	}
	public $pathIndex	= 'application.modules.articles.views.viewArticle.index';
	
	public function filters()
	{
		return array(
			array(
				// Validate code if code is invalid or expired
				'greennet.modules.users.filters.ValidUserFilter + addComment, like, unlike',
				'out'	=> array(
					"files" => array(
						array(
							"error"			=> true,
							"message"		=> "You need to login before continue"
						)
					)
				)
			)
		);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CController::actions()
	 */
	public function actions(){
		return array(
			'index'	=> array(
				'class'	=> 'application.modules.articles.actions.ViewArticleAction',
				'pathIndex'	=> $this->pathIndex
			),
			'moreComments'	=> array(
				'class'	=> 'application.modules.comments.actions.ZoneListCommentsAction'
			)
		);
	}
	
	public function actionLike() {
		if (!empty($_POST['object_id'])) {
			Yii::import('application.modules.like.models.ZoneLike');
			
			$binObjectID = IDHelper::uuidToBinary($_POST['object_id']);
			$out = ZoneLike::model()->like($binObjectID);
			
			$articleID = array(
				'article_id' => $_POST['object_id']
			);
			
			$out = array_merge($out, $articleID);
			ajaxOut($out, false);
			
			Yii::import('application.modules.activities.models.*');
			Yii::import('application.components.notification.JLNotificationWriter');
			Yii::import('application.components.notification.ZoneStickerNotificationDocument');
			
			$currentUser = currentUser();
			
			$article = ZoneArticle::get($_POST['object_id'], array(
				'loadPoster'	=> true,
				'loadLikes'		=> false, 
				'loadComments'	=> 0
			));
			
			// make sure the current action is like (so the status will be Unlike)
			if ($out['value'] == 'Unlike') {

				/*
				 * Top notification:
				* - Notify to receiver
				*/
				$data = array(
					'notifier_id'	=> currentUser()->hexID,
					'receive_id'	=> $article['author']['id'],
					'object_id'		=> $_POST['object_id'],
					'created'		=> $article['created'],
					'type'			=> 'likeArticle',
				);
				Yii::import('application.components.notification.JLNotificationWriter');
				JLNotificationWriter::send(
					$article['author']['id'],
					"application.components.notification.renderer.ZoneLikeNotification",
					$data
				);

				/*
				 * Sidebar notification:
				* - Notify to friends
				*/
				$friendIDs = ZoneListHelper::getFriends($currentUser->hexID);
				foreach ($friendIDs as $friendID) {
					$data = array(
						'namespace'		=> 'zone-sticker',
						'data'			=> array(
							'object_type'	=> 'Article',
							'type'		=> 'like',
							'user'		=> ZoneUser::model()->get($currentUser->hexID),
							'article'	=> $article,
							'object'	=> null //
						),
						'userID'		=> $friendID
					);
						
					JLNotificationWriter::push($data);
					JLNotificationWriter::savePushData($friendID, $data);
				}
			}
		}
	
		//ZoneResourceImage::model()->deleteCache($_POST['object_id']);
		// 		if($_POST['node_id'] !=0 && $like != false ){
		// 			$node_id		= IDHelper::uuidToBinary($_POST['node_id']);
		// 			Yii::import('application.modules.followings.models.ZoneFollowing');
		// 			$followers = ZoneFollowing::model()->followers($node_id);
		// 			foreach($followers as $key=>$follower){
		// 				if($follower['user_id'] != currentUser()->hexID){
		// 					$image = ZoneResourceImage::model()->findByPk($binObjectId);
		// 					if(!empty($image)){
		// 						ZoneImageActivity::model()->saveActivity(IDHelper::uuidToBinary($follower['user_id']), currentUser()->id, $binObjectId, ZoneActivity::TYPE_LIKE);
		// 					}
	
		// 				}
		// 			}
		// 		}
	}
	
	public function actionUnlike() {
		if (!empty($_POST)) {
			Yii::import('application.modules.like.models.ZoneLike');
			
			$binObjectID = IDHelper::uuidToBinary($_POST['object_id']);
			$out = ZoneLike::model()->like($binObjectID);
			$articleID = array(
				'article_id' => $_POST['object_id']
			);
			
			$out = array_merge($out, $articleID);
			ajaxOut($out);
		}
		//ZoneResourceImage::model()->deleteCache($_POST['object_id']);
	}
	
	/**
	 * This action is used to add new comment for an article
	 */
	public function actionAddComment(){
		$model	= new ZoneComment;
	
		if (!empty($_POST) && !empty($_POST['content'])) {
			$binObjectID = IDHelper::uuidToBinary($_POST['objectId']);
				
			try {
				// Save comment
				$model = $model->createComment($_POST['content'], $binObjectID);
			} catch (Exception $ex) {
				$out = array(
					'error'		=> true,
					'type'		=> 'error',
					'message'	=> $ex->getMessage()
				);
				ajaxOut($out);
			}
				
			// return data
			$cntComments	= ZoneComment::model()->countComments(IDHelper::uuidFromBinary($model->object_id));
			
			$token = !empty($_POST['token']) ? $_POST['token'] : '';
			
			$arrComment = $model->toArray(true);
			$result = array(
				'error'		=> false,
				'type'		=> 'success',
				'message'	=> "Comment has been saved successfuly!",
				'total'		=> $cntComments,
				'id'		=> IDHelper::uuidFromBinary($model->id, true),
				'token'		=> $token,
				'content'	=> $arrComment
			);
			
			ajaxOut($result, false);

			/*
			 * Top notification:
			* - Notify to receiver
			*/
			$article = ZoneArticle::get($_POST['objectId'], array(
				'loadPoster'	=> true,
				'loadLikes'		=> false, 
				'loadComments'	=> 0
			));
			$user_id = $article['author']['id'];
			$data = array(
				'notifier_id'	=> currentUser()->hexID,
				'receive_id'	=> $user_id,
				'content'		=> $model->content,
				'object_id'		=> IDHelper::uuidFromBinary($model->object_id, true),
				'comment_id'	=> IDHelper::uuidFromBinary($model->id, true),
				'type'			=> 'commentArticle'
			);
			Yii::import('application.components.notification.JLNotificationWriter');
			JLNotificationWriter::send(
				$user_id,
				"application.components.notification.renderer.ZoneCommentNotification",
				$data
			);
			
// 			// Send notification
// 			Yii::import('application.components.notification.JLNotificationWriter');
// 			$currentUser = currentUser();
// 			$currentUser->attachBehavior('UserFriend', 'application.modules.friends.components.behaviors.GNUserFriendBehavior'); // Attach behavior friend for user
// 			$friends = $currentUser->friends('', '', 1000, 0);
// 			$currentUser->detachBehavior('UserFriend');
	
// 			if(!empty($friends)) {
// 				foreach($friends as $key=>$friend){
// 					$user_id = $friend['user_id'];
					
// 					$article = ZoneArticle::model()->get(IDHelper::uuidFromBinary($model->object_id, true));
// 					$data = array(
// 						'notifier_id'	=> currentUser()->hexID,
// 						'receive_id'	=> $user_id,
// 						'content'		=> $model->content,
						
// 						// author cua node
// 						//'author_id'		=> !empty($status->namespace) ? IDHelper::uuidFromBinary($status->namespace->holder_id,true) : null,
						
// 						'object_id'		=> $article['object_id'],
// 						'comment_id'	=> $arrComment['id'],
// 						'type'			=> commentArticle
// 					);
// 					JLNotificationWriter::send(
// 						$user_id,
// 						"application.components.notification.renderer.ZoneCommentNotification",
// 						$data
// 					);
// 				}
// 			}
		} else {
			$cntComments	= $model::model()->count();
			$result = array(
				'error'		=> true,
				'type'		=> 'error',
				'message'	=> "Error while saving comment. Please contact administrator for more information",
				'total'		=> $cntComments,
				'token'		=> $token,
				
			);
			ajaxOut($result);
		}
	
	}
	
	/**
	 * This method is used to hide article
	 * @author: Chu Tieu
	 * @param $uid
	 */
	public function actionHideArticle($uid=null) {
		if (!empty($uid)) {
	
			$binID = IDHelper::uuidToBinary($uid);
			$model = ZoneArticle::model()->hideArticle($binID);
			if ($model) {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => false,
						'message' => 'This object has been deleted.'
					));
				}
			} else {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => true,
						'message' => 'This object has not been deleted.'
					));
				}
			}
		}
	}
	
/**
	 * This method is used to restore article
	 * @author: Chu Tieu
	 * @param $uid
	 */
	public function actionRestoreArticle($uid=null) {
		
		if (!empty($uid)) {
	
			$binID = IDHelper::uuidToBinary($uid);
			$model = ZoneArticle::model()->restoreArticle($binID);
			if ($model) {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => false,
						'message' => 'This object has been restored.'
					));
				}
			} else {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => true,
						'message' => 'This object has not been restored.'
					));
				}
			}
		}
	}
}