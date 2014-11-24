<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

$path = GNAssetHelper::setBase('greennet.modules.users.assets', 'user');
GNAssetHelper::jsCollection("js-users-login", "{$path}js/", array(
	'jlbd.users.login'
), CClientScript::POS_END, "js-users-login");

//GNAssetHelper::scriptFile('jlbd.users.login', CClientScript::POS_END);
?>

<div id="loginbox">
		<form id="login-form" action="/login" method="post">
			<p>Enter username and password to continue</p>

			<div class="control-group">
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-user"></i> </span> <input
							placeholder="Email" name="GNLoginForm[email]"
							id="LoginForm_username" type="text" />
					</div>
					<div class="errorMessage" id="LoginForm_username_em_"
						style="display: none"></div>
				</div>
			</div>

			<div class="control-group">
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on"><i class="icon-lock"></i> </span> <input
							placeholder="Password" name="GNLoginForm[password]"
							id="LoginForm_password" type="password" />
					</div>
					<div class="errorMessage" id="LoginForm_password_em_"
						style="display: none"></div>
				</div>
			</div>

			<div class="form-actions">
				<span class="pull-left"> <input id="ytLoginForm_rememberMe"
					type="hidden" value="0" name="GNLoginForm[rememberMe]" /><input
					name="LoginForm[rememberMe]" id="LoginForm_rememberMe" value="1"
					type="checkbox" /> <label for="LoginForm_rememberMe">Remember me</label>
					<div class="errorMessage" id="LoginForm_rememberMe_em_"
						style="display: none"></div>
				</span> <span class="pull-right"> <input class="btn btn-inverse"
					type="submit" name="yt0" value="Login" />
				</span>
			</div>

		</form>
	</div>
	<!-- form -->
	<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($) {
jQuery('#login-form').yiiactiveform({'validateOnSubmit':true,'attributes':[{'id':'LoginForm_username','inputID':'LoginForm_username','errorID':'LoginForm_username_em_','model':'LoginForm','name':'username','enableAjaxValidation':false,'clientValidation':function(value, messages, attribute) {

if(jQuery.trim(value)=='') {
	messages.push("Username cannot be blank.");
}

}},{'id':'LoginForm_password','inputID':'LoginForm_password','errorID':'LoginForm_password_em_','model':'LoginForm','name':'password','enableAjaxValidation':false,'clientValidation':function(value, messages, attribute) {

if(jQuery.trim(value)=='') {
	messages.push("Password cannot be blank.");
}

}},{'id':'LoginForm_rememberMe','inputID':'LoginForm_rememberMe','errorID':'LoginForm_rememberMe_em_','model':'LoginForm','name':'rememberMe','enableAjaxValidation':false,'clientValidation':function(value, messages, attribute) {

if(jQuery.trim(value)!='' && value!="1" && value!="0") {
	messages.push("Remember me must be either 1 or 0.");
}

}}]});
});
/*]]>*/
</script>
	