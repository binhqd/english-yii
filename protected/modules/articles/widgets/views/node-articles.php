<?php
$this->widget('ext.yiinfinite-scroll.YiinfiniteScroller', array(
	'contentSelector' => '#articleSelector',
	'itemSelector' => 'li#article-item',
	'loadingText' => 'Loading more...',
	'customStyle'=>'overflow: hidden;  width: 135px;  font-size: 12px;  color: #777;  margin: 0 auto 10px;',
	'donetext' => ' ',
	'debug' => false,
	'pages' => $pages,
	'maxPage' => ceil($pages->itemCount/$pages->pageSize),
	'loading'=>array(
		'img'=>baseUrl()."img/front/ajax-loader.gif",
		'msgText'=>'Loading more...'
	)
));
?>