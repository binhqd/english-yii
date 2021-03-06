<?php

/**
 * minScript Application Component.
 *
 * Takes care of converting the groupMap and generating URLs.
 *
 * @package ext.minScript.components
 * @author TeamTPG
 * @copyright Copyright &copy; 2011 TeamTPG
 * @license BSD 3-clause
 * @link http://code.teamtpg.ch/minscript
 * @version 1.0.4
 *
 * @property array $groupMap Returns the minScript groupMap.
 */
class ExtMinScript extends CApplicationComponent {

	/**
	 * @var string ID of the minScript Controller as defined in the controllerMap property.
	 * Defaults to "min".
	 */
	public $controllerID = 'min';

	/**
	 * @var string Minify root directory.
	 */
	private $_minifyDir;

	/**
	 * @var boolean Whether groupMap is read-only.
	 */
	private $_readOnlyGroupMap = false;

	private $_groupMap = array();

	/**
	 * Initialize minScript Component and convert groupMap.
	 */
	public function init() {
		parent::init();
		$this -> _minifyDir = dirname(dirname(__FILE__)) . '/vendors/minify/min';
		$groupMap = $this -> getGroupMap();
		$groupsConfig = '&lt;?php return array(';
		//Groups
		foreach($groupMap as $group => $items) {
			if($groupsConfig == '&lt;?php return array(') {
				$groupsConfig .= '\'' . $group . '\'=>array(';
			} else {
				$groupsConfig .= '),\'' . $group . '\'=>array(';
			}
			//Files
			foreach($items as $index => $path) {
				$filename = basename($path);
				if(strpos($filename, '*') !== false) {
					Yii::log('No asterisks in filename, skipping file ' . $path, 'warning', 'minScript');
					unset($groupMap[$group][$index]);
					continue ;
				}
				$groupsConfig .= '\'' . $path . '\',';
			}
		}
		if($groupsConfig == '&lt;?php return array(') {
			$groupsConfig .= ');';
		} else {
			$groupsConfig .= '));';
		}
		if($this -> _compareGroupsConfig($groupsConfig)) {
			$this -> _writeGroupsConfig($groupsConfig);
		}
		$this -> setGroupMap($groupMap);
		$this -> _readOnlyGroupMap = true;
	}

	/**
	 * Get the minScript groupMap.
	 * @return array The minScript groupMap.
	 */
	public function getGroupMap() {
		return $this -> _groupMap;
	}

	/**
	 * Set the minScript groupMap. This method needs to be executed before the
	 * component is initialized.
	 * @param array $groupMap Array containing groups with files that need to be served. Files with asterisks
	 * in their filenames will be skipped and logged.
	 */
	public function setGroupMap($groupMap) {
		if(!$this -> _readOnlyGroupMap) {
			$this -> _groupMap = $groupMap;
		}
	}

	/**
	 * Generate Yii's scriptMap from minScript's groupMap
	 * @param string $group Group to convert to scriptMap. Defaults to all groups.
	 */
	public function generateScriptMap($group ='') {
		$groupMap = $this -> getGroupMap();
		if(!empty($group)) {
			if(isset($groupMap[$group])) {
				$minScriptUrl = $this -> generateUrl($group);
				//Files
				foreach($groupMap[$group] as $path) {
					$filename = basename($path);
					Yii::app() -> clientScript -> scriptMap[$filename] = $minScriptUrl;
				}
			}
		} else {
			//Groups
			foreach($groupMap as $group => $items) {
				$minScriptUrl = $this -> generateUrl($group);
				//Files
				foreach($items as $path) {
					$filename = basename($path);
					Yii::app() -> clientScript -> scriptMap[$filename] = $minScriptUrl;
				}
			}
		}
	}

	/**
	 * Generate group URL to minScript Controller.
	 * @param string $group The name of the group.
	 * @return string URL to minScript Controller.
	 */
	public function generateUrl($group) {
		$noFilemtime = false;
		$filemtimes = array();
		$params = array();
		$groupMap = $this -> getGroupMap();
		if(isset($groupMap[$group])) {
			$params['g'] = $group;
			//Files
			foreach($groupMap[$group] as $path) {
				$filemtime = @filemtime($path);
				if($filemtime !== false) {
					$filemtimes[] = $filemtime;
				} else {
					Yii::log('Can\'t access ' . $path, 'error', 'minScript');
					$noFilemtime = true;
				}
			}
			if(!empty($filemtimes) && !$noFilemtime) {
				$params[max($filemtimes)] = '';
			}
		}
		$minScriptUrl = rtrim(Yii::app() -> createUrl($this -> controllerID . '/serve', $params), '=');
		return $minScriptUrl;
	}

	/**
	 * Compare given string with minify's groupsConfig.
	 * @param string $str String to compare.
	 * @return boolean True if given string differs.
	 */
	private function _compareGroupsConfig($str) {
		$groupsConfig = @file_get_contents($this -> _minifyDir . '/groupsConfig.php');
		if($groupsConfig === false) {
			return false;
		}
		$str = str_replace('&lt;', '<', $str);
		if($str != $groupsConfig) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Write string to minify's groupsConfig.
	 * @param string $str String to write.
	 * @throws CException if minify's groupsConfig is not writable.
	 */
	private function _writeGroupsConfig($str) {
		$str = str_replace('&lt;', '<', $str);
		if(file_put_contents($this -> _minifyDir . '/groupsConfig.php', $str, LOCK_EX) === false) {
			throw new CException('minScript: ' . $this -> _minifyDir . '/groupsConfig.php is not writable.');
		}
	}

}
