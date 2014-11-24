;(function($, scope){
	scope['users_changepass'] = {
		Libs: {
			t: function(str) {
				if (typeof Yii!="undefined" && typeof Yii.t!="undefined")
					return Yii.t('UsersModule', str);
				return str;
			},
			validateFormFullScreen: function(frm, res) {
				// thinhpq add code at 7/8/2013
				if(typeof res.error !="undefined"){
					if(res.error){
						var objError = frm.find('input:first').parent().find('.error');
						objError.html(res.message);
						objError.show();
					}else{
						$(".wd-top-mess-content").addClass('wd-top-mess-content-success');
						$(".content-message .wd-intro").html(res.message);
						$(".wd-top-mess").css({opacity:0,display:"block"});
						$(".wd-top-mess").animate({opacity:1},1000);
						frm[0].reset();
					}
				}
			},
			requiredValidate: function($object, message) {
				if ($.trim($object.val()) == '') {
					var options = {
						message	: jlbd.users_changepass.Libs.t(message),
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
							if (response.errors) {
								$.each(response.errors, function (key, element) {
									objForm.find('#GNChangePasswordForm_'+key+'_em_').html(element[0]).show();
								});
							}
							if (response.message) {
								var options = {
									message : response.message,
									autoHide : true,
									timeOut : 3,
									type : 'error'
								};
								jlbd.dialog.notify(options);
							}
						} else {
							$.magnificPopup.close();
							$('#js-change-password-current-password-element').css('display', 'block');
							$('#userProfileChangepassForm input[type="password"]').val('');
							var options = {
								message : response.message,
								autoHide : true,
								timeOut : 3,
								type : 'success',
								callback: function() {
									// if (response.url) jlbd.redirect(response.url);
								}
							};
							jlbd.dialog.notify(options);
						}
					}
				});
			},
			initFormChange: function($objForm) {
				// thinhpq add code at 7/8/2013
				if(!jlbd.users_changepass.isPopup) return false;
				
				$objForm.submit(function(){
					var getValue = $('#GNChangePasswordForm_password');
					var getValue2 = $('#GNChangePasswordForm_confirmPassword');
					if (!jlbd.users_changepass.Libs.requiredValidate(getValue, 'New Password cannot be blank.')
						|| !jlbd.users_changepass.Libs.requiredValidate(getValue2, 'Confirm Password cannot be blank.')
					) return false;

					jlbd.users_changepass.Libs.sendForm($objForm);
					return false;
				});
			}
			,initFormChangePasswordFull: function($objForm) {
				// thinhpq add code at 7/8/2013
				if(!jlbd.users_changepass.isPopup) return false;
				
				
				$objForm.submit(function(){
					var getValue = $('#GNChangePasswordForm_currentPassword');
					var getValue2 = $('#GNChangePasswordForm_password');
					var getValue3 = $('#GNChangePasswordForm_confirmPassword');
					if (!jlbd.users_changepass.Libs.requiredValidate(getValue, 'Current Password cannot be blank.')
						|| !jlbd.users_changepass.Libs.requiredValidate(getValue2, 'New Password cannot be blank.')
						|| !jlbd.users_changepass.Libs.requiredValidate(getValue3, 'Confirm Password cannot be blank.')
					) return false;

					jlbd.users_changepass.Libs.sendForm($objForm);
					return false;
				});
			}
		},
		isPopup : true
	};
})(jQuery, jlbd);



