<?php
	GNAssetHelper::setBase('myzone_v1');
	GNAssetHelper::scriptFile('youlook-report-concern', CClientScript::POS_END);
?>

<div class="wd-show-popup" style="display:none">
	<div id="wd-popup-report-concern-content" class="wd-container-popup youlook-popup-report-concern">
		<div class="wd-popup-content">
			<h2 class="wd-tt-pp-5"><?php echo Yii::t("Youlook", "Report Concern");?></h2>
			<div class="wd-upload-photo-content-pp js-upload-a-photo-select" style="display:block">
				<div class="wd-upload-photo-maincontent-pp">
					<div class="wd-upload-photo-st1">
						<form id="youlook-report-concern-form" onsubmit="return false;" method="POST" action="<?php echo ZoneRouter::createUrl('/reports/report/concern')?>" >
							<fieldset class="wd-upload-report-concern-form-pp">
								<p class="wd-des"><?php echo Yii::t("Youlook", "");?></p>
								<input class="youlook-object-id-report-concern" name="id" id="ZoneReportConcern_object_id" type="hidden">
								<input class="youlook-object-type-report-concern" name="type" id="ZoneReportConcern_object_type" type="hidden">
								<div class="wd-input">
									<textarea class="youlook-input-textarea" rows="6" cols="65" placeholder="Content" name="message" id="ZoneReportConcern_content"></textarea>
								</div>
								<?php if (false) : ?>
									<div class="wd-input youlook-captcha">
										<?php if(CCaptcha::checkRequirements()): ?>
											<?php
												$this->widget('CCaptcha',array(
														'buttonOptions'	=> array('style' => 'display:block'),
														'captchaAction'	=> ZoneRouter::createUrl('/reports/report/captcha')
													)
												); ?>
											<input class="wd-input youlook-verifyCode" placeholder="Type the text" name="verifyCode" id="ZoneRegisterForm_verifyCode" type="text">
										<?php endif; ?>
									</div>
								<?php endif;?>
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
					<a class="wd-accept-btf floatR youlook-submit-report-concern" href="javascript:void(0)"><?php echo Yii::t("Youlook", "Submit");?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="display:none">
	<a href="#wd-popup-report-concern-content" class="youlook-report-concern-popup wd-popup-report wd-show-popup"></a>
</div>