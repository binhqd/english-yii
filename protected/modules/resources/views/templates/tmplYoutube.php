<script id="tmplYoutubeResultItem" type="text/x-jquery-tmpl">
	{{each( i, item ) items}}
		<li class="wd-video-item {{if i%2==0}}wd-video-item-odd{{/if}} {{if items.length==1}}bdrno{{/if}}">
			<div class="wd-video-item-content">
				<div class="wd-video-item-left">
					<a href="${homeUrl}/video/detail?yid=${item.id}" class="wd-image"><img src="http://i1.ytimg.com/vi/${item.id}/mqdefault.jpg" alt="Eagle steals camera near crocodile meat trap" height="112" width="200"/></a>
					<a href="${homeUrl}/video/detail?yid=${item.id}" class="wd-bg"></a>
					<span class="wd-time-play gn-to-time">${item.duration}</span>
					<a href="${homeUrl}/video/detail?yid=${item.id}" class="wd-play-bt-5"></a>
				</div>
				<div class="wd-video-item-right">
					<h3 class="wd-title-vd"><a href="${homeUrl}/video/detail?yid=${item.id}">${item.title}</a></h3>
					{{if typeof item.viewCount!='undefined'}}
						<p class="wd-desc-v"><span class="wd-text-st youlook-views-count">${item.viewCount}</span><span class="wd-text-sp"> View{{if (item.viewCount!=1)}}s{{/if}}</span></p>
					{{else}}
						<p class="wd-desc-v"><span class="wd-text-st">0 </span><span class="wd-text-sp">Views</span></p>
					{{/if}}
					<p class="wd-desc-info youlook-youtube-description">${item.description}</p>
				</div>
			</div>
		</li>
	{{/each}}
</script>