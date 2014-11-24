<?php
Yii::import('application.modules.followings.models.ZoneFollowing');
$countFollowers = ZoneFollowing::model()->countFollowers(IDHelper::uuidToBinary($namespaceID));
$urlFollowers = GNRouter::createUrl('/followings/list/followers');
if (currentUser()->isGuest || currentUser()->id != IDHelper::uuidToBinary($namespaceID)) {
	$params = array('token='.$followingObjectType.'_'.$namespaceID);
	if (isset($_GET['label'])) $params[] = 'label=' . $_GET['label'];
	if (isset($_GET['refer'])) $params[] = 'refer=' . $_GET['refer'];
	$urlFollowers = GNRouter::createUrl('/followings/list/followers') . '?' . implode('&', $params);
}


if($this->targetNode){
	if(Yii::app()->controller->module!=null)
		$paramsUrl = str_replace("/".Yii::app()->controller->module->id."/".Yii::app()->controller->id."/".Yii::app()->controller->action->id,"",Yii::app()->request->url);
	else $paramsUrl = str_replace("/".Yii::app()->controller->id."/".Yii::app()->controller->action->id,"",Yii::app()->request->url);
}else{
	$paramsUrl = "?id={$this->namespaceID}";
}

// hack code
$paramsUrl = str_replace("token","id",$paramsUrl);
$paramsUrl = str_replace("object_","",$paramsUrl);
?>
<div class="wd-headline custom-header-action-photo ">
	<?php
	if(!empty($this->pages) && $this->pages == "photo"){
	?>
	
	<div class="wd-act-button-photo-update">
		<?php if(currentUser()->isGuest): ?>
			
			<a href="javascript:void(0)" onclick='$(".wd-login-yltn-bt").magnificPopup({tClose: "Close (Esc)",closeBtnInside:false}).trigger("click");' class="wd-bt-add-photo"><span class="wd-icon-add-photo"></span>Add photos</a>
		<?php else: ?>
			<a href="javascript:void(0)" onclick="addPhotos()" class="wd-bt-add-photo"><span class="wd-icon-add-photo"></span>Add photos</a>
		<?php endif;?>
		
		<a href="javascript:void(0)" id="doneUpload" nodeid="<?php echo $namespaceID;?>" style="display:none" class="wd-bt-done-upload wd-save-button"><span>Done upload</span></a>
		<a href="javascript:void(0)" onclick="cancelPhotos()" style="display:none" id="cancelUpload" class="wd-bt-cancel">Cancel</a>
	</div>
	<?php
	}
	?>
	<ul class="wd-user-interaction-status">
		<li>
			<a href="<?php echo $urlFollowers; ?>">
				<span class="wd-icon-1 <?php echo (!empty(Yii::app()->controller->module->id) && Yii::app()->controller->module->id == "followings") ? 'wd-icon-follow-acti' : 'wd-icon-follow'?>">&nbsp;</span>
				<span class="wd-name js-follower-count-text" data-token="object_<?php echo $namespaceID; ?>">Follower<?php echo $countFollowers == 1 ? '' : 's'; ?></span>
				<span class="wd-value js-follower-count" data-token="object_<?php echo $namespaceID; ?>"><?php echo $countFollowers; ?></span>
			</a>
		</li>
		
		<li class="urlViewAllArticles">
			<a href="<?php echo GNRouter::createUrl("/articles/views/index{$paramsUrl}");?>" >
				<span class="wd-icon-1 <?php echo (Yii::app()->controller->id == "views" && Yii::app()->controller->module->id == "articles") ? "wd-icon-contribution-acti" : "wd-icon-contribution";?>">&nbsp;</span><span class="wd-name">Articles </span>
				<span class="wd-value"><?php echo $totalArticle;?></span>
			</a>
		</li>
		<li class="urlViewAllPhotos">
			<a href="<?php echo GNRouter::createUrl("/photos/views/index{$paramsUrl}");?>" >
				<span class="wd-icon-1 <?php echo (Yii::app()->controller->id == "views" && Yii::app()->controller->module->id == "photos") ? "wd-icon-photo-acti" : "wd-icon-photo";?>">&nbsp;</span>
				<span class="wd-name">Photos</span><span class="wd-value"><?php echo $totalImages;?></span>
			</a>
		</li>
		<li><a href="#"><span class="wd-icon-1 wd-icon-video">&nbsp;</span><span class="wd-name">Videos</span><span class="wd-value">0</span></a></li>
		
	</ul>
	
</div>