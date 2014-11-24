<?php
/**
 * ArticlesController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Apr 5, 2013 10:59:48 AM
 */
Yii::import('greennet.modules.articles.example.models.*');
class DefaultController extends GNController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	
	private $_common = array(
		'model'			=> array(
			'class'		=> 'ZoneArticle',
			'belongsTo'	=> array(
				'namespace'	=> array(
					'class'	=> 'ZoneArticleNamespace'
				),
				'author'	=> array(
					'class'	=> 'ZoneArticleAuthor'
				)
			)
		),
		'uploadPath'	=> 'upload/articles/',
		'indexUri'		=> '/articles/default/index',
		'createUri'		=> '/articles/default/create',
		'editUri'		=> '/articles/default/edit',
		'viewUri'		=> '/articles/default/view',
		'deleteUri'		=> '/articles/default/delete'
	);
	
	public function allowedActions()
	{
		return '*';
	}
	
	public function actions(){
		return array(
			// List articles
			'index'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNArticleIndexAction',
				'bulkDeleteUrl'	=> GNRouter::createUrl('/articles/default/bulk_delete'),
			), $this->_common),
				
			// Add new article
			// 'create'	=> CMap::mergeArray(array(
				// 'class'			=> 'greennet.modules.articles.actions.GNCreateArticleAction',
				// 'successUrl'	=> GNRouter::createUrl('/articles/default/index'),
				// 'errorUrl'		=> GNRouter::createUrl('/articles/default/create')
			// ), $this->_common),
				
			// Edit article
			'edit'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNEditArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/default/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/default/edit/'),
			), $this->_common),
			
			// Delete an article
			'delete'	=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNDeleteArticleAction',
				'successUrl'	=> GNRouter::createUrl('/articles/default/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/default/index')
			), $this->_common),
				
			// Bulk delete
			'bulk_delete'		=> CMap::mergeArray(array(
				'class'			=> 'greennet.modules.articles.actions.GNBulkDeleteArticlesAction',
				'successUrl'	=> GNRouter::createUrl('/articles/default/index'),
				'errorUrl'		=> GNRouter::createUrl('/articles/default/index')
			), $this->_common),
		);
	}
	public function actionCreate() {
		$model = new ZoneArticle();
		
		if(currentUser()->isGuest){
			jsonOut(array(
				'error'=>true,
				'message'=>'Please login'
			));
		}
		
		// FIXME: Will we need to set from form post
		$_POST['ZoneArticleAuthor']= array(currentUser()->hexID);

		if(isset($_POST['ZoneArticle'])) {
			$modelName = "ZoneArticle";
			unset($_POST[$modelName]['image']);
			unset($_POST[$modelName]['created']);
			unset($_POST[$modelName]['alias']);
			
			$model->setAttributes($_POST[$modelName], false);

			$model->created = date("Y-m-d H:i:s");
			
			$type = "articles";
			if(!empty($_POST['checkTitle'])) {
				$model->scenario = 'profile';
				$model = new ZoneStatus();
				$model->setAttributes($_POST[$modelName], false);
				$model->title = $model->content;
				$model->created = date("Y-m-d H:i:s");
				$type = "status";
			}else{
				
				if($model->type == ZoneArticle::TYPEIMAGE ){
					$model->scenario = 'post';
					
				}else{
					$model->scenario = 'normal';
				}
			}
			// dump(get_class($model));
			
			// Validate
			$validateModel = false;
			try {
				$validate = $model->validate();
			} catch (Exception $ex) {
				$strMessage = $ex->getMessage();
				
				if (Yii::app()->request->isAjaxRequest) {
					ajaxOut(array(
						'error'		=> true,
						'type'		=> 'error',
						'message'	=> $strMessage,
					));
				} else {
					// to do
				}
			}
			
			if ($validate) {
				try {
					$strContent = $model->content;
					$description = GNStringHelper::htmlPurify($strContent);
					
					if(get_class($model) != "ZoneStatus" ) $model->description = $description;
					
					if($model->save()) {
						/* Upload image */
						$config = array(
							'class'	=> 'greennet.extensions.GNUploader.components.GNSingleUploadComponent',
							'uploadPath'	=> $this->_common['uploadPath']
						);
						
						$uploader = Yii::createComponent($config);
						$image = $uploader->upload($model,'image');
						
						if (!empty($image)) {
							$model->image = $image['filename'];
							$model->save();
						}
						
						$binReceiverID = currentUser()->id;
						// Save associated data
						$belongsTo = $this->_common['model']['belongsTo'];
						
						$nodeIDs = array();
						foreach ($belongsTo as $name => $instance) {
						
							$className = $instance['class'];
							
							if (isset($_POST[$className])) {
								$holderIDs = $_POST[$className];
								if (is_array($holderIDs)) {
									foreach ($holderIDs as $value) {
										$obj = new $className;
										if($className == "ZoneArticleNamespace"){
											
											$binReceiverID = IDHelper::uuidToBinary($value);
											$nodeIDs[] = $binReceiverID;
										}
										$obj->article_id = $model->id;
										$obj->holder_id = IDHelper::uuidToBinary($value);
										
										$obj->save();
									}
								}
							}
						}
						
						$strMessage = "Article has been created successful";
						/**
							Assign images for articles
						**/
						$s3Files = array();
						if(!empty($_POST['images'])){
							$webroot = Yii::getPathOfAlias('jlwebroot');
							
							foreach($_POST['images'] as $key=>$image){
								$zoneResourceImage = ZoneResourceImage::model()->findByPk(IDHelper::uuidToBinary($image));
								if(!empty($zoneResourceImage)){
									$zoneResourceImage->object_id = IDHelper::uuidFromBinary($binReceiverID,true);
									$zoneResourceImage->album_id = $model->id;
									if($zoneResourceImage->save()){
										// Move to S3
										$filePath = "{$webroot}/upload/gallery/{$zoneResourceImage->image}";
										$s3Files[] = $filePath;
									} else {
										$errors  = $zoneResourceImage->getErrors();
										list ($field, $_errors) = each ($errors);
										if (Yii::app()->request->isAjaxRequest) {
											ajaxOut(array(
												'error'		=> true,
												'type'			=> 'error',
												'message'		=> $_errors[0],
												
											));
										}else throw new Exception($_errors[0]);
										
									}
								}
							}
						}
						
						$binUserID = currentUser()->id;
						$binObjectID = $model->id;
						
						$strArticleID = IDHelper::uuidFromBinary($model->id, true);
						// -----------------------
						$article = ZoneArticle::get($strArticleID, array(
							'loadPoster'	=> true,
							'loadLikes'		=> true, 
							'loadComments'	=> 5
						));
						
						$article['created'] = date(DATE_ISO8601,strtotime($article['created']));
						$user = ZoneUser::model()->get(currentUser()->hexID);
						if (Yii::app()->request->isAjaxRequest) {
							jsonOut(array(
								'error'			=> false,
								'binReciverID'	=> IDHelper::uuidFromBinary($binReceiverID, true),
								'binObjectID'	=> IDHelper::uuidFromBinary($binObjectID, true),
								'binUserID'		=> IDHelper::uuidFromBinary($binUserID, true),
								'type'			=> 'success',
								'message'		=> $strMessage,
								'type'=>($type == "status") ? 0 : 1,
								'article_id'	=> $strArticleID,
								'article'		=> $article,
								'author'		=> $user
							), false);
						} else {
							$out = array(
								"error"		=> true,
								"message"	=> "Invalid request"
							);
							ajaxOut($out);
						}
						
						// ====================================================================
						Yii::import('application.modules.activities.models.*');
						Yii::import('application.components.notification.JLNotificationWriter');
						Yii::import('application.components.notification.ZoneStickerNotificationDocument');
						
						$isUser = ZoneUser::model()->isUser($binReceiverID);
						$currentUser = currentUser();
						
						$strReceiverID = IDHelper::uuidFromBinary($binReceiverID, true);
						if ($isUser) {
							
// 							ZoneArticleActivity::model()->saveActivity(currentUser()->id, $binUserID, $binObjectID, ZoneActivity::TYPE_POST);
							
							// 1. If album is posted on own timeline
							if ($binReceiverID == currentUser()->id) {
								// TODO: Post on friends' timeline
								ZoneArticleActivity::model()->pushActivities($currentUser->hexID, $strArticleID, array(
									'owner'			=> true,
									'friends'		=> array($currentUser->hexID),
								), ZoneActivity::TYPE_POST);
								
								/**
								 * Sidebar notification:
								 * - Notify to friends
								 */
								$friendIDs = ZoneListHelper::getFriends($currentUser->hexID);
								foreach ($friendIDs as $friendID) {
									$data = array(
										'namespace'		=> 'zone-sticker',
										'data'			=> array(
											'object_type'	=> 'Article',
											'type'		=> 'self-posting',
											'user'		=> $user,
											'article'	=> $article,
											'object'	=> null //
										),
										'userID'		=> $friendID
									);
									JLNotificationWriter::push($data);
									JLNotificationWriter::savePushData($friendID, $data);
								}
							}
						
							// 2. If album is posted on another timeline
							else {
								/*
								 * Save Activities:
								* - Activity on receiver timeline
								* - Activity on receiver's friend's timeline
								*/
								ZoneArticleActivity::model()->pushActivities($currentUser->hexID, $strArticleID, array(
									'owner'			=> true,
									'particulars'	=> array($strReceiverID),
									'friends'		=> array($strReceiverID),
								), ZoneActivity::TYPE_POST);
								/*
								 * Top notification:
								* - Notify to receiver
								*/
								$data = array(
									'notifier_id'	=> $currentUser->hexID,
									'article_id'	=> $strArticleID,
									'type'			=> 'postArticle',
									'destination'	=> 'your_timeline',
								);
								
								JLNotificationWriter::send(
									$strReceiverID,
									"application.components.notification.renderer.ZoneArticleNotification",
									$data
								);
								/**
								 * Sidebar notification:
								 * - Notify to friends
								 */
								$friendIDs = ZoneListHelper::getFriends($currentUser->hexID);
								$receiver = ZoneUser::model()->get($strReceiverID);
								foreach ($friendIDs as $friendID) {
									$data = array(
										'namespace'		=> 'zone-sticker',
										'data'			=> array(
											'object_type'	=> 'Article',
											'type'		=> 'other-posting',
											'user'		=> $user,
											'article'	=> $article,
											'receiver'	=> $receiver,
										),
										'userID'		=> $friendID
									);
									JLNotificationWriter::push($data);
									JLNotificationWriter::savePushData($friendID, $data);
								}
							}
						} else {
							/*
							 * Save Activities:
							 * - Activity on own timeline
							 * - Activities on friends' timeline
							 * - Activities on node's timeline
							 */
							ZoneArticleActivity::model()->pushActivities($currentUser->hexID, $strArticleID, array(
								'owner'			=> true,
								'friends'		=> array($currentUser->hexID),
								'particulars'	=> array($strReceiverID),
								'followers'		=> array($strReceiverID),
								'categories'	=> array($strReceiverID)
							), ZoneActivity::TYPE_POST);
							// Activities on friends' timeline
// 							foreach ($friendIDs as $friendInfo) {
// 								$data = array(
// 									'namespace'		=> 'zone-sticker',
// 									'data'			=> array(
// 										'object_type'	=> 'Article',
// 										'type'		=> 'self-posting',
// 										'user'		=> $user,
// 										'article'	=> $article,
// 										'object'	=> null //
// 									),
// 									'userID'		=> $friendInfo['user_id']
// 								);

// 								JLNotificationWriter::push($data);
// 								JLNotificationWriter::savePushData($friendInfo['user_id'], $data);
// 							}
							// notify to followers
							Yii::import('application.modules.followings.models.ZoneFollowing');
							$articleID = IDHelper::uuidFromBinary($model->id, true);
							foreach ($nodeIDs as $nodeID) {
								// save activities for related types
								$strNodeID = IDHelper::uuidFromBinary($nodeID, true);
								
								// Save activities for followers
								$followers = ZoneFollowing::model()->followers($nodeID);
								foreach ($followers as $follower) {
									
									$followerID = $follower['user_id'];
									$binFollowerID = IDHelper::uuidToBinary($followerID);
									
									if ($binFollowerID != currentUser()->id) {
										// Save activities
										
										// Send notification to followers
										$data = array(
											//'friend_id'	=> currentUser()->hexID,
											'notifier_id'	=> currentUser()->hexID,
											'article_id'	=> $articleID,
											'type'			=> 'postArticle',
											'activity'		=> IDHelper::uuidFromBinary($activity['id'], true)
										);
										
										JLNotificationWriter::send(
											$followerID,
											"application.components.notification.renderer.ZoneArticleNotification",
											$data
										);
									}
								}
								
							}

							/**
							 * Sidebar notification:
							 * - Notify to friends
							 */
							foreach ($nodeIDs as $nodeID) {
								$followers = ZoneFollowing::model()->followers($nodeID);
								$node = ZoneInstanceRender::get(IDHelper::uuidFromBinary($nodeID, true))->toArray(true);
								foreach ($followers as $follower) {
									$followerID = $follower['user_id'];
									$data = array(
										'namespace'		=> 'zone-sticker',
										'data'			=> array(
											'object_type'	=> 'Article',
											'type'		=> 'node-posting',
											'user'		=> $user,
											'article'	=> $article,
											'node'		=> $node,
										),
										'userID'		=> $followerID
									);
									JLNotificationWriter::push($data);
									JLNotificationWriter::savePushData($followerID, $data);
								}
							}
						}
						// Loop: end
						
						// ------------------------
						// Upload images to S3
						$config	= array(
							'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
							'uploadPath'	=> 'upload/gallery/',
							'storageEngines'	=> array(
								's3'	=> array(
									'class'			=> 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
									'serverInfo'	=> array(
										'accessKey'	=> Yii::app()->params['AWS']['S3']['upload']['accessKey'],
										'secretKey'	=> Yii::app()->params['AWS']['S3']['upload']['secretKey'],
										'bucket'	=> 'static.youlook.net'
									)
								)
							)
						);
						$s3Uploader = Yii::createComponent($config);
						
						$folder = IDHelper::uuidFromBinary($model->id, true);
						foreach ($s3Files as $filePath) {
							$s3Uploader->store($filePath, array('s3path' => "upload/gallery/{$folder}"));
						}
					}
				} catch (Exception $ex) {
					$strMessage = $ex->getMessage();
					
					if (Yii::app()->request->isAjaxRequest) {
						ajaxOut(array(
							'error'			=> true,
							'type'			=> 'error',
							'message'		=> $strMessage,
						));
					} else {
						
					}
				}
			}
			
			
			jsonOut(array(
				'error'=>true,
				'data'=>$_POST
			));
		}
	}
	
	public function actionRenderArticle($article_id=null,$type = null){
		if(!empty($_POST)){
			$this->layout = "//layouts/master/ajax";
			$this->renderHtml = true;
			
			// Check if article ID is an ID of valid article
			Yii::import('application.modules.articles.models.*');
			$binArticleID = IDHelper::uuidToBinary($article_id);
			$article = ZoneArticle::model()->find('id=:aid', array(
				':aid'	=> $binArticleID
			));
			$activity = ZoneActivity::model()->findByAttributes(array('object_id'=>$article->id));
			$this->renderPartial($_POST['view'],array(
				'activity'=>$activity,
				'article'=>$article,
				'key'=>0,
				
				
			));
		}else{
			$this->redirect(GNRouter::createUrl('/articles/viewArticle',array('article_id'=>$article_id)));
		}
	}
	public function actionTopArticle(){
		if (Yii::app()->request->isAjaxRequest) {
			$this->layout = "//layouts/master/ajax";
			$this->renderHtml = true;
		}
		$nodeId = Yii::app()->request->getParam('nodeId');
		$topArticles = ZoneArticle::model()->topArticles(IDHelper::uuidToBinary($nodeId,true));
		$this->render('top_articles',array('topArticles'=>$topArticles));
	}
}