{{if !error && data.length}}
<div class="wd-type-combined-item">
	<h2 class="wd-tt-7">friend requests</h2>
	<ul class="wd-list-6 wd-list-friend-request">
		{{each(index, friend) data}}
		<li>
			<a class="wd-avatar" href="/profile/${friend.username}">
				<img alt="" src="${CDNUrl}/upload/user-photos/${friend.id}/fill/74-74/${friend.avatar}?album_id=${friend.id}" width="74" height="74">
			</a>
			<div class="wd-right-list-content">
				<h3 class="wd-tt"><a href="/profile/${friend.username}" class="wd-text-b">${friend.displayname}</a></h3>
				<p class="wd-infobar">{{html friend.location}}</p>
				<p class="wd-btn-bar js-friend-request-remove-this" data-user_id="${friend.user_id}">
					<a class="wd-gray-bt wd-follow-bt-3 js-friend-request" data-action="accept" data-user_id="${friend.user_id}" href="javascript:void(0)">Confirm</a>
					<span class="wd-dot"></span><a class="wd-btn-purple js-friend-request" data-action="deny" data-user_id="${friend.user_id}" href="javascript:void(0)">Not now</a>
				</p>
			</div>
		</li>
		{{/each}}
	</ul>
</div>
{{/if}}