<?php
GNAssetHelper::init(array(
	'image' => 'img',
	'css' => 'css',
	'script' => 'js',
));
GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::cssFile('view-all-list-celebrities');
GNAssetHelper::cssFile('peoples-interacted');
GNAssetHelper::cssFile('streamstory-viewall-action-composer');
GNAssetHelper::cssFile('viewall-content-2col');
GNAssetHelper::cssFile('landingpage');






GNAssetHelper::scriptFile('imagesloaded.pkgd.min', CClientScript::POS_END);
// GNAssetHelper::scriptFile('jquery.wookmark.min', CClientScript::POS_END);
// GNAssetHelper::scriptFile('jquery.lazy.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('masonry.pkgd.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('jquery.infinitescroll.min', CClientScript::POS_END);
GNAssetHelper::scriptFile('zone.land.search.new1', CClientScript::POS_HEAD);

$this->widget('ext.timeago.JTimeAgo', array(
	'selector' => ' .timeago',

));


?>
		<div class="wd-container wd-container-landing">
		<div class="wd-center center-landing-search" id="articleSelector">
			<div class="wd-contain-content">
				<?php $this->renderPartial("application.views.common.header.message-full");?>
				
<!-- header line -->
<!-- 				<div class="wd-headline"> -->
					<?php
					
// 					$this->widget('widgets.menu.MenuLandingPage');
					
// 					?>
<!-- 				</div> -->
				
				<div id="swipe" class="wd-main-content">
					<div class="wd-pagelet-list-celebrities">
						<div class="wd-view-all-list-celebrities transitions-enabled itemAppend"  style="opacity:1;padding-bottom:40px;">
							
							<?php
							if(empty($result['articles']) && empty($result['nodes'])){
								if(!Yii::app()->request->isAjaxRequest){
							?>
							<div class="wd-results-container">
								<div class="wd-empty-results-description">
									<p class="mt35">Search result not found. Try another keyword !</p>
									
								</div>
							</div>
							<?php
								}
							}else{
								$this->renderPartial('application.views.common.search.index',array(
									'result'=>$result,
									'page'=>$page,
									'type'=>'search'
								));
							}
							
							?>
							<div id="page-nav" style="display:none">
								<a href="<?php echo ZoneRouter::createUrl('search/default/index?keyword='.urlencode($keyword).'&page='.($page+1));?>">next</a>
								
							</div>
							
						</div>
						<div class="clear"></div>
					</div>
					
				</div>
				
				
			</div>
			<div class="clear"></div>
			
		</div>
</div>

<style>
#infscr-loading{
	position:absolute!important;
	bottom:10px!important;
	left:44%!important;
}
.infinite_navigation{display:none}
</style>
<script>

	var page = <?php echo $page;?>;
	var limit = <?php echo $limit;?>;
	var $container = $('.itemAppend');
	
</script>