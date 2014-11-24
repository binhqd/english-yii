<?php
/**
 * This controller is used to delete article
 * @author huytbt <huytbt@gmail.com>
 */
class DeleteController extends ZoneController
{
	public function allowedActions()
	{
		return '*';
	}

	/**
	 * This action is used to delete article
	 */
	public function actionIndex($article_id)
	{
		$articleId = IDHelper::uuidToBinary($article_id);
		$article = ZoneArticle::model()->findByPk($articleId); // @TODO: write method for get article
		if ($article) {
			if ($article->cleanUp()) {
				ajaxOut(array(
					'error'	=> false,
				));
			} else {
				ajaxOut(array(
					'error'	=> true,
				));
			}
		}
	}
}