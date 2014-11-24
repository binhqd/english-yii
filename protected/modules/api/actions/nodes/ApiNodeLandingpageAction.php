<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiNodeLandingpageAction extends GNAction {

	public function getType() {
		return array(
			'album' => UsersModule::t('Photo albums'),
			'article' => UsersModule::t('Articles'),
			'video' => UsersModule::t('Videos'),
			'topic' => UsersModule::t('Topics'),
		);
	}

	/**
	 * This method is used to run action
	 */
	public function run($interest = '', $filter = '', $q = '', $type = '') {
		$Paginate = $this->controller->paginate(0);
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'interest' => $this->interest(),
			'type' => $this->type,
			'limit' => $Paginate->limit,
			'page' => $Paginate->currentPage + 1,
			'data' => $this->get($interest, $filter
					, $q, $type, $Paginate->limit, $Paginate->currentPage)));
	}

	public function interest() {
		$result = array();
		$categories = ZoneCategories::model()->getCategories(10);
		foreach ($categories as $model) {
			$result[$model->key_search] = $model->title;
		}
		return $result;
	}

	public function parseFilter($interest, $filter) {
		$categories = ZoneCategories::model()->getCategories(10);
		$_interest = '';
		foreach ($categories as $model) {
			if ($model->key_search == $interest) {
				$_interest = $model->id;
				break;
			}
		}
		if ($filter) {
			$filter = IDHelper::uuidToBinary($filter);
		}
		return array($_interest, $filter);
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get($interest, $filter, $q, $type = '', $limit = 10, $page = 0) {
		$types = array_keys($this->type);
		if ($type && in_array($type, $types)) {
			$types = array($type);
		}

		$total = $limit * $page;
		$idx = $total % count($types);
		if ($idx) {
			$_types = array_splice($types, $idx);
			$types = array_merge($_types, $types);
		}
		if (empty($types)) {
			throw new Exception(UsersModule::t('Invalid type config'), 400);
		}
		$even = floor($total / count($types));
		$odd = $total - (count($types) * $even);
		$itemOffsets = array_fill_keys($types, $even);
		reset($types);
		for ($i = 0; $i < $odd; $i++) {
			next($types);
			$itemOffsets[current($types)]++;
		}
		$itemLimits = array();
		while ($limit > 0) {
			foreach ($types as $type) {
				if (!isset($itemLimits[$type])) {
					$itemLimits[$type] = 0;
				}
				$itemLimits[$type]++;
				$limit--;
				if (!$limit) {
					break;
				}
			}
		}
		$fieldset = array();
		foreach ($types as $type) {
			$method = 'get' . ucfirst($type);
			$fieldset[$type] = $this->{$method}($interest, $filter
					, $q, $itemLimits[$type], $itemOffsets[$type]);
		}
		$result = array();
		while ($fieldset) {
			foreach ($types as $type) {
				if (empty($fieldset[$type])) {
					unset($fieldset[$type]);
					continue;
				}
				$data = array_shift($fieldset[$type]);
				$result[] = array(
					'type' => $type,
					'data' => $data
				);
			}
		}
		return $result;
	}

	public function getTopic($interest, $filter, $q, $limit, $offset) {
		$condition = InterestCondition::getValue($q, $interest);
		if (!empty($filter)) {
			switch ($interest) {
				case 'movie':
					$type = '/film/film/genre';
					break;
				case 'people':
					$type = '/people/person/profession';
					break;
				case 'cultures':
					$type = '/cultures/culture/category';
					break;
				case 'itv':
					$type = '/itv/channel/category';
					break;
			}
			if (!empty($type)) {
				$condition[$type] = $filter;
			}
		}
		$nodes = ZoneInstance::searchByIndex($condition, $limit, $offset);
		$Action = $this->controller->initAction('follower', array(
			'follower' => 'application.modules.api.actions.nodes.ApiNodeFollowerAction'
		));
		$result = array();
		$CurrentUser = currentUser();
		$CurrentUser->attachBehavior('CurrentUserFollowing'
				, 'application.modules.followings.components.behaviors.GNUserFollowingBehavior');

		foreach ($nodes as $node) {
			$node = ZoneInstanceRender::getResourceImage($node);
			$node['owner'] = ZoneUser::model()->get(ZoneInstance::initNode(array('id' => 0) + $node)->creatorID);
			$node['created'] = date(DATE_ISO8601 , $node['timestamp']);
			$info = array(
				'node' => $node,
				'follower' => array(
					'total' => $Action->count($node['zone_id']),
					'items' => $Action->get($node['zone_id'], '', 5)
				)
			);
			if (!$CurrentUser->isGuest) {
				$binObjectID = IDHelper::uuidToBinary($node['zone_id']);
				$info['node']['isFollowing'] = $CurrentUser->isFollowing($binObjectID);
			}

			$result[] = $info;
		}
		return $result;
	}

	public function getVideo($interest, $filter, $q, $limit, $offset) {
		list($interest, $filter) = $this->parseFilter($interest, $filter);
		$videos = ZoneSearchVideo::model()->search($q, $filter, IDHelper::uuidToBinary($filter), $limit, $offset);

		$result = array();
		foreach ($videos as $video) {
			$videoID = IDHelper::uuidFromBinary($video->video_id, true);
			try {
				$info = ZoneResourceVideo::model()->get($videoID);
				if (empty($info)) {
					continue;
				}
			} catch (Exception $ex) {
				continue;
			}
			$totalComment = ZoneComment::model()->countComments($video['video']['id']);
			$info['video']['shortTitle'] = JLStringHelper::word_limiter(GNStringHelper::htmlPurify($info['video']['title']), 7);
			$info['url'] = GNRouter::createUrl('/video/detail?id=' . $info['video']['id']);
			$info['comment'] = array(
				'total' => (int) $totalComment
			);
			$info['video']['poster']['profileUrl'] = ZoneRouter::createUrl("/profile/{$info['video']['poster']['username']}");
			if (!empty($info['video']['length'])) {
				$arr = explode(':', $info['video']['length']);
				if (count($arr) == 3 && $arr[0] == '00') {
					$info['video']['length'] = "{$arr[1]}:{$arr[2]}";
				}
			}
			$info['timestamp'] = strtotime($info['video']['created']);
			$info['created'] = date(DATE_ISO8601, $info['timestamp']);
			$result[] = $info;
		}

		return $result;
	}

	public function getAlbum($interest, $filter, $q, $limit, $offset) {
		list($interest, $filter) = $this->parseFilter($interest, $filter);
		$albums = ZoneSearchAlbum::model()->search($q, $interest, IDHelper::uuidToBinary($filter), $limit, $offset);

		$result = array();
		$currentUserID = currentUser()->id;
		foreach ($albums as $album) {
			$albumID = IDHelper::uuidFromBinary($album->album_id, true);
			try {
				$_info = ZoneResourceAlbum::model()->get($albumID);
				if (empty($_info)) {
					continue;
				}
			} catch (Exception $ex) {
				continue;
			}
			$totalComment = ZoneComment::model()->countComments($_info['id']);
			$bindID = IDHelper::uuidToBinary($_info['id']);
			$items = ZoneResourceAlbum::model()->getImages($bindID, 10, 0);
			foreach ($items as &$item){
				$binID = IDHelper::uuidToBinary($item['photo']['id']);
				$item['like'] = LikeObject::model()->getLikeInfo($binID, $currentUserID);
			}
			$info = array(
				'id' => $_info['id'],
				'title' => $_info['title'],
				'description' => (string) $_info['description'],
				'poster' => $_info['poster'],
				'items' => $items,
				'comment' => array(
					'total' => (int) $totalComment
				),
				'timestamp' => strtotime($_info['created'])
			);
			$info['created'] = date(DATE_ISO8601, $info['timestamp']);
//			if ($info['image_count'] == 1) {
//				$format = UsersModule::t('added a photo for album');
//			} else {
//				$format =  UsersModule::t('added {count} photos for album' , array(
//					'{count}' => $info['image_count']
//				));
//			}
//			$info['message'] = $format;
			$result[] = $info;
		}

		return $result;
	}

	public function getArticle($interest, $filter, $q, $limit, $offset) {
		list($interest, $filter) = $this->parseFilter($interest, $filter);
		$articles = ZoneSearchArticle::model()->search($q, $interest, IDHelper::uuidToBinary($filter), $limit, $offset);

		$result = array();
		foreach ($articles as $article) {
			$articleID = IDHelper::uuidFromBinary($article->article_id, true);
			try {
				$info = ZoneArticle::model()->get($articleID);
				if (empty($info)) {
					continue;
				}
			} catch (Exception $ex) {
				continue;
			}
			$totalComment = ZoneComment::model()->countComments($info['id']);
			$info['content'] = JLStringHelper::word_limiter(GNStringHelper::htmlPurify($info['content']), 20);
			$info['url'] = GNRouter::createUrl('/article?article_id=' . $info['id']);
			$info['comment'] = array(
				'total' => (int) $totalComment
			);
			//$info['author']['profileUrl'] = ZoneRouter::createUrl("/profile/{$info['author']['username']}");
			$info['timestamp'] = strtotime($info['created']);
			$info['created'] = date(DATE_ISO8601, $info['timestamp']);
			$result[] = $info;
		}

		return $result;
	}

}