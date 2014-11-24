<div id="content-header">
	<h1><?php echo Yii::t("contact", "Contacts");?></h1>
</div>
<div id="breadcrumb">
	<a href="<?php echo GNRouter::createUrl('/')?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<a href="<?php echo GNRouter::createUrl('/contacts')?>" class="current"><?php echo Yii::t("contact", "Contacts");?></a>
</div>
<div class="container-fluid row-fluid">
	<div class="span11">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>Contact Detail</h5>
			</div>
			<div class="widget-content">
				<h3>Contact Name</h3>
				<p>Contact detail</p>
			</div>
		</div>
	</div>
	<?php $this->renderPartial('//footer')?>
	
</div>