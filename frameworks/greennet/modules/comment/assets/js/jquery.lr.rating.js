$(function(){
	
	$('.coregreennet-rate-lr').live('click', function(){
		var $obj			= $(this);
		var $parent			= $obj.parent('.coregreennet-wd-rating');
		var $objectIdBin	= $parent.attr('objectId');
		var $action			= $obj.attr('action');
		var $ratingValue	= $obj.attr('ratingValue');
		var $ratingType		= $parent.attr('ratingType');
		var $suffix			= $parent.attr('suffix');

		$.ajax({
			url		: $action,
			type	: 'POST',
			datatype: 'json',
			data	: 'objectIdBin=' + $objectIdBin
						+ '&ratingType=' + $ratingType
						+ '&ratingValue=' + $ratingValue,
			success	: function($res){

				if ($res != null) {
					$obj.attr('ratingValue', $res.ratingValue);
					$obj.text($res.text);
					
					if ($res.self!=null) {
						$parent.find('.coregreennet-self-rate').text($res.self);
						$parent.find('.coregreennet-suffix-rate').text($suffix);
					} else {
						$parent.find('.coregreennet-self-rate').text('');
					}
					
					if ($res.self==null && $res.people==null) {
						$parent.find('.coregreennet-suffix-rate').text('');
					}
					
//					if ($res.ratingValue=='rate') {
//						addRemoveClass($obj, $classUnrate, $classRate);
//					} else {
//						addRemoveClass($obj, $classRate, $classUnrate);
//					}
				}
			},
		});
	});
});

// This method is used to change class of object.
function addRemoveClass($obj, $removeClass, $addClass, $text, $attrName, $attrValue) {
	$obj.removeClass($removeClass);
	$obj.addClass($addClass);
};