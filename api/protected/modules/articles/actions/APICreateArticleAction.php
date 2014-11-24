<?php

Yii::import('api_app.components.*');

/**
 * Create Article Action
 * @author truonghn
 * @version 1.0
 */
class APICreateArticleAction extends CAction
{
	/**
	 * Description for this method
	 * @author truonghn<truonghn@greenglobal.vn>
	 * @param $article_id: id of an article
	 * @throws throws exception Invalid Request.
	 * @throws throws exception your posted data in invalid.
	 * @return return an article.
	 */
	public function run()
	{
		ApiAccess::allow("POST");
		//get post data from client
		$title = Yii::app()->request->getPost('title');
		$content = Yii::app()->request->getPost('content');
		$description = Yii::app()->request->getPost('description');

		// validate
		Yii::import('api_app.modules.articles.models.forms.APICreateArticleForm');
		$createArticleForm = new APICreateArticleForm();
		$createArticleForm->title = $title;
		$createArticleForm->description = $description;
		$createArticleForm->content = $content;
		if (!$createArticleForm->validate()) {
			$errors = array_shift(array_values($createArticleForm->errors));
			if (isset($errors[0])) {
				throw new Exception($errors[0], 400);
			}
		}

		//saving article to database
		Yii::import('api_app.modules.articles.models.APIZoneArticle');
		$article = APIZoneArticle::model()->createArticle($title, $content, $description,IDHelper::uuidToBinary(currentUser()->hexID), IDHelper::uuidToBinary(currentUser()->hexID));
		if ($article !== false) {
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
			);
		} else {
			$message = Yii::t("Youlook", 'The article coult not be created!');
			throw new Exception($message, 400);
		}

		//response
		$out = array(
			'article' => $arrArticles
		);
		Yii::app()->response->send(200, $out);
		
	}
}