<div id="content-header">
	<h1><?php echo Yii::t("articles", "Article Categories");?></h1>
</div>
<div id="breadcrumb">
	<a href="<?php echo GNRouter::createUrl('/')?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<a href="#" class="current"><?php echo Yii::t("article", "Article Categories");?></a>
</div>
<div class="container-fluid row-fluid">
	<div class="span11">
		<div class="widget-box">
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th class='span1'>#</th>
							<th class='span3'><?php echo Yii::t("articles", "Name")?></th>
							<th><?php echo Yii::t("admin", "Description")?></th>
							<th class='span3'><?php echo Yii::t("admin", "Actions")?></th>
						</tr>
					</thead>
					<tbody>
						<?php $cnt = 0;foreach ($categories as $category):$cnt++;?>
						<tr>
							<td><?php echo $cnt?></td>
							<td><a href='<?php echo GNRouter::createUrl('/articles/list?category='. IDHelper::uuidFromBinary($category->id, true) )?>'><?php echo $category->name?></a></td>
							<td><?php echo $category->description?></td>
							<td>
								<a href="<?php echo GNRouter::createUrl('/articles/categories/edit?id=' . IDHelper::uuidFromBinary($category->id, true))?>" class="btn btn-primary btn-mini"><?php echo Yii::t("admin", "Edit")?></a>
								<a href="<?php echo GNRouter::createUrl('/articles/categories/delete?id=' . IDHelper::uuidFromBinary($category->id, true))?>" class="btn btn-danger btn-mini"><?php echo Yii::t("admin", "Delete")?></a>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php $this->renderPartial('//footer')?>
	
</div>