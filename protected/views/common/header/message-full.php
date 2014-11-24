<?php
if(currentUser()->isAwaiting):
?>
<div class="wd-top-mess wd-top-mess-full">
	<div class="wd-top-mess-content">
		<span class="content-message">
		<span class="wd-intro">Hi <a href="<?php echo ZoneRouter::createUrl('/profile');?>" class="wd-text-strong"><?php echo currentUser()->displayname;?></a>, 
			your email not yet verified, please go to <a href="javascript:void(0)" class="wd-text-strong"><?php echo currentUser()->email;?></a> to complete the sign-up process.</span>
		<a class="wd-goto-mail-bt" href="http://gmail.com" target="_blank">Go to your email<span class="wd-arrow-icon"></span></a>
		<span class="wd-or">Or</span>
		<a class="wd-resend-mail resend-email" href="<?php echo ZoneRouter::createUrl('/users/resendEmail/activationAccount');?>">Resend Email </a>
		</span>
		<a class="wd-close-topmess" target="wd-top-mess-content"></a>
	</div>
</div>
<?php
endif;
?>