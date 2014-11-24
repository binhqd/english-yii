<?php

/**
 * @author tienvv <tienvv.it@gmail.com>
 * @version 1.0
 * 
 */
class ApiNodeCategoryAction extends GNAction {

	/**
	 * This method is used to run action
	 */
	public function run() {
		$this->controller->out(200, array(
			'data' =>  $this->get()));
	}

	/**
	 * This action is used to get list of videos of an user
	 */
	public function get() {
		$keys = array(
			'people' => '/people/profession',
			'movie' => '/film/film_genre',
			'culture' => '/cultures/culture_category',
			'itv' => '/itv/channel_category'
		);
		$result = array_fill_keys(array_keys($keys), array());
		foreach ($keys as $type => $key) {
			$data = ZoneInstance::searchByIndex(array($key => '*'), 100);
			foreach ($data as $i => $node) {
				$result[$type][$node['name'] . ' ' . $i] = array(
					'id' => $node['zone_id'],
					'name' => $node['name'],
					'notable' => $node['label']
				);
			}
			ksort($result[$type]);
			$result[$type] = array_values($result[$type]);
		}
		return $result;
	}

}