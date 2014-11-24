<div id="content-header">
	<h1><?php echo Yii::t("article", "Create new category");?></h1>
</div>
<div id="breadcrumb">
	<a href="<?php echo GNRouter::createUrl('/')?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<a href="<?php echo GNRouter::createUrl('/articles/categories')?>" class="tip-bottom"><?php echo Yii::t("article", "Categories");?></a>
	<a href="#" class="current"><?php echo Yii::t("article", "Create new category");?></a>
</div>
<div class="container-fluid row-fluid">
	<div class="span11">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5><?php echo Yii::t("articles", "Create category form")?></h5>
			</div>
			<div class="widget-content nopadding">
				<?php 
				$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					'id'=>'create-category',
					'enableClientValidation'=>true,
					'htmlOptions'=>array('class'=>'form-horizontal'),
				)); ?>
					<div class="control-group">
						<?php echo $form->labelEx($category,'Name', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo $form->textField($category,'name', array('class'=>'span3')); ?>
							<?php echo $form->error($category,'name'); ?>
							
						</div>
					</div>
					
					<div class="control-group">
						<?php echo $form->labelEx($category,'Description', array('class' => 'control-label')); ?>
						
						<div class="controls">
							<?php echo $form->textarea($category,'description', array('class'=>'span3')); ?>
							<?php echo $form->error($category,'description'); ?>
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
	<?php $this->renderPartial('//footer')?>
	
</div>