<!DOCTYPE html>
<?php 
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));
?>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php 
GNAssetHelper::cssCollection("css-admin-head", "/unicorn/css/", array(
	'bootstrap.min',
	'bootstrap-responsive.min',
	'fullcalendar',
	'unicorn.main',
	'unicorn.grey',
	'uniform'
), CClientScript::POS_HEAD, "css-admin-head");
?>
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	</head>
	<style>
	#username-indicator {
		float: left;
		color: #eee;
		margin-right: 10px;
		margin-top: 6px;
	}
	</style>
	<body>
		<div id="header">
			<h1><a href="./dashboard.html">Admin</a></h1>
		</div>
		<div id="search">
			<input type="text" placeholder="Search here..."/><button type="submit" class="tip-right" title="Search"><i class="icon-search icon-white"></i></button>
		</div>
		<div id="user-nav" class="navbar navbar-inverse">
            <ul class="nav btn-group">
                <li class="btn btn-inverse" ><a title="" href="#"><i class="icon icon-user"></i> <span class="text">Profile</span></a></li>
                <li class="btn btn-inverse dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a class="sAdd" title="" href="#">new message</a></li>
                        <li><a class="sInbox" title="" href="#">inbox</a></li>
                        <li><a class="sOutbox" title="" href="#">outbox</a></li>
                        <li><a class="sTrash" title="" href="#">trash</a></li>
                    </ul>
                </li>
                <li class="btn btn-inverse"><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li>
                <li class="btn btn-inverse"><a title="" href="login.html"><i class="icon icon-share-alt"></i> <span class="text">Logout</span></a></li>
            </ul>
        </div>
		<div class="clear"></div>
		<?php $this->renderPartial('//menu')?>
		
		<div id="content">
			<?php echo $content?>
			
		</div>
		<?php 
		GNAssetHelper::jsCollection("js-layout-post-end", "/assets/default/js/", array(
			'excanvas.min',
			'bootstrap.min',
			'jquery.flot.min',
			'jquery.flot.resize.min',
			'jquery.peity.min',
			'unicorn',
		), CClientScript::POS_END, "js-layout-post-end");
		?>
		
	</body>
</html>
