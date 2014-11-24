<?php 
	GNAssetHelper::init(array(
		'image' => 'img',
		'css' => 'css',
		'script' => 'js',
	));
	GNAssetHelper::setBase('myzone');
	GNAssetHelper::scriptFile('flvplayer/swfobject', CClientScript::POS_HEAD);
	GNAssetHelper::setBase('myzone_v1');
	GNAssetHelper::cssFile('type-detail-comment-box');
	GNAssetHelper::scriptFile('greennet.replace.link', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.cycle.all');
?>
<script id="tmpListVideoItem" type="text/x-jquery-tmpl">
{{each(i, video) videos}}
	{{if (video.id != current_video_id)}}
	<li>
		<a href="${homeUrl}/video/detail?id=${video.id}" class="wd-img-vd">
			{{if (video.thumbnail != '' && video.thumbnail != null && video.thumbnail != "null")}}
			<img src="${CDNUrl}/upload/videos/fill/100-65/${video.thumbnail}?album_id=${video.object_id}" alt="${video.title}">
			{{else}}
			<img src="${homeUrl}/myzone_v1/img/video-default.jpg" width='100' height='65' alt="${video.title}">
			{{/if}}
		</a>
		<div class="wd-video-info-right">
			<h3 class="wd-title">
				<a href="${homeUrl}/video/detail?id=${video.id}">${video.title}</a>
			</h3>
			<p class="wd-info-poster">by <a href="${homeUrl}/profile/${video.poster.username}" class="wd-username">${video.poster.displayname}</a>
			</p><p><span>${video.views}</span> <span class="wd-gray">{{if (video.views!=1)}}Views{{else}}View{{/if}}</span></p>
		</div>
	</li>
	{{/if}}
{{/each}}
</script>
<style>
.wd-type-rate {background: none !important;}
#video-side-ads {
	width: 288px;
	height:58px;
	clear: both;
	overflow: hidden;
}
</style>
<div class="wd-container wd-container-video-detail">
	<div class="wd-center">
		<!-- header - title movie -->
		<div class="wd-type-header">
			<div class="wd-type-header-left">
				<h2 class="wd-type-tt-1 title-video"><a href='<?php echo ZoneRouter::createUrl("/{$nodeType}?id={$node['zone_id']}")?>'><?php echo $node['name']?></a></h2>
<!-- 				<p>133 min  -  Action | Thriller  -  21 December 2011 (USA) </p> -->
			</div>
		</div>
		<!-- header - title movie .end -->
		<!-- main container -->
		<div class="wd-type-container">
			<!-- right content-->
			<div class="wd-type-right-content">
				<div class="wd-type-right-advertising" id='video-side-ads'>
					<a target="_blank" href='http://www.tnmonex.com'><img src="<?php echo ZoneRouter::createUrl('/')?>/myzone_v1/img/tnmonex.jpg" alt="advertising"></a>
					<a target="_blank" href='http://www.memoryamerica.com'><img src="<?php echo ZoneRouter::createUrl('/')?>/myzone_v1/img/memoryamerica.jpg" alt="advertising"></a>
					<a target="_blank" href='http://www.nationalcreditcard.us'><img src="<?php echo ZoneRouter::createUrl('/')?>/myzone_v1/img/nccp.jpg" alt="advertising"></a>
				</div>
<!-- Video right  -->
				
				<ul class="wd-type-right-listVideo" id="youlook-right-list-video">
				</ul>
				<div class="wd-bt-block" style='display:none'>
					<a class="wd-bt-load-more" href="#" id='lnkLoadMoreSideVideos'>Load more</a>
				</div>
			</div>
			<!-- right content .end-->
			<!-- main content-->
			<div class="wd-type-main-content">
				<div class="wd-type-view-video-detail">
					<h2 class="wd-tt-7"><a href="<?php echo ZoneRouter::createUrl("/{$nodeType}?id={$node['zone_id']}&tab=videos")?>">Video</a>: <span><?php echo $video['video']['title']?></span></h2>
					
					<div class="wd-video-detail-frame">
						<?php if(!$video['video']['is_converted'] || !$video['video']['data_status']):?>
							<div class="wd-empty-results-description">
								<?php if(!$video['video']['data_status']) : ?>
									<p class="mt35">Sorry, This video has been deleted! </p>
								<?php else : ?>
									<p class="mt35">Sorry, This video converting! </p>
								<?php endif;?>
							</div>
						<?php else :?>
							<div class="youtube-player jquery-youtube-tubeplayer">
								<?php 
								$url = "";
								if ($isYoutube) {
									$pattern = "/(&|\?)v=([a-zA-Z0-9\-_]+)/";
									preg_match($pattern, $video['video']['url'], $matches);
										
									if (!empty($matches) && isset($matches[2])) {
										$vid = $matches[2];
										$url = "http://www.youtube.com/embed/{$vid}?showinfo=0&amp;ps=docs&amp;autoplay=1&amp;iv_load_policy=3&amp;modestbranding=1&amp;nologo=1;wmode=transparent";
									}
									
								} else {
									$url = "/upload/videos/{$video['video']['video']}?autoplay=0&amp;autohide=0&amp;controls=1&amp;loop=0&amp;playlist&amp;rel=0&amp;fs=0&amp;wmode=transparent&amp;showinfo=0&amp;modestbranding=1&amp;iv_load_policy=1&amp;start=0&amp;theme=dark&amp;color=red&amp;playsinline=false&amp;enablejsapi=1";
								}
								?>
								<?php if ($isYoutube):?>
								<iframe id="tubeplayer-player-container-d551598a-2125-4e58-a7dc-46a9af5c79f4" 
								frameborder="0" allowfullscreen="1" 
								title="YouTube video player" width="100%" height="385" 
								src="<?php echo $url?>">
								</iframe>
								<?php else:?>
								<div class="media">
									<div id="youlook-player">
										<p>
											<a href="http://www.adobe.com/go/getflashplayer">
												<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
											</a>
										</p>
									 </div>
									<script type="text/javascript">
										(function($) {
											$(function() {
												var params = {};
												var flashvars = {
													movie: "<?php echo ZoneRouter::createUrl('/upload/videos/'.$node['zone_id'] . '/' . $video['video']['video'])?>",
													color_background: "efefef",
													name: "Youlook Video Player",
													controls: true,
													autoplay: false,
													buffer: false,
													preview: "<?php echo ZoneRouter::CDNUrl('/upload/videos/fill/620-375/'.$video['video']['thumbnail'] . '?album_id=' . $node['zone_id'])?>"
												};
												var attributes = {
													allowfullscreen: 'true',
													wmode: 'opaque',
													allowscriptaccess: 'always'
												};
												swfobject.embedSWF("/myzone/js/flvplayer/youlook.swf", "youlook-player", "620", "375", "9.0.0", "/myzone/js/flvplayer/expressInstall.swf", flashvars, params, attributes);
											});
										})(jQuery);
									</script> 
									<div><?php echo $video['video']['title'] ?></div> 
								</div>
								<?php endif;?>
							</div>
						<?php endif;?>
					</div>
					<?php if($video['video']['is_converted'] && $video['video']['data_status']):?>
					<div class="wd-video-detail-info">
						<div class="wd-video-detail-info-block">
							<div class="wd-entry-content-box item-box  hide-row-comment">
								<a class="wd-thumb" href="<?php echo ZoneRouter::createUrl('/profile/' . $video['video']['poster']['username'])?>" title="<?php echo $video['video']['poster']['displayname']?>">
									<img class="avatar" width="44" height="44" alt="<?php echo $video['video']['poster']['displayname']?>" src="<?php echo ZoneRouter::createUrl('/upload/user-photos/' . $video['video']['poster']['id'] . '/fill/44-44/' . $video['video']['poster']['profile']['image'])?>">
								</a>
								<div class="wd-contenright-box">
									<p class="wd-commentpost">
										<a href="<?php echo ZoneRouter::createUrl('/profile/' . $video['video']['poster']['username'])?>" class="wd-userpost-name"><?php echo $video['video']['poster']['displayname']?></a>
										<span class="wd-time-posted youlook-timeago" style="display:none">uploaded <abbr class="timeago" title="<?php echo $video['video']['timeIso']?>"><?php echo $video['video']['timeInt']?></abbr> </span>
									</p>
								</div>
							</div>
						</div>
						<div class="wd-video-detail-info-block" style="float: right;">
							<p class="wd-video-detail-number-view"><strong><?php echo $video['video']['views']?></strong> View<?php if($video['video']['views']!=1) echo 's'?></p>
							
							<?php 
								$this->widget('widgets.like.Like', array(
									'objectId'		=> $video['video']['id'],
									'modelObject'	=> 'likeObject',
									'modelStatistic'=> 'likeStatistic',
									'actionLike'	=> ZoneRouter::createUrl('/like/liked/liked'),
									'actionUnlike'	=> ZoneRouter::createUrl('/like/liked/like'),
									'classLike'		=> 'wd-like-bt',
									'classUnlike'	=> 'wd-like-bt',
									'ratingTemplate'=> 'application.modules.like.views.templates.zone-like'
								));
							?>
							
						</div>
					</div>
					<div class="wd-video-detail-content">
						<div class="wd-video-detail-content-inner greennet-replace-link">
							<?php echo nl2br($video['video']['description']);?>
						</div>	
					</div>
					<div class="wd-type-detail-comment-box wd-video-detail-comment">
						<h3 class="wd-tt-comment"><a href="#">All Comments <span class="wd-number">(<?php echo ZoneComment::model()->countComments($video['video']['id'])?>)</span></a></h3>
						<div class="wd-video-detail-comment">
							
							<div class="wd-bt-block">
								<?php 
								$countComments = ZoneComment::model()->countComments($video['video']['id']);
								
								$token = uniqid();
								//$countComment = 45;
								$limit = 3;
								$viewItemPath = 'application.modules.resources.views.videos.comment-item';
								$viewFormPath = 'application.modules.resources.views.videos.form';
								$this->widget('widgets.comment.Comment', array(
									'objectId'		=> $video['video']['id'],
									'countComment'	=> $countComments,
									'viewMore'		=> false,
									'loadJsTimeAgo'	=> false,
									'limit'			=> $limit,
									'strToken'		=> $token,
									'viewItemPath'	=> $viewItemPath,
									'viewFormPath'	=> $viewFormPath
								));
								?>
								
								<?php if ($countComments > $limit):?>
								<div class="wd-content-box wd-viewall-box">
									<a href="javascript:void(0)" 
									class="wd-bt-load-more"
									viewPath="<?php echo $viewItemPath;?>" 
									onclick="viewMore('<?php echo $token;?>')" 
									id="viewMore<?php echo $token;?>" 
									limit="10" 
									objectId="<?php echo $video['video']['id'];?>" 
									ref="<?php echo GNRouter::createUrl('/comments/comment/lists')?>" 
									startList="<?php echo $limit;?>" 
									totalRecord="<?php echo $countComments;?>">
										See more
									</a>
									<span id="loadingComment<?php echo $token;?>" style="display:none">
										<div class="loader-show-more-comment" >
											<img src="<?php echo GNRouter::createUrl('/');?>/myzone_v1/img/front/ajax-loader.gif" alt="loading">
										</div>
									</span>
								</div>
								<?php endif;?>
							</div>
						</div>
					</div>
					<?php endif;?>
				</div>
			</div>
			<!-- main content .end -->
			<div class="clear"></div>
		</div>
		<!-- main container -->
	</div>
</div>
<script>
	function loadListVideos(id, _page, limit) {
		var pages	= '<?php echo count(ZoneResourceVideo::getTotalByType($node['zone_id'], 'all', -1, 0))?>';
		
		pages = pages/limit;
		if(pages%limit>0){
			pages++;
		}
		
		$.ajax({
			url : '<?php echo ZoneRouter::createUrl("/api/node/videos");?>?id='+id+'&limit=' + limit + '&page=' + _page,
			dataType: 'json',
			success: function(res){
				if (res.videos.length == 0) {
					$('#lnkLoadMoreSideVideos').parent().hide();
				} else {
					var videos = $.tmpl($("#tmpListVideoItem"), res);
					
					// hide view more link if page is the end
					if (res.videos.length < limit) {
						$('#lnkLoadMoreSideVideos').parent().hide();
					} else {
						$('#lnkLoadMoreSideVideos').parent().show();
					}
					$("ul#youlook-right-list-video").append(videos);
					page++;
					if(page>pages){
						$('#lnkLoadMoreSideVideos').parent().hide();
					}
				}
			}
		});
	}
	
	
	var page = 1;
	var current_video_id = '<?php echo $video['video']['id']?>';
	var object_id = '<?php echo $video['video']['object_id']?>';
	$(document).ready(function(){
		$('a.media').media();
		loadListVideos(object_id, page, 10);

		$('#lnkLoadMoreSideVideos').click(function() {
			loadListVideos(object_id, page, 10);
			return false;
		});

		$('.wd-video-detail-content-inner').greennetExpand({
			readMoreText	: 'Read more',
			readLessText	: 'Read less',
			numberOfWord	: 200,
			dot				: '...',
			readLess		: false,
			readMore		: true
		});
		
		$('.youlook-comment-video-detail').greennetExpand({
			readMoreText	: 'see more',
			readLessText	: 'see less',
			numberOfWord	: 250,
			dot				: '...',
			readLess		: false,
			readMore		: true
		});
		
		// ads
		$('#video-side-ads').cycle({
			fx		: 'none',
			timeout : 2000
		});
	});
</script>