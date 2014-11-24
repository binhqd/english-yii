<style>
.font-small {font-size:11px;}
.traces {display:none;}
.traces li:nth-child(odd) {background-color: #f9f9f9;}
.traces li:nth-child(even) {background-color: #ffffff;}
.header-group {font-weight: bold;font-style:italic;background: #dddddd}
</style>
<div style='margin-top:10px'>&nbsp;</div>

<table class="table">
<tr>
	<td colspan='4'>[ <a href='<?php echo ZoneRouter::createUrl('/errors')?>'>Back to Error Index</a> ]
	<h4>File: <?php echo $file?></h4>
	<h4>Line: <?php echo $line?></h4>
	</td>
</tr>
<?php $cnt = 1;
foreach ($errors as $error):?>
<tr class='header-group'>
	<td>(Error <?php echo $error['code'];?>) <?php echo $error['request_method'];?>: <?php echo $error['uri'];?></td>
	<td class='span2'>TOTAL: <?php echo $error['total'];?></td>
	<td class='span2'>
		[ <a href='<?php echo ZoneRouter::createUrl("/errors/deleteErrorsByGroup?file=".urlencode($file)."&line={$line}&uri=".urlencode($error['uri'])."&method={$error['request_method']}")?>'>Remove group</a> ]
	</td>
</tr>
<tr>
	<td colspan='4'>
	<table class="table table-striped font-small">
	<tr>
		<th class='span1'>#</th>
		<th>Message</th>
		<th class='span4'>Referrer</th>
		<th class='span2'>IP</th>
		<th class='span2'>Browser</th>
		<th class='span2'>Date</th>
	</tr>
	<?php $itemCnt = 1;
	foreach ($error['items'] as $item):?>
	<tr>
		<td><?php echo $itemCnt++;?></td>
		<td>
			<?php echo $item['message']?>
			<ol class='traces'>
			<?php foreach ($item['traces'] as $trace):?>
			<li><?php echo $trace?></li>
			<?php endforeach;?>
			</ol>
			<div class='show-traces'>[ <a href='#'><span>Show traces</span></a> ]</div>
		</td>
		<td><?php echo $item['referrer']?></td>
		<td><?php echo $item['ip']?></td>
		<td><?php echo $item['browser']['platform']?> <?php echo $item['browser']['version']?></td>
		<td><?php echo date("Y-m-d H:i:s", $item['logtime'])?></td>
	</tr>
	<?php endforeach;?>
	</table>
	</td>
</tr>
<?php endforeach;?>
</table>

<script language='javascript'>
$(document).ready(function() {
	$('.show-traces').click(function() {
		if ($(this).hasClass('showed')) {
			$(this).parents('td').find('.traces').hide();
			$(this).removeClass('showed');
			$(this).find('span').html('Show Traces');
		} else {
			$(this).parents('td').find('.traces').show();
			$(this).addClass('showed');
			$(this).find('span').html('Hide Traces');
		}
		return false;
	});
});
</script>