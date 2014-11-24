<style>
.font-small {font-size:11px;}
.traces {display:none;}
.traces li:nth-child(odd) {background-color: #f9f9f9;}
.traces li:nth-child(even) {background-color: #ffffff;}
</style>
<div style='margin-top:10px'>&nbsp;</div>
<table class="table table-striped font-small">
<tr>
	<th class='span1'>#</th>
	<th class='span4'>Message</th>
	<th>File</th>
	<th class='span1'>Total</th>
	<th class='span2'>Action</th>
</tr>
<?php $cnt = 1;
foreach ($errors as $error):?>
<tr>
	<td><?php echo $cnt++;?></td>
	<td>
		<?php echo $error['message'];?>
	</td>
	<td>
		<div><?php echo $error['file'];?> (<?php echo $error['line'];?>)</div>
		
	</td>
	<td><?php echo $error['total'];?></td>
	<td>
		[ 
			<a href='<?php echo ZoneRouter::createUrl("/errors/deleteErrors?file=".urlencode($error['file'])."&line={$error['line']}")?>'>Delete</a> 
			| <a href='<?php echo ZoneRouter::createUrl("/errors/listErrors?file=".urlencode($error['file'])."&line={$error['line']}")?>'>List Errors</a> 
		]
	</td>
</tr>
<?php endforeach;?>
</table>

<script language='javascript'>

</script>