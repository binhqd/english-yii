<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<?php 
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js'
	));
	?>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
		<title><?php echo CHtml::encode($redirectOptions["title"]); ?></title>
		<meta http-equiv="refresh" content="<?php echo $redirectOptions["timeout"]; ?>; url=<?php echo $redirectOptions["url"]; ?>">
		<?php GNAssetHelper::setBase("main");?>
		<?php GNAssetHelper::cssFile('redirect');?>
		<!--[if lt IE 8]>
		<link rel="stylesheet" type="text/css" href="/css/ie.css" media="screen, projection" />
		<![endif]-->
	</head>
	<body id="ipboard_body" class="redirector">
		<div id="ipbwrapper">
			<h1></h1>
			<h2><?php echo CHtml::encode($redirectOptions["title"]); ?></h2>
			<p class="message">
				<strong><?php echo CHtml::encode($redirectOptions["message"]); ?></strong>
				<br><br>
				<?php echo Yii::t('justlook', "Please wait while we transfer you..."); ?>
				<br>
				<span class="desc">(<a href="<?php echo $redirectOptions["url"]; ?>"><?php echo Yii::t('justlook', "Or click here if you do not wish to wait"); ?></a>)</span>	
			</p>
		</div>
	</body>
</html>