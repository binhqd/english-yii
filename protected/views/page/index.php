<?php CVarDumper::dump($popup); ?>
<?php if($popup): ?>
<div id="success_msg">
    <div class="jlb_site_has_been_created jlb_dialog">
	<h1><?php echo Yii::t('sites','Congratulations! Your site has been created') ?>.</h1>
	<p>
		 <?php echo Yii::t('sites','We have successfully created your website at') ?> <a href="http://<?php echo JLTL_LIVESITE_DOMAIN . '/' . Yii::app()->user->name .'/' . Yii::app()->Config->SiteInfo->sitealias . '/page/home'; ?>">http://<?php echo JLTL_LIVESITE_DOMAIN . '/' . Yii::app()->user->name .'/' . Yii::app()->Config->SiteInfo->sitealias . '/page/home'; ?></a> <?php echo Yii::t('sites','with the following pages') ?>:
	</p>
	<ul>
		<?php foreach($pages as $page): ?>
            <li><?php echo $page->title; ?></li>
        <?php endforeach; ?>
	</ul>
	<p>
		<?php echo Yii::t('sites','You can always create other pages as well as edit or delete existing pages later') ?>.
	</p>
	<div class="jlb_align_center">
        <input type='submit' title="http://<?php echo JLTL_EDIT_DOMAIN . '/' . Yii::app()->user->name .'/' . Yii::app()->Config->SiteInfo->sitealias . '/page/home' ?>" value='<?php echo Yii::t('sites','Start editing') ?>' class='jlb_bt_create_step_2' />
        <input type='submit' title="" value='<?php echo Yii::t('sites','View list page') ?>' class='jlb_bt_create_step_2' />
	</div>
    </div>    
</div>

<?php GNAssetHelper::setBase("jlbackend");?>
<?php GNAssetHelper::cssFile('css/common');?>
<?php GNAssetHelper::cssFile('css/jquery.fancybox-1.3.4');?>

<script type="text/javascript">
$(function() {
	$.fancybox($("#success_msg").html(),
		{
			'autoDimensions'	: false,
			'width'         		: 757,
			'scrolling'   		: 'no',	
            'onComplete': function() {
				$("#fancybox-close").css({'opacity': 1});
   			}		
	});
    
    $('.jlb_bt_create_step_2').click(function(){
       if($(this).attr('title') != '')
            window.location.href = $(this).attr('title');
       else
            $.fancybox.close();       
       return false;
    });
});
</script>
<?php endif; ?>