;(function($, scope){
	scope['users_forgot'] = {
		Libs: {
			t: function(str) {
				if (typeof Yii!="undefined" && typeof Yii.t!="undefined")
					return Yii.t('UsersModule', str);
				return str;
			},
			requiredValidate: function($object, message) {
				if ($.trim($object.val()) == '') {
					var options = {
						message	: jlbd.users_forgot.Libs.t(message),
						autoHide : true,
						timeOut : 3,
						type : 'info'
					};
					jlbd.dialog.notify(options);
					$object.focus();
					return false;
				}
				return true;
			},
			sendForm : function(objForm) {
				$.ajax({
					dataType: 'json',
					type: "POST",
					url : objForm.attr('action'),
					data : objForm.serialize(),
					beforeSend: function() {
						objForm.find('.js-img-loading').show();
					},
					complete: function() {
						objForm.find('.js-img-loading').hide();
					},
					success : function(response){
						if(response.error == true){
							var options = {
								message : response.message,
								autoHide : true,
								timeOut : 3,
								type : 'error'
							}
							jlbd.dialog.notify(options);
						} else {
							var options = {
								message : response.message,
								autoHide : true,
								timeOut : 3,
								type : 'success',
								callback: function() {
									window.location.href = "/";
								}
							}
							jlbd.dialog.notify(options);
						}
					}
				});
			},
			init: function($objForm) {
				$objForm.submit(function(){
					// creat check input text !null by javascript
					var getValue = $('#GNForgotPasswordForm_email');
					if (!jlbd.users_forgot.Libs.requiredValidate(getValue, 'Email cannot be blank.'))
						return false;
					jlbd.users_forgot.Libs.sendForm($objForm);
					return false;
				});
			}
		}
	}
})(jQuery, jlbd);