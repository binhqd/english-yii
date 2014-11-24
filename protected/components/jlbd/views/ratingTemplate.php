<script id="ratingTemplate" type="text/x-jquery-tmpl">
<div class="jlbd-rating-nested jlbd-rating-nested-${avg_rate}">
	<span class="star-rating-control">
		<div class="rating-cancel" style="display: block; "><a title="Cancel Rating"></a></div>
		<div {{if disable}}style="cursor: default;"{{/if}} class="star-rating rater-0 star big-star-yellow star-rating-applied star-rating-live">{{if !disable}}<a class="jlbd-tiptip-top" title="${title} [1 star]">${title}</a>{{/if}}</div>
		<div {{if disable}}style="cursor: default;"{{/if}} class="star-rating rater-0 star big-star-yellow star-rating-applied star-rating-live">{{if !disable}}<a class="jlbd-tiptip-top" title="${title} [2 star]">${title}</a>{{/if}}</div>
		<div {{if disable}}style="cursor: default;"{{/if}} class="star-rating rater-0 star big-star-yellow star-rating-applied star-rating-live">{{if !disable}}<a class="jlbd-tiptip-top" title="${title} [3 star]">${title}</a>{{/if}}</div>
		<div {{if disable}}style="cursor: default;"{{/if}} class="star-rating rater-0 star big-star-yellow star-rating-applied star-rating-live">{{if !disable}}<a class="jlbd-tiptip-top" title="${title} [4 star]">${title}</a>{{/if}}</div>
		<div {{if disable}}style="cursor: default;"{{/if}} class="star-rating rater-0 star big-star-yellow star-rating-applied star-rating-live">{{if !disable}}<a class="jlbd-tiptip-top" title="${title} [5 star]">${title}</a>{{/if}}</div>
	</span>
	{{if !disable}}
	<input name="${name}" type="radio" class="star big-star-yellow star-rating-applied" style="display: none; ">
	<input name="${name}" type="radio" class="star big-star-yellow star-rating-applied" style="display: none; ">
	<input name="${name}" type="radio" class="star big-star-yellow star-rating-applied" style="display: none; ">
	<input name="${name}" type="radio" class="star big-star-yellow star-rating-applied" style="display: none; ">
	<input name="${name}" type="radio" class="star big-star-yellow star-rating-applied" style="display: none; ">
	{{/if}}
</div>
</script>