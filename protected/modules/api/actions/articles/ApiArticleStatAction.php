<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiArticleStatAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run($id = '') {
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $this->get($id)
		));
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get($id) {
		$options = array(
			'loadPoster' => true,
			'loadLikes' => true,
			'loadComments' => 5
		);
		$article = ZoneArticle::model()->get($id, $options);
		if (empty($article)) {
			throw new Exception(UsersModule::t('The article "{id}" is not found.', array(
				'{id}' => $id
			)));
		}
		$commentCount = ZoneComment::model()->countComments($article['id']);
		//$likeCount = LikeStatistic::countLike($article['id']);
		$currentUserID = currentUser()->id;
		$images = (array)ZoneArticle::model()->getImages(IDHelper::uuidToBinary($article['id']), 5);
		foreach($images as &$image){
			$binID = IDHelper::uuidToBinary($image['photo']['id']);
			$image['like'] = LikeObject::model()->getLikeInfo($binID, $currentUserID);
		}
		$extraInfo = array(
			'timestamp' => strtotime($article['created']),
			'images' => !empty($images) ? $images : null,
			'comment' => array(
				'total' => intval($commentCount),
				'items' => $article['comments']
			)
		);
		//debug($article);
		$extraInfo['created'] = date(DATE_ISO8601, $extraInfo['timestamp']);
		$extraInfo['author'] = ZoneUser::model()->get($article['author']['id']);
		$extraInfo['content'] = nl2br($article['content']);
		
		$article['like']['count'] = @intval($article['like']['count']);
		unset($article['comments']);

		if (isset($article['namespace']['holder_id'])) {
			try {
				$Node = ZoneInstanceRender::get($article['namespace']['holder_id']);
			} catch (Exception $e) {
				
			}
			$extraInfo['node'] = $Node->toArray();
		}
		return CMap::mergeArray($article, $extraInfo);
	}

}