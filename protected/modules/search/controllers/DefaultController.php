<?php

class DefaultController extends ZoneController {

	public $layout = "//layouts/master/myzone";

	public function allowedActions() {
		return 'index';
	}

	public function actionIndex() {
		$keyword = Yii::app()->request->getParam('keyword', null);

		$interest = Yii::app()->request->getParam('interest', null);
		$page = Yii::app()->request->getParam('page', 1);


		$limit = 14;
		$limitArticles = 12;

		$offset = (($page * $limit) - $limit);
		$offsetArticles = (($page - 1) * $limitArticles);
		if (!empty($_GET['debug'])) {
			dump($offset, false);
			dump($limit);
		}
		$nodes = ZoneInstance::searchByIndex(InterestCondition::getValue($keyword, $interest), $limit, $offset);

		$articles = ZoneArticle::model()->searchArticles($keyword, $limitArticles, $offsetArticles);
		
		if((count($nodes) == 1 && count($articles) == 0)){
			$this->redirect('/zone/pages/detail?id=' .  $nodes[0]['zone_id']);
		}

		$arrArticles = array();
		if (isset($articles)) {
			$i = 0;
			$hasShowAlbum = false;
			foreach ($articles as $article) {
				$info = $this->getArticleInfo($article);
				if (empty($info)) {
					continue;
				}

				$art = $info;
				$art['index'] = $i;
				$art['style_top'] = ($i++)*(175);
				$art['has_show_album'] = $hasShowAlbum;
				if (isset($art['images']) && count($art['images']) > 1)
					$hasShowAlbum = true;
				$arrArticles[] = $art;
			}
		}

		$result = array(
			'articles' => $arrArticles,
			'nodes' => $nodes,
		);

		// Add extra information for node
		foreach ($result['nodes'] as &$node) {
			$node['extraInfo'] = $this->getExtraNodeInformation($node);
		}

		if (Yii::app()->request->isAjaxRequest) {
			// $this->layout = "//layouts/master/ajax";
			// $this->renderHtml = true;
			// $this->renderPartial('application.views.common.search.index', array(
			// 	'result' => $result,
			// 	'page' => $page,
			// 	'interest' => $interest,
			// 	'type' => 'search'
			// ));
			ajaxOut(array(
				'result' => $result,
				'page' => $page,
				'interest' => $interest,
				'type' => 'search'
			));
		} else {
			$this->render('application.modules.landingpage.views.default.index', array(
				'keyword' => $keyword,
				'result' => $result,
				'page' => $page,
				'interest' => $interest,
				'limit' => $limit,
				'skipOffset' => 0,
				'notifications' => array(),
			));
		}
	}

	/**
	 * This method is used to get article information
	 */
	public function getArticleInfo($article)
	{
		$articleID = IDHelper::uuidFromBinary($article->id, true);
		try {
			$info = ZoneArticle::get($articleID);
		} catch (Exception $ex) {
			return null;
		}
		
		
		$strObjId = IDHelper::uuidFromBinary($article->id,true);
		
		$images = $article->getImages($article->id);
		if ($info['image'] != null) {
			$imagePrimary = ZoneRouter::CDNUrl("/")."/upload/gallery/fill/101-101/{$info['image']}?album_id={$info['id']}";
		} else {
			
			if (!empty($images)) {
				$imagePrimary = ZoneRouter::CDNUrl("/")."/upload/gallery/fill/101-101/{$images[0]['photo']['image']}?album_id={$images[0]['photo']['album_id']}";
			} else {
				$imagePrimary = ZoneRouter::CDNUrl("/upload/gallery/fill/101-101/");
			}
		}
		
		$totalComment = ZoneComment::model()->countComments(IDHelper::uuidFromBinary($article->id,true));
		$totalLike = $info['like']['count'];

		$author = $info['author'];
		
		$info['imagePrimary'] = $imagePrimary;
		$info['content'] = JLStringHelper::word_limiter(GNStringHelper::htmlPurify($article->content), 12);
		$info['url'] = GNRouter::createUrl('/article?article_id=' . IDHelper::uuidFromBinary($article->id,true));
		$info['comment'] = array(
			'total'		=> $totalComment
		);
		$info['author']['profileUrl'] = ZoneRouter::createUrl("/profile/{$info['author']['username']}");
		$info['timeago'] = date(DATE_ISO8601,strtotime($article->created));
		$info['images'] = $images;
		return $info;
	}

	/**
	 * This method is used to get extra information of node
	 * @author huytbt <huytbt@gmail.com>
	 */
	private function getExtraNodeInformation($node) {
		$isUser = ZoneUser::model()->findByPk(IDHelper::uuidToBinary($node['zone_id']));
		if (!empty($isUser)) {
			$urlImage = ZoneRouter::CDNUrl('/upload/user-photos/'.$node['zone_id'].'/thumbs/196-10000/' . (!empty($isUser->profile->image)?$isUser->profile->image:'') . '?album_id=' . $node['zone_id']);
		} else {
			$images = ZoneInstanceRender::getResourceImage(array(
				'zone_id' => $node['zone_id'],
				'image' => array()
			));
			if (!empty($images['image']['photo']['image'])) {
				$height = "auto";
				$urlImage = ZoneRouter::CDNUrl('/upload/gallery/thumbs/196-10000/' . $images['image']['photo']['image'] . '?album_id=' . $images['image']['photo']['album_id']);
			} else {
				$height = "415";
				if ($node['label'] == ZoneUser::$userNode)
					$height = '275';
				$urlImage = GNRouter::createUrl('/site/placehold', array('t' => '196x215-282828-969696'));
			}
		}
		
		$properties = ZoneNodeRender::properties($node['zone_id'], '/common/topic/description');
		$description = 'No wiki description';
		if (isset($properties['/common/topic/description']['items']))
			$description = GNStringHelper::htmlPurify($properties['/common/topic/description']['items']);
		if ($description == 'No wiki description')
			$description = 'No summary has been posted yet';
		$description = GNStringHelper::word_limiter($description, 20);

		$owner = ZoneInstance::initNode($node['zone_id'])->getOwner();
		$owner = GNUser::model()->findByPk(IDHelper::uuidToBinary($owner['zone_id']));
		$ownerInfo = null;
		if (!empty($owner) && !$owner->isGuest) {
			$ownerInfo = array(
				'displayname' => $owner->displayname,
				'profileUrl' => ZoneRouter::createUrl('profile/' . $owner->username),
				'imageUrl' => ZoneRouter::CDNUrl('/upload/user-photos/' . IDHelper::uuidFromBinary($owner->id, true) . '/fill/32-32/' . $owner->profile->image) . '?album_id=' . IDHelper::uuidFromBinary($owner->id, true),
			);
		}

		$totalArticle = ZoneArticle::model()->countArticlesByObject(IDHelper::uuidToBinary($node['zone_id']));
		$totalImages = ZoneResourceImage::model()->countImages(IDHelper::uuidToBinary($node['zone_id']));
		$totalVideos = ZoneResourceVideo::model()->getTotal(IDHelper::uuidToBinary($node['zone_id']));

		$isFollowing = false;
		if (!currentUser()->isGuest) {
			currentUser()->attachBehavior('UserFollowing', 'application.modules.followings.components.behaviors.GNUserFollowingBehavior'); // Attach behavior following for user
			$isFollowing = currentUser()->isFollowing(IDHelper::uuidToBinary($node['zone_id']));
			currentUser()->detachBehavior('UserFollowing');
		}
		Yii::import('application.modules.followings.models.ZoneFollowing');
		$countFollowers = ZoneFollowing::model()->countFollowers(IDHelper::uuidToBinary($node['zone_id']));

		return array(
			'imageUrl' => $urlImage,
			'imageTitle' => (isset($images) && !empty($images['image']['title'])) ? $images['image']['title'] : "",
			'nodeUrl' => ZoneRouter::createUrl('/zone/pages/detail/', array('id' => $node['zone_id'])),
			'description' => $description,
			'owner' => $ownerInfo,
			'articles' => array(
				'total' => $totalArticle,
				'url' => ZoneRouter::createUrl('/articles/views/index', array('id' => $node['zone_id'])),
				'text' => $totalArticle == 1 ? 'article' : 'articles',
			),
			'photos' => array(
				'total' => $totalImages,
				'url' => ZoneRouter::createUrl('/photos/views/index', array('id' => $node['zone_id'])),
				'text' => $totalImages == 1 ? 'photo' : 'photos',
			),
			'videos' => array(
				'total' => $totalVideos,
				'url' => ZoneRouter::createUrl('/zone/pages/detail/', array('id' => $node['zone_id'], 'tab' => 'videos')),
				'text' => $totalVideos == 1 ? 'video' : 'videos',
			),
			'followers' => array(
				'total' => $countFollowers,
				'url' => ZoneRouter::createUrl('/followings/list/followers', array('token' => 'object_' . $node['zone_id'])),
				'text' => $countFollowers == 1 ? 'follower' : 'followers',
			),
			'isFollowing' => $isFollowing,
		);
	}

}