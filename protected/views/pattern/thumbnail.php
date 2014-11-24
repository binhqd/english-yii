<h2>JLBD List Thumbnail (v3)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:250px;">
<?php
	$this->widget('widgets.list-thumbnail.JLBDThumbnail',array(
		'type' =>rand(0,1)								
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

			<pre class="brush: c-sharp;">			

			$this->widget('widgets.list-thumbnail.JLBDThumbnail',array(
				'type' =>1,	// [0] : mình xem mình  , [1] :  mình xem họ
			));				
			
			</pre>	
		</div>		
	</div>	
	
</div>
