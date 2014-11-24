<?php

Yii::import('api_app.components.*');

/**
 * List Article action
 * @author truonghn <truonghn@gmail.com>
 * @version 2.0
 */
class APITopicArticleListAction extends CAction
{
	/**
	 * Description for this method
	 * @author truonghn<truonghn@greenglobal.vn>
	 * @param int $limit: article limit
	 * @param int $page: article page
	 * @param string $fields: article fields
	 * @param string $parts: article parts
	 * @throws throws exception user is not login.
	 * @return return list of articles of topic.
	 */
	public function run($topic_id = null, $q= null, $limit = 10, $page = 0, $fields = '', $parts = '')
	{
		ApiAccess::allow("GET");
		// get articles
		Yii::import('api_app.modules.articles.models.APIZoneArticle');
		$binTopicId = IDHelper::uuidToBinary($topic_id);
		$totalArticles = APIZoneArticle::model()->countArticlesByTopic($binTopicId);
		$pages = new CPagination($totalArticles);
		$pages->pageSize = $limit;
		$articles = APIZoneArticle::model()->getArticlesByTopic($binTopicId, $pages->limit, $pages->offset);

		// process output
		if(empty($articles)){
			throw new Exception(null, 400);
		}
		$arrArticles = array();
		foreach ($articles as $article) {
			$strId = IDHelper::uuidFromBinary($article->id, true);
			$object_id = null;
			$object_type = null;
			$user = ZoneUser::model()->getUserInfo($article->author->holder_id);
			if (!$article->namespace) {
				$object_id = IDHelper::uuidFromBinary($article->author->holder_id, true);
				$object_type = 'user';
			} else {
				$object_id = IDHelper::uuidFromBinary($article->namespace->holder_id, true);

				if (!$user->isGuest) {
					$object_type = 'user';
				} else {
					$object_type = 'topic';
				}
			}
			$arrArticles[] = array(
				'id'			=> $strId,
				'title'			=> $article->title,
				'alias'			=> $article->alias,
				'description'	=> $article->description,
				'content'	=> $article->content,
				'object_id'		=> $object_id,
				'object_type'	=> $object_type,
				'created'		=> date(DATE_ISO8601, strtotime($article->created)),
				'likes'			=> LikeStatistic::countLike($strId),
				'comments'		=> ZoneComment::model()->countComments($strId),
			);
		}

		// response
		$out = array(
			"items" => $arrArticles,
			"pages" => array(
				'total' => (int)$pages->itemCount,
				'limit' => (int)$pages->limit,
				'pages' => (int)$pages->currentPage + 1,
			),
		);
		Yii::app()->response->send(200, $out);
	}
}