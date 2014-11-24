<?php

/**
 * Component dÃ¹ng chung cho toÃ n bá»™ controller cá»§a há»‡ thá»‘ng, dÃ¹ng Ä‘á»ƒ thiáº¿t láº­p cÃ¡c phÆ°Æ¡ng thá»©c, thuá»™c tÃ­nh máº·c Ä‘á»‹nh
 * 
 * @ingroup components
 * @class	   GNController
 * @author	  huytbt
 * @version	 1.0
 * @date		2011-05-23
 */
class GNController extends RController {

	const MESSAGE_INFO = 1;
	const MESSAGE_ERROR = 2;
	const MESSAGE_WARNING = 3;
	const MESSAGE_MESSAGE = 4;
	/**
	 * redirectOptions
	 */
	private $_redirectOptions = array();
	/**
	 * @var	$layout
	 * @brief  Thiáº¿t láº­p layout máº·c Ä‘á»‹nh
	 */
	public $layout = '//layouts/main';
	
	/**
	 * @var	$breadcrumbs
	 * @brief  Thiáº¿t láº­p $breadcrumbs
	 */
	public $breadcrumbs = array();
	public $debug = YII_DEBUG;

	public $renderHtml = false;
	
	// Using for method prototypeWidget
	private $_prototypeWidgetIDs = array();
	
	/**
	 * This method is used to allow action
	 * @return string
	 */
	/*public function allowedActions()
	{
		return '*';
	}*/
	
	public function init() {
		parent::init();
		// update all clicked notification if exists
		
		if (isset($_REQUEST['debug'])) $this->debug = $_REQUEST['debug'] == "false" ? false : (bool)$_REQUEST['debug'];
		
		$headScript = "
		var homeURL = \"".GNRouter::createUrl('/')."\";
		var cookieDomain = \"".Yii::app()->session->cookieParams['domain']."\";
				
		if (document.all && !document.addEventListener) {
			alert('" . Yii::t("greennet", "Your browser is out of date! It looks like you\'re using an old version of Internet Explorer.For the best Youlook experience, please update your browser.") . "');
		}
		
		/*window.onerror = ErrorLog;
		function ErrorLog (msg, url, line) {
			$.ajax({
				url : homeURL + '/js_error_log.php',
				data : 'msg='+encodeURIComponent(msg)+'&url='+encodeURIComponent(url)+'&line=' + line,
				success : function(response) {
					// do something here
				}
			});
			
			//return true;
		}*/";
		Yii::app()->getClientScript()->registerScript('registerGlobalVariables', $headScript, CClientScript::POS_HEAD);
		
		if (!currentUser()->isGuest) {
			//$user = currentUser()->attributes;
			$arrUser = currentUser()->toArray(true);
		} else {
			$arrUser = array(
				'id' => '-1',
				'displayname' => 'Guest',
			);
		}
		
		$script = "
			// handle js error
		
			if ((typeof jlbd != 'undefined' && jlbd !== null) && (typeof jlbd.user != 'undefined' && jlbd.user !== null)
			&& (jlbd.user.collection.current.user == null)
			) {
				var user = ".CJSON::encode($arrUser).";
				var currentUser = new jlbd.user.Libs.JLUser(user.id, user);
				jlbd.user.collection.current.user = currentUser;
			}
		";
		
		Yii::app()->getClientScript()->registerScript('createUserObject', $script, CClientScript::POS_BEGIN);
		
		// Notification message
		$redirectInfo = Yii::app()->session["Notification.showOnLoad"];
		if (!empty($redirectInfo)) {
			$script = "var options = {
				message	: '".$redirectInfo["message"]."',
				type : '".$redirectInfo["typeMessage"]."',
				autoHide : true,
				timeOut : 5
			}
			jlbd.dialog.notify(options);";
			Yii::app()->getClientScript()->registerScript('showNotificationOnload', $script, CClientScript::POS_READY);
			unset(Yii::app()->session["Notification.showOnLoad"]);
		}
		
	}
	/**
	 * @return array - List of filters
	 */
	public function filters()
	{
		return array(
			array(
				'application.components.filters.YXssFilter',
				'clean' => 'none',
				'tags' => 'soft',
				'actions' => 'all'
			),
			'rights',
		);
	}
	
	/**
	 * PhÆ°Æ¡ng thá»©c setRedirectOptions($arrOptions) dÃ¹ng Ä‘á»ƒ thiáº¿t láº­p cÃ¡c Options dÃ¹ng Ä‘á»ƒ xuáº¥t cÃ¡c thÃ´ng bÃ¡o trong trang Redirect
	 * 
	 * @param array $arrOptions $arrOptions['timeout']: thá»�i gian chuyá»ƒn trang, $arrOptions['title']: tiÃªu Ä‘á»�, $arrOptions['message']: chuá»—i thÃ´ng bÃ¡o
	 */
	public function setRedirectOptions($arrOptions) {
		$_default = array(
			'timeout' => 2,
			'title' => "Your title",
			'message' => "Your message",
			'type'	  => 'redirect',
			'typeMessage'=>'message'
		);

		$arrOptions = CMap::mergeArray($_default, $arrOptions);
		$this->_redirectOptions = $arrOptions;
	}

	/**
	 * PhÆ°Æ¡ng thá»©c redirect($url, $terminate=true, $statusCode=302) dÃ¹ng Ä‘á»ƒ redirect user Ä‘áº¿n url
	 * 
	 * @param $url string/array
	 * @param $terminate boolean
	 * @param $statusCode int 
	 */
	public function redirect($url, $terminate=true, $statusCode=302) {
		if (!empty($this->_redirectOptions)) {
			
			if (strtolower($this->_redirectOptions['type']) == "notification") {
				Yii::app()->session["Notification.showOnLoad"] = $this->_redirectOptions;
				parent::redirect($url, $terminate, $statusCode);
			} else {
				if(is_array($url))
				{
					$route=isset($url[0]) ? $url[0] : '';
					$url=$this->createUrl($route,array_splice($url,1));
				}
				
				$this->_redirectOptions['url'] = $url;
				
				$this->layout = "//layout/partial";
				$this->render('//common/redirect', array(
					'redirectOptions' => $this->_redirectOptions
				));
			
				Yii::app()->end();
			}
		} else {
			parent::redirect($url, $terminate, $statusCode);
		}
	}
	
	/**
	 * createUrl - PhÆ°Æ¡ng thá»©c dÃ¹ng Ä‘á»ƒ táº¡o URL
	 *
	 * @param type $route
	 * @param type $params
	 * @param type $ampersand
	 * @return type 
	 */
	public function createUrl($route,$params=array(),$ampersand='&') {
		if($route==='')
			$route=$this->getId().'/'.$this->getAction()->getId();
		else if(strpos($route,'/')===false)
			$route=$this->getId().'/'.$route;
		if($route[0]!=='/' && ($module=$this->getModule())!==null)
			$route=$module->getId().'/'.$route;
		return GNRouter::createAbsoluteUrl($route, $params, '', $ampersand);
	}
	
	/**
	 * getAssetPath - PhÆ°Æ¡ng thá»©c dÃ¹ng Ä‘á»ƒ láº¥y Ä‘Æ°á»�ng dáº«n Ä‘áº¿n thÆ° má»¥c cá»§a user (hoáº·c template)
	 *
	 * @return string
	 */
	public function getAssetPath()
	{
		if (APPLICATION_SCOPE == "TEMPLATE_MANAGER") {
			return dirname(__FILE__) . '/../../wwwroot/site_default/' . Yii::app()->params['site_info']['template'];
		} else {
			if (APPLICATION_SCOPE == "SITE_MANAGER")
				$path = 'editmode_webroot';
			else
				$path = 'uwebroot';
			return dirname(__FILE__) . '/../../wwwroot/' . $path . '/' . Yii::app()->params['site_info']['user'] . '/' . Yii::app()->params['site_info']['site'];
		}
	}
	
	public function showMessage($title, $content, $type = self::MESSAGE_INFO, $exit = true) {
		//error_reporting(0);
		
		// ------------------------------------
		ob_start();
		// $this->layout = '//layouts/dashboard';
		$this->render('//common/showMessage', array(
			'title'		=> $title,
			'content'	=> $content,
			'type'		=> $type
		));
		$size = ob_get_length();
		header("Content-Length: {$size}");
		header('Connection: close');
		header("Content-type: text/html");
		@ob_end_flush();
		@ob_flush();
		@flush();
		
		if ($exit) {
			if (YII_DEBUG) exit();
			else Yii::app()->end();
		} else {
			$session_id = session_id();
			if (session_id()) session_write_close();
			return $session_id;
		}
	}
	/*
	 * show message by viewPath
	 * @author: thienhv
	 */
	public function showMessageView($viewPath = '//common/showMessage', $data = array(), $exit = true) {
		ob_start();
		// $this->layout = '//layouts/dashboard';
		$this->render($viewPath, $data);
		$size = ob_get_length();
		header("Content-Length: {$size}");
		header('Connection: close');
		header("Content-type: text/html");
		@ob_end_flush();
		@ob_flush();
		@flush();
		
		if ($exit) {
			if (YII_DEBUG) exit();
			else Yii::app()->end();
		} else {
			$session_id = session_id();
			if (session_id()) session_write_close();
			return $session_id;
		}
	}
	
	/**
	 * This method is used render ajax pagination
	 */
	private $ajaxPagination;
	public function renderAjaxPagination($view, $arrParam)
	{
		$this->ajaxPagination['viewfile'] = $view;
		$this->ajaxPagination['viewparams'] = $arrParam;
		if (Yii::app()->request->isAjaxRequest) {
			if ($this->getViewFile('_'.$view) !== false) $this->renderPartial('_'.$view, $arrParam);
		} else {
			$this->render($view, $arrParam);
		}
	}
	
	public function echoAjaxPagination($arrParam = array())
	{
		$arrParam['options'] = $this->ajaxPagination;
		if(! isset($arraParam['loadFirst']) )
			$arrParam['options']['renderHtml'] = false;
		else
			$arrParam['options']['renderHtml'] = true;
		$this->widget('widgets.pagination.GNBDAjaxPagination', $arrParam);
	}
	
	public function afterRender($view, &$output) {
		Yii::app()->clientScript->printGNAssets();
		parent::afterRender($view,$output);
	}
	
	/**
	 * Creates a prototype widget and executes it.
	 * @param string $className the widget class name or class in dot syntax (e.g. application.widgets.MyWidget)
	 * @param array $properties list of initial property values for the widget (Property Name => Property Value)
	 * @param boolean $captureOutput whether to capture the output of the widget. If true, the method will capture
	 * and return the output generated by the widget. If false, the output will be directly sent for display
	 * and the widget object will be returned. This parameter is available since version 1.1.2.
	 * @return mixed the widget instance when $captureOutput is false, or the widget output when $captureOutput is true.
	 */
	public function prototypeWidget($className,$properties=array(),$captureOutput=false)
	{
		$exists = in_array($className, $this->_prototypeWidgetIDs);
		if (!$exists) {
			$this->_prototypeWidgetIDs[] = $className;
			return $this->widget($className, $properties, $captureOutput);
		}
	}
	
	/**
	 * This method is used to check request is JSON?
	 */
	public function getIsJsonRequest()
	{
		return Yii::app()->request->isAjaxRequest || (isset($_REQUEST['requestType']) && $_REQUEST['requestType']=='json');
	}
	
	public function render($view, $data=NULL, $return=false) {
		if ($this->isJsonRequest && !$this->renderHtml) {
			ajaxOut($data);
		} else {
			parent::render($view, $data, $return);
		}
	}
}
