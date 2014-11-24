<h2>JLBD Popup (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:50px;">
<?php
	$this->widget('widgets.popup.JLBDPopup', array(
		'arrPopup' 	=> array(			
				'nameButton'=>'Popup Html',	
				'id' =>'jlbd-0',
		),
		'data' 	=> array(			
				'title'=>'Title popup',	
				'content' =>'Content popup',
		),		
    ));	
?><br /><br />

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
			$this->widget('widgets.popup.JLBDPopup', array(
				'arrPopup' 	=> array(			
						'nameButton'=>'Popup Html',	
						'id' 		=>'jlbd-0',
				),
				'data' 	=> array(			
						'title'		=>'Title popup',	
						'content'	=>'Content popup',
				),		
			));		

			</pre>	
		</div>		
	</div>	
	<b>
		Nút [Popup Html] với chức năng như sau : <br>
		- Nếu như dữ liệu đã có sẵn trong popup và việc bung popup chỉ là hiển thị thì ta dùng đoạn code trên.		
	</b>
	<div id="wd-demo" style="height:50px;">

<?php
	$this->widget('widgets.popup.JLBDPopup', array(
		'arrPopup' 	=> array(			
				'nameButton'=>'Popup Iframe',
				'href' => 'http://google.com',
				'type' => 'iframe',
				'id' =>'jlbd-1'													
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
			$this->widget('widgets.popup.JLBDPopup', array(
				'arrPopup' 	=> array(			
						'nameButton'=>'Popup Iframe',
						'href' 		=>'http://google.com',
						'type'		=>'iframe', 
						'id'		=>'jlbd-1'													
				),
			));	
			
	
			</pre>			
		</div>
	</div>
	<b>
		Nút [Popup Iframe] với chức năng như sau : <br>
		- Nếu dữ liệu đưa vào là quá nhiều thì ta sẽ dùng tính năng iframe của fancybox 
		<br>
		<span style='color:red;'> Chú ý :  ta chú ý thuộc tính type của arrPopup . Nếu dùng iframe thì ta khai báo , còn không thì bỏ qua .</span>
	</b>	
</div>
</div>
