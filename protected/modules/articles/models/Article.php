<?php
Yii::import('greennet.modules.articles.models.GNArticle');
class Article extends GNArticle {
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function getName() {
		return __CLASS__;
	}
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'articles';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content', 'required','on'=>'create'),
		);
	}
	
// 	public function afterSave() {
// 		parent::afterSave();
		
// 		if ($this->isNewRecord) {
// 			$images = array();
// 			if (isset($_POST['images'])) {
// 				$images = $_POST['images'];
// 			}
			
// 			Yii::import('application.modules.resources.models.ZoneResourceImage');
			
// 			$cnt = 0;
// 			foreach ($images as $imageID) {
// 				$gallery = ZoneResourceImage::model()->findByPk(IDHelper::uuidToBinary($imageID));
// 				$gallery->object_id = IDHelper::uuidFromBinary($this->id, true);
// 				$gallery->save();
				
// 				if ($cnt == 0) {
// 					$obj = self::model()->findByPk($this->id);
					
// 					$obj->image = $gallery->image;
// 					$obj->save();
// 				}
				
// 				$cnt++;
// 			}
// 		}
// 	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return CMap::mergeArray(
			parent::relations(),
			array(
// 				'author' => array(self::HAS_ONE, 'ZoneArticleAuthor', 'article_id'),
// 				'namespace' => array(self::HAS_ONE, 'ZoneArticleNamespace', 'article_id'),
			)
		);
	}
	
	/**
	 * This method is used to get all article
	 * @author: Chu Tieu
	 */
	public function getAllArticle($dataStatus=null, $limit=50){
		$criteria = new CDbCriteria();
		if(isset($dataStatus)){
			$criteria->condition = 'data_status=:dataStatus';
			$criteria->params = array(
				':dataStatus' => $dataStatus
			);
		}
		$criteria->order = 'created desc';
		
		$pages = new CPagination(count(self::model()->findAll($criteria)));
		
		$pages->pageSize=$limit;
		$pages->applyLimit($criteria);
		
		return array(
			'model'	=> self::model()->findAll($criteria),
			'pages'		=> $pages
		);
	}
	
	public function getImages($binObjectId=null, $limit = 10, $offset = 0){
		return ZoneResourceAlbum::model()->getImages($binObjectId, $limit, $offset);
	}
	
	
	/**
	 * This method is used to convert an article object to an array
	 * @param bool $getPoster
	 * @return array $arrArticle
	 */
	public function toArray($options = array()) {
		$binArticleID = $this->id;
		// default options
		$_defaultOptions = array(
			
		);
		
		// merge options with default options
		$options = CMap::mergeArray($_defaultOptions, $options);
		
		$ret = $this->attributes;
		$ret['id'] = IDHelper::uuidFromBinary($ret['id'], true);
		
		return $ret;
	}
	
	/**
	 * This method is used to return an article as an array
	 * @param string $strArticleID
	 * @param array $options
	 * @return array $articleInfo
	 */
	public static function get($strArticleID, $options = array(
		
	)) {
		
		// default options
		$_defaultOptions = array(
			
		);
		
		// merge options with default options
		$options = CMap::mergeArray($_defaultOptions, $options);
		
		$article = self::model()->findByPk(IDHelper::uuidToBinary($strArticleID));
		
		if (empty($article)) return array();
		
		return $article->toArray($options);
	}
	
	/**
	 * This method is used to search articles
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function searchArticles($keyword = '', $limit = -1, $offset = -1, $timeBegin = null)
	{
		$criteria = new CDbCriteria();
		//$criteria->with = array('author' => array('joinType' => 'INNER JOIN'), 'namespace' => array('joinType' => 'INNER JOIN'));
		$criteria->together = true;
		$criteria->order = 'created desc';
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$criteria->condition = "t.title LIKE :keyword";
		$criteria->params = array(':keyword' => "%$keyword%");
		if (!empty($timeBegin)) {
			$criteria->condition .= " AND t.created<=:timeBegin";
			$criteria->params[':timeBegin'] = date('Y-m-d H:i:s', $timeBegin);
		}
		return self::model()->findAll($criteria);
	}
	
	/**
	 * This method is used to count articles
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function countArticles($keyword = '', $timeBegin = null)
	{
		$criteria = new CDbCriteria();
		//$criteria->with = array('author' => array('joinType' => 'INNER JOIN'), 'namespace' => array('joinType' => 'INNER JOIN'));
		$criteria->together = true;
		$criteria->condition = "t.title LIKE :keyword";
		$criteria->params = array(':keyword' => "%$keyword%");
		if (!empty($timeBegin)) {
			$criteria->condition .= " AND t.created<=:timeBegin";
			$criteria->params[':timeBegin'] = date('Y-m-d H:i:s', $timeBegin);
		}
		return self::model()->count($criteria);
	}
}