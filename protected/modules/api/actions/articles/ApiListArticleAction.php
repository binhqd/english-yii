<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
Yii::import('application.modules.articles.components.behaviors.UserArticlesBehavior');

class ApiListArticleAction extends GNAction {

	protected $_runtime = array();

	public function get($id, $q = '', $limit = 10, $offset = 0) {
		$Node = ZoneInstanceRender::get($id);
		$binID = IDHelper::uuidToBinary($Node->zone_id);

		$criteria = new CDbCriteria();
		$criteria->with = array('author', 'namespace');
		$criteria->together = true;
		$criteria->order = 'created desc';

		if ($Node->isUserNode()) {
			$criteria->compare('author.holder_id', $binID);
		} else {
			$criteria->compare('namespace.holder_id', $binID);
		}
		if ($q) {
			$criteria->addSearchCondition('t.title', $q, true);
		}
		$criteria->compare('t.data_status', ZoneArticle::DATA_STATUS_NORMAL);
		$total = intval(ZoneArticle::model()->count($criteria));

		$criteria->limit = $limit;
		$criteria->offset = $offset;

		return array(
			'total' => $total,
			'data' => ZoneArticle::model()->findAll($criteria)
		);
	}

	/**
	 * This method is used to run action
	 */
	public function run($id = '', $q = '') {
		$Paginate = $this->controller->paginate(0);
		$articles = $this->get($id, $q, $Paginate->limit, $Paginate->offset);
		$result = array();
		foreach ($articles['data'] as $model) {
			$extraInfo = array('target' => 'user');
			try {
				$article = $model->toArray(array(
					'loadPoster' => true,
					'loadLikes' => true,
					'loadComments' => 3
				));
				if (isset($article['namespace'])) {
					$node = ZoneInstanceRender::get($article['namespace']['holder_id']);
					if (!$node->isUserNode()) {
						$extraInfo['target'] = 'node';
					}
					$extraInfo['node'] = $node;
				}
			} catch (Exception $ex) {
				$model->setInvalid();
				continue;
			}
			
			$article['like']['count'] = @intval($article['like']['count']);
			$commentsCount = ZoneComment::model()->countComments($article['id']);
			$images = ZoneArticle::model()->getImages($model->id, 5);
			$extraInfo += array(
				'timestamp' => strtotime($article['created']),
				'images' => !empty($images) ? $images : null,
				'comment' => array(
					'total' => intval($commentsCount),
					'items' => $article['comments']
				)
			);
			$extraInfo['created'] = date(DATE_ISO8601, $extraInfo['timestamp']);
			unset($article['comments']);
			//$extraInfo['target'] = 'user';

			$result[] = CMap::mergeArray($article, $extraInfo);
		}

		$this->controller->out(200, array(
			'cdn' => ZoneRouter::CDNUrl("/"),
			'data' => $result,
			'page' => $Paginate->currentPage + 1,
			'limit' => $Paginate->limit,
			'total' => $articles['total']
		));
	}

}