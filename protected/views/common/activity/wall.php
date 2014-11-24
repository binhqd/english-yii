<?php
if (!empty($activities)) {
	foreach ($activities as $key=>$activity) {
		if ($activity->object_type == 'Node' && strtolower($activity->type) == "follow") {
			$this->widget('widgets.activity.FollowNodeWidget', array(
				'activity'=>$activity
			));
		} elseif ($activity->object_type == 'Article' && strtolower($activity->type) == "like") {
			$this->widget('widgets.activity.LikeArticleWidget', array(
				'activity'=>$activity
			));
		} elseif ($activity->object_type == 'Album' && strtolower($activity->type) == "like") {
			$this->widget('widgets.activity.LikeAlbumWidget', array(
				'binAlbumID'	=> $activity->object_id,
				'activity'=>$activity
			));
		} elseif ($activity->object_type == 'Status') {
			$status = ZoneStatus::model()->findByPk($activity->object_id);

			$this->renderPartial('//common/status/index',array(
				'activity'=>$activity,
				'status'=>$status,
				'ownerWall'=>!empty($user) ? $user : null,
				'key'=>$key
			));
		} elseif ($activity->object_type == 'Album') {
			$this->widget('widgets.activity.AlbumWidget', array(
				'activity'=>$activity,
				'binAlbumID'	=> $activity->object_id
			));
		} elseif (strtolower($activity->type) == "post"){
			$article = ZoneArticle::model()->findByPk($activity->object_id);
			if (!empty($article)) {
				$this->renderPartial('//common/article',array(
					'activity'=>$activity,
					'article'=>$article,
					'key'=>$key,
					
				));
			} else {
				//dump(IDHelper::uuidFromBinary($activity->id,true),false);
			}
		} else {
			// echo 2;
		}
		
		
	}
}
?>