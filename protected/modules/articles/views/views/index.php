<?php 
$user = currentUser();

Yii::import('application.modules.users.models.ZoneUserAvatar');

?>
<?php //$this->renderPartial('//common/user-related', compact('user'));?>

<?php
GNAssetHelper::init(array(
'image'		=> 'img',
'css'		=> 'css',
'script'	=> 'js',
));
GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::cssFile('viewall-content-2col');
GNAssetHelper::cssFile('streamstory-viewall-action-composer');
GNAssetHelper::cssFile('nav');
GNAssetHelper::cssFile('md-header');
GNAssetHelper::cssFile('header-search');
GNAssetHelper::cssFile('setting-header');
GNAssetHelper::cssFile('jewelcontainer');
GNAssetHelper::cssFile('bottom-header');
GNAssetHelper::cssFile('user-interaction-status');
GNAssetHelper::cssFile('userconnected');
GNAssetHelper::cssFile('topsearch-pagelet-form');
GNAssetHelper::cssFile('search-activities');
GNAssetHelper::cssFile('pagelet-stream-post');
GNAssetHelper::cssFile('pagelet-stream-setting-streamstory');

GNAssetHelper::scriptFile('jquery.wookmark.min', CClientScript::POS_END);
?>
<?php
if($nodeId == currentUser()->hexID){
	$this->renderPartial('application.views.common.user-related', compact('user'));	
}else{
	$this->widget('widgets.node.SlideBar', array(
		'nodeId' => $nodeId,
	));
}
?>



	
<div class="wd-container">
<div class="wd-center wd-center-content-layout3">
	<div class="wd-right-content">
		
		<!-- How You're Connected -->
		<?php $this->widget('application.modules.followings.components.widgets.ZoneFollowingHowConnected', array(
			'object_id' => $nodeId,
		)); ?>
		<!-- How You're Connected .end -->
		<!-- People’s you may know -->
		<?php $this->widget('application.modules.friends.components.widgets.ZoneFriendsPeopleYouMayKnow'); ?>
		<!-- People’s you may know .end -->
		<!-- People also viewed -->
		<?php $this->renderPartial('application.modules.users.views.elements.people-also-view') ?>
		<!-- People also viewed .end -->
		<!-- YouLook for mobile -->
		<?php $this->renderPartial('application.modules.users.views.elements.youlook-for-mobile') ?>
		<!-- YouLook for mobile .end -->
		
		
	</div>
	<div class="wd-contain-content">
<!-- header line -->
		<?php
		
		
		
		$this->widget('application.modules.articles.widgets.NamespaceMenuWidget', array(
				'namespaceID' => $nodeId,
				'viewPath' => 'application.modules.articles.widgets.views.menu'
			));

		?>
<!-- header line .end-->
<!-- main content -->
		<div class="wd-main-content">
			
			<div class="wd-viewallobjn-topsearch-form">
				<div class="wd-search-activities wd_parenttoggle floatL">
					<a href="#" class="wd-activities-bt wd_toggle_bt">All Activities<span class="wd-arrow"></span></a>
					<div class="wd-search-activities-toggle wd_toggle">
						<div class="wd-search-activities-content">
							<ul>
								<li><a href="#">Share Activities</a></li>
								<li><a href="#">Comment Activities</a></li>
								<li><a href="#">Post Activities</a></li>
								<li><a href="#">Create Activities</a></li>
								<li><a href="#">Share Activities</a></li>
								<li><a href="#">Comment Activities</a></li>
								<li><a href="#">Post Activities</a></li>
								<li><a href="#">Create Activities</a></li>
								<li><a href="#">Share Activities</a></li>
								<li><a href="#">Comment Activities</a></li>
								<li><a href="#">Post Activities</a></li>
								<li><a href="#">Create Activities</a></li>
							</ul>
						</div>
					</div>
				</div>
				<fieldset class="wd-topsearch-pagelet-form floatR">
					<div class="wd-input-search">
						<form action="<?php echo GNRouter::createUrl("/articles/views/index");?>" method="GET">
						<input type="hidden" value="<?php echo $nodeId;?>" name="id">
						<input type="text" placeholder="Search..." name="keywordArticle" class="wd-text-search" value="<?php echo ($keywordArticle!=null) ? $keywordArticle : "";?>">
						<input type="submit" value="" class="wd-submit wd-tooltip-hover" title="Search">
						</form>
					</div>
				</fieldset>
				<div class="clear"></div>
			</div>
			<div class="wd-pagelet-stream-wiew">
				<ul class="wd-streamstory-lo2" id="findLi">
					<li class="wd-streamstory-lo2-item ml0" id="findFormPost">
						<?php
						$this->widget('widgets.formPost.FormPost',array(
							'authorId'=>currentUser()->hexID,
							'namespaceId'=>$nodeId,
							'bothType'=>false,
							'textPostArticle'=>'Article',
							'textPostPhoto'=>'Photos',
							'placeholderPostPhoto'=>'Import content or (Copy link paste here)',
							'placeholderPostArticle'=>'Article title...',
							'realTime'=>array(
								'containerObject'=>'#articleSelector',
								'viewArticle'=>'application.views.common.articles.item_view_all',
								'status'=>true
							),
							'editor'=>"true"
							
						));
						?>
						
					</li>
					<div id="articleSelector">
					<?php
					$this->widget('ext.timeago.JTimeAgo', array(
						'selector' => ' .timeago',

					));
					$cnt = $articles['pagination']->currentPage * $articles['pagination']->pageSize;
					$countItem = 0;
					if(!empty($articles['data'])){
						foreach($articles['data'] as $key=>$article){
							$countItem = $cnt + $key;
							$this->renderPartial('//common/articles/item_view_all',array(
								'article'=>$article,
								'key'=>$countItem,
							));
							

						}
					}
					?>
					</div>
					
					
				</ul>
				
				<?php
				// dump($articles);
				if(!empty($articles['pagination'])){
					$pages = $articles['pagination'];
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
							'img'=>baseUrl()."/img/front/ajax-loader.gif",
							'msgText'=>'Loading more...'
						),
						'callback'=>"
							try {
								zone.articles.initLinks($(newElements).find('.js-article-delete'));
							} catch(e) {}
						",
					));
				}
				?>
				
				
			</div>
			
		</div>
<!-- main content .end-->
	</div>
	<div class="clear"></div>
</div>
</div>
<script>

function initWookmark(){
	if($.trim($("#articleSelector").html()) != ""){
		$('ul.wd-streamstory-lo2 li.wd-streamstory-lo2-item').wookmark({
			itemWidth: "48.5%", // Optional min width of a grid item
			autoResize: true, // This will auto-update the layout when the browser window is resized.
			container: $('.wd-pagelet-stream-wiew'), // Optional, used for some extra CSS styling
			offset: 20, // Optional, the distance between grid items
		});
	}
	
}

$(document).ready(function(){
	/* sort */
	
	initWookmark();
	
	$(document).ajaxComplete(function() {
		initWookmark();
		try{
			jQuery(" .timeago").timeago();
		}catch(e){
			console.log(e.message());
		}
	});
	var heightFirst = $("#findFormPost form textarea").height();
	$('body').on('keyup', '#findFormPost form textarea,.redactor_editor', function(e){
		if($(this).height() > heightFirst){
			
			initWookmark();
			
		}
	});
	
	
	setInterval(function(){
		initWookmark();
	},500);
	
});
</script>
<style>
#infscr-loading{
	position:absolute!important;
	bottom:-10px!important;
	left:44%!important;
}
</style>