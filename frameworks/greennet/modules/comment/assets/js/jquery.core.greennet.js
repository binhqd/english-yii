(function($){
	$.fn.greennetExpand = function($o){
		var $defaults = {
			readMoreText	: 'Read more',
			readLessText	: 'Read less',
			numberOfWord	: 100,
			readLess		: true,
		};
		var $options = $.extend($defaults, $o);
		
		this.each(function(){
			var $obj		= $(this);
			var $textPrefix	= $obj.html().trim();
			var $limit		= $options.numberOfWord;
			var $textSuffix	= null;
			var $readMore	= '<a class="cgn-readmore" href="javascript:void(0);" style="cursor:pointer;">' + $defaults.readMoreText + '</a>';
			var $readLess	= '';
			var $space		= ' ';
			if ($defaults.readLess){
				$readLess	= '<a class="cgn-readless" href="javascript:void(0);" style="cursor:pointer;display:none">' + $defaults.readLessText + '</a>';
			}
			if ($textPrefix.length > $limit) {
				var $word	= $textPrefix.substr($limit,1);
				
				if ($word!=$space) {
					while ($word!=$space) {
						$limit	-= 1;
						$word	= $textPrefix.substr($limit,1);
					}
				}
				$textSuffix	= $space + $textPrefix.substr($limit).trim();
				$textPrefix	= $textPrefix.substr(0,$limit).trim()+'<span class="threeDot">...</span>';
				$obj.html($textPrefix);
				$obj.append('<span class="textSuffix" style="display:none"> ' + $textSuffix + ' </span>' + '<span class="cgn-readmoreless">' +$readLess + $readMore + '</span>');
			}
			$obj.find('.cgn-readmoreless').live('click', function(){
				$obj.find('.threeDot').toggle("fast");
				$obj.find('.textSuffix').toggle();
				$obj.find('.cgn-readless').toggle();
				$obj.find('.cgn-readmore').toggle();
			});
		});
	};
})(jQuery);