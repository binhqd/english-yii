<script id="tmplMovieTopVideos" type="text/x-jquery-tmpl">
	{{each(i, video) videos}}
		{{if video.type!='other' }}
			
			<li class="youlook-video-item">
				<div class="wd-video-thumb-left">
					<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-img-thumb-video">
						{{if (video.thumbnail != '' && video.thumbnail != null && video.thumbnail != "null")}}
						<img src="${CDNUrl}/upload/videos/fill/250-140/${video.thumbnail}?album_id=${zoneid}" alt="${video.title}" height="140" width="250"/>
						{{else}}
						<img src="${CDNUrl}/myzone_v1/img/video-default.jpg" width='250' height='140' alt="${video.title}">
						{{/if}}
					</a>
					<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-type-video-play"></a>
					<span class="wd-video-time">${video.length}</span>
				</div>
				<div class="wd-video-info-right">
					{{if video.poster.id == user.id}}
						<div class="wd-setting-streamstory wd_parenttoggle ml25 mr5 floatR">
							<span class="wd-icon-16 wd-icon-setting-stream-story wd_toggle_bt wd-tooltip-hover" title="More functions..."></span>
							
							<div class="wd-setting-stream-content wd_toggle">
								<span class="wd-uparrow-1"></span>
								<ul>
									<li style="background: none; padding: 0;">
										<a href="javascript:void(0);" data-id="${video.id}" class="delete-user-property lnkRemoveVideo" type="top">
											Delete
										</a>
									</li>
								</ul>
							</div>
						</div>
					{{/if}}
				
					<h3 class="wd-title">
						<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>">${video.title}</a>
					</h3>
					<p class="wd-info-poster">By
						<a href="${homeURL}/profile/${video.poster.username}" class="wd-username">${video.poster.displayname}</a> 
						- <span class="wd-gray"><abbr class="timeago" title="${video.timeIso}">${video.timeInt}</abbr></span> 
						- <span class="wd-text-strong">${video.views}</span>
						<span class="wd-gray">View{{if video.views!=1}}s{{/if}}</span>
					</p>
					<!-- <p class="wd-language"><span class="wd-text-strong">Language:</span> English, Russian, French, Arabic</p> -->
					<p class="wd-description">{{html video.description}}</p>
				</div>
			</li>
		{{/if}}
	{{/each}}
</script>

<!-- Video  -->

<script id="tmplMovieVideos" type="text/x-jquery-tmpl">
	{{each(i, video) videos}}
		{{if video.type=='other' }}
			<li class="youlook-video-item">
				
				<div class="wd-video-thumb-left">
					<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-img-thumb-video">
						{{if (video.thumbnail != '' && video.thumbnail != null && video.thumbnail != "null")}}
						<img src="${CDNUrl}/upload/videos/fill/250-140/${video.thumbnail}?album_id=${zoneid}" alt="${video.title}" height="140" width="250"/>
						{{else}}
						<img src="${CDNUrl}/myzone_v1/img/video-default.jpg" width='250' height='140' alt="${video.title}">
						{{/if}}
					</a>
					<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-type-video-play"></a>
					<span class="wd-video-time">${video.length}</span>
				</div>
				<div class="wd-video-info-right">
					{{if video.poster.id == user.id}}
						<div class="wd-setting-streamstory wd_parenttoggle ml25 mr5 floatR">
							<span class="wd-icon-16 wd-icon-setting-stream-story wd_toggle_bt wd-tooltip-hover" title="More functions..."></span>
							
							<div class="wd-setting-stream-content wd_toggle">
								<span class="wd-uparrow-1"></span>
								<ul>
									<li style="background: none; padding: 0;">
										<a href="javascript:void(0);" data-id="${video.id}" class="delete-user-property lnkRemoveVideo" type="top">
											Delete
										</a>
									</li>
								</ul>
							</div>
						</div>
					{{/if}}
					<h3 class="wd-title">
						<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>">${video.title}</a>
					</h3>
					<p class="wd-info-poster">By
						<a href="${homeURL}/profile/${video.poster.username}" class="wd-username">${video.poster.displayname}</a> 
						- <span class="wd-gray timeago"><abbr class="timeago" title="${video.timeIso}">${video.timeInt}</abbr></span> 
						- <span class="wd-text-strong">${video.views}</span> 
						<span class="wd-gray">View{{if video.views!=1}}s{{/if}}</span>
					</p>
					<!-- <p class="wd-language"><span class="wd-text-strong">Language:</span> English, Russian, French, Arabic</p> -->
					<p class="wd-description">{{html video.description}}</p>
				</div>
			</li>
		{{/if}}
	{{/each}}
</script>
<script id="tmplMovieAddVideos" type="text/x-jquery-tmpl">
	<li class="youlook-video-item">
		
		<div class="wd-video-thumb-left">
			<a href="${homeURL}/video/detail?id=${id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-img-thumb-video">
				{{if (thumbnail != '' && thumbnail != null && thumbnail != "null")}}
				<img src="${CDNUrl}/upload/videos/fill/250-140/${thumbnail}?album_id=${zoneid}" alt="${title}" height="140" width="250"/>
				{{else}}
				<img src="${CDNUrl}/myzone_v1/img/video-default.jpg" width='250' height='140' alt="${title}">
				{{/if}}
			</a>
			<a href="${homeURL}/video/detail?id=${id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-type-video-play"></a>
			<span class="wd-video-time">${length}</span>
		</div>
		<div class="wd-video-info-right">
			{{if poster.id == user.id}}
				<div class="wd-setting-streamstory wd_parenttoggle ml25 mr5 floatR">
					<span class="wd-icon-16 wd-icon-setting-stream-story wd_toggle_bt wd-tooltip-hover" title="More functions..."></span>
					
					<div class="wd-setting-stream-content wd_toggle">
						<span class="wd-uparrow-1"></span>
						<ul>
							<li style="background: none; padding: 0;">
								<a href="javascript:void(0);" data-id="${id}" class="delete-user-property lnkRemoveVideo" type="top">
									Delete
								</a>
							</li>
						</ul>
					</div>
				</div>
			{{/if}}
			<h3 class="wd-title">
				<a href="${homeURL}/video/detail?id=${id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>">${title}</a>
			</h3>
			<p class="wd-info-poster">By 
				<a href="${homeURL}/profile/${poster.username}" class="wd-username">${poster.displayname}</a>  
				- <span class="wd-gray timeago"><abbr class="timeago" title="${timeIso}">${timeInt}</abbr></span> 
				- <span class="wd-text-strong">${views}</span> 
				<span class="wd-gray">View{{if views!=1}}s{{/if}}</span>
			</p>
			<!-- <p class="wd-language"><span class="wd-text-strong">Language:</span> English, Russian, French, Arabic</p> -->
			<p class="wd-description">{{html description}}</p>
		</div>
	</li>
</script>
<script id="tmplMovieAddPostVideos" type="text/x-jquery-tmpl">
	<li class="youlook-video-item">
		
		<div class="wd-video-thumb-left">
			<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-img-thumb-video">
				{{if (video.thumbnail != '' && video.thumbnail != null && video.thumbnail != "null")}}
				<img src="${CDNUrl}/upload/videos/fill/250-140/${video.thumbnail}?album_id=${zoneid}" alt="${video.title}" height="140" width="250"/>
				{{else}}
				<img src="${CDNUrl}/myzone_v1/img/video-default.jpg" width='250' height='140' alt="${video.title}">
				{{/if}}
			</a>
			<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>" class="wd-type-video-play"></a>
			<span class="wd-video-time">${video.length}</span>
		</div>
		<div class="wd-video-info-right">
			{{if video.poster.id == user.id}}
				<div class="wd-setting-streamstory wd_parenttoggle ml25 mr5 floatR">
					<span class="wd-icon-16 wd-icon-setting-stream-story wd_toggle_bt wd-tooltip-hover" title="More functions..."></span>
					
					<div class="wd-setting-stream-content wd_toggle">
						<span class="wd-uparrow-1"></span>
						<ul>
							<li style="background: none; padding: 0;">
								<a href="javascript:void(0);" data-id="${video.id}" class="delete-user-property lnkRemoveVideo" type="top">
									Delete
								</a>
							</li>
						</ul>
					</div>
				</div>
			{{/if}}
			<h3 class="wd-title">
				<a href="${homeURL}/video/detail?id=${video.id}<?php echo empty($_GET['username'])? '' : '&user=' . $_GET['username'] ?>">${video.title}</a>
			</h3>
			<p class="wd-info-poster">By 
				<a href="${homeURL}/profile/${video.poster.username}" class="wd-username">${video.poster.displayname}</a>  
				- <span class="wd-gray timeago"><abbr class="timeago" title="${video.timeIso}">${video.timeInt}</abbr></span> 
				- <span class="wd-text-strong">${video.views}</span> 
				<span class="wd-gray">View{{if video.views!=1}}s{{/if}}</span>
			</p>
			<!-- <p class="wd-language"><span class="wd-text-strong">Language:</span> English, Russian, French, Arabic</p> -->
			<p class="wd-description">{{html video.description}}</p>
		</div>
	</li>
</script>
<script id="tmplMovieResultsYoutubeVideos" type="text/x-jquery-tmpl">
	<li class="wd-links-item">
		<div class="wd-image"><img src="${thumbnail.sqDefault}" alt="" width="72" height="40"/></div>
		<div class="wd-main-content-item">
			<h3 class="wd-tt">${title}</h3>
			<p class="wd-des"><span class="wd-time">${duration}</span><span class="wd-dot-icon"></span><span class="wd-count-view">127,485 views</span></p>
		</div>
		<span class="wd-select-bt"></span>
	</li>
</script>