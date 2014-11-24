<?php
GNAssetHelper::init(array(
	'image' => 'img',
	'css' => 'css',
	'script' => 'js',
));

GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::scriptFile('jquery.core.readmore', CClientScript::POS_BEGIN);
GNAssetHelper::scriptFile('greennet.toTime', CClientScript::POS_BEGIN);
GNAssetHelper::scriptFile('greennet.view.number', CClientScript::POS_BEGIN);

GNAssetHelper::cssFile('search-video');

GNAssetHelper::cssFile('common-yl-layout');
?>
<?php $this->renderPartial('application.modules.resources.views.templates.tmplYoutube') ?>
<?php
	$keyword = urldecode($keyword);
?>

<div class="wd-yl-container wd-yl-container-video" id="youlook-search-result-container">
	<div class="wd-center">
		<div class="wd-top-header-p">
			<p class="wd-result-number"><span class="wd-str youlook-total-search-result"></span> <span class="wd-text youlook-text-search-result">results</span></p>
			<h2 class="wd-tt">Videos: <span class="wd-text-v"><?php if(!empty($keyword)) : ?> "<?php echo $keyword;?>"<?php endif;?></span></h2>
		</div>
		<div class="wd-video-results-container">
			<ul class="wd-video-result-ls" id="youlook-youtube-video-result">
			</ul>
			
			<div style="display:none" class="wd-list-stream-loading youlook-load-more-videos"><img src="/myzone_v1/img/front/ajax-loader.gif" alt="loading"><span>Loading more...</span></div>
			<a style="display:none" class="wd-list-stream-seamore youlook-youtube-list-stream-seamore">Show more videos</a>
		</div>
	</div>
</div>
<script>
	$(function(){
		var startIndexYoutube = 1;
		var maxResultsYoutube = 10;
		var is_loading = false;
		var query = "<?php echo $q?>";
		
		var _totalResult = $('#youlook-search-result-container .youlook-total-search-result');
		var _textResult = $('#youlook-search-result-container .youlook-text-search-result');
		getDataYoutube();
		
		$('.youlook-youtube-list-stream-seamore').click(function(){
			$('.youlook-load-more-videos').show();
			getDataYoutube();
		});
		
		function getDataYoutube(){
			$.ajax({
				url : 'https://gdata.youtube.com/feeds/api/videos',
				type : 'get',
				dataType: 'jsonp',
				data 			: {
					q : decodeURIComponent(query), // keyword search 
					v : 2,
					'start-index' : startIndexYoutube,
					'max-results' : maxResultsYoutube,
					alt : 'jsonc'
				},
				success : function(res){
					
					if(startIndexYoutube==1){
						_totalResult.html(res.data.totalItems);
						if(res.data.totalItems!=1) 
							_textResult.html(' results');
						else
							_textResult.html(' result');
					}
					
					var responses = $("#tmplYoutubeResultItem").tmpl(res.data);
					responses.find('.gn-to-time').greennetToTime();
					responses.find('.youlook-views-count').greennetViewNumber();
					responses.find('.youlook-youtube-description').greennetExpand({
						numberOfWord	: 100,
						dot				: '...',
						readLess		: false,
						readMore		: false
					});
					responses.appendTo("#youlook-youtube-video-result"); 
					
					var totalShow = res.data.startIndex + res.data.itemsPerPage;
					
					if(totalShow>=res.data.totalItems){
						$('.youlook-youtube-list-stream-seamore').remove();
					}
					$('.youlook-load-more-videos').hide();
					startIndexYoutube += maxResultsYoutube;
				}
			}).always(function() { is_loading = false; });
		};
		// load more
		
		$(window).scroll(function() {
			if(startIndexYoutube!=1){
				if($(window).scrollTop() + $(window).height() >= $(document).height()) {
					if (is_loading) {
						return;
					}
					is_loading = true;
					$('.youlook-youtube-list-stream-seamore').trigger('click');
				}
			}
		});
		
	})
</script>