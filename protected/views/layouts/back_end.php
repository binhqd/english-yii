<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>	
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php 
	Yii::app()->clientScript->registerCoreScript('jquery');
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	
	GNAssetHelper::setBase('justlook');
	GNAssetHelper::setPriority(100);
	GNAssetHelper::scriptFile('jlbd', CClientScript::POS_HEAD);
	GNAssetHelper::cssFile('jlbd.message');
	GNAssetHelper::cssFile('jlbd.dialog');
	GNAssetHelper::cssFile('jlbd.notify');
	GNAssetHelper::cssFile('jl-alert-bootraps');
	GNAssetHelper::cssFile('jquery.fancybox-1.3.4');
	GNAssetHelper::cssFile('validationEngine.jquery');
	/** Add css file : Disable item menu is null **/
	GNAssetHelper::cssFile('menu-mutil-disable');
	GNAssetHelper::cssFile('admin-manage-business');
	
	GNAssetHelper::scriptFile('jlbd.message', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jlbd.dialog', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jlbd.notify', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.fancybox-1.3.4.pack');
	GNAssetHelper::scriptFile('jquery.validationEngine', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.validationEngine-en', CClientScript::POS_END);
	
	GNAssetHelper::setBase('ext.bootstrap.assets', 'bootstrap');
	GNAssetHelper::scriptFile('bootstrap-tooltip', CClientScript::POS_BEGIN);
	GNAssetHelper::scriptFile('bootstrap-popover', CClientScript::POS_BEGIN);
	//GNAssetHelper::scriptFile('bootstrap-dropdown', CClientScript::POS_BEGIN);
	GNAssetHelper::scriptFile('bootstrap-collapse', CClientScript::POS_BEGIN);
	
	GNAssetHelper::setBase('application.modules.admin_manage.assets');
	GNAssetHelper::cssFile('backend');
	?>
		
		<style>
		.required span{color:red;}
		</style>
	</head>

	<body>
		<?php //Yii::import('ext.bootstrap.*');?>

<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type'=>'inverse',
	'fixed'=>false,
	'brand'=>'JUSTLOOK',
	'brandUrl'=>'#',
	'collapse'=>true, // requires bootstrap-responsive.css
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'items'=>array(
				array('label'=>'Errors & Warnings', 'url'=> '#', 'items' => array(
					array('label'=> 'View all', 'url'=>JLRouter::createUrl('/admin_manage/logs')),
					array('label'=> 'Page not found', 'url'=>JLRouter::createUrl('/admin_manage/logs/category?c=404')),
					array('label'=> 'Javascript Errors', 'url'=>JLRouter::createUrl('/admin_manage/logs/category?c=js')),
					array('label'=> 'Bots', 'url'=>JLRouter::createUrl('/admin_manage/manageUser/bots'))
				)),
				array('label'=>'Monitor', 'url'=> '#', 'items' => array(
					array('label'=>'Business', 'url'=>JLRouter::createUrl('/admin_manage/monitor/business')),
					'---',
					array('label'=>'News Letters', 'url'=>JLRouter::createUrl('/admin_manage/newsLetters')),
					array('label'=>'Activation for user', 'url'=>JLRouter::createUrl('/admin_manage/activation')),
					'---',
					array('label'=>'Tag Cloud', 'url'=>JLRouter::createUrl('/admin_manage/tagCloud/index')),
					'---',
					array('label'=>'Published Lists', 'url'=>JLRouter::createUrl('/admin_manage/list/index')),
				)),
				array('label'=>'Users', 'url'=> '#', 'items' => array(
					array('label'=>'User', 'url'=>JLRouter::createUrl('/admin_manage/manageUser/monitor')),
					//array('label'=>'List User', 'url'=>JLRouter::createUrl('/admin_manage/manageUser/listUser')),
					array('label'=>'Created User', 'url'=>JLRouter::createUrl('/admin_manage/manageUser/created')),
				)),
				array('label'	=> 'Rights', 'url' => '#', 'items' => array(
					array('label'=>'Assignments', 'url'=>JLRouter::createUrl('/rights')),
					array('label'=>'Permissions', 'url'=>JLRouter::createUrl('/rights/authItem/permissions')),
					array('label'=>'Roles', 'url'=>JLRouter::createUrl('/rights/authItem/roles')),
					array('label'=>'Tasks', 'url'=>JLRouter::createUrl('/rights/authItem/tasks')),
					array('label'=>'Operations', 'url'=>JLRouter::createUrl('/rights/authItem/operations'))
				)),
				array('label' => 'Businesses', 'url'=>'#', 'items' => array(
					array('label'=>'Browse', 'url' => '#', 'items' => array(
						array(
							'label'	=> 'Has owner',
							'url'	=> JLRouter::createUrl('/admin_manage/business/hasOwner')
						),
						array(
							'label'	=> 'New registered businesses',
							'url'	=> JLRouter::createUrl('/admin_manage/business/newRegistered')
						),
						array(
							'label'	=> 'Awaiting businesses',
							'url'	=> JLRouter::createUrl('/admin_manage/business/awaiting')
						),
						'---',
						array(
							'label'	=> 'Custom search',
							'url'	=> JLRouter::createUrl('#/admin_manage/business/search')
						),
					)),
					'---',
					array('label'=>'Business Photos', 'url' => '#', 'items' => array(
						array(
							'label'	=> 'View all photos',
							'url'	=> JLRouter::createUrl('/admin_manage/photo/icons')
						),
						array(
							'label'	=> 'Group by business',
							'url'	=> JLRouter::createUrl('/admin_manage/photo')
						)
					)),
					'---',
					array('label'=>'Contributed businesses', 'url'=> '#', 'items' => array(
						array(
							'label'	=> 'Pendding',
							'url'	=> JLRouter::createUrl('/admin_manage/monitor/contributed')
						),
						array(
							'label'	=> 'Approved',
							'url'	=> JLRouter::createUrl('/admin_manage/monitor/publish')
						),
					)),
					'---',
					array('label'=>'Assign owner', 'url'=>JLRouter::createUrl('/businesses/assignUser')),
					'---',
					array('label'=>'Keyword Location', 'url'=>JLRouter::createUrl('admin_manage/keywordLocation/admin')),
					array('label'=>'Business Awaiting', 'url'=>JLRouter::createUrl('admin_manage/business/awaiting')),
					array('label'=>'Manage business', 'url'=>JLRouter::createUrl('admin_manage/business')),
					array('label'=>'Manage new businesses', 'url'=> '/admin_manage/registered/awaiting'),
					array('label'=>'Manage claimed businesses', 'url'=> '/admin_manage/claimed'),
					'---',
					array('label'=>'Attributes', 'url'=>JLRouter::createUrl('admin_manage/attribute')),
					array('label'=>'Assign Attributes', 'url'=>JLRouter::createUrl('admin_manage/AssignedCategoryAttributes/add'))
				)),
				array('label'=>'Reviews', 'url'=> JLRouter::createUrl('/admin_manage/reviews', array('JLReview_sort'=>'created.desc')), 'items'=>array(
					array('label'=>'Reviews Management', array('class'=>'nav-header')),
					array('label'=>'All Reviews', 'url'=> JLRouter::createUrl('/admin_manage/reviews/all', array('JLAdminReview_sort'=>'created.desc'))),
					array('label'=>'Reviews By User', 'url'=> JLRouter::createUrl('/admin_manage/reviews', array('JLReview_sort'=>'created.desc'))),
					array('label'=>'Reviews Has Badwords', 'url'=> JLRouter::createUrl('/admin_manage/reviews/hasBadwords', array('JLAdminReview_sort'=>'created.desc'))),
					array('label'=>'Comments Management', array('class'=>'nav-header')),
					array('label'=>'All Comments', 'url'=> JLRouter::createUrl('/admin_manage/comments/all', array('JLAdminComment_sort'=>'created.desc'))),
					array('label'=>'Comments Has Badwords', 'url'=> JLRouter::createUrl('/admin_manage/comments/hasBadwords', array('JLAdminComment_sort'=>'created.desc'))),
					array('label'=>'Compliments Management', array('class'=>'nav-header')),
					array('label'=>'All Compliments', 'url'=> JLRouter::createUrl('/admin_manage/compliments/all', array('JLAdminComment_sort'=>'created.desc'))),
					array('label'=>'Compliments Has Badwords', 'url'=> JLRouter::createUrl('/admin_manage/compliments/hasBadwords', array('JLAdminComment_sort'=>'created.desc'))),
				)),
				array('label'=>'Category', 'url'=>'#', 'items'=>array(
					//array('label'=>'DROPDOWN HEADER'),
					array('label'=>'JustLook Category', 'url'=>JLRouter::createUrl('admin_manage/categories')),
					'---',
					array('label'=>'Business Category', 'url'=>JLRouter::createUrl('admin_manage/businessCategories')),
				)),
				array('label'=>'Packages', 'url'=>'#', 'items'=>array(
					array('label'=>'Features', 'url'=>JLRouter::createUrl('/admin_manage//features/admin')),
					'---',
					array('label'=>'Packages', 'url'=>JLRouter::createUrl('/admin_manage//packages/admin')),
					'---',
					array('label'=>'Settings', 'url'=>JLRouter::createUrl('/admin_manage/settings')),
				)),
				array('label'=>'Feedback', 'url'=> JLRouter::createUrl('/admin_manage/feedback'), 'items'=>array(
					array('label'=>'Feedback', 'url'=> JLRouter::createUrl('/admin_manage/feedback')),
					array('label'=>'Report Concern', 'url'=> JLRouter::createUrl('/admin_manage/concern', array('JLConcern_sort'=>'created.desc'))),
				))
			),
		),
		array(
			'class'=>'ext.bootstrap.widgets.BootMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items'=>array(
				//array('label'=>'Link', 'url'=>'#'),
				array('label'=>'Toolbar', 'url'=>'#', 'items'=>array(
					//array('label'=>'DROPDOWN HEADER'),
					array('label'=>'Config', 'url'=>'#'),
					//array('label'=>'Exit', 'url'=>'#'),
					//array('label'=>'Something else here', 'url'=>'#'),
					'---',
					array('label'=>'Logout', 'url'=>JLRouter::createUrl('user/logout')),
				)),
			),
		),
		
	),
)); ?>
		<div style="margin:0 auto;">
		<?php echo $content;?>
		</div>
	</body>
</html>
