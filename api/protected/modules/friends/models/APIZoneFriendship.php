<?php
/**
 * APIZoneFriendship - This is the model class for extends ZoneFriendship
 * @author ngocnm <ngocnm@greenglobal.vn>
 * @version 1.0
 * @created 2014-08-06 11:00 AM
 */

Yii::import('application.modules.friends.models.ZoneFriendship');
Yii::import('application.modules.friends.models.ZoneFriendSuggestion');
class APIZoneFriendship extends ZoneFriendship
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
	 * This is method used to get count pending friends
	 * @author ngocnm
	 */
	public function countPendingFriends($binUserID)
	{
		$count = ZoneFriendship::model()->countPendingFriends($binUserID);
		return $count;
	}

	/**
	 * This is method used to get pending friends
	 *  @author ngocnm
	 */
	public function getPendingFriends($binUserID,$limit = 10, $offset = -1)
	{
		$currentUser = currentUser();
		$peoples = ZoneFriendship::model()->pendingFriends($binUserID,'', '', $limit, $offset);
		$friends = array();
		if(!empty($peoples)){
			foreach ($peoples as $user) {
				$userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($user['user_id']));
				$userInfo->setExtraInfo();
				$userInfoFormat = ZoneApiResourceFormat::formatData('user', $userInfo->toArray(true));
				if ($currentUser->id == $userInfo->id) {
					continue;
				}
				unset($userInfoFormat['email']);
				unset($userInfoFormat['location']);
				$data = array(
					'id'	=> $userInfoFormat['id'],
					'user'	=>	$userInfoFormat,
				);
				$friends[] = $data;
			}
		}
		return $friends;
	}

	/**
	 * This is method used to get count  friends
	 * @author ngocnm
	 */
	public function countFriends($id, $q = '')
	{
		$count = ZoneFriendship::model()->countFriends(IDHelper::uuidToBinary($id),$q);
		return intval($count);
	}

	/**
	 * This is method used to get friends
	 *  @author ngocnm
	 */
	public function getFriends($id, $q = '', $limit = 10, $offset = 0)
	{
		$friends = ZoneFriendship::model()->friends(IDHelper::uuidToBinary($id),'', '', $limit, $offset);
		$currentUser = currentUser();
		$results = array();
		foreach ($friends as $friend) {
			$userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($friend['user_id']));
			$userInfo->setExtraInfo();
			$userInfoFormat = ZoneApiResourceFormat::formatData('user', $userInfo->toArray(true));
			if ($currentUser->id == $userInfo->id) {
				continue;
			}
			unset($userInfoFormat['email']);
			unset($userInfoFormat['location']);
			$data = array(
				'id'	=> $userInfoFormat['id'],
				'user'	=>	$userInfoFormat,
			);
			$results[] = $data;
		}
		return $results;
	}

	/**
	 * This is method used to get mutual friends 
	 *  @author ngocnm
	 */
	public function countMutualFriends($binAUserID,$binBUserID)
	{
		$total = ZoneFriendship::model()->countMutualFriends($binAUserID,$binBUserID);
		return $total;
	}
	
	/**
	 * This is method used to get mutual friends 
	 *  @author ngocnm
	 */
	public function getMutualFriends($binAUserID,$binBUserID,$limit = 10, $offset = 0)
	{
		$peoples = ZoneFriendship::model()->mutualFriends($binAUserID,$binBUserID);
		$result = array();
		foreach ($peoples as $people) {
			$userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($people['user_id']));
			$userInfo->setExtraInfo();
			$userInfoFormat = ZoneApiResourceFormat::formatData('user', $userInfo->toArray(true));
			unset($userInfoFormat['email']);
			unset($userInfoFormat['location']);
			$data = array(
				'id'	=> $userInfoFormat['id'],
				'user'	=>	$userInfoFormat,
			);
			$result[] = $data;
		}
		return $result;
	}

	/**
	 * This is method used to get friends suggestion
	 *  @author ngocnm
	 */
	public function getPeopleSuggestions($user_id=null,$limit = 10, $offset = 0)
	{
		$binAUserID = currentUser()->id;
		$peoples = ZoneFriendSuggestion::model()->getPeople($binAUserID,$limit,$offset);
		
		$result = array();
		foreach ($peoples as $people) {
			$userInfo = ZoneUser::model()->getUserInfo(IDHelper::uuidToBinary($people['user_id']));
			$userInfo->setExtraInfo();
			$userInfoFormat = ZoneApiResourceFormat::formatData('user', $userInfo->toArray(true));
			unset($userInfoFormat['email']);
			unset($userInfoFormat['location']);
			$data = array(
				'id'	=> $userInfoFormat['id'],
				'user'	=>	$userInfoFormat,
			);
			$result[] = $data;
		}
		return $result;
	}
	/**
	 * This is method used to get count friends suggestion
	 *  @author ngocnm
	 */
	public function countPeopleSuggestions($user_id=null)
	{
		$total = ZoneFriendSuggestion::model()->countPeople($user_id);
		return $total;
	}

}