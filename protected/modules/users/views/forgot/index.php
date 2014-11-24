<?php
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	Yii::app()->clientScript->registerCoreScript('jquery');
	
	GNAssetHelper::setBase('myzone_v1');
	GNAssetHelper::cssFile('popup-content');
	GNAssetHelper::cssFile('uniform.default');
	GNAssetHelper::cssFile('uniform-default-custom');
	
	GNAssetHelper::cssFile('main-form');
	GNAssetHelper::cssFile('forgot-pass-succ');
	
	GNAssetHelper::scriptFile('zone', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('jquery.nicescroll', CClientScript::POS_END);
?>
<script>
$().ready(function(e){
	$("html").niceScroll({styler:"fb",cursorcolor:"#000"});
});
</script>
<style>
	.wd-or{margin-top: 10px !important;}
</style>
<div class="wd-form-content wd-form-setpass-content" id="youlook-forgot-pass">
	<?php /** @var BootActiveForm $form */
		// GNAssetHelper::registerScript('Login', "jlbd.users_login.Libs.initForm($('#userLoginForm'));", CClientScript::POS_READY);
		$formForgot = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'userForgotForm',
		'enableClientValidation' => true,
		'enableAjaxValidation' => true,
		'action'=>GNRouter::createUrl('/users/forgot'),
		'clientOptions'=> array(
			'validateOnSubmit'=>true,
			'validateOnChange'=>false,
			'afterValidate'=>'js:forgotpw'
		),
		'htmlOptions'=>array('class' => 'well'),
		'focus'=>array($model,'email'),
	));
	?>
		
		<fieldset class="wd-main-form wd-main-form-2">
		
			<h2 class="wd_tt_n3">Forgot your password?</h2>
			<p class="wd-dis"><span class="wd-text-red">Don’t worry!</span> Just fill in your email and we’ll help you reset your password</p>
			<div class="wd-input hide-label youlook-email-forgot" >
				<?php echo $formForgot->textFieldRow($model, 'email', array('class'=>'span3','placeholder'=>'Enter your email address','style'=>'width:90%')); ?>
			</div>
			
			<div class="wd-submit pb30" id="buttonForgot">
				<?php 
				$this->widget('bootstrap.widgets.TbButton', array(
					'buttonType' => 'submit',
					'label' => 'Send email',
					'type' => '',
					'htmlOptions'=>array(
						'class'=>'btn btn-continue'
					)
				));
				?>
				<span class="wd-or">Or</span>
				<a href="<?php echo ZoneRouter::createUrl('/users/login');?>" class="wd-link-comeback">Back to sign in</a>
			</div>
		</fieldset>
	<?php $this->endWidget(); ?>
</div>
<div class="wd-main-content-wr2 wd-main-content-bggray" id="youlook-forgot-pass-succ" style="display:none">
	<div class="wd-center">
		<div class="wd-form-content wd-form-setpass-content">
			<div class="wd-succes-mess">
				<h2 class="wd_tt_n3">Forgot your password?</h2>
				<span class="wd-icon-check"></span>
				<p>We’ve sent an email to:</p>
				<p class="wd-mail"><a href="#"><span class="youlook-email-forgot">email@email.com</span></a></p>
				<p class="wd-instruction">with instructions to create a new password. Please check your email to complete the forgot password process.</p>
			</div>
		</div>
	</div>
</div>
<script>


function forgotpw(frm,res){
	var email = $('#ZoneForgotPasswordForm_email').val();
	if(typeof res.error == "undefined"){
		$("#buttonForgot button").removeAttr('disabled');
		return false;
	}else{
		
		$("#ZoneForgotPasswordForm_email").val('');
		if(res.error){
			$('#ZoneForgotPasswordForm_email_em_').html(res.message).show();
			$(".wd-top-mess-content").addClass('wd-top-mess-content-error');
		}else{
			$(".wd-top-mess-content").addClass('wd-top-mess-content-success');
			$('.youlook-email-forgot').html(email);
			$('#youlook-forgot-pass').hide();
			$('#youlook-forgot-pass-succ').show();
		}
		$("#buttonForgot button").removeAttr('disabled');
		$(".content-message .wd-intro").html(res.message);
		$(".wd-top-mess").css({opacity:0,display:"block"});
		$(".wd-top-mess").animate({opacity:1},1000);
	}
	
}
$(document).ajaxSend(function() {
  $( "#buttonForgot .btn" ).attr('disabled','disabled');
  
});
$(document).ajaxSuccess(function() {
	$( "#buttonForgot .btn" ).removeAttr('disabled');
});
$(document).ajaxError(function() {
	
  $( "#buttonForgot .btn" ).removeAttr('disabled');
});
</script>
