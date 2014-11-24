<h2>JLBD Button (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:100px;">
<?php
		$this->widget('widgets.button.JLBDButton', array(
			'type'=>0,
			'label'=>'Link text',
			'action'=>'controller/action',
			
		));	
?>

<br><br>
<?php
		$this->widget('widgets.button.JLBDButton', array(
			'type'=>1,
			'label'=>'Login',						
		));	
?>


<br><br>	
<?php
		$this->widget('widgets.button.JLBDButtonSmall', array(
			'label'=>'Favourite',
			'action'=>'controller/action',
			
		));	
?>
	
<br><br>

	
<script type="text/javascript">	
	$("#test").click(function() {
	   alert("Hello, button blue !")
	});					
	$("#test1").click(function() {
	   alert("Hello, button green !")
	});			
</script>
<br><br>


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

		$this->widget('widgets.button.JLBDButton', array(
			'type'=>0,
			'label'=>'Link text',
			'action'=>'controller/action',
			
		));	
			
			
			</pre>	
			<pre class="brush:c-sharp;">

		$this->widget('widgets.button.JLBDButton', array(
			'type'=>1,
			'label'=>'Login',						
		));	

									
			</pre>	


			<pre class="brush:c-sharp;">			
				Đây là trường hợp dành cho nút Green
				
				$this->widget('widgets.button.JLBDButtonSmall', array(
					'label'=>'Favourite',
					'action'=>'controller/action',
					
				));	
				
				Ta gọi tương tự như trên theo từng trường hợp nhé.
				
			</pre>
			
		</div>		
	</div>	
	<b>
		Vì ở đây ta dùng CHtml của Yii nên muốn tham khảo thêm thì truy cập vào :
		http://www.yiiframework.com/doc/api/1.1/CHtml
	</b>		
</div>