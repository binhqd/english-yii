<style>
#wd-menu {
    float: left;
    width: 100%;
}
</style>
<script language="javascript">
var currentURI = "<?php echo Yii::app()->request->requestUri;?>";

$(document).ready(function() {
	$('.manage-business a').each(function() {
		if ($(this).attr('href') == currentURI || $(this).attr('href') == "http://<?php echo $_SERVER['HTTP_HOST']?>" + currentURI) {
			$(this).parents('li').eq(0).addClass('wd-current');
			$(this).wrap('<span class="background"/>');
		}
	});
});


</script>

<div id="wd-menu" class='manage-business'>
	<ul>
		<li class="wd-haschild menu-parent">
			<?php echo CHtml::link('Emailed company ('. $statistics['email_company']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedcompany/type'));?>
			<ul class="wd-item">
				<li><?php echo CHtml::link('Expired ('. $statistics['email_company_expired']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedcompany/type/expired'));?></li>
				<li><?php echo CHtml::link('Confirmed ('. $statistics['email_company_confirmed']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedcompany/type/confirmed')); ?></li>
				<li><?php echo CHtml::link('Not confirmed ('. $statistics['email_company_notconfirmed']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedcompany/type/notconfirmed')); ?></li>
				<li><?php echo CHtml::link('Waiting ('. $statistics['email_company_waiting']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedcompany/type/waiting')); ?></li>
			</ul>
		</li>
	</ul>    
	<ul>
		<li class='menu-parent'>
			<?php echo CHtml::link('Need call businesses ('. $statistics['call_company']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/callcompany/'));?>
		</li>
	</ul>	
	<ul>
		<li class="wd-haschild menu-parent">
			<?php echo CHtml::link('Email personal ('. $statistics['email_personal']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedpersonal/type'));?>
			<ul class="wd-item">
				<li><?php echo CHtml::link('Expired ('. $statistics['email_personal_expired']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedpersonal/type/expired'));?></li>
				<li><?php echo CHtml::link('Confirmed ('. $statistics['email_personal_confirmed']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedpersonal/type/confirmed'));?></li>
				<li><?php echo CHtml::link('Not confirmed ('. $statistics['email_personal_notconfirmed']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedpersonal/type/notconfirmed'));?></li>
				<li><?php echo CHtml::link('Waiting ('. $statistics['email_personal_waiting']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/emailedpersonal/type/waiting'));?></li>				
			</ul>
		</li>
	</ul>
	<ul>
		<li class='menu-parent'><?php echo CHtml::link('Confirmed business ('. $statistics['confirm']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/confirmed'));?></li>
	</ul>
	<ul>
		<li class='menu-parent'><?php echo CHtml::link('Not confirmed business ('. $statistics['not_confirm']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/notconfirmed'));?></li>
	</ul>
	<ul>
		<li class='menu-parent'><?php echo CHtml::link('Published business ('. $statistics['published']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/published'));?></li>
	</ul>
	<ul>
	    <li class='menu-parent'><?php echo CHtml::link('Unpublished business ('. $statistics['unpublished']. ')', JLRouter::createAbsoluteUrl('/admin_manage/claimed/unpublished'));?></li>
	</ul>
</div>
<div class="clear"></div>
