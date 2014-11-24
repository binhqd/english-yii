<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
Yii::import('application.modules.articles.components.behaviors.UserArticlesBehavior');

class ApiListArticleByNodeAction extends GNAction {

	protected $_runtime = array();

	/**
	 * This method is used to run action
	 */
	public function run($id = '', $q = '') {
		$Paginate = $this->controller->paginate(0);
		$Node = ZoneInstanceRender::get($id);
		$binID = IDHelper::uuidToBinary($Node->zone_id);
		if ($Node->isUserNode()) {
			$articles = ZoneArticle::model()->getArticlesByObject($binID, null, $q, $Paginate->limit);
		} else {
			$articles = ZoneArticle::model()->getArticlesByObject(null, $binID, $q, $Paginate->limit);
		}
		$result = array();
		foreach ($articles['data'] as $article) {
			$article = $article->toArray(array(
				'loadPoster' => true,
				'loadLikes' => true,
				'loadComments' => 3
			));
			$binID = IDHelper::uuidToBinary($article['id']);
			$commentsCount = ZoneComment::model()->countComments($article['id']);
			$images = ZoneArticle::model()->getImages($binID, 5);
			$extraInfo = array(
				'images' => !empty($images) ? $images : null,
				'comment' => array(
					'total' => intval($commentsCount),
					'items' => $article['comments']
				)
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

		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $result,
			'page' => $articles['pagination']->currentPage + 1,
			'limit' => $articles['pagination']->limit,
			'total' => $articles['pagination']->itemCount
		));
	}

}