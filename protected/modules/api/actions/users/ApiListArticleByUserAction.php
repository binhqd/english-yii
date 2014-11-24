<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
Yii::import('application.modules.articles.components.behaviors.UserArticlesBehavior');

class ApiListArticleByUserAction extends GNAction {

	protected $_runtime = array();

	/**
	 * This method is used to run action
	 */
	public function run($id) {
		$Paginate = $this->controller->paginate($this->count($id));
		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $this->get($id, $Paginate->limit, $Paginate->offset),
			'total' => $Paginate->itemCount,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit
		));
	}

	public function count($id) {
		$UserInfo = $this->controller->userInfo($id);
		return $UserInfo->stats['articles'];
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get($id, $limit = 10, $offset = 0) {
		$UserInfo = $this->controller->userInfo($id);
		$UserInfo->attachBehavior('ZoneArticles', 'UserArticlesBehavior');
		$articles = $UserInfo->getArticles($limit, $offset);
		$UserInfo->detachBehavior('ZoneArticles');

		$result = array();
		foreach ($articles as $article) {
			$binID = IDHelper::uuidToBinary($article['id']);
			$commentsCount = ZoneComment::model()->countComments($article['id']);
			//$likeCount = LikeStatistic::countLike($article['id']);
			$images = ZoneArticle::model()->getImages($binID, 5);
			$extraInfo = array(
				'images' => !empty($images) ? $images : null,
				'comment' => array(
					'total' => intval($commentsCount),
					'items' => $article['comments']
				),
//				'like' => array(
//					'total' => intval($likeCount),
//				),
			);
			unset($article['comments']);
			$extraInfo['target'] = 'user';
			if (!empty($article['namespace'])) {
				try {
					$node = ZoneInstanceRender::get($article['namespace']['holder_id']);
					if (!$node->isUserNode()) {
						$extraInfo['target'] = 'node';
					}
					$extraInfo['node'] = $node;
				} catch (Exception $ex) {
					$model = ZoneArticle::model()->findByPk($binID);
					$model->setInvalid();
					continue;
				}
			}
			$result[] = CMap::mergeArray($article, $extraInfo);
		}
		return $result;
	}

}