<script id="coregreennet-comment-item-template" type="text/x-jquery-tmpl">
	<div class="wd-content-box coregreennet-comment-item" objectId="${objectId}" commentId="${comment_id}">
		{{if isOwner}}
			<div class="coregreennet-delete-comment" style="display:none;"><img src="/assets/default/img/delete.png"></div>
		{{/if}}
		<a href="${profile_url}" class="wd-thumb"><img class="avatar" width="34" height="34" alt="You" src="${avatar_url}"></a>
		<div class="wd-right-box">
			<p class="wd-commentpost">
				<a href="${profile_url}" class="wd-userpost-name">
					<strong>${displayname}</strong>
				</a>
				<span class="contents">{{html comment_content}}</span>
			</p>
			<div class="wd-date-post">
				<ul>
					<li>
						<label class="cgn-time-count" style="float:left;">{{html comment_date}}</label>
					</li>
					<li>
						<div class="wd-pp-rate-content coregreennet-wd-rating" objectId="${rate.objectId}" ratingType="${rate.type}" suffix="${rate.suffix}">
							<a href="javascript:void(0);" class="coregreennet-rate-lr" action="${rate.action}" ratingValue="${rate.ratingValue}">${rate.text}</a>
							<span>
								<span class="coregreennet-self-rate">${rate.self}</span>
								<a class="coregreennet-people-rate" href="javascript:void(0);"> ${rate.people} {{if rate.people>0}} people {{/if}}</a>
								<span class="coregreennet-suffix-rate">{{if rate.self!=null || rate.people>0}} ${rate.suffix}{{/if}}</span>
							</span>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</script>