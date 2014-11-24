<?php
/**
 * ArticlesController.php
 *
 * @author BinhQD
 * @version 1.0
 * @created Apr 5, 2013 10:59:48 AM
 */

class ZoneResourceImageController extends ZoneController {
	/**
	 * This method is used to allow action
	 * @return string
	 */
	 
	private $_common = array(
		'model'			=> array(
			'class'		=> 'ZoneResourceAlbum',
			'belongsTo'	=> array(
				'namespace'	=> array(
					'class'	=> 'ZoneAlbumNamespace'
				),
				// 				'author'	=> array(
					// 					'class'	=> 'ZoneArticleAuthor'
					// 				)
			)
		),
		// 		'uploadPath'	=> 'upload/articles/',
			// 		'indexUri'		=> '/articles/default/index',
			// 		'createUri'		=> '/articles/default/create',
			// 		'editUri'		=> '/articles/default/edit',
			// 		'viewUri'		=> '/articles/default/view',
			// 		'deleteUri'		=> '/articles/default/delete'
	);

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
				'greennet.modules.users.filters.ValidUserFilter + upload',
				'out'	=> array("files" =>
					array(
						array(
							"error"			=> true,
							"message"		=> "You need to login before continue"
						)
					)
				)
			),
			array(
				'application.modules.photos.filters.PhotoOwnerFilter + delete-image',
				'out'	=> array(
					"error"			=> true,
					"message"		=> "You need to login before continue"
				)
			)
		);
	}

	public function actions(){
		return array(
			'upload'	=> array(
				'class'=> 'greennet.modules.gallery.actions.GNUploadGalleryItemAction',
				'model'=> array(
					'class'	=> 'application.modules.resources.models.ZoneResourceImage',
					'belongsTo'	=> array(
						'poster'	=> array(
							'class'	=> 'ZoneImagePoster'
						)
					)
				),
				'fieldName'		=> 'image',
				'uploadPath'	=> 'upload/gallery/',
				'uploader'		=> array(
					'class'			=> 'greennet.components.GNSingleUploadImage.components.GNSingleUploadImage',
					'uploadPath'	=> 'upload/gallery/',
// 					'storageEngines'	=> array(
// 						's3'	=> array(
// 							'class'			=> 'greennet.components.GNUploader.components.engines.s3.GNS3Engine',
// 							'serverInfo'	=> array(
// 								'accessKey'	=> Yii::app()->params['AWS']['S3']['upload']['accessKey'],
// 								'secretKey'	=> Yii::app()->params['AWS']['S3']['upload']['secretKey'],
// 								'bucket'	=> 'myzonedev'
// 							)
// 						)
// 					)
				)
			),
			'delete-image'	=> array(
				'class'=> 'greennet.modules.gallery.actions.GNDeleteGalleryItemAction',
				'model'=> 'application.modules.resources.models.ZoneResourceImage',
				'uploadPath'	=> 'upload/gallery/',
				'uploader'		=> array(
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
				)
			)
		);
	}

	public function actionAlbum() {
		$this->layout = '//layouts/master/myzone';
		if ($this->isJsonRequest){
			$this->layout = '//layouts/master/ajax';
			$this->renderHtml =true;
		}
		$album_id = Yii::app()->request->getParam('album_id');
		$edit = Yii::app()->request->getParam('edit',null);
		$binAlbumId = IDHelper::uuidToBinary($album_id);

		// get album
		$album = ZoneResourceAlbum::model()->findByPk($binAlbumId);
		if(empty($album) || $album->data_status==ZoneResourceImage::DATA_STATUS_DELETED){
			$this->render('application.views.common.albums.album-error');
			exit();
		}
		/*Get owner post photo  ---- VuNDH add code*/
		$owner = ZoneUser::model()->findByPk($album->owner_id);

		$otherAlbums = ZoneResourceAlbum::model()->getOtherAlbum($album->id,$album->owner_id);
		if ($this->isJsonRequest) {
			// $this->layout = '//layouts/master/ajax';
			$this->renderHtml = true;
			$albums = $otherAlbums['data'];
			$this->renderPartial('application.modules.photos.views.user-photos.item-album', compact( 'albums'));
			exit();
		}
		// get images
		$criteria = new CDbCriteria();
		$criteria->condition = "album_id=:album_id";
		$criteria->params = array(':album_id' => $binAlbumId);
		$criteria->order = "created desc";
		$pages = new CPagination(count(ZoneResourceImage::model()->findAll($criteria)));
		$pages->pageSize = 1000;
		$pages->applyLimit($criteria);
		$images = ZoneResourceImage::model()->findAll($criteria);

		$totalPicProfile = ZoneUserAvatar::model()->getTotal(IDHelper::uuidFromBinary($album->owner_id,true));
		$totalPhotos = ZoneUser::model()->countPhotos($album->owner_id);
		$totalAlbums = count(ZoneResourceAlbum::model()->findAllByAttributes(array('owner_id'=>$album->owner_id)));
		
		$photos = array();
		foreach ($images as $photo) {
			$photo = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($photo->id,true),$album_id);
			$strToken = md5(uniqid(32));

			$type = 'user';
			$photo['photo']['created'] = date(DATE_ISO8601, strtotime($photo['photo']['created']));
			$photo['photo']['url'] = ZoneRouter::CDNUrl("/upload/gallery/thumbs/10000-650/{$photo['photo']['image']}?album_id={$photo['photo']['album_id']}");

			//$photo['like']['actionUnlike']	= ZoneRouter::createUrl('/photo/unlike');
			$photo['like']['token']			= $strToken;
			$photo['token'] = $strToken;
			
			$totalComments = ZoneComment::model()->countComments(strtolower($photo['photo']['id']));
			$photo['photo']['totalComments'] = $totalComments;
			$photo['photo']['commentOffset'] = 0;

			$photo['photo']['receiver'] = array();
			$userReceiver = ZoneUser::model()->get($photo['photo']['object_id']);
			
			if(!empty($userReceiver)){
				$photo['photo']['receiver'] = $userReceiver;
				$photo['photo']['receiver']['type'] = "user";
				// TODO: Fix truong hop khong phai la user
				if($photo['photo']['receiver']['id'] == $photo['photo']['poster']['id']) $photo['photo']['poster'] = $photo['photo']['receiver'];
				
				
			}else{
				$photo['photo']['receiver']['type'] = "node";
				$photo['photo']['receiver'] = ImageReceiverPhoto::get($photo['photo']['object_id']);
				
			}
			$photos[] = $photo;
		}

		$strTokenPost = md5(uniqid(32));
		$this->render('application.views.common.albums.album-detail',array(
			'strTokenPost'	=> $strTokenPost,
			'images'		=> $images,
			'photos'		=> $photos,
			'album'			=> $album,
			'album_id'		=> $album_id,
			'pages'			=> $pages,
			'totalPicProfile'	=> $totalPicProfile,
			'totalPhotos'	=> $totalPhotos,
			'totalAlbums'	=> $totalAlbums,
			'otherAlbums'	=> $otherAlbums,
			'edit'			=> $edit,
			'user'			=> $owner
		));
	}
	// public function actionAlbum() {
	// }
	
	public function actionCreateAlbum() {
		
		$model = new ZoneResourceAlbum();
		$modelName = "ZoneResourceAlbum";
		
		if(isset($_POST[$modelName])) {
			
			$sendNotification = true;
			
			$code = 201; // Create new album
			
			if(!empty($_POST['album_id'])) {
				
				// TODO: Need to check if current user is album owner
				$sendNotification = false;
				$album = ZoneResourceAlbum::model()->findByPk(IDHelper::uuidToBinary($_POST['album_id'],true));
				if(!empty($album)){
					
					$model = $album;
					$code = 200; // Old album
					if (!empty($_POST[$modelName]['title'])) {
						$model->title =  $_POST[$modelName]['title'];
					}
				}
				unset($_POST[$modelName]['title']);
				unset($_POST[$modelName]['created']);
			} else {
				$model->created = date("Y-m-d H:i:s");
				$model->owner_id = currentUser()->id;
				if (!empty($_POST['name'])) {
					$model->description = $_POST['name'];
				}
				if (empty($_POST[$modelName]['title'])) {
					$_POST[$modelName]['title'] = "";
				}
			}
			$model->setAttributes($_POST[$modelName], false);
			// Validate
			$validateModel = false;
			try {
				$validate = $model->validate();
			} catch (Exception $ex) {
				$strMessage = $ex->getMessage();

				if ($this->isJsonRequest) {
					jsonOut(array(
						'error'		=> true,
						'type'		=> 'error',
						'message'	=> $strMessage,
					));
				} else {
					// TODO:
					Yii::log($ex->getMessage(), CLogger::LEVEL_ERROR, "Create album");
				}
			}

			if ($validate) {
				try {
					$strMessage = "Album has been created successful";

					if ($model->save()) {
						$binNamespaceID = null;
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
										if($className == "ZoneAlbumNamespace"){
											$binNamespaceID = IDHelper::uuidToBinary($value);
											$nodeIDs[] = $value;
										}
										$obj->album_id = $model->id;
										$obj->holder_id = IDHelper::uuidToBinary($value);

										$obj->save();
									}
								}
							}
						}
						/**
						 Assign images for albums
						 **/
						$s3Files = array();
						$uploadImages = array();
						
						if(!empty($_POST['images'])){
							$webroot = Yii::getPathOfAlias('jlwebroot');
							$score = 0;
							if(!empty($_POST['object_id'])) {
								/*Get photo has score max*/
								$photoMaxScore = ZoneResourceImage::model()->getMaxScore($_POST['object_id']);
								if(!empty($photoMaxScore)) {
									$score = $photoMaxScore->score;
								}
								/*Set score*/
							}
							foreach($_POST['images'] as $key=>$image){
								$zoneResourceImage = ZoneResourceImage::model()->findByPk(IDHelper::uuidToBinary($image));
								if(!empty($zoneResourceImage)){
									$zoneResourceImage->object_id = $_POST['object_id'];
									$zoneResourceImage->album_id = $model->id;
									/*Set score*/
									$zoneResourceImage->score = floatval(round($score - 0.1,4));
									if(!empty($_POST['des'])){
										foreach($_POST['des'] as $key=>$desImages){
											if($key==$image){
												$zoneResourceImage->description = $desImages;
											}
										}
									}
									
									if($zoneResourceImage->save()){
										
										ZoneResourceImage::model()->deleteCache(IDHelper::uuidFromBinary($zoneResourceImage->id,true));
										// Move to S3
										$filePath = "{$webroot}/upload/gallery/{$zoneResourceImage->image}";
										$s3Files[] = $filePath;
										$uploadImages[] = $zoneResourceImage;
									} else {
										$errors  = $zoneResourceImage->getErrors();
										list ($field, $_errors) = each ($errors);
										if ($this->isJsonRequest) {
											jsonOut(array(
												'error'		=> true,
												'type'			=> 'error',
												'message'		=> $_errors[0],
											));
										} else {
											Yii::log($_errors[0], CLogger::LEVEL_ERROR, "Save resource image");
											//throw new Exception($_errors[0]);
										}
									}
								}
							}
						}
						
						$binUserID = currentUser()->id;
						$binObjectID = $model->id;
						
						// update photos count
						$model->image_count = $model->image_count + count($uploadImages);
						$model->save();
						
						// ============
						$return = $model->toArray();
						$return['countPhotos'] = count($uploadImages);
						$return['created'] = date(DATE_ISO8601,strtotime($return['created']));
						
						$firstImage = current($uploadImages);
						
						if (empty($firstImage)) {
							$out = array(
								"error"		=> true,
								"message"	=> "No photo has been posted"
							);
							ajaxOut($out);
						}
						$image = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($firstImage->id, true));
						
						$return['photo'] = $image['photo'];
						/*Set like album*/
						$return['like']['count'] = 0;

						$photos = array();
						foreach ($uploadImages as $uploadImage) {
							$photo = ZoneResourceImage::model()->get(IDHelper::uuidFromBinary($uploadImage->id, true));
							
							$strToken = md5(uniqid(32));
							$photo['like']['token']		= $strToken;
							$photo['token']				= $strToken;
							$photo['objectID']				= !empty($_POST['object_id']) ? $_POST['object_id'] : null;

							$album = ZoneResourceAlbum::model()->getAlbum(IDHelper::uuidToBinary($photo['photo']['album_id']));
							if (empty($album) || empty($album['title'])) {
								$album['title'] = ZoneResourceAlbum::TITLE_DEFAULT;
							} else {
								$album = $album->toArray();
							}
							$photo['photo']['album'] = $album;
							$photo['photo']['created'] = date(DATE_ISO8601, strtotime($photo['photo']['created']));
							$photos[] = $photo;
						}
						
						jsonOut(array(
							'error'			=> false,
							'type'			=> 'success',
							'message'		=> $strMessage,
							'photos'		=> $photos,
							'album'			=> $return,
							'album_id'		=> $return['id'],
							'object_type'	=> 'album',
							'code'			=> $code
						), false);

						/**
						 * index photo album for search (landing page)
						 * @author huytbt
						 */
						Yii::import('application.modules.landingpage.models.*');
						try {
							$index = ZoneSearchAlbum::model()->indexSearch(IDHelper::uuidToBinary($album['id']), $album['title'], strtotime($album['created']), $album['image_count'], !empty($_POST['object_id']) ? IDHelper::uuidToBinary($_POST['object_id']) : null);
						} catch (Exception $ex) {
							Yii::log($ex->getMessage(), 'error', 'Search: index failure photo album (id:'.$album['id'].')');
						}
						/* end (index) */
						
						if($sendNotification){
							// ====================================================================
							Yii::import('application.modules.activities.models.*');
							Yii::import('application.components.notification.JLNotificationWriter');
							Yii::import('application.components.notification.ZoneStickerNotificationDocument');
							
							
							// thinhpq fix post on wall user
							$binReceiverID = IDHelper::uuidToBinary($_POST['object_id']);
							
							$isUser = ZoneUser::model()->isUser(IDHelper::uuidToBinary($_POST['object_id']));
							$currentUser = currentUser();
							
							$strAlbumID = IDHelper::uuidFromBinary($binObjectID, true);
							
							$receiverID = $_POST['object_id'];
							if ($isUser) {
								// 1. If album is posted on own timeline
								if ($_POST['object_id'] == currentUser()->hexID) {
									ZoneAlbumActivity::model()->pushActivities($currentUser->hexID, $strAlbumID, array(
										'owner'			=> true,
										'friends'		=> array($currentUser->hexID)
									), ZoneActivity::TYPE_POST);
									
									/*
									 * Sidebar notification:
									 * - Notify to friends
									 */
									
									$friendIDs = ZoneListHelper::getFriends($currentUser->hexID);
									foreach ($friendIDs as $friendID) {
										$data = array(
											'namespace'		=> 'zone-sticker',
											'data'			=> array(
												'object_type'	=> 'Album',
												'type'		=> 'self-posting',
												'user'		=> ZoneUser::model()->get($currentUser->hexID),
												'album'		=> $return,
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
									ZoneAlbumActivity::model()->pushActivities($currentUser->hexID, $strAlbumID, array(
										'owner'			=> true,
										'particulars'	=> array($receiverID), // receiver timeline
										'friends'		=> array($receiverID) // Activity on receiver's friend's timeline
									), ZoneActivity::TYPE_POST);
									
									/*
									 * Top notification:
									* - Notify to receiver
									*/
									$data = array(
										'notifier_id'	=> $currentUser->hexID,
										'album_id'		=> $strAlbumID,
										'type'			=> 'postAlbum',
										//'activity'		=> IDHelper::uuidFromBinary($activity['id'], true)
									);
									JLNotificationWriter::send(
										$receiverID,
										"application.components.notification.renderer.ZoneAlbumNotification",
										$data
									);
									
									//ZoneNotificationDocument::
									/*
									 * Sidebar notification:
									* - Notify to receiver's friends
									*/
									$friendIDs = ZoneListHelper::getFriends($receiverID);
									$userInfo = ZoneUser::model()->get($currentUser->hexID);
									foreach ($friendIDs as $friendID) {
										$data = array(
											'namespace'		=> 'zone-sticker',
											'data'			=> array(
												'object_type'	=> 'Album',
												'type'		=> 'other-posting',
												'user'		=> $userInfo,
												'album'		=> $return,
												'object'	=> null //
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
								ZoneAlbumActivity::model()->pushActivities($currentUser->hexID, $strAlbumID, array(
									'owner'			=> true,
									'friends'		=> array($currentUser->hexID),
									'particulars'	=> array($receiverID),
									'followers'		=> array($receiverID),
									'categories'	=> array($receiverID)
								), ZoneActivity::TYPE_POST);
								
								/* - Activities on node's timeline */
								
								$friendIDs = ZoneListHelper::getFriends($currentUser->hexID);
								$userInfo = ZoneUser::model()->get($currentUser->hexID);
								
								foreach ($nodeIDs as $strNodeID) {
									try {
										$nodeInfo = ZoneInstanceRender::get($strNodeID)->toArray();
									} catch (Exception $ex) {
										continue;
									}
									
									// notify to followers
									$followers = ZoneListHelper::getFollowers($strNodeID);
									$receiverIDs = array_merge($friendIDs, $followers);
									$receiverIDs = array_unique($receiverIDs);
									
									foreach ($receiverIDs as $receiverID) {
										if ($receiverID != $currentUser->hexID) {
											// Send notification to followers
											$data = array(
												'namespace'		=> 'zone-sticker',
												'data'			=> array(
													'object_type'	=> 'Album',
													'type'		=> 'other-posting',
													'user'		=> $userInfo,
													'album'		=> $return,
													'object'	=> $nodeInfo //
												),
												'userID'		=> $receiverID
											);
											
											JLNotificationWriter::push($data);
											JLNotificationWriter::savePushData($receiverID, $data);
										}
									}
									
									// sidebar notification
									try {
										$ownerID = ZoneListHelper::getNodeOwner($strNodeID);
										$data = array(
											'notifier_id'	=> $currentUser->hexID,
											'album_id'		=> $strAlbumID,
											'type'			=> 'postAlbum',
											//'activity'		=> IDHelper::uuidFromBinary($activity['id'], true)
										);
										JLNotificationWriter::send(
											$ownerID,
											"application.components.notification.renderer.ZoneAlbumNotification",
											$data
										);
									} catch (Exception $ex) {
										// TODO: May do something here
									}
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

					if ($this->isJsonRequest) {
						jsonOut(array(
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
	
	public function actionRenderAlbum() {
		if (empty($_GET['album_id'])) {
			jsonOut(array(
				'error'			=> true,
				'type'			=> 'error',
				'message'		=> "Invalid album_id"
			));
		}
		
		// continue
		$strAlbumID = $_GET['album_id'];
		
		$this->layout = "//layouts/master/ajax";
		$this->renderHtml = true;
		$viewPath = $_POST['view'];

		Yii::import('application.modules.resources.models.*');
			
		$binAlbumID = IDHelper::uuidToBinary($strAlbumID);
		$album = ZoneResourceAlbum::model()->find('id=:aid And data_status=:dataStatus', array(
			':aid'			=> $binAlbumID,
			':dataStatus'	=> ZoneResourceImage::DATA_STATUS_NORMAL
		));
		
		$owner = $album->owner;
		
		$images = $album->images;
		$this->renderPartial($viewPath, compact('album', 'owner', 'images'));
	}

	/**
	 * This is function use delete a album
	 * @author: VuNDH
	 */
	public function actionDeleteAlbum() {
		if(empty($_GET['album_id'])) {
			jsonOut(array(
				'error'=>true,
				'type'=>'error',
				'message'=>'Invalid album_id'
			));
		}
		$zoneActivity = AdminZoneActivity::model()->findByAttributes(array(
			'object_type'=> AdminZoneActivity::OBJECT_TYPE_ALBUM,
			'object_id'=>IDHelper::uuidToBinary($_GET['album_id']),
			'user_id'=>currentUser()->id,
			'receiver_id'=>currentUser()->id
		));
		if(!empty($zoneActivity)) {
			//Delete activity of album
			$zoneActivity->deleteActivity();
		}else {
			//Delete album
			AdminZoneActivity::model()->deleteAlbum(IDHelper::uuidToBinary($_GET['album_id']));
		}
		jsonOut(array(
			'totalPhoto'=>ZoneUser::model()->countPhotos(currentUser()->id),
		));
	}
	
	/**
	 * This method is used to hide album
	 * @author: Chu Tieu
	 */
	public function actionHideAlbum($binID=null){
		if (!empty($binID)) {
			$binID = IDHelper::uuidToBinary($binID);
			$countPhotoDelete = ZoneResourceAlbum::model()->hideAlbumById($binID);
			if ($countPhotoDelete > -1) {
				$photoOfAlbumHide = ZoneResourceImage::model()->findByAttributes(array('album_id'=>$binID));
				if(!empty($photoOfAlbumHide)){
					$arrPhotos = array();
					$photos = ZoneResourceImage::model()->getPhotosForObject($photoOfAlbumHide->object_id,6);
					$totalPhoto = count(ZoneResourceImage::model()->findAllByAttributes(array('object_id'=>$photoOfAlbumHide->object_id)));
					if(!empty($photos['data'])) {
						foreach ($photos['data'] as $photo) {
							$strPhotoID = IDHelper::uuidFromBinary($photo->id,true);
							$strAlbumID = IDHelper::uuidFromBinary($photo->album_id,true);
							$arrPhotos[] = $photo->get($strPhotoID,$strAlbumID);
						}
					}
					if(Yii::app()->request->isAjaxRequest){
						ajaxOut(array(
							'error'		=> false,
							'message'	=> 'This object has been deleted.',
							'photos'	=> $arrPhotos,
							'totalPhoto'=> $totalPhoto,
							'countPhotoDelete' => $countPhotoDelete
						), false);
						// delete activities
						ZoneActivity::model()->deleteAll('object_id=:object_id', array(
							':object_id'	=> $binID,
						));
						// end delete
					}
				}else{
					ajaxOut(array(
						'error'=>true,
						'message'=>'Album is invalid.'
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
	 * This is function use hide photos contributes
	 * @author VUNDH
	 */
	public function actionHidePhotos() {
		$strObjectID = Yii::app()->request->getParam('binID');
		if(!empty($strObjectID)) {
			
			/** 
			 * Get object
			 * Edit by : Chu Tieu
			 */
			$criteria = new CDBCriteria();
			$criteria->with = 'ImagePoster';
			$criteria->condition = 'ImagePoster.holder_id=:holderID and t.object_id=:objectID and t.data_status=:dataStatus';
			
			$criteria->params = array(
				':holderID'		=> currentUser()->id,
				':objectID'	=> $strObjectID,
				':dataStatus'	=> ZoneResourceImage::DATA_STATUS_NORMAL
			);
			$photos = ZoneResourceImage::model()->findAll($criteria);
			/** End */
			
			$countPhotoDelete = count($photos);
			if(!empty($photos)) {
				if(!empty($photos)) {
					foreach ($photos as $photo) {
						if(!$photo->hideImage()) {
							ajaxOut(array(
								'error' => true,
								'message' => 'This photos is hided fails.'
							));
						}
					}
					ajaxOut(array(
						'error' => false,
						'message' => 'This photos is hided successful.',
						'countPhotoDelete' => $countPhotoDelete
					));
				}
			}else {
				ajaxOut(array(
					'error' => true,
					'message' => 'This photo is invalid.'
				));
			}
		}else {
			ajaxOut(array(
				'error' => true,
				'message' => 'This photos is invalid.'
			));
		}
	}

	/**
	 * This method is used to restore album
	 * @author: Chu Tieu
	 */
	public function actionRestoreAlbum($binID=null){
	
		if (!empty($binID)) {
			$binID = IDHelper::uuidToBinary($binID);
			$model = ZoneResourceAlbum::model()->restoreAlbumById($binID);
			
			if ($model) {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => false,
						'message' => 'This object has been restore.'
					));
				}
			} else {
				if(Yii::app()->request->isAjaxRequest){
					ajaxOut(array(
						'error' => true,
						'message' => 'This object has not been restore.'
					));
				}
			}
		}
	}
	
	/**
	 * This method is used to hide photo
	 * @author: Chu Tieu
	 * VuNDH change code
	 */
	public function actionHidePhoto(){
		$photoID = Yii::app()->request->getParam('binID');
		if (!empty($photoID)) {
			$photo = ZoneResourceImage::model()->findByPk(IDHelper::uuidToBinary($photoID));
			$binAlbumID = $photo->album_id;
			if(!empty($photo)) {
				$strObjectID = $photo->object_id;
				if (!$photo->hideImage()) {
					ajaxOut(array(
						'error' => true,
						'message' => 'This photo has not been hided.'
					));
				}else {
						if(!empty($binAlbumID)) {
							/*Get album off photo*/
							$album = ZoneResourceAlbum::model()->findByPk($binAlbumID);
							if(!empty($album)) {/*If photos of album hide then hide album*/
								$photosOfAlbum = ZoneResourceImage::model()->getImagesFromAlbum($binAlbumID,-1,0);
								if(count($photosOfAlbum) == 0) {
									$album->hideAlbumById($binAlbumID);
									// delete activities
									ZoneActivity::model()->deleteAll('object_id=:object_id', array(
										':object_id'	=> $binAlbumID,
									));
								}
							}
						}
						/*Get photo has score max*/
						$photoMaxScore = ZoneResourceImage::model()->getMaxScore($strObjectID);
						ZoneResourceImage::model()->deleteCache($photoID);
						ajaxOut(array(
							'error' => false,
							'message'	=> 'This photo has been hided successfull.',
							'photo'		=> !empty($photoMaxScore) ? $photoMaxScore->get(IDHelper::uuidFromBinary($photoMaxScore->id, true)) : null,
						));
				}
			}
		}else {
			ajaxOut(array(
				'error' => true,
				'message' => 'This photo is invalid.'
			));
		}
	}
	
	/**
	 * This method is used to restore photo
	 * @author: Chu Tieu
	 */
	public function actionRestorePhoto($binID=null){
		if (!empty($binID)) {
			$binID = IDHelper::uuidToBinary($binID);
			$model = ZoneResourceImage::model()->restoreImage($binID);
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

	public function actionRotate() {
		$strPhotoID = Yii::app()->request->getParam('photoID');
		$angle = Yii::app()->request->getParam('angle');
		/*Get photo*/
		$photo = ZoneResourceImage::model()->findByPk(IDHelper::uuidToBinary($strPhotoID));
		if(!empty($photo)) {
			/*Delete cache*/
			$photo->deleteCache($strPhotoID);
			if(!empty($angle)) {
				$old_image_path = Yii::getPathOfAlias('webroot').'/upload/gallery/'.$photo->image;
				$new_image = md5($photo->image.rand(0, 1000)).strrchr($photo->image, ".");
				$new_image_path =  Yii::getPathOfAlias('webroot').'/upload/gallery/'.$new_image;
				$rotate = Yii::app()->iwi->load($old_image_path)->rotate($angle)->save($new_image_path);

				if($rotate) {
					$photo->image = $new_image;
					if($photo->save()){
						$rotate_photo = ZoneResourceImage::model()->get($strPhotoID, IDHelper::uuidFromBinary($photo->album_id,true));
						/*Get photo has score max*/
						$photoMaxScore = ZoneResourceImage::model()->getMaxScore($photo->object_id);
						$isPrimary = false;
						/*Check is primary*/
						if(IDHelper::uuidFromBinary($photoMaxScore->id,true) == $rotate_photo['photo']['id']) {
							$isPrimary = true;
						}

						jsonOut(array(
							'error'		=> false,
							'message'	=> 'Rotate image is successull!',
							'photo'		=> $rotate_photo,
							'isPrimary'	=> $isPrimary
						),false);

						/*Move image to S3 */
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
						$webroot = Yii::getPathOfAlias('jlwebroot');
						$filePath = "{$webroot}/upload/gallery/{$photo->image}";
						$s3Uploader = Yii::createComponent($config);
						$folder = IDHelper::uuidFromBinary($photo->album_id,true);
						$s3Uploader->store($filePath, array('s3path' => "upload/gallery/{$folder}"));

					}else {
						jsonOut(array(
							'error'=>true,
							'message'=>'Rotate image is fails'
						));
					}
						
				}
			}
		}
	}
}