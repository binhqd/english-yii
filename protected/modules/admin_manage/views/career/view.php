<div>
	<a href="<?php echo ZoneRouter::createURL('/admin_manage/career/delete/', array('id'=>$model->id))?>" >Delete</a>
</div>
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array('name'=>'id', 'label'=>'#'),
		array('name'=>'applicant', 'label'=>'Applicant'),
		array('name'=>'position', 'label'=>'Position'),
		array('name'=>'email', 'label'=>'Email'),
		array('name'=>'phone', 'label'=>'Phone'),
		array('name'=>'street', 'label'=>'Street'),
		array('name'=>'city', 'label'=>'City'),
		array('name'=>'state', 'label'=>'State'),
		array('name'=>'zipcode', 'label'=>'Zipcode'),
		array('name'=>'country', 'label'=>'Country'),
		array('name'=>'website', 'label'=>'Website'),
		array('name'=>'comment', 'label'=>'Comment'),
		array('name'=>'created', 'label'=>'Created'),
	),
)); ?>
<table class="detail-view table table-striped table-condensed" id="yw0">
	<tbody>
		<tr class="even">
			<th>File</th>
			<td>
				<?php
					foreach($files as $file){
						echo '<a href="' . ZoneRouter::createURL('/upload/career') . '/' . date("mdY", strtotime($file->created )).  '/' . $file->file . '">' . $file->file . '</a> | ';
					}
				?>
			</td>
		</tr>
	</tbody>
</table>