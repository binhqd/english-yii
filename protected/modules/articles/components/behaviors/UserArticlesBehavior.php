<?php
/**
 * GNUserFollowingBehavior - This behavior is used to support an object user. When an user object has attached this behavior it'll have all methods of this behavior.
 * @author binhqd <binhqd@gmail.com>
 * @version 1.0
 * @created 2013-10-07 9:27 AM
 */
class UserArticlesBehavior extends CActiveRecordBehavior {
	/**
	 * This method is used to return an array of articles of current user
	 * @param unknown_type $limit
	 * @param unknown_type $offset
	 * @param unknown_type $order
	 * @return multitype:multitype:
	 */
	public function getArticles($limit = 6, $offset = 0, $order = 'created desc') {
		$user = $this->owner;
		
		$records = Yii::app()->db->createCommand()
		->select('article.id')
		->from(ZoneArticle::model()->tableName() . ' as article')
		->leftJoin(ZoneArticleAuthor::model()->tableName()." as author_mapped", "author_mapped.article_id=article.id")
		->leftJoin(ZoneArticleNamespace::model()->tableName()." as namespace_mapped", "namespace_mapped.article_id=article.id")
		->where('author_mapped.holder_id=:user_id and article.invalid = 0', array(':user_id' => $user->id))
		->order($order)
		->limit($limit)
		->offset($offset)
		->queryAll();
		
		$articles = array();
		foreach ($records as $item) {
			$articles[] = ZoneArticle::model()->get(IDHelper::uuidFromBinary($item['id'], true));
		}
		
		return $articles;
	}
	
	/**
	 * This method is used to get total articles of current user
	 */
	public function countArticles() {
		$user = $this->owner;
		
		$count = Yii::app()->db->createCommand()
		->select('count(*)')
		->from(ZoneArticle::model()->tableName() . ' as article')
		->leftJoin(ZoneArticleAuthor::model()->tableName()." as author_mapped", "author_mapped.article_id=article.id")
		->leftJoin(ZoneArticleNamespace::model()->tableName()." as namespace_mapped", "namespace_mapped.article_id=article.id")
		->where('author_mapped.holder_id=:user_id and article.invalid = 0', array(':user_id' => $user->id))
		->queryScalar();
		
		return $count;
	}
}