<?php

Yii::import('api_app.components.*');

/**
 * Get Article Info action
 * @author truonghn <truonghn@gmail.com>
 * @version 2.0
 */
class APIGetArticleInfoAction extends CAction
{
	/**
	 * Description for this method
	 * @author truonghn<truonghn@greenglobal.vn>
	 * @param $article_id: id of an article
	 * @throws throws exception user is not login.
	 * @return return an article.
	 */
	public function run($article_id = '')
	{
		ApiAccess::allow("GET");

		if (empty($article_id)) {
			throw new Exception(null, 400);
		}

		Yii::import('api_app.modules.articles.models.APIZoneArticle');
		$key = Yii::app()->request->getParam('key', 'id');
		$article = APIZoneArticle::model()->findByKey(IDHelper::uuidToBinary($article_id), $key);
		if (empty($article)) {
			throw new Exception(null, 400);
		}

		//process output
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
		$userInfo = ZoneApiResourceFormat::formatData('user', $user->toArray(true));
		unset($userInfo['email']);
		unset($userInfo['location']);
		unset($userInfo['stats']);
		unset($userInfo['birthday']);
		$arrArticles = array(
			'id'			=> $strId,
			'title'			=> $article->title,
			'alias'			=> $article->alias,
			'content' 		=> $article->content,
			'description'	=> $article->description,
			'object_id'		=> $object_id,
			'object_type'	=> $object_type,
			'created'		=> date(DATE_ISO8601, strtotime($article->created)),
			'likes'			=> LikeStatistic::countLike($strId),
			'comments'		=> ZoneComment::model()->countComments($strId),
			'user'			=> $userInfo,
		);

		//response
		$out = array(
			'article' => $arrArticles,
		);
		Yii::app()->response->send(200, $out, "");
	}
}