<?php
class GNCheckExistingEmailBehavior extends CBehavior {
	/**
	* This action is used to check email registered and activated or not?
	* @author Hoàng Xuân Phi
	* @date 06-02-2013
	*/
	public function run($email)
	{
		$controller = $this->owner;
		// If user has registered but doesn't have activation code then Notice for user get activation code?
		if (GNTmpUser::model()->findByEmail($email)) {
			$strUrl = GNRouter::createUrl('/users/registration/resendCode', array('email'=>$email));
			$strMessage = Yii::t("greennet", 'Your email address has registered on our system.<br/>'.
				'Don\'t have a verification email? Click <b>OK</b> '.
				'to send a verification email to the following address : <b>{email}</b>', array('{strUrlResendCode}'=>$strUrl, '{email}'=>$email)
			);
	
			if ($controller->isJsonRequest) {
				ajaxOut(array(
					'error'		=> false,
					'type'		=> 'confirm',
					'message'	=> $strMessage,
					'callback'	=> 'function(reply){
						if (reply) {
							$.ajax({
								dataType : "json",
								type : "POST",
								url : "'.$strUrl.'",
								beforeSend: function() {
									$(".js-img-loading").show();
								},
								complete: function() {
									$(".js-img-loading").hide();
								},
								success : function(res) {
									jlbd.dialog.notify({
										type 	: res.type,
										message	: res.message,
										autoHide: true
									});
									
									//jlbd.redirect("/");
								}
							});
							
						}
					}'
				));
			} else {
				Yii::app()->jlbd->dialog->confirm(Yii::t("greennet", 'GreenNet Message!'), $strMessage, 'function(reply){
					if (reply) {
						jlbd.redirect("'.$strUrl.'");
					}
				}');
			}
		}
	
		// If email already exist in the system then Notice "Do you want to login?".
		if (GNUser::model()->findByEmail($email)) {
			$strUrl = GNRouter::createUrl('/login');
			$message = Yii::t("greennet", 'Your email <b>{email}</b> has been used in GreenNet system.<br/>Do you want to <a title="Login" href="{strUrlLogin}">login</a> with this email?', array('{strUrlLogin}'=>$strUrl, '{email}'=>$email));
			// Confirm not OK. :D
			if(yii::app()->request->isAjaxRequest) {
				ajaxOut(array(
					'error'		=> false,
					'type'		=> 'confirm',
					'message'	=> $message,
					'callback'	=> 'function(reply){
						if (reply) {
							jlbd.redirect("'.$strUrl.'");
						}
					}',
				));
			} else {
				Yii::app()->jlbd->dialog->confirm(Yii::t("greennet", 'GreenNet Message!'), $message, 'function(reply){
					if (reply) {
						jlbd.redirect("'.$strUrl.'");
					}
				}');
			}
		}
	}
}