<?php
/**
 * APIZoneFollowing - This is the model class for extends ZoneFriendship
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 * @created 2014-08-06 11:00 AM
 */

Yii::import('application.modules.followings.models.ZoneFollowing');
class APIZoneFollowing extends ZoneFollowing
{

	/**
	 * Returns the static model of the specified AR class.
	 * @param $className Class name of model
	 * @return Friendships the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * This is method used to get list following by user
	 *  @author ngocnm
	 */
	public function getListFollowingByUser($user_id=null,$limit = 10, $offset = 0)
	{
		// get all followings node id
		$descriptionAlias = '/common/topic/description';
		$followings = ZoneFollowing::model()->followingsByObjectType($user_id,'object','','',$limit,$offset);
		$userInfo = ZoneUser::model()->getUserInfo($user_id);
		$userInfo->attachBehavior('CurrentUserFollowing'
				, 'application.modules.followings.components.behaviors.GNUserFollowingBehavior');
		$result = array();
		foreach ($followings as $item) {
			$nodeObject = ZoneInstanceRender::get($item['object_id']);
			$node = array_merge(array(
				'description' => '',
				'owner' => array()
					), $nodeObject->toArray());
			$prop = ZoneNodeRender::properties($item['object_id'], $descriptionAlias);
			if (isset($prop[$descriptionAlias]['items'])) {
				$node['description'] = $prop[$descriptionAlias]['items'];
			}
			$binObjectID = IDHelper::uuidToBinary($item['object_id']);
			// total followers
			$countFollowers = ZoneFollowing::model()->countFollowers($binObjectID);
			$node['followers'] = $countFollowers;
			$creatorID = $nodeObject->getCreatorID();
			// images
			$resourceImage = ZoneInstanceRender::getResourceImage($node);
			$image = "";
			if(!empty($resourceImage['image'])){
				$image = $resourceImage['image']['photo']['image'];
			}
			$data= array(
				"zone_id"		=> $resourceImage['zone_id'],
				"name"			=> $resourceImage['name'],
				"label"			=> $resourceImage['notable'],
				"image"			=> $image,
				"description"	=> $resourceImage['description'],
				"followers"		=> $resourceImage['followers'],
			);
			$result[] = $data;
		}
		return $result;
	}
	/**
	 * This is method used to get count friends suggestion
	 *  @author ngocnm
	 */
	// public function countPeopleSuggestions($user_id=null)
	// {
	// 	$total = ZoneFriendSuggestion::model()->countPeople($user_id);
	// 	return $total;
	// }

}