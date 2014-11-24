<table cellspacing="0" cellpadding="0" border="0" style="color:#333;background:#fff;padding:0;margin:0;width:100%;font:15px/1.25em 'Helvetica Neue',Arial,Helvetica">
	<tbody>
		<tr width="100%">
			<td valign="top" align="left" style="background:#eef0f1;font:15px/1.25em 'Helvetica Neue',Arial,Helvetica">
				<table style="border:none;padding:0 18px;margin:50px auto;width:500px">
				<tbody>
					<tr width="100%" height="60"> 
						<td valign="top" align="left" style="border-top-left-radius:4px;border-top-right-radius:4px;background: #493e86  bottom left repeat-x;padding:10px 18px;text-align:center"> 
							<img height="40" width="125" src="http://youlook.net/myzone_v1/img/front/youlook-logo.gif" title="Youlook" style="font-weight:bold;font-size:18px;color:#fff;vertical-align:top">
						</td> 
					</tr>
					<tr width="100%"> 
						<td valign="top" align="left" style="border-bottom-left-radius:4px;background:#fff;padding:18px"> 
							<h1 style="font-size:12px;margin:0"><?php echo $data['message']?> </h1>
							<p>Applicant : <?php echo $data['model']->applicant?></p>
							<p>Position : <?php echo $data['model']->position?></p>
							<p>Email : <?php echo $data['model']->email?></p>
							<p>Phone : <?php echo $data['model']->phone?></p>
							<a href="<?php echo ZoneRouter::createAbsoluteUrl("/admin_manage/career/view/id/{$data['model']->id}");?>">View detail</a>
							<hr style="clear:both;min-height:1px;border:0;border:none;width:100%;background:#dcdcdc;color:#dcdcdc;margin:18px 0;padding:0"> 
							<span style="margin:0;padding:0;font-size:12px;font-family:Arial,Helvetica,sans-serif">
								Thank you !<br>
								The Youlook Team
								<br>
							</span>
						</td>
					</tr> 
				</tbody>
				</table>
			</td> 
		</tr>
	</tbody> 
</table>