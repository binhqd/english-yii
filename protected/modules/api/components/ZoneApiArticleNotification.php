<?php 
Yii::import("application.components.notification.renderer.JLNotificationRenderer");
class ZoneApiArticleNotification extends JLNotificationRenderer {
	public function render(&$data) {
		$notifier = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($data['notifier_id']));
		if (empty($notifier)) return null;
		
		$article = ZoneArticle::model()->find('id=:id', array(
			':id'	=> IDHelper::uuidToBinary($data['article_id'])
		));
		if (empty($article))
			return null;
		
		$author = $article->author;
		$nodeInfo = $article->namespace;
		
		$strNodeId = IDHelper::uuidFromBinary($nodeInfo->holder_id, true);
		$node = (ZoneInstance::initNode($strNodeId) != null ) ? ZoneInstance::initNode($strNodeId)->node->getProperties() : null;
		
		$format = "";
		$message = "dummy";
		
		// $image = "";
		// $imageObj = ZoneResourceImage::getNamespaceImage($strNodeId);
		// if (!empty($imageObj)) {
		// 	$image = $imageObj->image;
		// }
		$image = ZoneResourceImage::model()->getPhotos($strNodeId,1);
		
		$this->_otherInfo = array(
			'node'	=> array(
				'id'	=> $strNodeId,
				'image'	=> $image
			)
		);
		if(empty($data['type'])) return null;
		switch ($data['type']) {
			case "postArticle":
				$data['defaultLink'] = ZoneRouter::createUrl('/article?article_id=' . $data['article_id']);
				
				$format = '%s created %s for %s';
				$userLink = "<a href='".ZoneRouter::createUrl("/profile/{$notifier->username}")."'>{$notifier->displayname}</a>";
				$articleLink = "<a href='".ZoneRouter::createUrl("/article?article_id=" . IDHelper::uuidFromBinary($article->id, true))."'>{$article->title}</a>";
				$nodeLink = "<a href='".ZoneRouter::createUrl("/zone/pages/detail?id={$node['zone_id']}")."'>{$node['name']}</a>";
				
				$strImage = "";
				if(!empty($article->image)){
					$strImage = '<span class="floatR"><span class="wd-fr-albimg"></span><span class="wd-fr-img"><img src="'.ZoneRouter::CDNUrl("/upload/gallery/fill/50-50/{$article->image}").'" width="47" height="47" class="wd-jr" alt=""></span></span>';
				}

				$message = array(
					'message' => $notifier->displayname. ' created new article: '. $article->title,
					'article_id'=> IDHelper::uuidFromBinary($article->id, true)
				);
				
				// $message = $strImage.'<div class="of_1">
				// 		<p class="wd_tt_mn"><span>'.$notifier->displayname.'</span> created new article: "'.$article->title.'" </p>
				// 		<p class="wd-posttime"><span class="wd-icon-16 wd-icon-create-toppic-ntf"></span><span class="timeago" data-title="'.$data['created'].'"></span></p>
				// 	</div>';
				
				// $message = sprintf($format, $userLink, $articleLink, $nodeLink, date("Y-m-d H:i:s"));
				
				return $message;
				break;
			
		}
	}
}