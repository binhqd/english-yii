<h2>JLBD Title (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:400px;">
<?php
		$this->widget('widgets.title.JLBDTitleMain', array(			
			'label'=>'Reviews',						
		));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleMain', array(	
			'type'=>1,
			'label'=>'Reviews',
			'titleRight'=>array('private'=>10,'published'=>5,'bookmarked'=>8)
		));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleViewAll', array(			
			'label'=>'My Private Lists',
			'borderBottom'=>true,
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
		));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleViewAll', array(			
			'label'=>'My Private Lists',
			'borderBottom'=>true,
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'option'=>array('private'=>10,'published'=>5,'bookmarked'=>8)
		));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleViewAll', array(			
			'label'=>'My Private Lists',
			'borderBottom'=>false,
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
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
		$this->widget('widgets.title.JLBDTitleMain', array(			
			'label'=>'Reviews',						
		));	
				
			</pre>
			<pre class="brush:c-sharp;">			
		$this->widget('widgets.title.JLBDTitleMain', array(	
			'type'=>1,
			'label'=>'Reviews',
			'titleRight'=>array('private'=>10,'published'=>5,'bookmarked'=>8)
		));	
				
			</pre>				
			<pre class="brush:c-sharp;">			

		$this->widget('widgets.title.JLBDTitleViewAll', array(			
			'label'=>'My Private Lists',
			'borderBottom'=>true,
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'option'=>array('private'=>10,'published'=>5,'bookmarked'=>8)
		));					
			</pre>				
			<pre class="brush:c-sharp;">			
		$this->widget('widgets.title.JLBDTitleViewAll', array(			
			'label'=>'My Private Lists',
			'borderBottom'=>false,
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
		));	
				
			</pre>	
			
		</div>		
	</div>	
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:300px;">
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'Published Lists',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,	
			'button'=>array('label'=>'Demo','action'=>JLRouter::createUrl('controller/action/param1'))
		));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'My Bookmarked Lists',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-published'
			));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'Active Reviews',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-bookmarked'
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
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'Published Lists',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'button'=>array('label'=>'Demo','action'=>JLRouter::createUrl('controller/action/param1'))
		));	
			</pre>
			<pre class="brush:c-sharp;">				
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'My Bookmarked Lists',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-published'
			));	
			</pre>
			<pre class="brush:c-sharp;">				
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'Active Reviews',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-bookmarked'
		));	
			</pre>
		</div>
	</div>
</div>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:200px;">
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleViewDel', array(			
			'label'=>'Published Lists',
			'actionDel'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,				
		));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleViewDel', array(			
			'label'=>'My Bookmarked Lists',
			'actionDel'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-detail-published'
			));	
?>
<br>
<br>
<?php
		$this->widget('widgets.title.JLBDTitleViewDel', array(			
			'label'=>'My Bookmarked Lists',
			'actionDel'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-detail-bookmarked'
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
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'Published Lists',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,			
		));	
			</pre>
			<pre class="brush:c-sharp;">				
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'My Bookmarked Lists',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-published'
			));	
			</pre>
			<pre class="brush:c-sharp;">				
		$this->widget('widgets.title.JLBDTitleTotalViewAll', array(			
			'label'=>'Active Reviews',
			'actionViewAll'=>JLRouter::createUrl('controller/action/param1'),
			'total'=>10,
			'className'=>'wd-title-bookmarked'
		));	
			</pre>
		</div>
	</div>
</div>
</div>