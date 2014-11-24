<h2>JLBD Compliment & Flavours   (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:100px;">
<div class="wd-container-tags">
	<?php
			$this->widget('widgets.tags.JLBDTags', array(
				'name' => 'Tags 1',
				'action'=>JLRouter::createUrl('#')
			));	
	?>
</div>
</div>
<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<b>
		../views/[Tên controller]/[Tên file].php
	</b>
	<br>
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
		<b style='color:red'>
			HTML bên  ngoài :
		</b>
		<pre class="brush:c-sharp;">	
			<div class="wd-container-tags">
				//for widgets tags
			</div>
		</pre>
			<pre class="brush:c-sharp;">	
			$this->widget('widgets.tags.JLBDTags', array(
				'name' => 'Tags 1',
				'action'=>JLRouter::createUrl('#')
			));				
			
			</pre>
	
		</div>
	</div>
</div>