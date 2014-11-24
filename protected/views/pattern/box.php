<h2>JLBD Comment Box (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:150px;">
	<?php
		$this->widget('widgets.comment-box.JLBDCommentBox', array(
			'strToken' => 'review_4f3dd404-2008-4ed4-9dee-09bccbdd56cb',
		));			
	?>

</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<b>
		../views/[Tên controller]/[Tên file].php
	</b>
	<br>
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
				$this->widget('widgets.comment-box.JLBDCommentBox', array(
					'strToken' => 'review_4f3dd404-2008-4ed4-9dee-09bccbdd56cb',
				));	
			</pre>		
		</div>		
	</div>	
	
</div>
<h2>JLBD Comment Items (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:250px;">
	<?php
		$this->widget('widgets.comment-box.JLBDCommentItems');			
	?>
	

</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<b>
		../views/[Tên controller]/[Tên file].php
	</b>
	<br>
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
			$this->widget('widgets.comment-box.JLBDCommentItems');	
			</pre>		
		</div>		
	</div>	
	
</div>
