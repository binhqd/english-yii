{{if (data.object_type == 'Video')}}
<li class="wd-ticker-story">
	{{if (data.type == 'self-posting')}}
	<a href="${homeURL}/video/detail?id=${data.video.id}" class="wd-ticker-storyblocklink">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span> added new video: <span class="wd-token">${data.video.title}</span></h5>
			</div>
		</div>
	</a>
	{{/if}}
</li>
{{else (data.object_type == 'Album')}}
<li class="wd-ticker-story">
	{{if (data.type == 'self-posting')}}
	<a href="${homeURL}/user/${encodeURIComponent(data.user.username)}?tab=photos&action=album_detail&album_id=${data.album.id}" class="wd-ticker-storyblocklink">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span>  created new album: <span class="wd-token">${data.album.title}</span></h5>
			</div>
		</div>
	</a>
	{{else (data.type == 'other-posting')}}
	<a href="${homeURL}/user/${encodeURIComponent(data.user.username)}?tab=photos&action=album_detail&album_id=${data.album.id}" class="wd-ticker-storyblocklink">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span>  created new album: <span class="wd-token">${data.album.title}</span></h5>
			</div>
		</div>
	</a>
	{{/if}}
</li>
{{else (data.object_type == 'Photo')}}
<li class="wd-ticker-story">
	{{if (data.type == 'self-like-a-photo')}}
	<a href="${homeURL}/photos/viewPhoto?photo_id=${data.photo.photo.id}&album_id=${data.album.id}" album_id="${data.album.id}}" photo_id="${data.photo.photo.id}" filename="${data.photo.photo.image}" class="wd-ticker-storyblocklink lnkViewPhotoDetail">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span> liked a photo on album: <span class="wd-token">${data.album.title}</span></h5>
			</div>
		</div>
	</a>
	{{else (data.type == 'self-comment-on-a-photo')}}
	<a href="${homeURL}/photos/viewPhoto?photo_id=${data.photo.photo.id}&album_id=${data.album.id}" album_id="${data.album.id}}" photo_id="${data.photo.photo.id}" filename="${data.photo.photo.image}" class="wd-ticker-storyblocklink lnkViewPhotoDetail">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span> comment to a photo on album <span class="wd-token">${data.album.title}</span>: "${data.content}"</h5>
			</div>
		</div>
	</a>
	{{/if}}
</li>
{{else (data.object_type == 'Article')}}
<li class="wd-ticker-story">
	{{if (data.type == 'self-posting')}}
	<a href="${homeURL}/user/${encodeURIComponent(data.user.username)}?action=article-detail&a_id=${data.article.id}" class="wd-ticker-storyblocklink">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span>  created new article: <span class="wd-token">${data.article.title}</span></h5>
			</div>
		</div>
	</a>
	{{else (data.type == 'other-posting')}}
	<a href="${homeURL}/user/${encodeURIComponent(data.user.username)}?action=article-detail&a_id=${data.article.id}" class="wd-ticker-storyblocklink">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span>  created new article: <span class="wd-token">${data.article.title}</span> on ${data.receiver.displayname}'s timeline</h5>
			</div>
		</div>
	</a>
	{{else (data.type == 'node-posting')}}
	<a href="${homeURL}/article?article_id=${data.article.id}" class="wd-ticker-storyblocklink">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span>  created new article: <span class="wd-token">${data.article.title}</span> on ${data.node.name}</h5>
			</div>
		</div>
	</a>
	{{else (data.type == 'like')}}
	<a href="${homeURL}/user/${encodeURIComponent(data.article.author.username)}?action=article-detail&a_id=${data.article.id}" class="wd-ticker-storyblocklink">
		<div class="wd-ticker-storyblock">
			<img src="${CDNUrl}/upload/user-photos/${data.user.id}/fill/40-40/${data.user.profile.image}" alt="${data.user.displayname}" height="40" width="40">
			<div class="wd-ticker-storycontent">
				<h5 class="wd-ticker-mess"><span class="wd-passive-name">${data.user.displayname}</span>  like article: <span class="wd-token">${data.article.title}</span></h5>
			</div>
		</div>
	</a>
	{{/if}}
</li>
{{/if}}