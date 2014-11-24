<?php
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js'
	));
	GNAssetHelper::setBase('assets/default');
	GNAssetHelper::cssFile('common');
	GNAssetHelper::cssFile('popup-content');
	GNAssetHelper::cssFile('magnific-popup');
	GNAssetHelper::cssFile('reset');
	GNAssetHelper::scriptFile('jquery', CClientScript::POS_HEAD);
	GNAssetHelper::scriptFile('jquery.magnific-popup.min', CClientScript::POS_END);
?>
<style>
.font-small {font-size:11px;}
.traces {display:none;}
.traces li:nth-child(odd) {background-color: #f9f9f9;}
.traces li:nth-child(even) {background-color: #ffffff;}
input[type="checkbox"] {
	width: 30px;
	margin: 0;
}
</style>
<!-- <div style='margin-top:10px'>&nbsp;</div>  -->
<h2>Report Concern Items</h2> (<a href='<?php echo ZoneRouter::createUrl('/reports/archived')?>'>View archived items</a>)
<table class="table table-striped font-small">
<tr>
	<th class='span1'>#</th>
	<th class='span4'>Object</th>
	<th class='span1'>Report Count</th>
	<th class='span2'>Action</th>
</tr>
<?php $cnt = 1;
foreach ($records as $item):
$strObjectID = IDHelper::uuidFromBinary($item['object_id'], true);
?>
<tr class="youlook-report-item" object_type="<?php echo $item['object_type']?>">
	<td><?php echo $cnt++;?></td>
	<td>
		<?php if ($item['object_type'] == 'article'):?>
		<b>Article</b>: <a target='_blank' href='<?php echo DEFAULT_DOMAIN . ZoneArticle::createUrl($item['related_info'])?>'>
			<?php echo $item['related_info']['title']?> 
			</a> | Author : <a target='_blank' href='<?php echo DEFAULT_DOMAIN ?>/profile/<?php echo $item['related_info']['author']['username']?>'><?php echo $item['related_info']['author']['displayname']?></a>
		<?php elseif ($item['object_type'] == 'image'):?>
		<b>Photo</b>: <a target='_blank' href='<?php echo DEFAULT_DOMAIN . ZoneResourceImage::createUrl($item['related_info'])?>'>
			<?php if ($item['related_info']['type'] == 'gallery'):?>
			
			<img class="" width="40" height="40" src="<?php echo DEFAULT_DOMAIN. "/upload/gallery/fill/40-40/{$item['related_info']['image']}?album_id={$item['related_info']['album_id']}";?>">
			<?php else:?>
			<img class="" width="40" height="40" src="<?php echo DEFAULT_DOMAIN. "/upload/user-photos/{$item['related_info']['poster']['id']}/fill/40-40/{$item['related_info']['image']}?album_id={$item['related_info']['album_id']}";?>">
			<?php endif;?>
			</a>
			| Author : <a target='_blank' href='<?php echo DEFAULT_DOMAIN ?>/profile/<?php echo $item['related_info']['poster']['username']?>'><?php echo $item['related_info']['poster']['displayname']?></a>
		<?php elseif ($item['object_type'] == 'video'):?>
		<b>Video</b>: <a target='_blank' href='<?php echo DEFAULT_DOMAIN . ZoneResourceVideo::createUrl($item['related_info'])?>'>
			<?php echo $item['related_info']['title']?>
			</a>
			| Author : <a target='_blank' href='<?php echo DEFAULT_DOMAIN ?>/profile/<?php echo $item['related_info']['poster']['username']?>'><?php echo $item['related_info']['poster']['displayname']?></a>
		<?php endif;?>
		<div>
			<b>Messages:</b>
		</div>
		<ul class='messages'>
			<?php foreach ($item['messages'] as $message):?>
			<li><i><?php echo date("Y-m-d H:i:s")?></i>: <?php echo strip_tags($message['content'])?></li>
			<?php endforeach;?>
		</ul>
	</td>
	<td><?php echo $item['total']?></td>
	<td>
		[ 
			  <a class="youlook-archive" object_id="<?php echo $strObjectID;?>" href="javascript:void(0)" status="delete" >Delete</a> 
			| <a class="youlook-archive" object_id="<?php echo $strObjectID;?>" href="javascript:void(0)" status="archive" >Archive</a>
		]
	</td>
</tr>
<?php endforeach;?>
</table>

<script language='javascript'>
	$(document).ready(function(){
		var trDelete;
		//popup
		$('.wd-show-popup').magnificPopup({
			tClose: 'Close',closeBtnInside:true
		});
		
		$(document).on('click', '.youlook-archive', function(){
			var _this = $(this);
			var objectID	= _this.attr('object_id');
			var objectType	= _this.parents('.youlook-report-item').attr('object_type');
			var status	= _this.attr('status');
			
			trDelete = _this.parents('.youlook-report-item');
			
			var _form		= $('#youlook-send-email-form');
			
			_form.find('input.youlook-object-id').val(objectID);
			_form.find('input.youlook-status').val(status);
			_form.find('input.youlook-object-type').val(objectType);
			$('.youlook-send-email-popup').trigger('click');
			return false;
		});
		$(document).on('click', '.youlook-submit-send-email', function(){
			var _form = $('#youlook-send-email-form');
			$.ajax({
				url : _form.attr("action"),
				type: "POST",
				dataType : 'json',
				data : _form.serialize(),
				success:function(res, textStatus, jqXHR){
					if(res.error){
						_form.find('.messageError span').html(res.message);
						_form.find('.messageError').show();
					} else {
						trDelete.remove();
						
						_form.find('.messageError').hide();
						_form.find('textarea').val('');
						_form.hide();
						$('.youlook-footer-popup-report-concern').hide();
						$('.youlook-report-concern-success').show();
						setTimeout(function(){
							$('.youlook-popup-report-concern').find('.mfp-close').trigger('click');
							$('.youlook-report-concern-success').hide();
							_form.show();
							$('.youlook-footer-popup-report-concern').show();
						},2000);
					}
				}
			});
			// e.preventDefault(); //STOP default action
			return false;
		});
	});
</script>

<div class="wd-show-popup" style="display:none">
	<div id="wd-popup-report-concern-content" class="wd-container-popup youlook-popup-report-concern">
		<div class="wd-popup-content">
			<h2 class="wd-tt-pp-5"><?php echo Yii::t("Youlook", "Send email to owner");?></h2>
			<div class="wd-upload-photo-content-pp js-upload-a-photo-select" style="display:block">
				<div class="wd-upload-photo-maincontent-pp">
					<div class="wd-upload-photo-st1">
						<form id="youlook-send-email-form" onsubmit="return false;" method="POST" action="<?php echo ZoneRouter::createUrl('/reports/send')?>" >
							<fieldset class="wd-upload-report-concern-form-pp">
								<p class="wd-des"><?php echo Yii::t("Youlook", "");?></p>
								<input class="youlook-object-id" name="object_id" id="object_id" type="hidden">
								<input class="youlook-object-type" name="object_type" id="object_id" type="hidden">
								<input class="youlook-status" name="status" id="status" type="hidden">
								
								<input type="checkbox" name="message[]" value="Spam">Spam<br>
								<input type="checkbox" name="message[]" value="Nudity or Pornography">Nudity or Pornography<br>
								<input type="checkbox" name="message[]" value="Graphic Violence">Graphic Violence<br>
								<input type="checkbox" name="message[]" value="Actively promotes self-harm">Actively promotes self-harm<br>
								<input type="checkbox" name="message[]" value="Attacks a group or individual">Attacks a group or individual<br>
								<input type="checkbox" name="message[]" value="Hateful Speech or Symbols">Hateful Speech or Symbols<br>
								<br>
								<div class="wd-input">
									<textarea class="youlook-input-textarea" rows="6" cols="65" placeholder="Content" name="message[]" id="ZoneReportConcern_content"></textarea>
								</div>
								<div class="clear"></div>
								<div style="display:none" class="wd-red-cl pt5 messageError">
									<span>error message</span>
								</div>
							</fieldset>
						</form>
						<div style="display:none;text-align:center" class="youlook-report-concern-success">
							<span>Your message has been sent successfully.</span>
						</div>
					</div>
				</div>
				<div class="wd-footer-popup youlook-footer-popup-report-concern">
					<a class="wd-accept-btf floatR youlook-submit-send-email" href="javascript:void(0)"><?php echo Yii::t("Youlook", "Send");?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="display:none">
	<a href="#wd-popup-report-concern-content" class="youlook-send-email-popup wd-popup-report wd-show-popup">Send</a>
</div>