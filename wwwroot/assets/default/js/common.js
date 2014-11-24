(function($) {
	$(function() {

		$('.article-social-seperator').remove();

		if ($.fn.expander) {
			$('.view-more-container').expander({
				expandSpeed: 1000,
				collapseSpeed: 1000,
				slicePoint: 1000,
				beforeExpand: function() {
					var $expander = $(this);
					$expander.find('.details').css('min-height',
							$expander.find('.summary').height() + 'px');
				},
				afterExpand: function() {
					$(this).find('.details').css('min-height', '');
				},
				onCollapse: function() {
					$(this).data('expander').beforeExpand.call(this);
				},
				afterCollapse: function() {
					$(this).parent().data('expander').afterExpand.call($(this).parent());
				}
			});
		}

		var lastRequest = {abort: $.noop};
		$('.navbar-search .search-query').autocomplete({
			source: function(request, response) {
				lastRequest.abort();
				lastRequest = $.ajax({
					url: BASE_URL + '/pages/search',
					cache: true,
					dataType: "json",
					data: {
						term: request.term
					},
					success: function(data) {
						response(data.result);
					}
				});
			},
			minLength: 3,
			delay: 500,
			select: function(e, ui) {
				if (ui.item) {
					window.location.href = BASE_URL + '/pages/detail/?id=' + ui.item.id;
				}
			}
		});
	});
})(jQuery);