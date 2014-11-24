<?php
class GNTemplateEngine extends CWidget {
	// Render script position
	const POS_READY = 1;
	const POS_IMME = 2;
	
	// add template methods
	const ADD_APPEND = 'append';
	const ADD_PREPEND = 'prepend';
	
	private $_data = array();
	private $_scriptPos;
	private $_templateContent;
	private $_templateID;
	private $_token;
	private $_attachData = null;
	
	private $_containerSelector;
	private $_containerAddMethod;
	
	private static $_templates = array();
	private static $_templateData = array();
	
	private $_ajaxRequest = false;
	private $_ajaxUrl ='';
	private $_ajaxResponseData = 'res';
	
	private $_beforeRender;
	private $_afterRender;
	private $_success;
	public $dataType = '';
	
	private $_hasTemplatePath = true;
	
	public function setContainer(array $containerInfo) {
		if (empty($containerInfo['selector'])) {
			throw new Exception(Yii::t("greennet", "You need to give selector for container"));
		}
		if (empty($containerInfo['type'])) {
			if (!in_array($containerInfo['type'], array(
				self::ADD_APPEND,
				self::ADD_PREPEND
			))) {
				throw new Exception(Yii::t("greennet", "Invalid template adding method"));
			}
		}
		$this->_containerSelector = $containerInfo['selector'];
		$this->_containerAddMethod = $containerInfo['type'];
		
	}
	public function setScriptPos($pos = self::POS_READY) {
		if (!in_array($pos, array(
			self::POS_IMME,
			self::POS_READY
		)))
			throw new Exception(Yii::t("greennet", "Invalid template engine script position"));
		
		$this->_scriptPos = $pos;
	}
	
	public function getToken() {
		if (empty($this->_token)) {
			$this->_token = uniqid();
		}
		
		return $this->_token;
	}
	
	public function getJsDataVariable() {
		return "_jsTemplateData_{$this->token}";
	}
	
	public function getJsRenderedObject() {
		return "_jsRenderedObject_{$this->token}";
	}
	
	public function setData(array $data) {
		// prevent empty data
		if (empty($data))
			$data = array();
		
		if (!empty($data['url']) && !empty($data['type']) && strtolower($data['type']) == 'ajax') {
			$this->_ajaxRequest = true;
			$this->_ajaxUrl = $data['url'];
			$this->_ajaxResponseData = $data['responseData'];
			if (!empty($data['attachData']))
				$this->_attachData = $data['attachData'];
		} else {
			$this->_data = $data;
			
			$dataScript = "\nvar {$this->jsDataVariable} = ".@CJSON::encode($this->_data).";\n";
			Yii::app()->clientScript->registerScript($this->token, $dataScript, CClientScript::POS_BEGIN);
		}
	}
	
	public function setTemplate(array $templateInfo) {
		// Check the required value: id
		if (empty($templateInfo['id'])) {
			throw new Exception(Yii::t("greennet", "Id of template is empty"));
		}
			
		$this->_templateID = $templateInfo['id'];
		
		if (isset($templateInfo['path'])) {
			$path = Yii::getPathOfAlias($templateInfo['path']);
			
			if ($path !== false) {
				ob_start();
				include("{$path}.php");
				$this->_templateContent = ob_get_contents();
				ob_end_clean();
				 
			} else {
				throw new Exception(Yii::t("greennet", "Invalid template path"));
			}
		} else {
			$this->_hasTemplatePath = false;
		}
	}
	
	public function init() {
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));
		GNAssetHelper::scriptFile('jquery.tmpl.min', CClientScript::POS_BEGIN);
		
		if (!$this->_hasTemplatePath) {
			//parent::init();
			ob_start();
		}
	}
	
	public static function getContentOfTemplates() {
		$output = "";
		foreach (self::$_templates as $templateID => $content) {
			$output .= "\n{$content}";
		}
		
		return $output;
	}
	
	public function getBeforeRender() {
		return $this->_beforeRender;
	}
	
	public function getAfterRender() {
		return $this->_afterRender;
	}
	
	public function setCallbacks(array $callbacks) {
		if (isset($callbacks['success'])) {
			$this->_success = $callbacks['success'];
		}
		if (isset($callbacks['beforeRender'])) {
			$this->_beforeRender = $callbacks['beforeRender'];
		}
		if (isset($callbacks['afterRender'])) {
			$this->_afterRender = $callbacks['afterRender'];
		}
	}
	public function run() {
		if (!$this->_hasTemplatePath) {
			$this->_templateContent = ob_get_clean();
		}
		
		$scriptContent = "<script id=\"{$this->_templateID}\" type=\"text/x-jquery-tmpl\">
			{$this->_templateContent}
		</script>\n";
		
		self::$_templates[$this->_templateID] = $scriptContent;
		
		if ($this->_ajaxRequest) {
			$jsonAttachData = @CJSON::encode($this->_attachData);
			$script = "
			$.ajax({
				url : '{$this->_ajaxUrl}',
				dataType: 'JSON',
				success : function(res) {
					{$this->beforeRender}

					var data = {$this->_ajaxResponseData};
					var attachData = {$jsonAttachData};
					if (attachData)
						data = $.extend(attachData, data);
					var rendered = $.tmpl($(\"#{$this->_templateID}\"), data);

					// append or prepend
					$('{$this->_containerSelector}').data('data', res);
					$('{$this->_containerSelector}').{$this->_containerAddMethod}(rendered);
					{$this->afterRender}
				}
			});
			";
		} else {
			$script = "
			{$this->beforeRender}
			var rendered  = $.tmpl($(\"#{$this->_templateID}\"), {$this->jsDataVariable});
			
			// append or prepend
			$('{$this->_containerSelector}').{$this->_containerAddMethod}(rendered);
			{$this->afterRender}
			";
		}
		if ($this->_scriptPos == self::POS_IMME) {
			$script =  "\n<script>
			{$script}
			</script>\n";
			echo $script;
		} else if ($this->_scriptPos == self::POS_READY) {
			Yii::app()->clientScript->registerScript($this->token, $script, CClientScript::POS_READY);
		}
		//echo $scriptContent;
// 		$script = ob_get_clean();
		
	}
}
