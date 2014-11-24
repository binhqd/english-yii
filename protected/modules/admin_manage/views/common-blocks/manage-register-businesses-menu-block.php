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
<?php 
$items = array(
        array(
			'label'=>'New registered business ('. $statistics['pending']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/awaiting'), 
			'active'=>false
		),
        array(
			'label'=>'Emailed company ('. $statistics['email_company']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedcompany'),
			'items'=>array(
				array(
					'label'=>'Expired ('. $statistics['email_company_expired']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedcompany/type/expired')
				),
				array(
					'label'=>'Claimed ('. $statistics['email_company_claimed']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedcompany/type/claimed')
				),
				array(
					'label'=>'Confirmed ('. $statistics['email_company_confirmed']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedcompany/type/confirmed')
				),
				array(
					'label'=>'Not confirmed ('. $statistics['email_company_notconfirmed']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedcompany/type/notconfirmed')
				),
				array(
					'label'=>'Waiting ('. $statistics['email_company_waiting']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedcompany/type/waiting')
				),
			),
			'active'=>false
		),
        array(
			'label'=>'Need call businesses ('. $statistics['call_company']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/callcompany/'), 
			'active'=>false
		),
        array(
			'label'=>'Email personal ('. $statistics['email_personal']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedpersonal'), 
			'active'=>false,
			'items'=>array(
				array(
					'label'=>'Expired ('. $statistics['email_personal_expired']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedpersonal/type/expired')
				),
				array(
					'label'=>'Confirmed ('. $statistics['email_personal_confirmed']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedpersonal/type/confirmed')
				),
				array(
					'label'=>'Not confirmed ('. $statistics['email_personal_notconfirmed']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedpersonal/type/notconfirmed')
				),
				array(
					'label'=>'Not confirmed ('. $statistics['email_company_notconfirmed']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedcompany/type/notconfirmed')
				),
				array(
					'label'=>'Waiting ('. $statistics['email_personal_waiting']. ')',
					'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/emailedpersonal/type/waiting')
				),
			)
		),
        array(
			'label'=>'Confirmed business ('. $statistics['confirm']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/confirmed'), 
			'active'=>false
		),
        array(
			'label'=>'Not confirmed business ('. $statistics['not_confirm']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/notconfirmed'), 
			'active'=>false
		),
        array(
			'label'=>'Published business ('. $statistics['published']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/published'), 
			'active'=>false
		),
        array(
			'label'=>'Unpublished business ('. $statistics['unpublished']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/unpublished'), 
			'active'=>false
		),
        array(
			'label'=>'Special consideration business ('. $statistics['special_consideration']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/special'), 
			'active'=>false
		),
        array(
			'label'=>'Spammed business ('. $statistics['spammed']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/spamed/'), 
			'active'=>false
		),
        array(
			'label'=>'Deleted business ('. $statistics['deleted']. ')', 
			'url'=>JLRouter::createAbsoluteUrl('/admin_manage/registered/deleted'), 
			'active'=>false
		),
		
    );

if(!empty($items)){
	foreach($items as $key=>$value){
		if(!empty($value['items'])){
			foreach($value['items'] as $k=>$v){
				if("http://".$_SERVER['HTTP_HOST'].Yii::app()->request->requestUri==$v['url']){
					$items[$key]['items'][$k]['active'] = true;
				}
			}
		}else{
			if("http://".$_SERVER['HTTP_HOST'].Yii::app()->request->requestUri==$value['url']){
				$items[$key]['active'] = true;
			}
		}
	}
}
echo '<div class="customMenu">';
$this->widget('bootstrap.widgets.BootMenu', array(
    'type'=>'list', // '', 'tabs', 'pills' (or 'list')
    'stacked'=>false, // whether this is a stacked menu
    'items'=>$items,
)); 
echo '</div>';
?>


<!-- menu.end -->
<?php
/*$this->widget('zii.widgets.CMenu', array(
	'activeCssClass'	=> 'wd-current',
	'items'=>array(	
	  array('label'=>'New registered', 'url'=>array('/admin_manage/awaitingbusiness/index')),
	  array('label'=>'Email compnay', 'url'=>array('/admin_manage/emailedcompany/index'), 'items'=>array(
		  array('label'=>'Expired', 'url'=>array('/admin_manage/emailedcompany/expired')),
		  array('label'=>'Waiting', 'url'=>array('/admin_manage/emailedcompany/waiting')),
	  )),
	),
  ));*/
?>

<script type="text/javascript">

</script>