<?php
/**
 * GNAssetHelper - Helper chứa các phương thức phục vụ việc sử dụng theme
 * 
 * @ingroup ui_helpers
 * @author Khanhtq
 * @version 1.0
 */
class GNAssetHelper extends CComponent
{
	private static $basePath;
	private static $hasProcessedPath = false;
	public static $cssPath;
	public static $scriptPath;
	public static $imagePath;
	private static $folders;
	private static $useBase = true;
	public static $useCache = false;
	private static $_priority = 0;
	private static $domain = '';
	private static $assetRoot = false;
	
	public static function setPriority($priority) {
		self::$_priority = $priority;
	}
	/**
	* GNAssetHelper::getModule() - Phương thức dùng để trả về giá trị là tên của module hiện tại
	* 
	* @return
	*/
	public static function getModule()
	{
		return Yii::app()->controller->module->id; 
	}
	
	/**
	 * 
	 * Phương thức được gọi đầu mỗi layout khi sử dụng
	 * @param $folders
	 */
	public static function init($folders = array()) {
		/*if (isset(Yii::app()->theme)) {
			if (!self::$hasProcessedPath) {
				
			}
		} else {
			self::$basePath = "/";
		}*/
		
		$defaultFolder = array(
			'css'		=> 'css',
			'script'	=> 'js',
			'image'		=> 'images',
		);
		self::$folders = CMap::mergeArray($defaultFolder, $folders);
		
		if (self::$useBase) {
			self::$cssPath = self::$folders["css"] . "/";
			self::$scriptPath = self::$folders['script'] . "/";
			self::$imagePath = self::$folders['image'] . "/";
		} else {
			self::$cssPath = self::$basePath . self::$folders["css"] . "/";
			self::$scriptPath = self::$basePath . self::$folders['script'] . "/";
			self::$imagePath = self::$basePath . self::$folders['image'] . "/";
		}
	}
	
	public static function getAssetPath() {
		if (isset(self::$basePath) && !empty(self::$basePath)) {
			return self::$basePath;
		} else {
			return null;
		}
	}
	/**
	 * 
	 * Phương thức được sử dụng để set base cho css, js và image của ứng dụng
	 * @param unknown_type $base
	 */
	public static function setBase($base, $domain = "default", $folders = array()) {
		if (!empty($folders)) {
			self::$folders = CMap::mergeArray($defaultFolder, $folders);
		}
		if ($base == "/") {
			return;
		}
		
		self::$domain = $domain;
		
		if (isset($domain) && !empty($domain)) {
			Yii::app()->assetManager->domain = $domain;
		} else {
			Yii::app()->assetManager->domain = "default";
		}
		
		
		if (is_dir(Yii::getPathOfAlias($base))) {
			self::$basePath = Yii::app()->assetManager->publish(Yii::getPathOfAlias($base), false, -1, true) . "/";
			self::$cssPath = self::$folders["css"] . "/";
			self::$scriptPath = self::$folders['script'] . "/";
			self::$imagePath = self::$folders['image'] . "/";
			self::$assetRoot = '';
		} else {
			//self::$basePath = Yii::app()->assetManager->publish(Yii::getPathOfAlias('jlwebroot') ."/" . $base, false, -1, true) . "/";
			self::$basePath = Yii::app()->assetManager->setBase($base);
			self::$assetRoot = $base;
			self::$cssPath = self::$folders["css"] . "/";
			self::$scriptPath = self::$folders['script'] . "/";
			self::$imagePath = self::$folders['image'] . "/";
		}
		
		return self::$basePath;
	}
	
	public static function removeBase() {
		echo "<base href=\" " . self::$baseDomain . "\" />\n";
	}
	
	/**
	* GNAssetHelper::cssFile() - Phương thức dùng để gọi css theo theme
	* @param $url
	* @param $media
	* @return
	*/
	public static function cssFile($url, $media='screen', $pack = true)
	{
		Yii::app()->clientScript->addCssFile(array(
			'path'	=> self::$cssPath . $url . '.css',
			'media'	=> $media,
			'priority'	=> self::$_priority,
			'domain'	=> self::$domain,
			'assetRoot' => self::$assetRoot,
			'pack'		=> $pack
		));
		
		//Yii::app()->clientScript->registerCSSFile(self::$cssPath . $url.'.css', $media);
	}
	
	public static function cssCollection($collection, $dir, $arrFiles, $position = CClientScript::POS_HEAD , $outputFilename) {
		
		$webroot = (Yii::getPathOfAlias("wwwroot"));
		if (strtoupper(ENVIRONMENT) == "DEVELOPMENT" || !is_file("{$webroot}{$dir}{$outputFilename}.r".SITE_REVISION.".css")) {
			
			$combinedFile = '';
			foreach ($arrFiles as $cssFile) {
				$combinedFile .= file_get_contents("{$webroot}{$dir}{$cssFile}.css");
			}
			
			$combinedFile = self::minifyCss($combinedFile);
			@file_put_contents("{$webroot}{$dir}{$outputFilename}.r".SITE_REVISION.".css", $combinedFile);
		} else {
			
		}
		Yii::app()->clientScript->registerCssFile("{$dir}{$outputFilename}.r".SITE_REVISION.".css");
		//echo "<link rel=\"stylesheet\" href=\"{$dir}{$outputFilename}.r".SITE_REVISION.".css\"/>\n";
		
	}
	
	public static function jsCollection($collection, $dir, $arrFiles, $position = CClientScript::POS_HEAD , $outputFilename) {
		$webroot = (Yii::getPathOfAlias("wwwroot"));
		if (strtoupper(ENVIRONMENT) == "DEVELOPMENT" || !is_file("{$webroot}{$dir}{$outputFilename}.r".SITE_REVISION.".js")) {
				
			$combinedFile = '';
			foreach ($arrFiles as $jsFile) {
				$combinedFile .= "/*{$jsFile}.js*/\n" . file_get_contents("{$webroot}{$dir}{$jsFile}.js") . "\n\n";
			}
			
			$combinedFile = self::minifyJs($combinedFile);
			@file_put_contents("{$webroot}{$dir}{$outputFilename}.r".SITE_REVISION.".js", $combinedFile);
		} else {
				
		}
		Yii::app()->clientScript->registerScriptFile("{$dir}{$outputFilename}.r".SITE_REVISION.".js", $position);
		//echo "<script language=\"javascript\" src=\"{$dir}{$outputFilename}.r".SITE_REVISION.".js\"></script>\n";
	
	}
	
	public static function minifyJs($js)
	{
		$path = Yii::getPathOfAlias('greennet.extensions.jsmin.*'); 
		require_once("{$path}/JSMin.php");
		return JSMin::minify($js, 2);
	}
	
	public static function minifyCss($css)
	{
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
	/**
	 * GNAssetHelper::scriptFile() - Phương thức dùng để Includes js theo theme
	 * @param string $url URL for the JavaScript file
	 * @return string the JavaScript file tag
	 */
	public static function scriptFile($url, $position = CClientScript::POS_END, $pack = true)
	{
		Yii::app()->clientScript->addScriptFile(array(
			'path'	=> self::$scriptPath . $url . '.js',
			'position'	=> $position,
			'priority'	=> self::$_priority,
			'domain'	=> self::$domain,
			'assetRoot' => self::$assetRoot,
			'pack'		=> $pack
		));
		//Yii::app()->clientScript->registerScriptFile(self::$scriptPath . $url . '.js', $position);
	}
	
	/**
	 *  GNAssetHelper::image() - Phương thức dùng để tạo image có đường dẫn theo theme
	 * @param string $src the image URL
	 * @param string $alt the alternative text display
	 * @param array $htmlOptions additional HTML attributes (see tag).
	 * @return string the generated image tag
	 */
	public static function image($src, $alt='',$htmlOptions=array())
	{
		$htmlOptions['src'] = CHtml::encode(self::$basePath . "/" . self::$imagePath . $src);
		$htmlOptions['alt'] = $alt;
		echo CHtml::tag('img',$htmlOptions);
	}

	/**
	 * Registers a piece of javascript code.
	 * @param string $id ID that uniquely identifies this piece of JavaScript code
	 * @param string $script the javascript code
	 * @param integer $position the position of the JavaScript code. Valid values include the following:
	 * <ul>
	 * <li>CClientScript::POS_HEAD : the script is inserted in the head section right before the title element.</li>
	 * <li>CClientScript::POS_BEGIN : the script is inserted at the beginning of the body section.</li>
	 * <li>CClientScript::POS_END : the script is inserted at the end of the body section.</li>
	 * <li>CClientScript::POS_LOAD : the script is inserted in the window.onload() function.</li>
	 * <li>CClientScript::POS_READY : the script is inserted in the jQuery's ready function.</li>
	 * </ul>
	 * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
	 */
	public static function registerScript($id,$script,$position=null)
	{
		Yii::app()->clientScript->registerScript($id,$script,$position);
	}
	
	/**
	 * This method is used to pack css collection
	 * @param object $owner
	 * @param string $collectionName
	 * @param string $path
	 * @param array $files
	 * @param string $outputFilename
	 */
// 	public static function cssCollection($owner, $collectionName, $path, $files = array(), $outputFilename = "") {
// 		if (strtoupper(ENVIRONMENT) == "DEVELOPMENT" || !is_file(APP_DIR  . "/../public/{$path}{$outputFilename}.r".SITE_REVISION.'.css')) {
// 			$collection = $owner->assets
// 			->collection($collectionName);
// 			foreach ($files as $item) {
	
// 				$collection->addCss("{$path}{$item}");
// 			}
				
// 			if (!empty($outputFilename)) {
// 				$collection->setTargetPath(APP_DIR  . "/../public/{$path}{$outputFilename}.r".SITE_REVISION.'.css')
// 				->setTargetUri("{$path}{$outputFilename}.r".SITE_REVISION.'.css')
// 				->addFilter(new \Phalcon\Assets\Filters\Cssmin());
// 			}
				
// 			$collection->join(true);
// 		} else {
// 			$collection = $owner->assets
// 			->collection($collectionName)
// 			->addCss("{$path}{$outputFilename}.r".SITE_REVISION.'.css');
// 		}
	
// 		$owner->assets->outputCss($collectionName);
// 	}
	
	/**
	 * This method is used to pack js collection
	 * @param object $owner
	 * @param string $collectionName
	 * @param string $path
	 * @param array $files
	 * @param string $outputFilename
	 */
// 	public static function jsCollection($owner, $collectionName, $path, $files = array(), $outputFilename = "") {
// 		if (strtoupper(ENVIRONMENT) == "DEVELOPMENT" || !is_file(APP_DIR  . "/../public/{$path}{$outputFilename}.r".SITE_REVISION.'.js')) {
// 			$collection = $owner->assets
// 			->collection($collectionName);
// 			foreach ($files as $item) {
// 				$collection->addJs("{$path}{$item}");
// 			}
				
// 			if (!empty($outputFilename)) {
// 				$collection->setTargetPath(APP_DIR  . "/../public/{$path}{$outputFilename}.r".SITE_REVISION.'.js')
// 				->setTargetUri("{$path}{$outputFilename}.r".SITE_REVISION.'.js')
// 				->addFilter(new \Phalcon\Assets\Filters\Jsmin());
// 			}
// 			$collection->join(true);
// 		} else {
// 			$collection = $owner->assets
// 			->collection($collectionName)
// 			->addJs("{$path}{$outputFilename}.r".SITE_REVISION.'.js');
// 		}
	
// 		$owner->assets->outputJs($collectionName);
// 	}
}
