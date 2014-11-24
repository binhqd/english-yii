<?php
class GNAddCommentWidget extends GNWidget {
	
	private	$_assetUrl;
	public	$objectId;
	public	$addCommentUrl;
	public	$addCallBack			= null;
	public	$assetPath				= 'greennet.modules.comment.assets';
	public	$profile				= '#';
	public	$avatar					= 'https://fbcdn-profile-a.akamaihd.net/hprofile-ak-prn2/202852_1850869728_48581571_q.jpg';
	public	$viewAddCommentPath		= 'greennet.modules.comment.widgets.views.add-form';
	public	$viewAddForm			= 'greennet.modules.comment.widgets.views.view-add-form';
	public	$heightTextarea			= 21;
	public	$loadReverse	= true;
	
	/**
	 * 
	 */
	public function init() {
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));
		$this->_assetUrl = GNAssetHelper::setBase($this->assetPath);
		GNAssetHelper::cssFile('add-form');
		GNAssetHelper::scriptFile('jquery.autosize', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('core.comments', CClientScript::POS_HEAD);
	}
	/**
	 * 
	 */
	public function run () {
		if (!currentUser()->isGuest) {
			$this->render ($this->viewAddCommentPath);
		}
	}
}