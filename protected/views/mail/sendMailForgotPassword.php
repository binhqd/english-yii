<?php if(!empty($data)) : ?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td style="padding:10px;">
				<p style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #666; font-weight:200; font-size: 18px; text-align:center; padding:5px; margin:10px 0px 0px 0px;">
					Hi <?php echo $data['user']['displayname'];?>
				</p>
				<p style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #666; font-weight:200; font-size: 14px; text-align:center; padding:0px; margin:5px 0px 10px 0px;">You recently asked to reset your YouLook password. So, here is your account information:</p>
			</td>
		</tr>
		<tr>
			<!-- Text Area -->
			<td style="padding:0px 100px;">
				<table style="width:100%;" align="center" border="0" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<!-- Firstname -->
							<td style=" width:50%;padding:7px 10px 7px 5px; border-right: 1px solid #e6e6e6; border-bottom: 1px solid #e6e6e6; text-align:right;">
								<span style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #ccc; font-weight:200; font-size: 12px; text-align:right; text-transform: uppercase;">First Name</span>
							</td>
							<td style=" width:50%;padding:7px 5px 7px 10px; border-bottom: 1px solid #e6e6e6;">
								<span style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #999; font-weight:200; font-size: 14px; text-align:left;"><?php echo $data['user']['firstname'];?></span>
							</td>
						</tr>
						<tr>
							<!-- Lastname -->
							<td style=" width:50%;padding:7px 10px 7px 5px; border-right: 1px solid #e6e6e6; border-bottom: 1px solid #e6e6e6; text-align:right;">
								<span style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #ccc; font-weight:200; font-size: 12px; text-align:right; text-transform: uppercase;">Last Name</span>
							</td>
							<td style=" width:50%;padding:7px 5px 7px 10px; border-bottom: 1px solid #e6e6e6;">
								<span style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #999; font-weight:200; font-size: 14px; text-align:left;"><?php echo $data['user']['lastname'];?></span>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<!-- End User Data -->
		<tr>
			<td style="padding:20px 0px;">
				<p style="text-align:center; padding:0px; margin:15px 0px 10px 0px;">
					
					<a href="<?php echo ZoneRouter::createAbsoluteUrl('/users/recoverPassword', array('code' => $data['codeForgotPassword'])) ?>" style="margin-top: 10px;background-color: #79bd42;border: 1px solid #5d9f28;color: #fff;text-decoration: none;font-size: 22px;font-weight: bold;padding: 10px 40px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;text-shadow: 2px 2px 2px #5e9533;">
						CLICK HERE TO CHANGE PASSWORD
					</a>
				
				</p>
				<p style="text-align:center; padding:0px; margin:25px 0px 0px 0px; font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #999; font-size:12px; font-weight:200; "><em>Or, you can copy and paste this link into your browser:</em></p>
				<p style="text-align:center; padding:0px; margin:0px; font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #999; font-size:12px; font-weight:200; "><?php echo ZoneRouter::createAbsoluteUrl('/users/recoverPassword', array('code' => $data['codeForgotPassword'])) ?></p>
			</td>
		</tr>
	</tbody>
</table>
<?php endif; ?>