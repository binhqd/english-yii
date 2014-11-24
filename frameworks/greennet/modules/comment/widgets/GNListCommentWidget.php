<?php
class GNListCommentWidget extends GNWidget {
	
	/**
	 * This property is used to set rating for comment
	 */
	public $suffix	= 'like this';
	
	/**
	 * This property is used to set rating for comment
	 */
	public $rating	= true;
	
	/**
	 * This property is used to set action rating for comment
	 */
	public $actionRate;
	
	/**
	 * This property is used to set rating for comment
	 */
	public $ratingType	= 'like';
	/**
	 * This property is used to preload comment.
	 */
	public	$preLoads	= 5;
	
	/**
	 * 
	 * This property is used to limit comment when read more 
	 */
	public	$limit	= 10;
	
	/**
	 * 
	 * This property is used to view word of comment
	 */
	public	$numberOfWord	= 200;
	
	/**
	 * 
	 * This property is used to get url delete of comment
	 */
	public	$deleteCommentUrl;
	
	/**
	 * 
	 * This property is used to set asset url
	 */
	private	$_assetUrl;
	
	/**
	 * 
	 * This property is used to get url view more
	 */
	public	$viewMoreUrl;
	
	/**
	 * 
	 * This property is used to get asset path
	 */
	public	$assetPath	= 'greennet.modules.comment.assets';
	
	/**
	 * 
	 * This property is used to call Callback.
	 */
	public	$deleteCallBack	= null;
	
	/**
	 * 
	 * This property is used to get template path
	 */
	public	$listCommentsTemplate	= 'greennet.modules.comment.widgets.views.comment-item';
	
	/**
	 * 
	 * This property is used to  get view of list comment path
	 */
	public	$viewListCommentPath	= 'greennet.modules.comment.widgets.views.list-form';
	
	/**
	 * 
	 * This property is used to set read more, default : true
	 */
	public	$readLessShow	= true;
	
	/**
	 * 
	 * This property is used to get text read more
	 */
	public	$readMoreText	= 'Read more';
	
	/**
	 * 
	 * This property is used to get text read less
	 */
	public	$readLessText	= 'Read less';
	
	/**
	 * 
	 * This property is used to get object id of comments
	 */
	public	$objectId;
	
	/**
	 * 
	 * This property is used to set total of comment
	 */
	public	$totalComments	= null;
	
	/**
	 * This property is used to load converse
	 */
	public $loadReverse	= true;
	
	/**
	 * This method is used to construct class
	 * @see CWidget::init()
	 */
	public function init() {
		GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
		));
		$this->_assetUrl = GNAssetHelper::setBase($this->assetPath);

		GNAssetHelper::scriptFile('jquery.core.count.time', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('jquery.lr.rating', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('core.comments', CClientScript::POS_HEAD);
		GNAssetHelper::cssFile('list-form');
	}

	/**
	 *
	 * @see run()
	 */
	public function run() {
		$user	= new GNUser();
		$user	= $user::model()->findAll();
		$ratingType	= $this->ratingType;
		$config		= array(
			'class'		=> 'greennet.components.GNRating.components.GNRatingComponent',
			'action'	=> $this->actionRate,
			'suffix'	=> $this->suffix,
		);
		$rating	= Yii::createComponent($config);
		
		$modelComment			= new $this->_model;
		$criteria				= new CDbCriteria();
		$criteria->condition	= 'object_id = :objectId';
		$criteria->params		= array(
			':objectId'	=> IDHelper::uuidToBinary($this->objectId)
		);
		$totalComments			= count($modelComment::model()->findAll($criteria));
		
		if ($this->preLoads>0) {
			$criteria->limit	= $this->preLoads;
			$criteria->offset	= $totalComments -$this->preLoads;
		}
		
		$comments		= $modelComment::model()->findAll($criteria);
		$out	= array();
		$result	= array();
		
		foreach ($comments as $comment) {
			$user	= $comment->user;
			if ($this->rating)
				$result	= $rating->rateRating($ratingType, $comment->id);

			$out[] = array(
				'currentUser'		=> currentUser()->id==-1?false:true,
				'comment_id'		=> IDHelper::uuidFromBinary($comment->id, true),
				'comment_content'	=> $comment->content,
				'user_id'			=> IDHelper::uuidFromBinary($user->id, true),
				//'avatar_url'		=> GNRouter::createUrl("/upload/user-photos/".IDHelper::uuidFromBinary($comment->user_id, true)."/fill/32-32/" . $user->profile->image),
				//'profile_url'		=> GNRouter::createUrl('/profile/' . IDHelper::uuidFromBinary($user->id, true)),
				'avatar_url'		=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-prn2/202852_1850869728_48581571_q.jpg',
				'profile_url'		=> '#',
				'username'			=> $user->username,
				'displayname'		=> $user->displayname,
				'comment_date'		=> $modelComment::model()->viewTime($comment->date),
				'isOwner'			=> currentUser()->id == $comment->user_id,
				'rate'				=> $result,
				'actionDelete'		=> $this->deleteCommentUrl,
			);
		}
		if ($this->loadReverse) {
			$out	= array_reverse($out);
		}
		$this->totalComments	= $totalComments;
		$this->render($this->viewListCommentPath, compact('out'));
	}
}
