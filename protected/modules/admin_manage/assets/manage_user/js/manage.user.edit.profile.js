;(function($, scope){
	scope['manage_user_edit_profile'] = {
		Libs : {
			Action : function(){
				this.form = $("#form-edit-user-data");
				var _self = this
				this.getInfo = function(jlObject) {
					$.ajax({
						dataType: 'JSON',
						type: 'GET',
						url: homeURL +'/admin_manage/manageUser/edit/binUserID/' + jlObject.attr('altUserID'),
						complete: function(){
						},
						success: function(response){
							console.log(response);
							_self.appendData(response);
						}
					});
				},
				this.show = function() {
					$.fancybox({
						'width'				: 660,
						'height'			: 390,
						'autoScale'			: false,
						'content'			: $('#form-edit-user-content')
					});	
					$('.form-actions .update-info-user').click(function() {
						_self.send();
						return false;
					});
				},
				this.appendData = function(data) {
					$("#manage-edit-profile").html('');
					var html = '<div id="form-edit-user-content" style="margin: 0px 100px;">'
						+'<h3 style="margin-top:20px;">Edit info of user : '+ data.username +'</h3>'
						+'<form altUserID="'+data.user_id +'" class="form-vertical" id="form-edit-user-data" action="/admin_manage/manageUser/edit/binUserID/'+data.user_id+'" method="post">'
							+'<p class="help-block">Fields with <span class="required">*</span> are required.</p>'
							+'<label for="JLUser_email" class="required">Email <span class="required">*</span></label>'
							+'<input class="span5" name="JLUser[email]" id="JLUser_email" type="text" maxlength="128" value="'+data.email+'">'
							+'<label for="JLUser_firstname">First Name</label>'
							+'<input class="span5" name="JLUser[firstname]" id="JLUser_firstname" type="text" maxlength="20" value="'+data.firstname+'">'
							+'<label for="JLUser_lastname">Last Name</label>'
							+'<input class="span5" name="JLUser[lastname]" id="JLUser_lastname" type="text" maxlength="50" value="'+data.lastname+'">'
							+'<div class="form-actions">'
								+'<button class="btn update-info-user" type="submit" name="yt0">Save</button>'
							+'</div>'
						+'</form>'
						+'<div style="margin:15px;"class="message-status"><span></span></div>'
					+'</div>';
					$("#manage-edit-profile").append(html);
					_self.show();
				},
				this.send = function() {
					$.ajax({
						type : "POST",
						url : homeURL +'/admin_manage/manageUser/edit/binUserID/'+$('#form-edit-user-data').attr('altUserID'),
						data : $('#form-edit-user-data').serialize(),
						success : function(data){
							console.log(data);
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
	$('a.form-edit-user').on('click', function() {
		var _this = $(this);
		var userInfo = new jlbd.manage_user_edit_profile.Libs.Action();
		userInfo.getInfo(_this);
		
		return false;
	});
});