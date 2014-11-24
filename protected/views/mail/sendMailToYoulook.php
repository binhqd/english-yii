<?php if(!empty($data)) : ?>
<div align="center" style="margin:0 auto; min-height:100%;">
	<div style="background-color: #fff;border:1px solid #d1d1d1;font-family: Arial,Helvetica,sans-serif;margin: 0;min-height: 100%;text-align: left;width: 700px;">
		<div style="width:650px;height:34px; background:#f2f2f2;border-bottom:1px solid #d1d1d1;padding:22px 25px 20px">
			<img style="border:none" src="<?php echo ZoneRouter::createAbsoluteUrl('/myzone_v1/img/front/email-logo.jpg');?>" alt="logo"/>
		</div>
		<div style="padding:5px 25px 10px;">
			<div>
				<div style="float:right;width:200px;background-color:#ffffff;border:1px solid #dddddd;margin-left:15px">
					<h3 style="background-color:#f2f2f2;border-bottom:1px solid #ddd;font-size:12px;color:#0000;font-weight:normal;text-transform:uppercase;padding:12px;margin:0">SENDER INFORMATION:</h3>
					<div style="padding:12px">
						<p style="color:#6b6b6b;font-size:11px;margin:0 0 2px 0">Name: </p>
						<p style="color:#000;font-size:12px;font-weight:bold;margin:0 0 5px 0"><?php echo $data['username']?></p>
						<p style="color:#6b6b6b;font-size:11px;margin:0 0 2px 0">Email: </p>
						<p style="color:#000;font-size:12px;font-weight:bold;margin:0 0 2px 0"><?php echo $data['emailUser']?></p>
					</div>
				</div>
				<div style="font-size:12px;min-height:135px">
					<p style="line-height: 20px;">
						<?php echo $data['message']?>
					</p>
				</div>
			</div>
			<div style="border-top:1px solid #dddddd;color:#727272;font-size:12px; padding-top:14px">
				<p style="margin:0">This message has sent at: <?php echo date("M");?> <?php echo date("d");?> , <?php echo date("Y");?></p>
			</div>
		</div>
		<div style="overflow:hidden"><img src="<?php echo ZoneRouter::createAbsoluteUrl('/myzone_v1/img/front/email-footer.jpg');?>" alt="footer-line"></div>
	</div>
</div>
<?php endif; ?>