<?php
Yii::import('greennet.modules.articles.models.GNArticle');
class ZoneArticle extends GNArticle {
	const TYPEIMAGE = 1;
	const TYPEARTICLE = 0;
	const DATA_STATUS_NORMAL = 1;
	const DATA_STATUS_DELETED = 0;
	
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
		return 'zone_articles';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content', 'required','on'=>'normal'),
			array('title', 'required','on'=>'post'),
			array(' ', 'required','on'=>'profile'),
		);
	}
	
	public function afterSave() {
		parent::afterSave();
		
		if ($this->isNewRecord) {
			$images = array();
			if (isset($_POST['images'])) {
				$images = $_POST['images'];
			}
			
			Yii::import('application.modules.resources.models.ZoneResourceImage');
			
			$cnt = 0;
			foreach ($images as $imageID) {
				$gallery = ZoneResourceImage::model()->findByPk(IDHelper::uuidToBinary($imageID));
				$gallery->object_id = IDHelper::uuidFromBinary($this->id, true);
				$gallery->save();
				
				if ($cnt == 0) {
					$obj = self::model()->findByPk($this->id);
					
					$obj->image = $gallery->image;
					$obj->save();
				}
				
				$cnt++;
			}
		}
	}
	
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
				'author' => array(self::HAS_ONE, 'ZoneArticleAuthor', 'article_id'),
				'namespace' => array(self::HAS_ONE, 'ZoneArticleNamespace', 'article_id'),
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
	
	/**
	 * This method used get total articles for user
	 * Author: thinhpq
	 */
	public function countArticlesByObject($binNodeId = null)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array("namespace");
		$criteria->together = true;
		$criteria->condition = 'namespace.holder_id=:holder_id and t.data_status=:data_status';
		$criteria->params = array(
			':holder_id'	=> $binNodeId,
			':data_status'	=> self::DATA_STATUS_NORMAL,
		);

		$count = self::count($criteria);

		return $count;
	}
	
	/**
	 * This method used get top article for node
	 * Author: thinhpq
	 */
	public function topArticles($binNodeId = null,$limit = 5,$toArray = true,$size = "96-96"){
		$data = ZoneArticle::model()->getArticlesByObject(null,$binNodeId,null,$limit,array(
			'type'=>ZoneArticle::TYPEARTICLE
		));
		if($toArray){
			$items = array();
			foreach($data['data'] as $key=>$article){
				$items[$key] = $article->attributes;
				$images = $article->getImages($article->id,1);
				
				$items[$key]['avatar'] = null;
				$items[$key]['id'] = IDHelper::uuidFromBinary($article->id,true);
				$items[$key]['less_des'] = JLStringHelper::char_limiter_word(strip_tags($article->content,""),200);
				if(!empty($images[0])){
					$image = $images[0];
					$items[$key]['avatar'] = ZoneRouter::CDNUrl("/upload/gallery/fill/{$size}/{$image['photo']['image']}?album_id=".IDHelper::uuidFromBinary($image['photo']['album_id'],true));
				}
				$items[$key]['author'] = array('avatar'=>null,'username'=>null);
				if(!empty($article->author->user)){
					$avatar = null;
					if(!empty($article->author->user->profile))
						$avatar = ZoneRouter::CDNUrl('/')."/upload/user-photos/".IDHelper::uuidFromBinary($article->author->user->id,true)."/fill/40-40/{$article->author->user->profile->image}";
					$items[$key]['author']['avatar'] = $avatar;
					$items[$key]['author']['username'] = $article->author->user->username;
					$items[$key]['author']['displayname'] = $article->author->user->displayname;
					$items[$key]['author']['id'] = IDHelper::uuidFromBinary($article->author->user->id,true);
				}
				
			}
			return $items;
		}
		return ZoneArticle::model()->getArticlesByObject(null,$binNodeId,null,$limit,array(
			'type'=>ZoneArticle::TYPEARTICLE
		));
	}
	/**
	 * This method used get article for node or user
	 * Author: thinhpq
	 * TODO: hàm này chuối quá, cần xem xét chỉnh sửa (huytbt)
	 */
	public function getArticlesByObject($binAuthorId=null,$binNodeId = null,$keyword =null, $limit=10,$sorts = array()){
		$criteria=new CDbCriteria();
		$criteria->with = array("author","namespace");
		$criteria->together = true;
		$criteria->order = "created desc";
		
		if($binAuthorId != null && $binNodeId!=null){
			$criteria->addCondition('author.holder_id = :author_holder_id','OR');
			$criteria->addCondition('namespace.holder_id = :namespace_holder_id','OR');
			
			$criteria->params = array(':author_holder_id' => $binAuthorId,
				':namespace_holder_id' => $binNodeId,
				
			);
		}else{
			if($binAuthorId!=null) $criteria->compare('author.holder_id',$binAuthorId);
			if($binNodeId!=null) $criteria->compare('namespace.holder_id',$binNodeId);
		}
		if($keyword!=null){
			$criteria->addSearchCondition("t.title",$keyword,true);
			// $criteria->addSearchCondition("t.content",$keyword,true,'AND');
		}
		
		if(!empty($sorts)){
			foreach($sorts as $key=>$value){
				$criteria->compare($key,$value); 
			}
		}
		$criteria->compare('t.data_status', self::DATA_STATUS_NORMAL);
		// $criteria->condition = "({$criteria->condition}) AND data_status=" . self::DATA_STATUS_NORMAL;

		if($limit != null){
			$pages = new CPagination(count(ZoneArticle::model()->findAll($criteria)));
			$pages->pageSize = $limit;
			$pages->applyLimit($criteria);
		}
		
		
		
		return array(
			'pagination'=>!empty($pages) ? $pages : null,
			'data'=>ZoneArticle::model()->findAll($criteria)
		);
	}
	public function relatedArticles($binNodeId = null,$binArticleId = null){
		$criteria=new CDbCriteria();
		$criteria->with = array("author","namespace");
		$criteria->together = true;
		$criteria->limit = 5;
		$criteria->order = "created desc";
		$criteria->compare('namespace.holder_id',$binNodeId);
		$criteria->addNotInCondition('t.id',array($binArticleId)); 
		
		return ZoneArticle::model()->findAll($criteria);
		
	}
	public function getImages($binObjectId=null, $limit = 10, $offset = 0){
		return ZoneResourceAlbum::model()->getImages($binObjectId, $limit, $offset);
	}
	
	public function countArticlesByUserID($binUserID) {
		$strUserID = IDHelper::uuidFromBinary($binUserID, true);
		
		$command = Yii::app()->db->createCommand()
		->select('count(*)')
		->from(self::model()->tableName() . ' as article')
		->where('article.invalid = 0')
		->join(ZoneArticleAuthor::model()->tableName()." as holder", "holder.article_id=article.id and holder_id=:userID");
		
		$command->bindValues(array(
			':userID'		=> $binUserID
		));
		
		$count = $command->queryScalar();
		
		return abs((int)$count);
	}

	/**
	 * This method is used to check article is owner or not?
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function getIsOwner()
	{
		if (currentUser()->isAdmin)
			return true;

		if (!empty($this->author) && $this->author->holder_id == currentUser()->id)
			return true;

		return false;
	}

	/**
	 * This method is used to clean up article
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function cleanUp()
	{
		if ($this->isNewRecord)
			throw new Exception('This method is only support for an instance.');

		$this->data_status = self::DATA_STATUS_DELETED;
		if (!$this->save())
			throw new Exception('Cannot clean up data.');

		// TODO: clean up comment, activity, notification

		return true;
	}
	
	/**
	 * This method is used hide article
	 * @author: Chu Tieu
	 */
	public function hideArticle($binID=null)
	{
		if(!empty($binID)){
			$model = ZoneArticle::model()->findByPk($binID);
			if(!empty($model)){
				$model->data_status = self::DATA_STATUS_DELETED;
				if($model->save()){
					return true;
				} else return false;
			} else return false;
		}
		return false;
	}
	
	/**
	 * This method is used restore article
	 * @author: Chu Tieu
	 */
	public function restoreArticle($binID=null)
	{
		if(!empty($binID)){
			$model = ZoneArticle::model()->findByPk($binID);
			if(!empty($model)){
				$model->data_status = self::DATA_STATUS_NORMAL;
				if($model->save()){
					return true;
				} else return false;
			} else return false;
		}
		return false;
	}
	
	/**
	 * This method is used to convert an article object to an array
	 * @param bool $getPoster
	 * @return array $arrArticle
	 */
	public function toArray($options = array(
		'loadPoster'	=> true,
		'loadLikes' => true, 
		'loadComments' => 5
	)) {
		$binArticleID = $this->id;
		// default options
		$_defaultOptions = array(
			'loadPoster'	=> true,
			'loadLikes' => true,
			'loadComments' => 5
		);
		
		// merge options with default options
		$options = CMap::mergeArray($_defaultOptions, $options);
		
		$ret = $this->attributes;
		$ret['id'] = IDHelper::uuidFromBinary($ret['id'], true);
		
		$poster = array();
		if ($options['loadPoster']) {
			$poster = $this->poster;
			
			$poster = ZoneUser::model()->cache->get($poster['id']);
			if (empty($poster)) {
				$poster = ZoneUser::model()->toArray(true);
			}
		}
		$ret['author'] = $poster;
		
		$node = null;
		if(isset($this->namespace)){
			$ret['namespace'] = array();
			$ret['namespace']['id'] = isset($this->namespace->id)?IDHelper::uuidFromBinary($this->namespace->id,true):null;
			$ret['namespace']['article_id'] = isset($this->namespace->article_id)?IDHelper::uuidFromBinary($this->namespace->article_id, true):null;
			$ret['namespace']['holder_id'] = isset($this->namespace->holder_id)?IDHelper::uuidFromBinary($this->namespace->holder_id,true):null;
			
			$node = ZoneInstanceRender::get($ret['namespace']['holder_id']);
			if (empty($node)) {
				$receiver = ZoneUser::model()->get($article['namespace']['holder_id']);
				if (empty($receiver)) {
					// set article & activity as invalid and continue
					$out = array(
						'error'		=> true,
						'message'	=> "Invalid article"
					);
					ajaxOut($out);
				} else {
					$ret['target'] = 'user';
					
				}
			} else {
				$ret['target'] = 'node';
				$node = $node->toArray();
				$receiver = null;
			}
		} else {
			$ret['target'] = 'user';
			$receiver = $ret['author'];
		}
		
		$ret['node'] = $node;
		$ret['receiver'] = $receiver;
		
		// Load object
		
		// ========================================================
		
		$like = array();
		if ($options['loadLikes']) {
			// Get like information
			Yii::import('application.modules.like.models.LikeStatistic');
			Yii::import('application.modules.like.models.LikeObject');
		
			$statistic = LikeStatistic::model()->getLikeStatistic($binArticleID);
		
			if(!empty($statistic)){
				$like = LikeObject::model()->getLikeInfo($binArticleID, currentUser()->id);
				if (isset($like['you_liked']) && $like['you_liked']) {
					$like['liked'] = true;
				} else {
					$like['liked'] = false;
				}
			} else {
				$like	= array(
					'you_liked'		=> false,
					'classRating'	=> '',
					'value'			=> LikeObject::VALUE_RATING_LIKE,
					'count'			=> 0,
					'text'			=> 'Like',
					'object_id'		=> $ret['id'],
					'type'			=> 'like'
				);
			}
		}
		$ret['like'] = $like;
		
		$comments = array();
		if ($options['loadComments']) {
			// get comments
		
			$comments = ZoneComment::model()->getComments($ret['id'], 0, $options['loadComments'], true);
		
		}
		$ret['comments'] = $comments;
		
		return $ret;
	}
	
	/**
	 * This method is used to return poster of an article
	 * @return array $poster
	 */
	public function getPoster() {
		$holder = ZoneArticleAuthor::model()->find('article_id=:article_id', array(
			':article_id'	=> $this->id
		));
		
		if (empty($holder)) {
			Yii::log('There is no author for this article: ' . IDHelper::uuidFromBinary($this->id, true));
			$poster = array();
		} else {
			$poster = ZoneUser::model()->get(IDHelper::uuidFromBinary($holder->holder_id));
		}
		
		return $poster;
	}
	
	/**
	 * This method is used to return an article as an array
	 * @param string $strArticleID
	 * @param array $options
	 * @return array $articleInfo
	 */
	public static function get($strArticleID, $options = array(
		'loadPoster'	=> true,
		'loadLikes'		=> true, 
		'loadComments'	=> 5
	)) {
		
		// default options
		$_defaultOptions = array(
			'loadPoster'	=> true,
			'loadLikes' => true,
			'loadComments' => 5
		);
		
		// merge options with default options
		$options = CMap::mergeArray($_defaultOptions, $options);
		
		$article = self::model()->findByPk(IDHelper::uuidToBinary($strArticleID));
		
		if (empty($article)) return array();
		
		return $article->toArray($options);
	}
	
	public function setInvalid() {
		$this->invalid = 1;
		$this->save();
	}

	/**
	 * This method is used to search articles
	 * @author huytbt <huytbt@gmail.com>
	 */
	public function searchArticles($keyword = '', $limit = -1, $offset = -1, $timeBegin = null)
	{
		$criteria = new CDbCriteria();
		$criteria->with = array('author' => array('joinType' => 'INNER JOIN'), 'namespace' => array('joinType' => 'INNER JOIN'));
		$criteria->together = true;
		$criteria->order = 'score desc, created desc';
		$criteria->limit = $limit;
		$criteria->offset = $offset;
		$criteria->condition = "t.title LIKE :keyword AND data_status=" . ZoneArticle::DATA_STATUS_NORMAL;
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
		$criteria->with = array('author' => array('joinType' => 'INNER JOIN'), 'namespace' => array('joinType' => 'INNER JOIN'));
		$criteria->together = true;
		$criteria->condition = "t.title LIKE :keyword AND data_status=" . ZoneArticle::DATA_STATUS_NORMAL;
		$criteria->params = array(':keyword' => "%$keyword%");
		if (!empty($timeBegin)) {
			$criteria->condition .= " AND t.created<=:timeBegin";
			$criteria->params[':timeBegin'] = date('Y-m-d H:i:s', $timeBegin);
		}
		return self::model()->count($criteria);
	}
	
	public static function createUrl($article) {
		$zonetype = 'node';
		if ($article['target'] == 'node' && !empty($article['node'])) {
			$zonetype = 'node';
			$url = ZoneRouter::createUrl("/{$zonetype}?id={$article['node']['zone_id']}&action=article-detail&a_id={$article['id']}");
		} else {
			$url = ZoneRouter::createUrl("/user/{$article['author']['username']}?action=article-detail&a_id={$article['id']}");
		}
		
		return $url;
	}

}