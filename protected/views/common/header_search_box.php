<?php
GNAssetHelper::setBase('myzone_v1');
GNAssetHelper::scriptFile('toogle-header-search', CClientScript::POS_END);

/* Autocomplete*/
GNAssetHelper::cssFile('type-movie-post-video');
GNAssetHelper::cssFile('youlook-header');
GNAssetHelper::scriptFile('gnAutocomplete', CClientScript::POS_END);
GNAssetHelper::scriptFile('youlook-header', CClientScript::POS_END);
?>
<fieldset id="wd-header-search">
	<div class="wd-input">
		<!-- form search for youtube -->
		<form class="js-form-search" id="frm-header-search-youtube" name="searchYoutube" method="GET" action="<?php echo GNRouter::createUrl("/video/search");?>">
			<input type="text" placeholder="<?php echo Yii::t('Youlook', 'Search for Videos'); ?>"  id="input-header-search-youtube" autocomplete="off" class="wd-text-search youlook-text-search" name="keyword" value="<?php echo (!empty($_GET['keyword']) && Yii::app()->controller->id=='video' && Yii::app()->controller->action->id=='search') ? CHtml::encode($_GET['keyword']) : ''?>"/>
			<input type="submit" value="" class="wd-search-bt wd-submit wd-tooltip-hover-north" title="<?php echo Yii::t('Youlook', 'Search'); ?>" />
		</form>
		<!-- form search for people -->
		<form class="js-form-search" style="display:none" id="frm-header-search-people" name="searchNodePeople" method="GET" action="<?php echo GNRouter::createUrl("/search");?>">
			<input type="text" placeholder="<?php echo Yii::t('Youlook', 'Search for People'); ?>"  id="input-header-search-people"  class="wd-text-search youlook-text-search" name="keyword" autocomplete="off" value="<?php // echo (!empty($_GET['keyword']) && Yii::app()->controller->id=='search' && Yii::app()->controller->action->id=='index') ? CHtml::encode($_GET['keyword']) : ''?>"/>
			<input type="submit" value="" class="wd-search-bt wd-submit wd-tooltip-hover-north" title="<?php echo Yii::t('Youlook', 'Search'); ?>" />
		</form>
		<!-- form search for movie -->
		<form class="js-form-search" style="display:none" id="frm-header-search-movie" name="searchNodeMovies" method="GET" action="<?php echo GNRouter::createUrl("/search");?>">
			<input type="text" placeholder="<?php echo Yii::t('Youlook', 'Search for Movies'); ?>"  id="input-header-search-movie"  class="wd-text-search youlook-text-search" name="keyword" autocomplete="off" value="<?php // echo (!empty($_GET['keyword']) && Yii::app()->controller->id=='search' && Yii::app()->controller->action->id=='index') ? CHtml::encode($_GET['keyword']) : ''?>"/>
			<input type="submit" value="" class="wd-search-bt wd-submit wd-tooltip-hover-north" title="<?php echo Yii::t('Youlook', 'Search'); ?>" />
		</form>
		<!-- form search for cultures -->
		<form class="js-form-search" style="display:none" id="frm-header-search-cultures" name="searchNodeCultures" method="GET" action="<?php echo GNRouter::createUrl("/search");?>">
			<input type="text" placeholder="<?php echo Yii::t('Youlook', 'Search for Cultures'); ?>"  id="input-header-search-cultures"  class="wd-text-search youlook-text-search" name="keyword" autocomplete="off" value="<?php // echo (!empty($_GET['keyword']) && Yii::app()->controller->id=='search' && Yii::app()->controller->action->id=='index') ? CHtml::encode($_GET['keyword']) : ''?>"/>
			<input type="submit" value="" class="wd-search-bt wd-submit wd-tooltip-hover-north" title="<?php echo Yii::t('Youlook', 'Search'); ?>" />
		</form>

		<a class="wd-header-search-detail-bt youlook-header-search-detail-bt wd_toggle_bt">
			<span class="wd-icon-search-top wd-icon-search-video"></span>
		</a>
		<div class="wd-header-search-pp wd_toggle" id="youlook-checkbox-search">
			<ul class="wd-search-choice youlook-search-choice">
				<li class="wd-active wd-item" data-form-name="searchYoutube">
					<a href="javascript:void(0)" class="wd-item-link"><span class="wd-icon-search-video"></span><span class="wd-icon-checked"></span><span class="">Videos</span></a>
				</li>
				<li class="wd-item" data-form-name="searchNodePeople">
					<a href="javascript:void(0)" class="wd-item-link"><span class="wd-icon-search-people"></span><span class="wd-icon-checked"></span><span class="">People</span></a>
				</li>
				<li class="wd-item" data-form-name="searchNodeMovies">
					<a href="javascript:void(0)" class="wd-item-link"><span class="wd-icon-search-movie"></span><span class="wd-icon-checked"></span><span class="">Movies</span></a>
				</li>
				<li class="wd-item" data-form-name="searchNodeCultures">
					<a href="javascript:void(0)" class="wd-item-link"><span class="wd-icon-search-culture"></span><span class="wd-icon-checked"></span><span class="">Cultures</span></a>
				</li>
			</ul>
		</div>
	</div>
</fieldset>