<style>
.font-small {font-size:11px;}
.traces {display:none;}
.traces li:nth-child(odd) {background-color: #f9f9f9;}
.traces li:nth-child(even) {background-color: #ffffff;}
</style>
<!-- <div style='margin-top:10px'>&nbsp;</div>  -->
<h2>Report Concern Items</h2> (<a href='<?php echo ZoneRouter::createUrl('/reports')?>'>View unarchived items</a>)
<table class="table table-striped font-small">
<tr>
	<th class='span1'>#</th>
	<th class='span4'>Object</th>
	<th class='span1'>Report Count</th>
	<th class='span2'>Action</th>
</tr>
<?php $cnt = 1;
foreach ($records as $item):?>
<tr>
	<td><?php echo $cnt++;?></td>
	<td>
		<?php if ($item['object_type'] == 'article'):?>
		Article: <a target='_blank' href='<?php echo DEFAULT_DOMAIN . ZoneArticle::createUrl($item['related_info'])?>'>
			<?php echo $item['related_info']['title']?>
			</a>
		<?php elseif ($item['object_type'] == 'image'):?>
		Photo: <a target='_blank' href='<?php echo DEFAULT_DOMAIN . ZoneResourceImage::createUrl($item['related_info'])?>'>
			<?php if ($item['related_info']['type'] == 'gallery'):?>
			<img class="" width="40" height="40" src="<?php echo DEFAULT_DOMAIN. "/upload/gallery/fill/40-40/{$item['related_info']['image']}?album_id={$item['related_info']['album_id']}";?>">
			<?php else:?>
			<img class="" width="40" height="40" src="<?php echo DEFAULT_DOMAIN. "/upload/user-photos/{$item['related_info']['poster']['id']}/fill/40-40/{$item['related_info']['image']}?album_id={$item['related_info']['album_id']}";?>">
			<?php endif;?>
			</a>
		<?php elseif ($item['object_type'] == 'video'):?>
		Video: <a target='_blank' href='<?php echo DEFAULT_DOMAIN . ZoneResourceVideo::createUrl($item['related_info'])?>'>
			<?php echo $item['related_info']['title']?>
			</a>
		<?php endif;?>
	</td>
	<td><?php echo $item['total']?></td>
	<td>
		[ 
			<a href='<?php echo ZoneRouter::createUrl("/reports/restore?object_id=".IDHelper::uuidFromBinary($item['object_id'], true))?>'>Restore</a>
		]
	</td>
</tr>
<?php endforeach;?>
</table>

<script language='javascript'>

</script>