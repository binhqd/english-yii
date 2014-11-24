<?php
/* 
 * Compress and cache used JS and CSS files.
 * Needs jsmin in helpers and csstidy in extensions
 *
 * Ties into the 1.0.4 (or > SVN 813) Yii CClientScript functions
 *
 * @author Maxximus <maxximus007@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2009
 * @license http://www.yiiframework.com/license/
 * @version 0.4
 */

class ExtendedClientScript extends CClientScript
{
	/**
	 * Compress all Javascript files with JSMin. JSMin must be installed as an extension in dir jsmin.
	 * code.google.com/p/jsmin-php/
	 */
	public $compressJs = false;
	/**
	 * Compress all CSS files with CssTidy. CssTidy must be installed as an extension in dir csstidy.
	 * Specific browserhacks will be removed, so don't add them in to be compressed CSS files
	 * csstidy.sourceforge.net
	 */
	public $compressCss = false;
	/**
	 * Combine all JS and CSS files into one. Be careful with relative paths in CSS.
	 */
	public $combineFiles = false;
	/**
	 * Exclude certain files from inclusion. array('/path/to/excluded/file') Useful for fixed base
	 * and incidental additional JS.
	 */
	public $excludeFiles = array();
	/**
	 * Path where the combined/compressed file will be stored. Will use coreScriptUrl if not defined
	 */
	public $filePath;
	/**
	 * If true, all files to be included will be checked if they are modified.
	 * To enhance speed (eg production) set to false.
	 */
	public $autoRefresh = false;
	/**
	 * Relative Url where the combined/compressed file can be found
	 */
	public $fileUrl;
	/**
	 * Path where files can be found
	 */
	public $basePath;
	/**
	 * Dir where files can be found, from webroot
	 */
	public $baseDir;
	/**
	 * Used for garbage collection. If not accessed for that period: remove.
	 */
	public $ttlDays = 1;
	/**
	 * prefix for the combined/compressed files
	 */
	public $prefix = 'c_';
	/**
	 * CssTidy template. See CssTidy for more information
	 */
	public $cssTidyTemplate = "default";
	/**
	 * CssTidy parameters. See CssTidy for more information
	 */
	public $cssTidyConfig = array(
		'css_level' => 0,
		'discard_invalid_properties' => FALSE,
		'lowercase_s' => FALSE,
		'sort_properties' => FALSE,
		'sort_selectors' => FALSE,
		'preserve_css' => FALSE,
		'timestamp' => FALSE,
		'remove_bslash' => FALSE,
		'compress_colors' => FALSE,
		'compress_font-weight' => FALSE,
		'remove_last_,' => FALSE,
		'optimise_shorthands' => FALSE,
		'case_properties' => FALSE,
		'merge_selectors' => FALSE,
	);
	
	public $scriptGroup = array();
	public $cssGroup = array();
	
	private $_changesHash = '';
	private $_renewFile;
	
	private $_domains = array();
	
	private $_coreScripts = array();
	
	public function init() {
		$this->basePath = Yii::getPathOfAlias('jlwebroot') . $this->baseDir;
	}
	/**
	 * (non-PHPdoc)
	 * @see yii/framework/CClientScript::renderCoreScripts()
	 */
// 	public function renderCoreScripts() {
// 		parent::renderCoreScripts();
		
// 		if (!isset($this->scriptGroup[$this->coreScriptPosition]))
// 			$this->scriptGroup[$this->coreScriptPosition] = array();
		
// 		foreach($this->coreScripts as $name=>$package) {
// 			$baseUrl = $this->getPackageBaseUrl($name);
// 			if (!isset($this->scriptGroup[$this->coreScriptPosition][$baseUrl]))
// 				$this->scriptGroup[$this->coreScriptPosition][$baseUrl] = array();
			
// 			if(!empty($package['js']))
// 			{
// 				foreach($package['js'] as $js) {
// 					$this->scriptGroup[$this->coreScriptPosition][$baseUrl][] = $js;
// 				}
// 			}
// 		}
// 	}
	
	public function registerCoreScript($name) {
		Yii::app()->assetManager->domain = null;
		return parent::registerCoreScript($name);
	}
	/**
	 * (non-PHPdoc)
	 * @see yii/framework/CClientScript::registerCssFile()
	 */
	public function registerScriptFile($url, $position = 0) {
		parent::registerScriptFile($url, $position);
	}
	
	public function registerCssFile($url, $media = "screen") {
		parent::registerCssFile($url, $media);
	}
	/**
	 * Will combine/compress JS and CSS if wanted/needed, and will continue with original
	 * renderHead afterwards
	 *
	 * @param <type> $output
	 */
	public function renderHead(&$output)
	{
		$html = GNTemplateEngine::getContentOfTemplates();
		
		if($html!=='') {
			$count=0;
			$output=preg_replace('/(<body\b[^>]*>)/is','<###template-container###>$1',$output,1,$count);
			if($count)
				$output=str_replace('<###template-container###>',$html,$output);
			else
				$output=$html.$output;
		}
		
		parent::renderHead($output);
		
	}

	/**
	 * Will combine/compress JS if wanted/needed, and will continue with original
	 * renderBodyEnd afterwards
	 *
	 * @param <type> $output
	 */
// 	public function renderBodyBegin(&$output)
// 	{
// 		if ($this->combineFiles)
// 		{
// 			if (isset($this->scriptGroup[parent::POS_BEGIN]) && count($this->scriptGroup[parent::POS_BEGIN]) !==  0)
// 			{
// 				foreach ($this->scriptGroup[parent::POS_BEGIN] as $dir => $jsFiles) {
// 					$this->combineAndCompress('js', $jsFiles, parent::POS_BEGIN, $dir);
// 				}
// 			}
// 		}
		
// 		parent::renderBodyBegin($output);
// 	}

	/**
	 * Will combine/compress JS if wanted/needed, and will continue with original
	 * renderBodyEnd afterwards
	 *
	 * @param <type> $output
	 */
// 	public function renderBodyEnd(&$output)
// 	{
// 		if ($this->combineFiles)
// 		{
// 			if (isset($this->scriptGroup[parent::POS_END]) && count($this->scriptGroup[parent::POS_END]) !==  0)
// 			{
// 				foreach ($this->scriptGroup[parent::POS_END] as $dir => $jsFiles) {
// 					$this->combineAndCompress('js', $jsFiles, parent::POS_END, $dir);
// 				}
// 			}
			
// 		}
// 		parent::renderBodyEnd($output);
// 	}
	
	public function createCombinedFilename($urls, $type = 'js') {
		$combineHash = md5(implode('',$urls));
		
		$optionsHash = ($type == 'js') ? md5($this->basePath.$this->compressJs.$this->ttlDays.$this->prefix):
		md5($this->basePath.$this->compressCss.$this->ttlDays.$this->prefix.serialize($this->cssTidyConfig));
		
		$filename = $this->prefix.md5($combineHash.$optionsHash.$this->_changesHash).".$type";
		
		return $filename;
	}
	/**
	 *	Performs the actual combining and compressing
	 *
	 * @param <type> $type
	 * @param <type> $urls
	 * @param <type> $pos
	 */
	private function combineAndCompress($type, $urls, $pos, $savePath = "", $isRoot = false)
	{
		if ($isRoot) {
			$this->fileUrl = "/{$savePath}";
			$this->basePath = realpath(Yii::getPathOfAlias('jlwebroot')) . "/";
			
			$this->filePath = "{$this->basePath}{$savePath}/";
		} else {
			$this->fileUrl = $this->baseDir . $savePath;
			$this->basePath = Yii::app()->assetManager->basePath;
				
			$this->filePath = "{$this->basePath}/{$savePath}/";
		}
		
		if ($this->autoRefresh)
		{
			$mtimes = array();
			foreach ($urls as $file) {
				$mtimes[] = filemtime($this->basePath."/{$type}/".trim($file,"/"));
			}
			
			$this->_changesHash = md5(serialize($mtimes));
		}
		$combineHash = md5(implode('',$urls));

		$optionsHash = ($type == 'js') ? md5($this->basePath.$this->compressJs.$this->ttlDays.$this->prefix):
			md5($this->basePath.$this->compressCss.$this->ttlDays.$this->prefix.serialize($this->cssTidyConfig));

		$fileName = $this->createCombinedFilename($urls, $type);
		
		$this->_renewFile = (file_exists($this->filePath."/{$type}/".$fileName) && !isset($_REQUEST['cleanAsset'])) ? false : true;
		
		if ($this->_renewFile)
		{
			Yii::trace("{$this->filePath}/{$type}/{$fileName}", "Combine assets files");
			//debug("{$this->filePath}/{$type}/{$fileName}");
			$this->garbageCollect($type);
			$combinedFile = '';

			foreach ($urls as $key => $file)
				$combinedFile .= file_get_contents("{$this->filePath}/{$file}");

			if ($type == 'js' && $this->compressJs)
				$combinedFile = $this->minifyJs($combinedFile);

			if ($type == 'css' && $this->compressCss)
				$combinedFile = $this->minifyCss($combinedFile);
			
// 			debug("{$this->filePath}{$type}/{$fileName}");
			@chmod("{$this->filePath}{$type}/{$fileName}",0755);
			@file_put_contents("{$this->filePath}{$type}/{$fileName}", $combinedFile);
		}

		foreach ($urls as $url) {
			$this->scriptMap[basename($url)] = "{$this->fileUrl}/{$type}/{$fileName}";
		}
// 		dump($this->scriptMap);
		$this->remapScripts();
	}

	private function garbageCollect($type)
	{
		
		$files = CFileHelper::findFiles($this->filePath, array('fileTypes' => array($type), 'level'=> 0));
		foreach($files as $file)
		{
			if (strpos($file, $this->prefix) !== false && $this->fileTTL($file))
				@unlink($file);
		}
	}

	/**
	 * See if file is ready for deletion
	 *
	 * @param <type> $file
	 */
	private function fileTTL($file)
	{
		$ttl = $this->ttlDays * 60 * 60 * 24;
		return ((fileatime($file) + $ttl) < time()) ? true : false;
	}

	/**
	 * Minify javascript with JSMin
	 *
	 * @param <type> $js
	 */
	private function minifyJs($js)
	{
		Yii::import('greennet.extensions.jsmin.*');
		require_once('JSMin.php');
		return JSMin::minify($js, 2);
	}

	/**
	 * Yii-ified version of CSS.php of the Minify package with fixed options
	 *
	 * @param <type> $css
	 */
	private function minifyCss($css)
	{
	    /*
		require_once(Yii::getPathOfAlias('application.extensions.csstidy') . "/cssmin.php");
        $result = CssMin::minify($css);
		return $result;
        */
		/*Yii::import('greennet.extensions.csstidy.*');
		require_once('class.csstidy.php');

		$cssTidy = new csstidy();
		$cssTidy->load_template($this->cssTidyTemplate);

		foreach($this->cssTidyConfig as $k => $v)
			$cssTidy->set_cfg($k, $v);

		$cssTidy->parse($css);
		return $cssTidy->print->plain();*/
// 		debug($css);
        $inflatedCss = $css;
        if (!is_string($inflatedCss))
			trigger_error(__METHOD__ . "() - input is not a string");
		$o = "";
		$isComment = false;
		foreach (explode("\n", $inflatedCss) as $l) {
			$commentStart = strpos($l, "/*");
			if (!$isComment && $commentStart !== false && strpos($l, "*/") !== false)
				$l = preg_replace("/\/\*.*\*\//", "", $l);
			if (!$isComment && $commentStart !== false && ($pos = strpos($l, "/*")) !== false) {
				$isComment = true;
				$l = substr($l, 0, $pos);
			} elseif ($isComment && ($pos = strpos($l, "*/")) !== false) {
				$isComment = false;
				$l = substr($l, $pos + 2);
			} elseif ($isComment) {
				continue;
			}
			$o .= preg_replace("/\s*(;|:|}|{|,)\s*/", "\\1", trim(str_replace("\t", " ", $l)));
		}
		return $o;
	}
	
	public function addCssFile($arrAttr) {
		$domain = "default";
		if (isset($arrAttr['domain']) && !empty($arrAttr['domain'])) {
			$domain = $arrAttr['domain'];
		}
		
		if (!isset($this->_domains[$arrAttr['priority']])) {
			$this->_domains[$arrAttr['priority']] = array();
		}
		if (empty($arrAttr['assetRoot'])) {
			if (!isset($this->_domains[$arrAttr['priority']][$domain])) {
				$this->_domains[$arrAttr['priority']][$domain] = array(
					'addedCssFiles'		=> array(),
					'addedScriptFiles'	=> array()
				);
			}
			
			if (!isset($this->_domains[$arrAttr['priority']][$domain]['addedCssFiles'][$arrAttr['media']])) {
				$this->_domains[$arrAttr['priority']][$domain]['addedCssFiles'][$arrAttr['media']] = array();
			}
				
			$this->_domains[$arrAttr['priority']][$domain]['addedCssFiles'][$arrAttr['media']][] = $arrAttr['path'];
		} else {
			if (!isset($this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']])) {
				$this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']] = array(
					'addedCssFiles'		=> array(),
					'addedScriptFiles'	=> array(),
					'isRoot'			=> true
				);
			}
				
			if (!isset($this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']]['addedCssFiles'][$arrAttr['media']])) {
				$this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']]['addedCssFiles'][$arrAttr['media']] = array();
			}
			
			$this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']]['addedCssFiles'][$arrAttr['media']][] = $arrAttr['path'];
		}
		
		//--------------------------------
	}
	
	public function addScriptFile($arrAttr) {
		$domain = "default";
		if (isset($arrAttr['domain']) && !empty($arrAttr['domain'])) {
			$domain = $arrAttr['domain'];
		}
		
		if (!isset($this->_domains[$arrAttr['priority']])) {
			$this->_domains[$arrAttr['priority']] = array();
		}
		
		if (empty($arrAttr['assetRoot'])) {
			if (!isset($this->_domains[$arrAttr['priority']][$domain])) {
				$this->_domains[$arrAttr['priority']][$domain] = array(
					'addedCssFiles'		=> array(),
					'addedScriptFiles'	=> array()
				);
			}
			
			if (!isset($this->_domains[$arrAttr['priority']][$domain]['addedScriptFiles'][$arrAttr['position']])) {
				$this->_domains[$arrAttr['priority']][$domain]['addedScriptFiles'][$arrAttr['position']] = array();
			}
			
			$this->_domains[$arrAttr['priority']][$domain]['addedScriptFiles'][$arrAttr['position']][] = $arrAttr['path'];
		} else {
			if (!isset($this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']])) {
				$this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']] = array(
					'addedCssFiles'		=> array(),
					'addedScriptFiles'	=> array(),
					'isRoot'			=> true
				);
			}
				
			if (!isset($this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']]['addedScriptFiles'][$arrAttr['position']])) {
				$this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']]['addedScriptFiles'][$arrAttr['position']] = array();
			}
				
			$this->_domains[$arrAttr['priority']][$arrAttr['assetRoot']]['addedScriptFiles'][$arrAttr['position']][] = $arrAttr['path'];
		}
	}
	
	public function printGNAssets() {
		krsort($this->_domains);
// 		debug($this->_domains);
		foreach ($this->_domains as $priority => $domains) {
			if (!empty($domains)) {
				foreach ($domains as $domain => $assets) {
					$addedCssFiles = $assets['addedCssFiles'];
					$addedScriptFiles = $assets['addedScriptFiles'];
					$isRoot = !empty($assets['isRoot']) ? $assets['isRoot'] : false;
					
					// if combineFiles is true
					if ($this->combineFiles) {
						$paths = array();
						foreach ($addedCssFiles as $media => $urls) {
							$urls = array_unique($urls);
							foreach ($urls as $item) {
								if ($isRoot) {
									$this->registerCSSFile("/{$domain}/{$item}", $media);
								} else {
									$this->registerCSSFile("{$this->baseDir}{$domain}/{$item}", $media);
								}
								
							}
							if ($this->compressCss) $this->combineAndCompress('css', $urls, $media, $domain, $isRoot);
						}
						
						foreach ($addedScriptFiles as $position => $urls) {
							$urls = array_unique($urls);
							foreach ($urls as $item) {
								if ($isRoot) {
									$this->registerScriptFile("/{$domain}/{$item}", $position);
								} else {
									$this->registerScriptFile("{$this->baseDir}{$domain}/{$item}", $position);
								}
							}
							if ($this->compressJs) $this->combineAndCompress('js', $urls, $position, $domain, $isRoot);
						}
					} else {
						$paths = array();
						foreach ($addedCssFiles as $media => $urls) {
							foreach ($urls as $item) {
								if ($isRoot) {
									$this->registerCSSFile("/{$domain}/{$item}", $media);
								} else {
									$this->registerCSSFile("{$this->baseDir}{$domain}/{$item}", $media);
								}
							}
						}
						
						foreach ($addedScriptFiles as $position => $urls) {
							foreach ($urls as $item) {
								if ($isRoot) {
									$this->registerScriptFile("/{$domain}/{$item}", $position);
								} else {
									$this->registerScriptFile("{$this->baseDir}{$domain}/{$item}", $position);
								}
							}
						}
					}
				}
				
			}
		}
		
	}
}