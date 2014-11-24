<?php exit; ?>
<!-- <table class='table'>
<tr>
	<th class='span1'>#</th>
	<th class='span3'>ID</th>
	<th class='span4'>Source</th>
	<th class='span2'>Status</th>
</tr>
<?php
$source = array(); 
$cnt=0;
foreach ($records as $item):
$source[] = array(
	'id'		=> IDHelper::uuidFromBinary($item->id, true),
	'fb_id'		=> $item->id,
	'source'	=> $item->source
);
$cnt++;
?>
<tr>
	<td><?php echo $cnt;?></td>
	<td><?php echo $item->fb_id?></td>
	<td><?php echo $item->source?></td>
	<td><?php echo $item->done == 1 ? "Done" : "Processing"?></td>
</tr>
<?php endforeach;?>
</table> -->
<style>
#syncProgress, #syncProgress span {font-size: 15px;}
#syncProgress span {font-weight: bold;}
</style>
<div id='syncProgress'>
<span class='done'><?php echo $done?></span> of <span class='total'><?php echo $total?></span> photos has been migrated
</div>
<script language='javascript'>
var source = <?php echo @json_encode($source)?>;
var currentDone = <?php echo $done?>;
var i = 0;
function migrate(id) {
	$.ajax({
		url : homeURL + '/facebook/getPhoto?id=' + id,
		success : function(res) {
			i++;
			if (res.error) {
				console.log(res);
			} else {
				currentDone++;
				$('#syncProgress span.done').html(currentDone);
			}
			if (typeof source[i] != "undefined") migrate(source[i].id);
		} 
	});
}

$(document).ready(function() {
	if (typeof source[0] != "undefined") migrate(source[0].id);
	if (typeof source[1] != "undefined") migrate(source[1].id);
	if (typeof source[2] != "undefined") migrate(source[2].id);
	i = 2;
});
</script>