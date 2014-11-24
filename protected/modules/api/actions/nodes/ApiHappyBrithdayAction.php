<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiHappyBrithdayAction extends GNAction {

	protected static $_userTimeZone = null;

	/**
	 * This method is used to run action
	 */
	public function run($day = '', $month = '') {
		$Paginate = $this->controller->paginate(0);
		$data = $this->get($day, $month, $Paginate->limit, $Paginate->offset);
		$data['limit'] = $Paginate->limit;
		$data['page'] = $Paginate->currentPage + 1;
		
		$this->controller->out(200, $data);
	}

	public function getUserTimeZone() {
		if (!static::$_userTimeZone) {
			if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
				static::$_userTimeZone = new DateTimeZone('Asia/Phnom_Penh');
			} else {
				$cacheKey = '_ip_' . $_SERVER['REMOTE_ADDR'];
				$timezone = ZoneBaseConnection::cache($cacheKey);
				if (!$timezone) {
					$url = 'http://smart-ip.net/geoip-json/' . $_SERVER['REMOTE_ADDR'];
					list(, $response) = InstanceCrawler::transport($url);
					$data = @json_decode($response);
					if (!empty($data->timezone)) {
						$timezone = ZoneBaseConnection::cache($cacheKey, $data->timezone);
					} elseif (!empty($data->countryCode) && $data->countryCode != 'US') {
						// xử lý geo timezone ở đây
					}
				}
				if ($timezone) {
					static::$_userTimeZone = new DateTimeZone($timezone);
				} else {
					static::$_userTimeZone = new DateTimeZone('America/Los_Angeles');
				}
			}
		}
		return static::$_userTimeZone;
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get($day = '', $month = '', $limit = 20, $offset = 0) {
		if (empty($day) || empty($month)) {
			$DateTime = new DateTime('now', $this->userTimeZone);
		} else {
			$DateTime = new DateTime(date('Y') . '-' . $month . '-' . $day);
		}
		$day = intval($DateTime->format('d'));
		$month = intval($DateTime->format('m'));
		$cacheKey = "happy_birthday_{$day}_{$month}.{$limit}_{$offset}";
		$data = ZoneBaseConnection::cache($cacheKey, null, '+30 day');

		if (!is_array($data)) {
			$data = ZoneInstance::searchByIndex(array(
						'/people/person/day_of_birth' => $day,
						'/people/person/month_of_birth' => $month,
						'not' => array(
							'type' => '/people/deceased_person'
						)), $limit, $offset);
			ZoneBaseConnection::cache($cacheKey, $data, '+30 day');
		}
		foreach ($data as &$n) {
			$n = ZoneInstanceRender::getResourceImage($n);
		}
		return array(
			'data' => $data,
			'day' => $day,
			'month' => $month,
			'timestamp' => $DateTime->getTimestamp()
		);
	}

}