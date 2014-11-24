;(function($, scope){
	scope['manage_user_re_send_email'] = {
		Libs : {
			Action : function(){
				this.reSendEmail = function(jlObject) {
					$.ajax({
						dataType: 'JSON',
						type: 'GET',
						url: jlObject.attr('href'),
						complete: function(){
						},
						success: function(response){
							if (!response.error) {
								var options = {
									message	: response.message,
									autoHide : true,
									timeOut : 5,
									type : 'success'
								}
								jlbd.dialog.notify(options);
							} else {
								var options = {
									message	: response.message,
									autoHide : true,
									timeOut : 4,
									type : 'error'
								}
								jlbd.dialog.notify(options);
							}
						}
					});
				}
			}
		}
	}
})(jQuery, jlbd);
$(document).ready(function() {
	$('a.Manage_ResendEmail').on('click', function() {
		var userInfo = new jlbd.manage_user_re_send_email.Libs.Action();
		userInfo.reSendEmail($(this));
		
		return false;
	});
});