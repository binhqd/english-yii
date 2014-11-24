;(function($, scope) {
	scope['users_editprofile'] = {
		Libs: {
			sendFormEditProfile: function (objForm) {
				$.ajax({
					dataType: 'json',
					type: "POST",
					url : objForm.attr('action'),
					data : objForm.serialize(),
					beforeSend: function(x) {
						objForm.find('.js-img-loading').show();
					},
					complete: function() {
						objForm.find('.js-img-loading').hide();
					},
					success : function(response){
						if(response.error == true){
							var options = {
								message	: response.message,
								autoHide : true,
								timeOut : 3,
								type : 'error'
							};
							jlbd.dialog.notify(options);
						} else {
							var options = {
								message	: response.message,
								autoHide : true,
								timeOut : 2,
								type : 'success',
								callback : function() {
									if (response.url) {
										jlbd.redirect(response.url);
									}
								}
							};
							jlbd.dialog.notify(options);
						}
					}
				});
			},
			initForm: function($objForm) {
				$objForm.submit(function() {
					$objForm.find('.js-img-loading').show();
					/**
					 * not using ajax because cannot upload image with ajax
					 * TODO: fix this
					 */
					return true;
					// jlbd.users_editprofile.Libs.sendFormEditProfile($objForm);
					return false;
				});
			}
		}
	}
})(jQuery, jlbd);