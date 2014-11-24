/**
 * @author	: Chu Tieu
 * @version	: 1.0
 * @date	: 06-06-2013
 */
$(function(){
	$(".coregreennet-comment-item").live({
		mouseenter: function() { 
			$(this).find('.coregreennet-delete-comment').show();
		},
		mouseleave: function () {
			$(this).find('.coregreennet-delete-comment').hide();
		}
	});

	$('.coregreennet-show-comments').click(function () {
		var $_obj			= $(this);
		var $_parents		= $_obj.parents('.coregreennet-wp-comments');
		var $_parent		= $_parents.find('.coregreennet-comment-list-container');
		var $_childParent	= $_parents.find('.coregreennet-comment-list');
		var $_limit			= $_parent.attr('limit');
		var $_preloads		= $_parent.attr('preloads');
		var $_objectId		= $_parent.attr('objectId');
		var $_loadReverse	= $_parent.attr('loadReverse');
		var $_totalComments	= $_parent.attr('totalComments');
		
		var $_preloads	= parseInt($_limit) + parseInt($_preloads);
		var $_starList	= $_totalComments - $_preloads;

		if ($_starList<=0) {
			$_limit		= $_starList + parseInt($_limit);
			$_starList	= 0;
		}

		$.ajax({
			url			: $viewMoreCommentUrl,
			type		: 'POST',
			datatype	: 'json',
			data		: 'startList=' + $_starList 
							+ '&objectId=' + $_objectId 
							+ '&limit=' + $_limit
							+ '&loadReverse=' + $_loadReverse,
			success		: function ($res) {
				
				if ($res!=null){
					var comments = $.tmpl($('#coregreennet-comment-item-template'), $res.out);
					if (!$_loadReverse) {
						$_childParent.prepend(comments);
					} else {
						$_childParent.append(comments);
					}
					$_preloads		= $_parent.attr('preloads', $_preloads);
					if ($_starList==0){
						$_parents.find('.coregreennet-show-comments').remove();
					}
				}
			}
		});
	});
	
	$('.coregreennet-delete-comment').live('click', function () {
		if (confirm('Are you sure to delete this comment?')) {
			
			var $_obj		= $(this);
			var $_parent	= $_obj.parent('.coregreennet-comment-item');
			var $_commentId	= $_parent.attr('commentId');
			
			$.ajax({
				url			: $actionDeleteComment,
				type		: 'POST',
				datatype	: 'json',
				data		: 'commentId='+ $_commentId,
				success		: function (res){
					if (!res.error) {
						$_parent.fadeOut(500).remove();
					} else {
						jlbd.dialog.notify({
							type: 'error',
							message : res.message
						});
					}
				}
			});
		}
	});
	
	// add form comment
	$('.coregreennet-textarea').keydown(function(e){
		if (e.keyCode == 13 && e.shiftKey)
		{
			$('.coregreennet-textarea').autosize();
		}
	});
	$('.coregreennet-textarea').keydown(function(e){
		var $_obj			= $(this);
		
		var $_parents		= $_obj.parents('.coregreennet-wp-comments');
		var $_parent		= $_parents.find('.coregreennet-comment-list');
		var $_objectId		= $_parents.find('.coregreennet-comment-list-container').attr('objectId');
		var $_loadReverse	= $_parents.find('.coregreennet-comment-list-container').attr('loadReverse');
		var $_dataTextArea	= $_obj.val();
		
		if (e.keyCode == 13 && !e.shiftKey)
		{
			if ($.trim($_dataTextArea)!=""){
				$.ajax({
					url			: $actionAdd,
					type		: 'POST',
					data		: 'dataTextArea=' + $_dataTextArea
									+ '&objectId=' + $_objectId,
					datatype	: 'json',
					success		: function ($res) {
						var comments = $.tmpl($('#coregreennet-comment-item-template'), $res.content);
						if ($_loadReverse) {
							$_parent.prepend(comments);
						} else {
							$_parent.append(comments);
						}
						$_obj.val('');
					}
				});
			}
			return false;
		}
	});
});