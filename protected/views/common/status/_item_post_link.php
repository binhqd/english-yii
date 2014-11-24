<?php
	if(is_array($content)):
		parse_str( parse_url( @$content['url'], PHP_URL_QUERY ), $my_array_of_vars );
		
		$token = md5(uniqid(32));
?>

<div class="wd-link-content" id="<?php echo $token;?>">
	<?php if($close):?>
		<a class="wd-close-topmess wd-close-top3 close-video" onclick="$('#pullPostLink').html(''); $('#pullPostLink').hide(); zone.formPost.checkGetUrl=false;"></a>
	<?php endif;?>
	
		<?php
		if(!empty($my_array_of_vars['v'])):
		?>
		<a class="wd-addnew-image view-video" href="javascript:void(0)" target="<?php echo $token;?>" key="<?php echo $my_array_of_vars['v'];?>">
			<img src="<?php echo "http://img.youtube.com/vi/".$my_array_of_vars['v']."/0.jpg";?>" height="120" width="120">
			<span class="wd-icon-play" ></span>	
		</a>
		<?php else:?>
			<?php if(@$content['image'] != null):?>
				<a class="wd-addnew-image view-video" href="<?php echo @$content['url'];?>" target="wd-link-content">
					<img src="<?php echo @$content['image'];?>" height="120" width="120">
				</a>
			<?php endif;?>
		<?php endif;?>
		
	
	<div class="wd-addnew-text wd-addnew-link-infor">
		<div class="wd-nameposter">
			<h3 class="wd_tt_n1"><a href="<?php echo @$content['url'];?>" class="wd-title"><?php echo @$content['title'];?></a> </h3>
			<p class="wd-link"><?php echo @$content['url'];?></p>
		</div>
		<div class="wd-disc">
			<p><?php echo @$content['description'];?></p>
		</div>
	</div>
</div>
<?php if($close):?>
<div style="display:none">
	<textarea  name="dataOther"  id="dataOther"><?php echo @CJSON::encode($content);?></textarea>
</div>

<?php
endif;
	endif;
?>