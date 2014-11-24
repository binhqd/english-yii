<div id="yw0" class="grid-view">
	<table class="items table table-bordered">
		<thead>
			<tr>
				<th id="yw0_c0"><a class="sort-link" href="#">#<span class="caret"></span></a></th>
				<th id="yw0_c1"><a class="sort-link" href="#">Date<span class="caret"></span></a></th>
				<th id="yw0_c2"><a class="sort-link" href="#">Applicant<span class="caret"></span></a></th>
				<th id="yw0_c3"><a class="sort-link" href="#">Position<span class="caret"></span></a></th>
				<th id="yw0_c4"><a class="sort-link" href="#">Email<span class="caret"></span></a></th>
				<th id="yw0_c5"><a class="sort-link" href="#">Phone<span class="caret"></span></a></th>
				<th id="yw0_c6"><a class="sort-link" href="#">File<span class="caret"></span></a></th>
				<th class="button-column" id="yw0_c5">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php if(!empty($models)) : ?>
				<?php foreach($models as $model) : ?>
					<tr class="odd">
						<td><?php echo $model['id'] ?></td>
						<td><?php echo $model['created'] ?></td>
						<td><?php echo $model['applicant'] ?></td>
						<td><?php echo $model['position'] ?></td>
						<td><?php echo $model['email'] ?></td>
						<td><?php echo $model['phone'] ?></td>
						<td>
							<?php if($model['totalFile']>0):?>
								<a class="youlook-view-file-attachment" title="View file<?php echo $model['totalFile']!=1 ? 's' : ''?>" rel="tooltip" href="javascript:void(0)"><?php echo $model['totalFile'] ?> file<?php echo $model['totalFile']!=1 ? 's' : ''?></a>
								<div class="youlook-file-attachment-detail" style="display:none">
									<?php if(!empty($model['files'])) :?>
										<?php foreach($model['files'] as $file) :?>
											<a href='<?php echo ZoneRouter::createURL("/upload/career") . '/' . date("mdY", strtotime($model['created'])) . '/' . $file?>'> <?php echo $file?></a><br>
										<?php endforeach;?>
									<?php endif;?>
								</div>
							<?php endif;?>
						</td>
						<td style="width: 50px">
							<a style="padding:0px 10px 0px 5px" class="view" title="View" rel="tooltip" href="<?php echo ZoneRouter::createURL('/admin_manage/career/view', array('id'=>$model['id']))?>"><i class="icon-eye-open"></i></a>
							<?php echo CHtml::link(
								'<i class="icon-trash"></i>',
								 ZoneRouter::createURL('/admin_manage/career/delete', array('id'=>$model['id'])),
								 array(
									'confirm' => 'Are you sure?',
									'class'=>'delete',
									'rel'=>'tooltip',
									'title'=>'Delete'
								 )
							);?>
						</td>
					</tr>
				<?php endforeach;?>
			<?php endif;?>
		</tbody>
	</table><div class="keys" style="display:none" title="/admin_manage/career"><span>72</span></div>
</div>
<div class="wd-pagination">
	<?php $this->widget('CLinkPager', array(
		'pages' => $pages,
		'header'=>'',
	)) ?>
</div>
<script>
	$(function(){
		$('.youlook-view-file-attachment').click(function(){
			$(this).toggle();
			$(this).parent().find('.youlook-file-attachment-detail').toggle();
		});
	});
</script>