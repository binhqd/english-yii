<?php

/**
 * Component dùng để xử lý các vấn đề chuyên về assets cho dự án JustLook
 * 
 * @ingroup components
 * @class	   GNAssetManager
 * @author	  binhqd
 * @version	 1.0
 * @date		2012-03-06
 */
class GNAssetManager extends CAssetManager  {
	public $domain;
	
	public $_domainPublished = array();
	
	public $publishedPaths;
	public $publishedPathFile;
	private $_cleanAssetPool = array();
	public function init() {
		parent::init();
		$this->publishedPathFile = Yii::app()->runtimePath . "/published.txt";
		
		if (!file_exists($this->publishedPathFile)) {
			$this->publishedPaths = array(
				'domained'		=> array(),
				'undomained'	=> array(),
				'files'			=> array()
			);
			file_put_contents($this->publishedPathFile, CJSON::encode($this->publishedPaths));
		} else {
			$content = file_get_contents($this->publishedPathFile);
			$this->publishedPaths = CJSON::decode($content);
		}
	}
	
	public function setBase($base) {
		$realPath = realpath(Yii::getPathOfAlias('jlwebroot') ."/" . $base);
		return "/{$base}/";
	}
	/**
	 * (non-PHPdoc)
	 * @see CAssetManager::publish()
	 */
	public function publish($path, $hashByName=false, $level=-1, $forceCopy=false) {
		if (strpos(realpath($path), Yii::getPathOfAlias("framework")) !== false) {
			$this->domain = null;
			return parent::publish($path, $hashByName, $level);
		}
		
		if ($this->domain == null)
			$this->domain = "default";
		
		if (isset($_REQUEST['cleanAsset'])) {
			//if ($path == "F:\\webroot\\yii\\demos\\jlbd_v0.1\\jlprotected\\extensions\\jnotify\\jnotify")
				
			if (isset($this->domain)) {
				if (!isset($this->_cleanAssetPool[$path])) {
					$this->_cleanAssetPool[$path] = array(
						$this->domain => 'published'
					);
					
					
					return $this->_publish($path, $hashByName, $level);
				} else if (!isset($this->_cleanAssetPool[$path][$this->domain])) {
					$this->_cleanAssetPool[$path][$this->domain] = 'published';
					return $this->_publish($path, $hashByName, $level);
				}
			} else {
				return $this->_publish($path, $hashByName, $level);
			}
		}

		$realPath = realpath($path);
		if (is_dir($path)) {
			if ($this->domain != null) {
				
				if (isset($this->publishedPaths['domained'][$realPath]) && isset($this->publishedPaths['domained'][$realPath][$this->domain])) {
					return $this->publishedPaths['domained'][$realPath][$this->domain]['path'];
				} else {
					return $this->_publish($path, $hashByName, $level);
				}
			} else {
				if (isset($this->publishedPaths['undomained'][$realPath])) {
					return $this->publishedPaths['undomained'][$realPath];
				} else {
					return $this->_publish($path, $hashByName, $level);
				}
			}
		} else if (is_file($path)) {
			return $this->_publish($path, $hashByName, $level);
		} else {
			Yii::log("{$path} doesn't exist", 1, 'published');
		}
		
		Yii::trace($path, "Can't publish");
	}
	
	private function _publish($path, $hashByName, $level) {
		if (is_file($path)) {
			$realPath = dirname($path);
			$filename = basename($path);
			if (isset($this->publishedPaths['files'][$path])) {
				return $this->publishedPaths['files'][$path];
			} else {
				$publishedFile = parent::publish($path, $hashByName, $level, true);
				Yii::trace($publishedFile, "Force public file: {$path}");
				$this->publishedPaths['files'][$path] = $publishedFile;
				return $publishedFile;
			}
		}
		Yii::trace($path, "Publish path - Domain: {$this->domain}");
		//$bt =  debug_backtrace();
		$time = time();
		$realPath = realpath($path);
		// unset($this->_published[$path]);
		$publishedPath = parent::publish($path, $hashByName, $level, true);
		Yii::trace($publishedPath, "Force publish: {$path}");
		if ($this->domain != null) {
			
			if (!isset($this->publishedPaths['domained'][$realPath])) $this->publishedPaths['domained'][$realPath] = array();
			$this->publishedPaths['domained'][$realPath][$this->domain] = array(
				'time' 	=> $time,
				'path'	=> $publishedPath
			);
			$this->domain = null;
		} else {
			$this->publishedPaths['undomained'][$realPath] = $publishedPath;
		}
		
		file_put_contents($this->publishedPathFile, CJSON::encode($this->publishedPaths));
		
		//$this->_published = array();
		
		return $publishedPath;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see CAssetManager::hash()
	 */
	protected function hash($path)
	{
		if (isset($this->domain) && !empty($this->domain)) {
			return $this->domain;
		}
		
		//$hash = sprintf('%x',crc32($path.Yii::getVersion()));
		return parent::hash($path);
	}
}
