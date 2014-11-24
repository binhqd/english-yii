<h2>JLBD Tooltip (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:100px;">
	<div style='width:100%;'>
		<div style='width:50%;float:left;'>
			<div class="wd-bt-big" >
				<?php
					$this->widget('widgets.tooltip.JLBDTooltip');	
				?>				
				<?php
					echo CHtml::link('Hover Tooltip Top',array('controller/action'),array('title'=>'Hover Tooltip Top','class'=>'tooltip-top'));
				?>								
			</div>
			<br><br>
			<div class="wd-bt-big" >
				<?php
					echo CHtml::link('Hover Tooltip Right',array('controller/action'),array('title'=>'Hover Tooltip Right','class'=>'tooltip-right'));
				?>				
			</div>			
		</div>
		<div style='width:50%;float:left;'>
			<div class="wd-bt-big" >
				<?php
					echo CHtml::link('Hover Tooltip Left',array('controller/action'),array('title'=>'Hover Tooltip Left','class'=>'tooltip-left'));
				?>				
			</div>
			<br><br>
			<div class="wd-bt-big" >
				<?php
					echo CHtml::link('Hover Tooltip Bottom',array('controller/action'),array('title'=>'Hover Tooltip Bottom','class'=>'tooltip-bottom'));
				?>	
			</div>		
			<br><br>	

		</div>		
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
			<pre class="brush:c-sharp;">
			//Load CSS & JS cho tooltip
			$this->widget('widgets.tooltip.JLBDTooltip');
			
			echo CHtml::link('Hover Tooltip Top',array('controller/action'),array('title'=>'Hover Tooltip Top','class'=>'tooltip-top'));
			
			echo CHtml::link('Hover Tooltip Right',array('controller/action'),array('title'=>'Hover Tooltip Right','class'=>'tooltip-right'));
			
			echo CHtml::link('Hover Tooltip Left',array('controller/action'),array('title'=>'Hover Tooltip Left','class'=>'tooltip-left'));
			
			echo CHtml::link('Hover Tooltip Bottom',array('controller/action'),array('title'=>'Hover Tooltip Bottom','class'=>'tooltip-bottom'));
			
			</pre>	
		</div>		
	</div>	
	<b>
		Chỉ cần gọi widgets này thì chỗ nào cần dùng tooltip thì ta thêm thuộc tính [title][class].
		<br>
		<span style='color:red'>
			Chú ý : class = tooltip-top,bottom,left,right] . title = " Nội dung cần hiển thị "
			<br>
			Chỉ thêm thuộc tính cho thẻ [a] thì mới sử dụng được tooptip
		</span>
	</b>	
</div>
