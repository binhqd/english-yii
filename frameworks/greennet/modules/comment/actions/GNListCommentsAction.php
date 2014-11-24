<?php
class GNListCommentsAction extends GNAction {
	
	/**
	 * This property is used to set action rating for comment
	 */
	public $actionRate;
	
	/**
	 * This property is used to set rating for comment
	 */
	public $rating	= true;
	
	/**
	 * This property is used to set rating for comment
	 */
	public $suffix	= 'like this';
	
	/**
	 * This property is used to set rating for comment
	 */
	public $ratingType	= 'like';
	
	public function run () {
		$modelComment			= new $this->model;
		// Rating
		$ratingType	= $this->ratingType;
		$config		= array(
			'class'		=> 'greennet.components.GNRating.components.GNRatingComponent',
			'action'	=> $this->actionRate,
			'suffix'	=> $this->suffix,
		);
		$rating	= Yii::createComponent($config);
		
		$criteria				= new CDbCriteria();
		$criteria->condition	= 'object_id=:objectId';
		$criteria->params		= array(
			':objectId'	=> IDHelper::uuidToBinary($_POST['objectId'])
		);
		$criteria->offset		= $_POST['startList'];
		$criteria->limit		= $_POST['limit'];
		$comments	= $modelComment::model()->findAll($criteria);
		$show		= count($comments);
		
		$result	= array();
		
		if ($comments) {
			$out = array();		
			foreach ($comments as $comment) {
				$user = $comment->user;
				if ($this->rating) $result	= $rating->rateRating($ratingType, $comment->id);
				
				$out[] = array(
					'currentUser'		=> currentUser()->id==-1?false:true,
					'comment_id'		=> IDHelper::uuidFromBinary($comment->id, true),
					'comment_content'	=> $comment->content,
					'user_id'			=> IDHelper::uuidFromBinary($user->id, true),
// 					'avatar_url'		=> GNRouter::createUrl("/upload/user-photos/".IDHelper::uuidFromBinary($comment->user_id, true)."/fill/32-32/" . $user->profile->image),
// 					'profile_url'		=> GNRouter::createUrl('/profile/' . IDHelper::uuidFromBinary($user->id, true)),
					'avatar_url'		=> 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-prn2/202852_1850869728_48581571_q.jpg',
					'profile_url'		=> '#',
					'username'			=> $user->username,
					'displayname'		=> $user->displayname,
					'comment_date'		=> $modelComment::model()->viewTime($comment->date),
					'isOwner'			=> currentUser()->id == $comment->user_id,
					'rate'				=> $result,
				);
			}
			if ($_POST['loadReverse']) {
				$out	= array_reverse($out);
			}
			ajaxOut(array(
				'out'			=> $out,
				'show'			=> $show,
			));
		}
	} 
}