<div id="content-header">
	<h1><?php echo Yii::t("contact", "Contacts");?></h1>
</div>
<div id="breadcrumb">
	<a href="<?php echo GNRouter::createUrl('/')?>" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<a href="#" class="current"><?php echo Yii::t("contact", "Contacts");?></a>
</div>
<div class="container-fluid row-fluid">
	<div class="span11">
		<div class="widget-box">
			<div class="widget-title">
				<span class="icon">
					<i class="icon-th"></i>
				</span>
				<h5>List contacts</h5>
			</div>
			<div class="widget-content nopadding">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th class='span1'>#</th>
							<th>Column name</th>
							<th class='span3'><?php echo Yii::t("admin", "Created")?></th>
							<th class='span3'><?php echo Yii::t("admin", "Actions")?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td><a href='<?php echo GNRouter::createUrl('/contacts/detail?id=5')?>'>Click to view detail</a></td>
							<td>Row 3</td>
							<td>
								<a href="#" class="btn btn-primary btn-mini">Edit</a>
								<a href="#" class="btn btn-danger btn-mini">Delete</a>
								<a href="#" class="btn btn-success btn-mini">Publish</a>
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Row 2</td>
							<td>Row 3</td>
							<td>Row 4</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Row 2</td>
							<td>Row 3</td>
							<td>Row 4</td>
						</tr>
						<tr>
							<td>4</td>
							<td>Row 2</td>
							<td>Row 3</td>
							<td>Row 4</td>
						</tr>
						<tr>
							<td>5</td>
							<td>Row 2</td>
							<td>Row 3</td>
							<td>Row 4</td>
						</tr>
						<tr>
							<td>6</td>
							<td>Row 2</td>
							<td>Row 3</td>
							<td>Row 4</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php $this->renderPartial('//footer')?>
	
</div>