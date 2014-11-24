<?php 
/**
 * This widget is used to render content support ajax
 *
 * @author huytbt <huytbt@gmail.com>
 * @version 1.0
 * @package ajaxContent
 */
class HAjaxContent extends CWidget
{
	const POS_MIDDLE = 0;
	const POS_READY = 1;

	public $url;
	public $jsInit = '';
	public $jsSuccess = '';
	public $jsComplete = '';
	public $jsBeforeSend = '';
	public $cachePages = 5;
	public $enableCache = true;
	public $loadFirst = false;
	public $syncURL = false;
	public $ajaxClassLinks = array('.yiiPager li a');
	public $vssDivID = ''; // Support for VSS
	public $firstContent = ''; // First content
	public $viewFile = ''; // View display first
	public $viewParams = ''; // Params of view display first
	public $scriptPosition = self::POS_MIDDLE; // Params of view display first
	public $templateID = "";
	public $supportJQueryTemplate = true;
	public $supportLoadMore = false;

	/**
	 * This method is used to initial widget
	 */
	public function init()
	{
		if ($this->url == null)
			throw new CException(Yii::t("greennet", 'The property "url" cannot be empty.'));

		if ($this->supportLoadMore !== false) {
			if (!isset($this->supportLoadMore['linkPager']))
				throw new CException(Yii::t("greennet", 'The property "supportLoadMore.linkPager" cannot be empty.'));
			if (!isset($this->supportLoadMore['loadingElementClass']))
				throw new CException(Yii::t("greennet", 'The property "supportLoadMore.loadingElementClass" cannot be empty.'));
		}

		parent::init();
	}

	/**
	 * This method is used to run widget
	 */
	public function run()
	{
		if ($this->viewFile && is_file($this->owner->viewPath . '/' . $this->viewFile . '.php')) {
			$this->viewFile = $this->owner->viewPath . '/' . $this->viewFile . '.php';
		}
		if (!is_array($this->ajaxClassLinks))
			$this->ajaxClassLinks = array($this->ajaxClassLinks);

		$jsonOptions = @CJSON::encode(array(
			'cachePages' => $this->cachePages,
			'enableCache' => $this->enableCache,
			'loadFirst' => $this->loadFirst,
			'url' => $this->url,
			'vssDivID' => $this->vssDivID,
			'syncURL' => $this->syncURL,
			'ajaxClassLinks' => $this->ajaxClassLinks,
			'firstContent' => $this->firstContent,
			'supportLoadMore' => $this->supportLoadMore,
		));

		$this->render('h-ajax-content', array(
			'divID' => 'js-h-ajax-content-' . $this->id,
			'url' => $this->url,
			'jsonOptions' => $jsonOptions,
			'jsSuccess' => $this->jsSuccess,
			'jsBeforeSend' => $this->jsBeforeSend,
			'jsComplete' => $this->jsComplete,
			'jsInit' => $this->jsInit,
			'viewFile' => $this->viewFile,
			'viewParams' => $this->viewParams,
			'firstContent' => $this->firstContent,
			'scriptPosition' => $this->scriptPosition,
			'supportJQueryTemplate' => $this->supportJQueryTemplate,
			'supportLoadMore' => $this->supportLoadMore,
		));
	}
}