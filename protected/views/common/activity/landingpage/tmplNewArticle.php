{{if (object_type == 'Article')}}
<li class="wd-jewelItem">
	<a class="wd-jewelMainLink" href="${homeURL}/node?id=${related.object.zone_id}&action=article-detail&a_id=${related.article.id}">
		<img width="34" height="34" alt="${related.user.displayname}" src="${CDNUrl}/upload/user-photos/${related.user.id}/fill/38-38/${related.user.profile.image}?album_id=${related.user.id}" class="wd-jl">
		<div class="wd-jItermContent">
			<div class="of_1 font_11">
				<p class="wd_tt_mn"><strong>${related.user.displayname}</strong> wrote new article for <span>${related.object.name}</span></p>
			</div>
		</div>
	</a>
</li>
{{else (object_type == 'Album')}}
<li class="wd-jewelItem">
	<a class="wd-jewelMainLink" href="${homeURL}/node?id=${related.object.zone_id}&tab=photos">
		<img width="34" height="34" alt="${related.user.displayname}" src="${CDNUrl}/upload/user-photos/${related.user.id}/fill/38-38/${related.user.profile.image}?album_id=${related.user.id}" class="wd-jl">
		<div class="wd-jItermContent">
			<div class="of_1 font_11">
				<p class="wd_tt_mn"><strong>${related.user.displayname}</strong> created new album for <span>${related.object.name}</span></p>
			</div>
		</div>
	</a>
</li>
{{else (object_type == 'Node')}}
<li class="wd-jewelItem">
	<a class="wd-jewelMainLink" href="${homeURL}/zone/pages/detail?id=${related.object.zone_id}">
		<img width="34" height="34" alt="${related.user.displayname}" src="${CDNUrl}/upload/user-photos/${related.user.id}/fill/38-38/${related.user.profile.image}?album_id=${related.user.id}" class="wd-jl">
		<div class="wd-jItermContent">
			<div class="of_1 font_11">
				<p class="wd_tt_mn"><strong>${related.user.displayname}</strong> started following <span>${related.object.name}</span></p>
			</div>
		</div>
	</a>
</li>
{{else (object_type == 'Video')}}
<li class="wd-jewelItem">
	<a class="wd-jewelMainLink" href="${homeURL}/video/detail?id=${related.video.id}">
		<img width="34" height="34" alt="${related.user.displayname}" src="${CDNUrl}/upload/user-photos/${related.user.id}/fill/38-38/${related.user.profile.image}?album_id=${related.user.id}" class="wd-jl">
		<div class="wd-jItermContent">
			<div class="of_1 font_11">
				<p class="wd_tt_mn"><strong>${related.user.displayname}</strong> created new video for <span>${related.object.name}</span></p>
			</div>
		</div>
	</a>
</li>
{{/if}}