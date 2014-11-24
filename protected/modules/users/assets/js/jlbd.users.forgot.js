;(function($, scope){
	scope['users_forgot'] = {
		Libs: {
			sendForm : function(objForm) {
				$.ajax({
					dataType: 'json',
					type: "POST",
					url : objForm.attr('action'),
					data : objForm.serialize(),
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
								autoHiden : true,
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
					var getValue = $('#GNForgotPasswordForm_email').val();
					if(getValue ==''){
						$("#GNForgotPasswordForm_email_em_").html('Your email cannot be blank.');
						$("#GNForgotPasswordForm_email_em_").css("display","");	
						return false;
					}					  
					jlbd.users_forgot.Libs.sendForm($objForm);
					return false;
				});
			}
		}
	}
})(jQuery, jlbd);