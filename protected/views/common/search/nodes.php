<?php
if (!empty($result['nodes'])) {
	foreach($result['nodes'] as $key=>$node) {
		if ($type == "landingpage" && $key==0){ 
		} else {
			$this->renderPartial('application.views.common.node._item_search',array(
				'node'=>$node,
				'page'=>$page
			));
		}
	}
}
?>