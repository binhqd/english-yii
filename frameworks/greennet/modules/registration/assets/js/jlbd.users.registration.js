;(function($,scope){
	scope['users_registration'] = {
		Libs:{
			t: function(str) {
				if (typeof Yii!="undefined" && typeof Yii.t!="undefined")
					return Yii.t('UsersModule', str);
				return str;
			},
			requiredValidate: function($object, message) {
				if ($.trim($object.val()) == '') {
					var options = {
						message	: jlbd.users_registration.Libs.t(message),
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
			sendFormRegister: function(objForm, formName) {
				$.ajax({
					dataType : "json",
					type : "POST",
					url : objForm.attr('action'),
					data : objForm.serialize(),
					beforeSend: function() {
						objForm.find('.js-img-loading').show();
					},
					complete: function() {
						objForm.find('.js-img-loading').hide();
					},
					success: function(response) {
						if (response.error) {
							var options = {
								message: response.message,
								autoHide: true,
								timeOut: 10,
								type: 'error'
							};
							jlbd.dialog.notify(options);
						} else {
							if (response.type == 'confirm') {
								jlbd.dialog.confirm(jlbd.users_registration.Libs.t('Confirm'), response.message, response.callback);
							} else {
								var options = {
									message: response.message,
									autoHide: true,
									timeOut: 10,
									type: response.type,
									callback: function() {
										if (response.url) jlbd.redirect(response.url);
									}
								};
								jlbd.dialog.notify(options);
							}
						}
					}
				});
			}
			,initFormRegisterByFillInfo: function($objForm){
				$objForm.submit(function() {
					var acceptRule = '';
					if ($objForm.find('.js-check-read').attr('checked') || $objForm.find('.js-check-read').length == 0) acceptRule = 'true';
					if (!jlbd.users_registration.Libs.requiredValidate($objForm.find('#GNRegisterByInformation_email'), 'Email cannot be blank.')
						|| !jlbd.users_registration.Libs.requiredValidate($objForm.find('#GNRegisterByInformation_confirmEmail'), 'Confirm Email cannot be blank.')
						|| !jlbd.users_registration.Libs.requiredValidate($objForm.find('#GNRegisterByInformation_password'), 'Password cannot be blank.')
						|| !jlbd.users_registration.Libs.requiredValidate($objForm.find('#GNRegisterByInformation_confirmPassword'), 'Confirm Password cannot be blank.')
						|| !jlbd.users_registration.Libs.requiredValidate($objForm.find('#GNRegisterByInformation_firstname'), 'First name cannot be blank.')
						|| !jlbd.users_registration.Libs.requiredValidate($objForm.find('#GNRegisterByInformation_lastname'), 'Last name cannot be blank.')
					) return false;

					if (acceptRule == '') {
						var options = {
							message	: jlbd.users_registration.Libs.t('Please read and agree to our Terms & Conditions.'),
							autoHide : true,
							timeOut : 3,
							type : 'info'
						};
						jlbd.dialog.notify(options);
						return false;
					}

					jlbd.users_registration.Libs.sendFormRegister($objForm, 'GNRegisterByInformation');
					return false;
				});
			}
			,initFormRegisterByFillEmail: function($objForm){
				$objForm.submit(function() {
					jlbd.users_registration.Libs.sendFormRegister($objForm, 'GNRegisterByEmail');
					return false;
				});
			}
		}
	}
})(jQuery,jlbd);