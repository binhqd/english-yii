<h2>JLBD Breadcrumb (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:150px;">
	<?php
		$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
			'arrBreadcrums' 	=> array(
				array('label'=>'Lists', 'url'=>  JLRouter::createUrl('#')),
				array('label'=>'My Bookmarked Lists', 'url'=>  JLRouter::createUrl('#'), 'class' => 'wd-finish-link wd-my-bookmarked-lists'),
				array('label'=>"Wendy's Way", 'class' => 'wd-finish-notlink'),
			),
		));	
	?>
	<?php
		$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
			'arrBreadcrums' 	=> array(
				array('label'=>'Lists', 'url'=>  JLRouter::createUrl('#')),
				array('label'=>'My Bookmarked Lists', 'url'=>  JLRouter::createUrl('#'), 'class' => 'wd-finish-link wd-my-published-lists'),
				array('label'=>"Wendy's Way", 'class' => 'wd-finish-notlink'),
			),
		));	
	?>
		<?php
		$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
			'arrBreadcrums' 	=> array(
				array('label'=>'Lists', 'url'=>  JLRouter::createUrl('#')),
				array('label'=>'My Bookmarked Lists', 'url'=>  JLRouter::createUrl('#'), 'class' => 'wd-finish-link wd-my-private-lists'),
				array('label'=>"Wendy's Way", 'class' => 'wd-finish-notlink'),
			),
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
			$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
				'arrBreadcrums' 	=> array(
					array('label'=>'Lists', 'url'=>  JLRouter::createUrl('#')),
					array('label'=>'My Bookmarked Lists', 'url'=>  JLRouter::createUrl('#'), 'class' => 'wd-finish-link wd-my-bookmarked-lists'),
					array('label'=>"Wendy's Way", 'class' => 'wd-finish-notlink'),
				),
			));	
			</pre>	
			<pre class="brush:c-sharp;">			
			$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
				'arrBreadcrums' 	=> array(
					array('label'=>'Lists', 'url'=>  JLRouter::createUrl('#')),
					array('label'=>'My Bookmarked Lists', 'url'=>  JLRouter::createUrl('#'), 'class' => 'wd-finish-link wd-my-published-lists'),
					array('label'=>"Wendy's Way", 'class' => 'wd-finish-notlink'),
				),
			));	
			</pre>	
			<pre class="brush:c-sharp;">			
		$this->widget('widgets.breadcrumb.JLBDBreadcrumb', array(
			'arrBreadcrums' 	=> array(
				array('label'=>'Lists', 'url'=>  JLRouter::createUrl('#')),
				array('label'=>'My Bookmarked Lists', 'url'=>  JLRouter::createUrl('#'), 'class' => 'wd-finish-link wd-my-private-lists'),
				array('label'=>"Wendy's Way", 'class' => 'wd-finish-notlink'),
			),
		));	
			</pre>	
		</div>		
	</div>	
	<b>
		Với widget này ta sẽ coi như mỗi Items của Breadcrumb là 1 mảng
		<br>
		- Với mỗi mảng thì ta có các thuộc tính [label][class][url] 
		<br>
		<span style='color:red'>
			Chú ý : Widgets chỉ áp dụng cho JLBD
		</span>
	</b>	
</div>
