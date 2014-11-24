<?php
/**
 * HLinkPager class file.
 *
 * @author huytbt <huytbt@gmail.com>
 * @version 1.0
 */
class HLinkPager extends CLinkPager
{
	/**
	 * This method is used to displaying the generated page buttons.
	 * @param array $options the options of widget
	 * @return array the pages and buttons
	 */
	public static function getLinkPager($options = array())
	{
		$className = __CLASS__;
		$linkPager = new $className;
		foreach ($options as $option => $value)
			$linkPager->$option = $value;
		$linkPager->init();
		$buttons = $linkPager->createPageButtons();
		$pages = array();
		if (isset($options['pages'])) {
			$pages = array(
				'currentPage' => $options['pages']->currentPage,
				'itemCount' => $options['pages']->itemCount,
				'limit' => $options['pages']->limit,
				'offset' => $options['pages']->offset,
				'pageCount' => $options['pages']->pageCount,
				'pageSize' => $options['pages']->pageSize,
				'pageVar' => $options['pages']->pageVar,
				'params' => $options['pages']->params,
				'route' => $options['pages']->route,
			);
		}
		return array(
			'pages'		=> $pages,
			'buttons'	=> $buttons,
			'options'	=> array(
				'firstPageCssClass'		=> $linkPager->firstPageCssClass,
				'lastPageCssClass'		=> $linkPager->lastPageCssClass,
				'previousPageCssClass'	=> $linkPager->previousPageCssClass,
				'nextPageCssClass'		=> $linkPager->nextPageCssClass,
				'internalPageCssClass'	=> $linkPager->internalPageCssClass,
				'hiddenPageCssClass'	=> $linkPager->hiddenPageCssClass,
				'selectedPageCssClass'	=> $linkPager->selectedPageCssClass,
				'maxButtonCount'		=> $linkPager->maxButtonCount,
				'nextPageLabel'			=> $linkPager->nextPageLabel,
				'prevPageLabel'			=> $linkPager->prevPageLabel,
				'firstPageLabel'		=> $linkPager->firstPageLabel,
				'lastPageLabel'			=> $linkPager->lastPageLabel,
				'header'				=> $linkPager->header,
				'footer'				=> $linkPager->footer,
			),
			'htmlOptions'=> $linkPager->htmlOptions,
		);
	}

	/**
	 * Creates a page button.
	 * You may override this method to customize the page buttons.
	 * @param string $label the text label for the button
	 * @param integer $page the page number
	 * @param string $class the CSS class for the page button.
	 * @param boolean $hidden whether this page button is visible
	 * @param boolean $selected whether this page button is selected
	 * @return string the generated button
	 */
	protected function createPageButton($label,$page,$class,$hidden,$selected)
	{
		if($hidden || $selected)
			$class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
		return array(
			'class'	=> $class,
			'label'	=> !empty($label) ? $label : '',
			'url'	=> $this->createPageUrl($page),
		);
	}
}
