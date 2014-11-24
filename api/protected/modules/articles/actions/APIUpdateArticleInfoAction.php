<?php

Yii::import('api_app.components.*');

/**
 * Update Article Info action
 * @author truonghn <truonghn@gmail.com>
 * @version 2.0
 */
class APIUpdateArticleInfoAction extends CAction
{
	/**
	 * This method for run action
	 * @author truonghn<truonghn@greenglobal.vn>
	 * @param string $article_id article id
	 * @throws Exception user is not login.
	 * @return return list of articles of topic.
	 */
	public function run($article_id = null)
	{
		ApiAccess::allow("PUT");

		if (empty($article_id)) {
			throw new Exception(null, 400);
		}

		//get put data from client
		$title = Yii::app()->request->getPut('title');
		$content = Yii::app()->request->getPut('content');
		$description = Yii::app()->request->getPut('description');

		//check owner
		Yii::import('api_app.modules.articles.models.APIZoneArticle');
		$author = APIZoneArticle::model()->findByKey(IDHelper::uuidToBinary($article_id));
		if (currentUser()->hexID !== IDHelper::uuidFromBinary($author->author->holder_id, true)) {
			throw new Exception(null, 403);
		}

		// validate
		Yii::import('api_app.modules.articles.models.forms.APIUpdateArticleForm');
		$updateArticleForm = new APIUpdateArticleForm();
		$updateArticleForm->title = $title;
		$updateArticleForm->article_id = $article_id;
		$updateArticleForm->content = $content;
		if (!$updateArticleForm->validate()) {
			$errors = array_shift(array_values($updateArticleForm->errors));
			if (isset($errors[0])) {
				throw new Exception($errors[0], 400);
			}
		}

		//update article
		
		$article = APIZoneArticle::model()->updateArticle(IDHelper::uuidToBinary($article_id) , $title, $content, $description);
		if ($article) {
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
			$arrArticles = '';
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
		} else {
			$message = Yii::t("Youlook", 'Cannot update article.');
			throw new Exception($message, 500);
		}

		//response
		$out = array(
			'article' => $arrArticles
		);
		Yii::app()->response->send(200, $out);
	}
}