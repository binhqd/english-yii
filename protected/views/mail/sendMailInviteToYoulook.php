<?php if(!empty($data)) : ?>
<div align="center" style="margin:0 auto; min-height:100%;">
	<div style="background-color: #fff;border:1px solid #d1d1d1;font-family: Arial,Helvetica,sans-serif;margin: 0;min-height: 100%;text-align: left;width: 700px;">
		<div style="width:650px;height:34px; background: url(<?php echo ZoneRouter::createAbsoluteUrl('/myzone_v1/img/front/bg-md-header.jpg');?>) repeat-x;border-bottom:1px solid #d1d1d1;padding:8px 25px 8px">
			<img style="border:none" src="<?php echo ZoneRouter::createAbsoluteUrl('/myzone_v1/img/front/youlook-logo.gif');?>" alt="logo"/>
		</div>
		<div style="padding:5px 25px 10px;">
			<div>
				<div style="font-size:12px;min-height:135px">
					<p style="line-height: 20px;">
						Hi!<br/>
						Yay! You have been invited to Youlook by <?php echo $data['senderName']; ?><br/>
						<br/>
						YouLook is the social network of human-knowledge. YouLook's mission is to provide a free online open encyclopedia for the Internet community of the World, a place where you might find, share, discuss anything of knowledge, understanding and experience.<br/>
						YouLook is different from other encyclopedias and social networks. If you want to have an overview of YouLook's concepts, please read on.<br/>
						<br/>
						<a href="http://youlook.net">Click here to get started</a><br/>
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