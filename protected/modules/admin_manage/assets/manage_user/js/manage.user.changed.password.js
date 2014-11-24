;(function($, scope){
	scope['manage_user_changed_password'] = {
		Libs : {
			Action : function(){
				this.form = $("#form-changed-password-user");
				var _self = this
				
				this.init = function(jlObject) {
					var options = {
						username : jlObject.attr('altUsername'),
						user_id : jlObject.attr('altUserID'),
					}
					_self.appendData(options);
				},
				
				this.show = function() {
					$.fancybox({
						'width'				: 660,
						'height'			: 390,
						'autoScale'			: false,
						'content'			: $('#form-changed-password-content')
					});	
					$('.submit input.change_submit').click(function() {
						_self.send();
						return false;
					});
				},
				this.appendData = function(data) {
					$("#manage-user-changed-password").html('');
					var html = '<div id="form-changed-password-content" style="margin: 0px 100px;">'
						+'<h3 style="margin-top:20px;">Changed password for user : '+ data.username +'</h3>'
						+'<form altUserID="'+data.user_id +'" class="form-vertical" id="form-changed-password-user" action="" method="post">'
						+'<fieldset>'
							+'<p class="wd-note wd-none-italic">Fields with <sup>(<span class="wd-required">*</span>)</sup> are required.</p>'
								+'<label class="required" for="JLRegistrationForm_password">New Password<sup>(<span class="required">*</span>)</sup></label>'
								+'<input class="validate[required,minSize[6]] text-input" id="JLChangePasswordForm_password" name="JLChangePasswordForm[password]" type="password" maxlength="128">'
								+'<label class="required" for="JLRegistrationForm_password">Verify Password<sup>(<span class="required">*</span>)</sup></label>'
								+'<input class="validate[required,equals[JLChangePasswordForm_password]] text-input" name="JLChangePasswordForm[verifyPassword]" id="JLChangePasswordForm_verifyPassword" type="password">'
								+'<div class="submit" style="margin:20px 0px ;">'
									+'<input class="change_submit" type="submit" name="yt0" value="Changed Password">'
								+'</div>'
						+'</fieldset>'
						+'</form>'
						+'<div style="margin:15px 0px;"class="message-status"><span></span></div>'
					+'</div>';
					$("#manage-user-changed-password").append(html);
					_self.show();
				},
				this.send = function() {
					$.ajax({
						type : "POST",
						url : homeURL +'/admin_manage/manageUser/changedPassword/binUserID/'+$('#form-changed-password-user').attr('altUserID'),
						data : $('#form-changed-password-user').serialize(),
						success : function(data){
							if (!data.error) {
								$('.message-status span').attr('style','color:green').html(data.message);
							} else {
								$('.message-status span').attr('style','color:red').html(data.message);
							}
						}
					});
				}
				
			}
		}
	}
})(jQuery, jlbd);
$(document).ready(function() {
	$('a.changed-password').on('click', function() {
		var _this = $(this);
		var changedPassword = new jlbd.manage_user_changed_password.Libs.Action();
		changedPassword.init(_this);
		
		return false;
	});
});