<?php

Yii::import('greennet.modules.articles.models.GNArticle');

/**
 * APIZoneArticle active record
 * @author huytbt <huytbt@gmail.com>
 * @version 2.0
 */
class APIZoneArticle extends ZoneArticle
{
	/**
	 * @var array Define keys for query
	 */
	public $queryKeys = array('id', 'alias');

	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Find by key
	 * @param mix $queryValue
	 * @param string $key
	 * @return APIZoneArticle
	 */
	public function findByKey($queryValue, $key = 'id')
	{
		if (!in_array($key, $this->queryKeys)) {
			return null;
		}
		$article = $this->findByAttributes(array(
			$key			=> $queryValue,
			'data_status'	=> self::DATA_STATUS_NORMAL,
		));
		return $article;
	}

	/**
	 * Count articles by author
	 * @param binary $user_id id of user
	 * @param string $search search string
	 * @return total article
	 */
	public function countArticlesByAuthor($user_id, $search = '')
	{
		$criteria = new CDbCriteria();
		$criteria->together = true;
		$criteria->with = array("author");
		$criteria->condition = 't.data_status = 1 AND author.holder_id = :user_id';
		$criteria->params = array(':user_id' => $user_id);
		if (!empty($search)) {
			$criteria->condition .= ' AND t.title = :search';
			$criteria->params[':search'] = $search;
		}
		$articles = ZoneArticle::model()->count($criteria);
		return $articles;
	}

	/**
	 * Get articles by author
	 * @param binary $user_id id of user
	 * @param string $search search string
	 * @param int $limit limit of article
	 * @param string $offset offset
	 * @return list articles
	 */
	public function getArticlesByAuthor($user_id, $limit = 10, $offset = -1, $search = '')
	{
		$criteria = new CDbCriteria();
		$criteria->together = true;
		$criteria->with = array("author", 'namespace');
		$criteria->condition = 't.data_status = 1 AND author.holder_id = :user_id';
		$criteria->params = array(':user_id' => $user_id);
		if (!empty($search)) {
			$criteria->condition .= ' AND t.title = :search';
			$criteria->params[':search'] = $search;
		}
		$criteria->order = "created desc";
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$articles = ZoneArticle::model()->findAll($criteria);
		return $articles;
	}

	/**
	 * Get articles by topic
	 * @param binary $user_id id of user
	 * @param string $search search string
	 * @param int $limit limit of article
	 * @param string $offset offset
	 * @return list articles
	 */
	public function getArticlesByTopic($topic_id, $limit = 10, $offset = -1, $search = '')
	{
		$criteria = new CDbCriteria();
		$criteria->together = true;
		$criteria->with = array("author", 'namespace');
		$criteria->condition = 't.data_status = 1 AND namespace.holder_id = :topic_id';
		$criteria->params = array(':topic_id' => $topic_id);
		if (!empty($search)) {
			$criteria->condition .= ' AND t.title = :search';
			$criteria->params[':search'] = $search;
		}
		$criteria->order = "created desc";
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$articles = ZoneArticle::model()->findAll($criteria);
		return $articles;
	}

	/**
	 * count article by topic
	 * @param binary $user_id id of user
	 * @param string $search search string
	 * @return total of article
	 */
	public function countArticlesByTopic($topic_id, $search = '')
	{
		$criteria = new CDbCriteria();
		$criteria->together = true;
		$criteria->with = array("author", 'namespace');
		$criteria->condition = 't.data_status = 1 AND namespace.holder_id = :topic_id';
		$criteria->params = array(':topic_id' => $topic_id);
		if (!empty($search)) {
			$criteria->condition .= ' AND t.title = :search';
			$criteria->params[':search'] = $search;
		}
		$articles = ZoneArticle::model()->count($criteria);
		return $articles;
	}

	/**
	 * Get article by Id
	 * @param binary $article_id id of article
	 * @return article
	 */
	public function getArticle($article_id)
	{
		$model = ZoneArticle::model()->with('author','namespace')->find(array(
			"condition"		=> "t.id = :id AND t.data_status = 1",
			"params"		=> array(":id" => $article_id))
		);
		return $model;
	}

	/**
	 * Update article
	 * @param binary $article_id id of article
	 * @param string $title title of the article
	 * @param string $content content of the article
	 * @param string $description description of the article
	 * @return article
	 */
	public function updateArticle($article_id, $title, $content, $description)
	{
		Yii::import('greennet.helpers.Sluggable');
		$model = APIZoneArticle::model()->findByKey($article_id);
		if (empty($model)) {
			$message = Yii::t("Youlook", 'The article is not found');
			throw new Exception($message, 400);
		}
		$model->title = $title;
		$model->alias = Sluggable::convertToLatin($model->title);
		$model->content = $content;
		$model->description = $description;
		if (!$model->save()) {
			return false;
		}
		return $model;
	}

	/**
	 * Create article
	 * @param binary $article_id id of article
	 * @param binary $authorId author of article
	 * @param binary $objectId object id of article
	 * @param string $title title of the article
	 * @param string $content content of the article
	 * @param string $description description of the article
	 * @return article
	 */
	function createArticle($title, $content, $description, $objectId, $authorId)
	{
		Yii::import('greennet.helpers.Sluggable');
		$objectId = IDHelper::uuidFromBinary($objectId);
		$binAuthorId = IDHelper::uuidFromBinary($authorId);
		$model = new ZoneArticle();
		$model->title = $title;
		$model->content = $content;
		$model->description = $description;
		$model->alias = Sluggable::convertToLatin($model->title);
		$model->created = date("Y-m-d H:i:s");
		if ($model->type == ZoneArticle::TYPEIMAGE) {
			$model->scenario = 'post';
		} else {
			$model->scenario = 'normal';
		}
		if (!$model->validate()) {
			$this->controller->out(400, array(
				'message' => Yii::t("Youlook", 'The data is invalid.'),
				'validationErrors' => $model->getErrors()
			));
		}
		set_time_limit(1000);

		if (!$model->save()) {
			throw new Exception(null, 500);
		}
		
		$node = ZoneInstanceRender::get($objectId);
		$author = new ZoneArticleAuthor();
		$author->article_id = $model->id;
		$author->holder_id = $authorId;
		if(!$author->save()){
			return false;
		}
		if ($node->zone_id != $binAuthorId) {
			$namespace = new ZoneArticleNamespace();
			$namespace->article_id = $model->id;
			$namespace->holder_id = IDHelper::uuidToBinary($node->zone_id);
			if (!$namespace->save()) {
				return false;
			}
		}
		return $model;
	}
}