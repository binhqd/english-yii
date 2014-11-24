<?php
$edu = (object) $edu;
$content = @CJSON::decode($edu->content);
$school = $content['school'];
$datesAttended = $content['datesAttended'];
$degree = $content['degree'];
$fieldOfStudy = $content['fieldOfStudy'];
$grade = $content['grade'];
$activitiesAndSocieties = $content['activitiesAndSocieties'];
$description = $content['description'];

$arrDay = explode("-",$datesAttended);
$start = "";
$end = "";
if(!empty($arrDay[0]))  $start = trim($arrDay[0]);
if(!empty($arrDay[1]))  $end = trim($arrDay[1]);
?>
	<?php
	if(empty($type)){
	?>
	<div class="wd-edit-block">
		<span class="wd-icon-edit-28 edit-education" uuid="<?php echo IDHelper::uuidFromBinary($edu->id,true);?>" 
			school="<?php echo $school;?>"
			datesAttended="<?php echo $datesAttended;?>"
			degree="<?php echo $degree;?>"
			fieldOfStudy="<?php echo $fieldOfStudy;?>"
			grade="<?php echo $grade;?>"
			activitiesAndSocieties="<?php echo $activitiesAndSocieties;?>"
			description="<?php echo $description;?>"
			start="<?php echo $start;?>"
			end="<?php echo $end;?>"
		style="display: block;"></span>
		<div class="wd-item-edit-lc" >
	
			<h3 class="wd_tts_1"><a href="javascript:void(0)"><?php echo $school;?></a></h3>
			<p><?php echo $activitiesAndSocieties;?></p>
			<p class="wd-gray-cl"><?php echo $datesAttended;?></p>
		</div>
	</div>
	<?php
	}else{
	?>
		<h3 class="wd_tts_1"><a href="javascript:void(0)"><?php echo $school;?></a></h3>
		<p><?php echo $activitiesAndSocieties;?></p>
		<p class="wd-gray-cl"><?php echo $datesAttended;?></p>
		
	<?php
	
	}
	?>
