<?php
Yii::import('application.modules.articles.models.*');
class ZoneArticleNamespace extends GNActiveRecord {
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zone_articles_namespaces';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
					
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'article' => array(self::BELONGS_TO, 'ZoneArticle', 'article_id'),
			'user' => array(self::BELONGS_TO, 'ZoneUser', 'holder_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			
		);
	}
	
	public function getArticles($binNamespaceID, $offset = 0, $limit = 10) {
// 		$criteria=new CDbCriteria;
		
// 		$criteria->alias = 't';
// 		$criteria->select = "orig.title, orig.description, orig.image";
		
// 		$criteria->join = "
// 		LEFT JOIN ".ZoneArticle::model()->tableName()." as orig ON orig.id=t.article_id
// 		";
		
// 		$results = $this->findAll($criteria);
// 		debug($results[0]);
		
		$results = Yii::app()->db->createCommand()
		->select('hex(orig.id) as id, orig.title, orig.description, orig.image, user.displayname as author, hex(user.id) as author_id, orig.created')
		->from($this->tableName() . ' as t')
		->leftJoin(ZoneArticle::model()->tableName()." as orig", "orig.id=t.article_id")
		->join(ZoneArticleAuthor::model()->tableName()." as author_mapped", "author_mapped.article_id=t.article_id")
		->join(GNUser::model()->tableName()." as user", "author_mapped.holder_id=user.id")
		->where('t.holder_id=:id', array(':id'=>$binNamespaceID))
		->order('orig.created DESC')
		->queryAll();
		
		//debug($results);
		
		return $results;
	}
	
	public function countByNamespace($namespaceID) {
		$cnt = 0;
		if (isset($namespaceID)) {
			$cnt = $this->count('holder_id=:holder_id', array(':holder_id' => $namespaceID));
		}
	
		return $cnt;
	}
	

	public static function nodeToolBar($strNodeId = null , $refer = null){
		
		if($strNodeId == null) return array();

		$objNode = ZoneInstanceRender::get(@$strNodeId);

		// $objNode = ZoneInstanceRender::get(@$_GET['id']);
		$properties = $objNode->properties();
		$labels = $relateds = array();
		foreach ($properties as $row) {
			$Property = ZoneProperty::initNode($row[0]);
			$Type = $Property->getExpected();
			if ($Type->isRelation() && !$Type->isEnumeration() && !empty($row[1])) {
				$labels[$Property->zone_id] = $Property->node->label ? $Property->node->label : $Property->node->name;
			}
		}
		if (!empty($_GET['label'])) {
			if (!isset($labels[$_GET['label']]) || empty($properties[$_GET['label']][1])) {
				$this->redirect('/zone/pages/detail?id=' . $objNode->zone_id);
			}
			$Property = ZoneProperty::initNode($properties[$_GET['label']][0]);
			foreach ($properties[$_GET['label']][1] as $val) {
				if (($info = ZoneInstanceRender::featureNode($Property, $objNode->zone_id, $val['node']))) {
					$relateds[] = ZoneInstanceRender::getResourceImage($info);
				}
			}
		} else {
			$_labels = ZoneInstanceRender::labels($objNode->zone_id);
			$relateds = ZoneInstanceRender::relateds($objNode->zone_id, array_keys($_labels));
		}
		
		
		
		return compact('relateds', 'labels') +
				array('node' => ZoneInstanceRender::getResourceImage($objNode->toArray()),
					'refer' => !empty($objRefer) ? ZoneInstanceRender::getResourceImage($objRefer->toArray()) : null);
		
		
	}
	/**
	 * This method used get node info 
	 * Author: thinhpq
	 */
	public static function nodeInfo($strNodeId = null){
		if($strNodeId == null) return array();
		// dump(ZoneInstance::initNode($strNodeId),false);
		return (ZoneInstance::initNode($strNodeId) != null ) ? ZoneInstance::initNode($strNodeId)->toArray() : null;
	}

	public function afterSave()
	{
		parent::afterSave();
		if ($this->isNewRecord && !empty($this->article)) {
			/**
			 * index article for search (landing page)
			 * @author huytbt
			 */
			Yii::import('application.modules.landingpage.models.*');
			try {
				$article = $this->article;
				$object = $this;
				if (!empty($object))
					$index = ZoneSearchArticle::model()->indexSearch($article->id, $article->title, strtotime($article->created), $article->score, $object->holder_id);
			} catch (Exception $ex) {
				Yii::log($ex->getMessage(), 'error', 'Search: index failure article (id:'.IDHelper::uuidFromBinary($article->id,true).')');
			}
			/* end (index) */
		}
	}
	
}