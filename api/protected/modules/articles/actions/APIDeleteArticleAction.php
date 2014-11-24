<?php

Yii::import('api_app.components.*');

/**
 * Delete Article action
 * @author truonghn <truonghn@gmail.com>
 * @version 2.0
 */
class APIDeleteArticleAction extends CAction
{
	/**
	 * Description for this method
	 * @author truonghn<truonghn@greenglobal.vn>
	 * @param string $article_id: id of an article which user want to delete.
	 * @throws throws exception The article is invalid.
	 * @throws throws exception The article could not be deleted.
	 * @return return response to client.
	 */
	public function run($article_id = '')
	{
		ApiAccess::allow("DELETE");
		if (empty($article_id)) {
			throw new Exception(null, 403);
		}

		//get article info
		Yii::import('api_app.modules.articles.models.APIZoneArticle');
		$article = APIZoneArticle::model()->getArticle(IDHelper::uuidToBinary($article_id));
		if (!$article) {
			throw new Exception(null, 400);
		}
		// Check owner
		if (currentUser()->hexID !== IDHelper::uuidFromBinary($article->author->holder_id, true)) {
			throw new Exception(null, 403);
		}
		if (!$article || $article->data_status == ZoneArticle::DATA_STATUS_DELETED) {
			Yii::app()->response->send(200, array(), "Deleted");
			Yii::app()->end();
		}

		if (!$article->cleanUp()) {
			throw new Exception("Cannot delete article", 500);
		}

		//response
		Yii::app()->response->send(200, array(), "Deleted");
	}
}