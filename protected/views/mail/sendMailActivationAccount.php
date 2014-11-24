<?php if(!empty($data)) : ?>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tbody>
		<tr>
			<td style="padding:10px;">
				<p style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #666; font-weight:200; font-size: 18px; text-align:center;">
					Hey there <?php echo $data['user']['displayname'];?>
				</p>
				<p style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #666; font-weight:200; font-size: 14px; text-align:center; padding:0px; margin:5px 0px 10px 0px;">We are so excited to have you as part of the YouLook community!</p>
				<p style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #666; font-weight:200; font-size: 14px; text-align:center; padding:0px; margin:5px 0px 10px 0px;">Your account information is as follows:</p>
			</td>
		</tr>
		<tr>
			<!-- Class Data -->
			<td style="padding:0px 100px;">
				<table cellpadding="0" cellspacing="0" border="0" style="width:100%;" align="center">
					<tbody>
						<tr>
							<!-- Day of the Week -->
							<td style=" width:50%;padding:7px 10px 7px 5px; border-right: 1px solid #e6e6e6; border-bottom: 1px solid #e6e6e6; text-align:right;">
								<span style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #ccc; font-weight:200; font-size: 12px; text-align:right; text-transform: uppercase;">First Name</span>
							</td>
							<td style=" width:50%;padding:7px 5px 7px 10px; border-bottom: 1px solid #e6e6e6;"><span style="color: rgb(153, 153, 153); font-family: 'helvetica neue', Arial, Helvetica, sans-serif; font-size: 14px;"><?php echo $data['user']['firstname'];?></span></td>
						</tr>
						<tr>
							<!-- Time -->
							<td style=" width:50%;padding:7px 10px 7px 5px; border-right: 1px solid #e6e6e6;  text-align:right;">
								<span style="font-family:'helvetica neue', Arial, Helvetica, sans-serif; color: #ccc; font-weight:200; font-size: 12px; text-align:right; text-transform: uppercase;">Last Name</span>
							</td>
							<td style=" width:50%;padding:7px 5px 7px 10px; "><span style="color: rgb(153, 153, 153); font-family: 'helvetica neue', Arial, Helvetica, sans-serif; font-size: 14px;"><?php echo $data['user']['lastname'];?></span></td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<!-- End Class Data -->
		<tr>
			<td style="padding: 30px 0px;">
			  <p style="text-align:center; padding:0px; margin:0px;">
				<a href="<?php echo $data['strActiveUrl']?>?interests=1" style="margin-top: 10px;background-color: #79bd42;border: 1px solid #5d9f28;color: #fff;text-decoration: none;font-size: 22px;font-weight: bold;padding: 10px 40px;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;text-shadow: 2px 2px 2px #5e9533;">
					Start Exploring
				</a>
			</p>
			</td>
		</tr>
	</tbody>
</table>

<?php endif; ?>