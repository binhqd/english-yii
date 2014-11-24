;(function($, scope) {
	scope['users_changeEmail'] = {
		Libs: {
			sendFormConfirmChangeEmail: function (objForm)
			{
				$.ajax({
					dataType : "json",
					type : "POST",
					url : objForm.attr('action'),
					data : objForm.serialize(),
					success : function(response){
						if(response.error == true){
							if (response.message) {
								var options = {
									message	: response.message,
									autoHide : true,
									timeOut : 3,
									type : 'error'
								}
								jlbd.dialog.notify(options);
							}
							if (response.errors) {
								$.each(response.errors, function (key, element) {
									objForm.find('#GNChangeEmailForm_'+key+'_em_').html(element[0]).show();
								});
							}
						} else {
							var options = {
								message	: response.message,
								autoHide : true,
								timeOut : 2,
								type : 'success',
								callback : function() {
									if (response.url!=="") {
										jlbd.redirect(response.url);
									}
								}
							}
							jlbd.dialog.notify(options);
						}
					}
				});
			},
			initForm: function($objForm) {
				$objForm.submit(function() {
					jlbd.users_changeEmail.Libs.sendFormConfirmChangeEmail($objForm);
					return false;
				});
			}
		}
	}
})(jQuery, jlbd);