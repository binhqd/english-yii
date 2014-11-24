<div id="content-header">
	<h1><?php echo Yii::t("article", "Create new Article");?></h1>
</div>
<div id="breadcrumb">
	<a href="<?php echo GNRouter::createUrl('/')?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<a href="<?php echo GNRouter::createUrl('/articles/list')?>" class="tip-bottom"><?php echo Yii::t("article", "Articles");?></a>
	<a href="#" class="current"><?php echo Yii::t("article", "Create new Article");?></a>
</div>
<div class="container-fluid row-fluid">
	<div class="span11">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5><?php echo Yii::t("articles", "Create Article form")?></h5>
			</div>
			<div class="widget-content nopadding">
				<?php 
				$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
					'id'=>'create-article',
					'enableClientValidation'=>true,
					'htmlOptions'=>array('class'=>'form-horizontal'),
				)); ?>
					<div class="control-group">
						<?php echo $form->labelEx($article,'Title', array('class' => 'control-label')); ?>
						<div class="controls">
							<?php echo $form->textField($article,'title', array('class'=>'span3')); ?>
							<?php echo $form->error($article,'title'); ?>
							
						</div>
					</div>
					
					<div class="control-group">
						<?php echo $form->labelEx($article,'Description', array('class' => 'control-label')); ?>
						
						<div class="controls">
							<?php echo $form->textarea($article,'description', array('class'=>'span3')); ?>
							<?php echo $form->error($article,'description'); ?>
						</div>
					</div>
					
					<div class="control-group">
						<?php echo $form->labelEx($article,'Content', array('class' => 'control-label')); ?>
						
						<div class="controls">
							<?php echo $form->textarea($article,'content', array('class'=>'span3')); ?>
							<?php echo $form->error($article,'content'); ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label"><?php echo Yii::t("articles", "Categories")?></label>
						<div class="controls">
							<?php foreach ($categories as $category):?>
			                <label class="checkbox">
			                  <input name="Article[ArticleCategory][]" type="checkbox" value='<?php echo $category['id']?>'> <?php echo $category['name']?>
			                </label>
			                <?php endforeach;?>
			              </div>
						
					</div>
					<div style='display:none' id='createArticleHiddenInputs'>
					
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Save</button>
					</div>
				<?php $this->endWidget(); ?>
				
				<div class="controls form-horizontal">
					<div class="control-group">
						<div class="control-label"><?php echo Yii::t('articles', 'Gallery'); ?></div>
						<div class="controls">
							<?php
								$this->widget('greennet.modules.gallery.widgets.GNManageGalleryWidget', array(
									'model'			=> 'ArticleImage',
									'fieldName'		=> 'filename',
									'object_id'		=> empty($model) ? '' : IDHelper::uuidFromBinary($model->id, true),
									'uploadPath'	=> '/upload/article-photos/',
									'fileUri'		=> '/upload/article-photos',
									'deleteUrl'		=> GNRouter::createUrl('/articles/image/delete'),
									'maxNumberOfFiles' => 10000,
									//'maxNumberOfFiles'	=> 3,
									//'uploadTemplate'	
									'autoUpload'	=> true,
									'callbacks'	=> array(
										'change' => '
// 											if(typeof data.files[0].type != "undefined"){
// 												var typeImages = ["image/gif","image/jpeg","image/png"];
// 												var inArray = false;
// 												$.each(typeImages,function(x,y){
// 													if(data.files[0].type  == y){
// 														inArray = true;
// 														return;
// 													}
// 												});
// 												// $(".errorFormPost").html("");
// 												if(!inArray){
// 													// fix ie 
													
// 														jAlert("There was a problem with the image file.", "Bad Image", function(){
// 															$("#filesStatusContainer .fade").last().remove();
// 														});
// 													return;
// 												}
// 											}
											
											//objCountFile.article.addFile = objCountFile.article.addFile + data.files.length;
										',
										'added'		=> '
											//$("#zoneListPhoto").hide();
											var input = $("<input ref=\'"+data.result.files[0].fileid+"\' type=\'hidden\' name=\'images[]\' value=\'"+data.result.files[0].fileid+"\'/>");
											$("#createArticleHiddenInputs").append(input);
											
// 											objCountFile.article.doneFile = objCountFile.article.doneFile+1;
// 											if(objCountFile.article.doneFile == objCountFile.article.addFile){
// 												$("#btnPostStatus").removeAttr("disabled");
// 											}
										',
										'deleted'	=> '
// 											if (res.error) {
												
// 											} else {
												$("#createArticleHiddenInputs input[ref=\'"+res.fileid+"\']").remove();
// 											}
										'
									),
									'uploadUrl'	=> GNRouter::createUrl('/articles/image/upload')
								));
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php $this->renderPartial('//footer')?>
	
</div>