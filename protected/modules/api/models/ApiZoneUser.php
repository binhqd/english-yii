<?php

/**
 * API ZoneUser model
 *
 * @author TienVV
 * @version 1.0
 */
class ApiZoneUser {

	protected $_instance = null;

	public function __construct($user) {
		if (!($user instanceof ZoneUser)) {
			switch (strlen($user)) {
				case 16:
					break;
				case 32:
					$_user = $user;
					$user = IDHelper::uuidToBinary($user);
					break;
				default :
					$_user = $user;
			}
			$this->_instance = ZoneUser::model()->getUserInfo($user);
			if (!$this->_instance || $this->_instance->id == -1) {
				if (!isset($_user)) {
					$_user = IDHelper::uuidFromBinary($user, true);
				}
				throw new Exception(UsersModule::t('The user "{user}" is not found.', array(
					'{user}' => $_user
				)), 404);
			}
		} else {
			$this->_instance = $user;
		}
	}

	public function __destruct() {
		if ($this->_instance) {
			$this->_instance->detachBehavior('UserFriend');
		}
	}

	public function __call($name, $arguments) {
		if (!method_exists($this->_instance, $name) ||
				!$this->_instance->asa('UserFriend')) {
			$this->_instance->attachBehavior('UserFriend'
					, 'application.modules.friends.components.behaviors.GNUserFriendBehavior');
		}
		return call_user_func_array(array(
			$this->_instance, $name), $arguments);
	}

	public function __isset($name) {
		return isset($this->_instance->{$name});
	}

	public function __set($name, $value) {
		$this->_instance->{$name} = $value;
	}

	public function __get($name) {
		if ($name == 'hexID' && !$this->_instance->hexID) {
			$this->_instance->hexID = IDHelper::uuidFromBinary($this->_instance->id, true);
		}
		return $this->_instance->{$name};
	}

	public function profile($focceGetBrithday = false) {
		if (!$this->_instance->profile) {
			return null;
		}
		$location = $this->_instance->profile['location'];
		if ($location) {
			try {
				if (preg_match('/^[a-z0-9]{32}$/i', $location)) {
					$Node = ZoneInstanceRender::get($location);
					$location = $Node->node->name;
				}
			} catch (Exception $e) {
				$location = '';
			}
		}
		$birthday = '';
		if ($this->_instance->profile['birth']) {
			$birthday = strtotime($this->_instance->profile['birth']);
		} elseif ($focceGetBrithday) {
			$prop = ZoneNodeRender::properties($this->_instance->hexID);
			if (!empty($prop['/people/person/date_of_birth']['items'])) {
				$birthday = strtotime($prop['/people/person/date_of_birth']['items']);
			}
		}
		if ($birthday) {
			$birthday = date('F d', $birthday);
		}
		return array(
			'location' => $location,
			'gender' => intval($this->_instance->profile['gender']),
			'image' => $this->_instance->profile['image'],
			'description' => (string) $this->_instance->profile['status_text'],
			'birthday' => (string) $birthday
		);
	}

	public function accessToken() {
		return ApiAccessToken::generate();
	}

}
