<?php
$exper = (object) $exper;
$content = @CJSON::decode($exper->content);
$period = $content['period'];
$arrPeriod = explode("-",$period);
$periodStart  = "";
$periodEnd  = "";
$present  = "";
if(!empty($arrPeriod[0])) $periodStart  = trim($arrPeriod[0]);
if(!empty($arrPeriod[1])) $periodEnd  = trim($arrPeriod[1]);
if(strtolower($periodEnd) == 'present') $present  = strtolower($periodEnd);
?>
	<?php
	if(empty($type)){
	?>
	<div class="wd-edit-block">
		<span class="wd-icon-edit-28 edit-experience" uuid="<?php echo IDHelper::uuidFromBinary($exper->id,true);?>" present="<?php echo $present;?>" periodend=<?php echo @CJSON::encode(explode(" ",$periodEnd));?> periodstart=<?php echo @CJSON::encode(explode(" ",$periodStart));?> title="<?php echo $content['title'];?>" period="<?php echo $content['period'];?>" description="<?php echo $content['description'];?>" companyname="<?php echo $content['name'];?>" location="<?php echo $content['location'];?>" style="display: block;"></span>
		<div class="wd-item-edit-lc" >
	
			<h3 class="wd_tts_1"><?php echo $content['title'];?></h3>
			<p><a href="javascript:void(0)"><?php echo $content['name'];?></a></p>
			<p class="wd-gray-cl"><?php echo $content['period'];?> <?php echo $content['location'];?></p>
			<p><?php echo $content['description'];?></p>
		</div>
	</div>
	<?php
	}else{
	?>
		<h3 class="wd_tts_1"><?php echo $content['title'];?></h3>
		<p><a href="javascript:void(0)"><?php echo $content['name'];?></a></p>
		<p class="wd-gray-cl"><?php echo $content['period'];?> <?php echo $content['location'];?></p>
		<p><?php echo $content['description'];?></p>
	<?php
	
	}
	?>
