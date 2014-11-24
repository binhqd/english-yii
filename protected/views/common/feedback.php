<?php 
/*GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js'
));

GNAssetHelper::setBase('justlook');
GNAssetHelper::cssFile('validationEngine.jquery');
GNAssetHelper::scriptFile('jquery.validationEngine', CClientScript::POS_END);*/
?>
<input type='button' id="feedback-button"></input>

<div id="feedback-container">
	<div class='formContainer'>
		<h2>Send feedback</h2>
		<form action='' method='post' id='frmFeedback'>
			<div class='wd-input'>
				<label>Title:</label>
				<input type='text' name='feedback[name]' id='fieldName' class='validate[required]'/>
			</div>
			<div class='wd-textarea'>
				<label>Content:</label>
				<textarea name='feedback[content]' id='fieldContent' class='validate[required]'></textarea>
			</div>
			<div class='wd-input'>
				<!-- [ <a href='#' class='lnkTakeScreenShot'>Take screenshot</a> ]  -->
				<span class='notice'>You can drag many regions on the screen to notice us</span>
			</div>
			<span>[ <a href='#' target="_blank" class='view-instruction-video'>How to send feedback</a> ]</span>
			<div class='wd-submit wd-bt-big-2'>
				<input type='submit' class='btnFeedback' value='Submit'/>
				<input type='button' class='btnClose' value='Cancel'/>
			</div>
		</form>
	</div>
</div>