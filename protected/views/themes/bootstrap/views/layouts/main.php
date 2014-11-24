<!DOCTYPE HTML>
<html lang="en">
<head>
	<!-- Force latest IE rendering engine or ChromeFrame if installed -->
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<?php
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));
		GNAssetHelper::setBase('themes.bootstrap');
		GNAssetHelper::cssFile('main');
		GNAssetHelper::scriptFile('common', CClientScript::POS_HEAD);

		Yii::app()->jlbd->register(); // Register JLBD Library

		Yii::app()->bootstrap->register(); // Register bootstrap
	?>

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<!--[if lt IE 7]><link rel="stylesheet" href="http://blueimp.github.com/cdn/css/bootstrap-ie6.min.css"><![endif]-->
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
</head>

<body>
<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	// 'type'=>'inverse', // null or 'inverse'
	'brand'=>CHtml::encode(Yii::app()->name),
	'brandUrl'=>'/',
	'collapse'=>true, // requires bootstrap-responsive.css
	'items'=>array(
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'items'=>array(
				array('label'=>'Home', 'url'=>array('/site/index')),
				array('label'=>'Contact', 'url'=>array('/site/contact')),
			),
		),
		'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
		array(
			'class'=>'bootstrap.widgets.TbMenu',
			'htmlOptions'=>array('class'=>'pull-right'),
			'items'=>array(
				(currentUser()->id == -1) ?
					array('label'=>'Register', 'url'=>array('/users/registration/register'), 'items'=>array(
						array('label'=>'By Fill Information', 'url'=>array('/users/registration/register')),
						array('label'=>'By Email', 'url'=>array('/users/registration/registerByEmail')),
						array('label'=>'By Facebook', 'url'=>array('/facebook')),
						array('label'=>'By Google', 'url'=>array('/google')),
						array('label'=>'By Yahoo', 'url'=>array('/yahoo')),
						array('label'=>'By Twitter', 'url'=>array('/twitter')),
					)) :
					array()
				,
				(currentUser()->id == -1) ?
					array('label'=>'Login', 'url'=>array('/login')) :
					array('label'=>'User', 'url'=>array('/profile'), 'items'=>array(
						array('label'=>'Profile', 'url'=>array('/profile')),
						'---',
						// array('label'=>'NAV HEADER'),
						array('label'=>'Logout', 'url'=>array('/logout'))
					))
				,
			),
		),
	),
)); ?>

<div class="container" id="page">

	<div class="row">
		<?php echo $content; ?>
	</div>

</div><!-- page -->
<?php

?>

</body>
</html>
