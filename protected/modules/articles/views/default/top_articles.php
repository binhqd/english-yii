<?php
if(!empty($topArticles['data'])){
	foreach($topArticles['data'] as $key=>$value){
		$this->renderPartial('application.views.common.articles.item_top_article',array(
			'article'=>$value,
			'key'=>$key,
		));
	}
}
?>