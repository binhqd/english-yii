(function($) {
	$.fn.truncatable = function(o) {
		var defaults = {
			limit:    10,
			more:     ' Read more',
			less:     false,
			hideText: '[read less]'
		};
		
		var options = $.extend(defaults, o);

		return this.each(function(num) {
			var htmlContent = $(this).html().trim();
			
			var stringLength = htmlContent.length;
		
			if (stringLength <= defaults.limit) {
				return;
			}
		  
		  var splitText = htmlContent.substr(defaults.limit);
		  var splitPoint = splitText.substr(0, 1);
		  var whiteSpace = new RegExp(/^\s+$/);
		  
		  for (var newLimit = defaults.limit; newLimit < stringLength; newLimit++) {
			var newSplitText = htmlContent.substr(0, newLimit);
			var newSplitPoint = newSplitText.slice(-1);
			
			if (!whiteSpace.test(newSplitPoint)) {
				continue;
			}
			
			var newHiddenText = htmlContent.substr(newLimit);
			var hiddenText = '<span class="hiddenText_' + num + '" style="display:none">' + newHiddenText + '</span>';
			var setNewLimit = (newLimit - 1);
			var trunkLink = $('<a>').attr('class', 'more_' + num + '');
			
			$(this).html(htmlContent.substr(0, setNewLimit) + '<a class="more_' + num + '" href="#">' + defaults.more + '</a> ' + hiddenText);
			
			$('a.more_' + num).live('click', function() {
				$('span.hiddenText_' + num).show();
				$('a.more_' + num).hide();
				if (defaults.less == true) {
				$('span.hiddenText_' + num + ' > a.hide_' + num).remove();
				$('span.hiddenText_' + num).append('<a class="hide_' + num + '" href="#" title="' + defaults.hideText + '">' + defaults.hideText + '</a>');
				$('a.hide_' + num).live('click', function() {
				  $('.hiddenText_' + num).hide();
				  $('.more_' + num).show();
				  $('.hide_' + num).empty();
				  return false;
				});
			  }
			  return false;
			});
			
			newLimit = stringLength;
		  }
		});
	  };
})(jQuery);