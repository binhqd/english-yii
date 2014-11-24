<?php if(!empty($data)) : ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody><tr>
	<td style="padding:10px;">
	 <p style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #666; font-weight:200; font-size: 18px; text-align:center; padding:5px; margin:10px 0px 0px 0px;">
	 Hey there <?php echo $data['user']['displayname'];?>
	 </p>
	 <p style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #666; font-weight:200; font-size: 14px; text-align:center; padding:0px; margin:5px 0px 10px 0px;">We just wanted to let you know that your YouLook password was changed at  <?php echo date("Y-m-d H:i:s");?>.</p>
	 </td>
</tr>
</tbody></table>
<?php endif; ?>

