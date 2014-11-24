<h2>JLBD Note (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:100px">
	<?php
		$this->widget('widgets.note.JLBDNote', array(			
			'totalComments'=>'2',			
			'totalCompliments' => '3',	
			'callbackComments'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('comment')
                            // code ajax
                            }})						
						",
			'callbackCompliments'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('compliment')
                            // code ajax
                            }})						
						"						
	    ));
		$this->widget('widgets.note.JLBDNoteYour', array(				
			'callbackSeeMore'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('comment')
                            // code ajax
                            }})						
						",
			'callbackEdit'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('edit')
                            // code ajax
                            }})						
						"						
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
			$this->widget('widgets.note.JLBDNote', array(			
				'totalComments'=>'2',// tổng số lượt comments				
				'totalCompliments' => '3',	// tổng số lượt compliments	
				'callbackComments'=>"
						$.ajax({
							type: 'POST',
							url: '".Yii::app()->createUrl('/pattern/index')."',
							success: function(msg){
								alert('comment')
								// code ajax
								}})						
							",
				'callbackCompliments'=>"
						$.ajax({
							type: 'POST',
							url: '".Yii::app()->createUrl('/pattern/index')."',
							success: function(msg){
								alert('compliment')
								// code ajax
								}})						
							"					
			));	
			</pre>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.note.JLBDNoteYour', array(				
			'callbackSeeMore'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('comment')
                            // code ajax
                            }})						
						",
			'callbackEdit'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('edit')
                            // code ajax
                            }})						
						"						
	    ));					
			</pre>	
		</div>		
	</div>	
	<b style='color:red'>
	Ta chỉ đưa vào id để xử lý script <br>
	Còn vấn đề script thì ta tự xử lý , vì không thể lường trước được độ phức tạp của những script khi bắt đầu dự án .
	</b>	
</div>
