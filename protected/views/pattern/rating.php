<h2>JLBD Rating (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:150px;">
	<div class="wd-rating">
	
	<?php	
		$this->widget('widgets.rating.JLBDRatingInteractive',
			array(
				'name'=>'star1',
				'starCount'=>5,	
				'maxRating'=>10,
				'htmlOptions'	=>	array('class'=>'star-yellow {split:2}'),
				'callback'=>'
					function(){				
							$.ajax({
								type: "POST",
								url: "'.Yii::app()->createUrl('/controller/action').'",
								success: function(msg){
									//code ajax
									}}
									)
								}'			
				)	
		);
	?>
	</div>
	<br>
	<br>
	<br>
	<div>
		<div class="wd-rating wd-rating-green">
		<?php	
			$this->widget('widgets.rating.JLBDRatingInteractive',
				array(
					'name'=>'star2',
					'starCount'=>5,	
					'maxRating'=>5,
					'htmlOptions'	=>	array('class'=>'star'),						
					'callback'=>'
						function(){				
								$.ajax({
									type: "POST",
									url: "'.Yii::app()->createUrl('/controller/action').'",
									success: function(msg){
										//code ajax
										}}
										)
									}'			
					)	
			);
		?>
		</div>	
	</div>
	<br>
	<br>
	<br>
	<div>
		<div class="wd-rating wd-rating-blue">
		<?php	
			$this->widget('widgets.rating.JLBDRatingInteractive',
				array(
					'name'=>'star3',
					'starCount'=>5,	
					'maxRating'=>5,
					'htmlOptions'	=>	array('class'=>'star'),						
					'callback'=>'
						function(){				
								$.ajax({
									type: "POST",
									url: "'.Yii::app()->createUrl('/controller/action').'",
									success: function(msg){
										//code ajax
										}}
										)
									}'			
					)	
			);
		?>		
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
			
			Yellow Star
			<div class="wd-rating">
				//call widgets
			</div>
			
			
			$this->widget('widgets.rating.JLBDRatingInteractive',
				array(
					'name'=>'star1',
					'starCount'=>5,	
					'maxRating'=>10,
					'htmlOptions'	=>	array('class'=>'star-yellow {split:2}'),						
					'callback'=>'
						function(){				
								$.ajax({
									type: "POST",
									url: "'.Yii::app()->createUrl('/controller/action').'",
									success: function(msg){
										//code ajax
										}}
										)
									}'			
					)	
			);
			
			</pre>	
			<pre class="brush:c-sharp;">

			Green Star
			
			<div class="wd-rating wd-rating-green">
				//call widgets
			</div>
			
			
			$this->widget('widgets.rating.JLBDRatingInteractive',
				array(
					'name'=>'star2',
					'starCount'=>5,	
					'maxRating'=>5,
					'htmlOptions'	=>	array('class'=>'star'),						
					'callback'=>'
						function(){				
								$.ajax({
									type: "POST",
									url: "'.Yii::app()->createUrl('/controller/action').'",
									success: function(msg){
										//code ajax
										}}
										)
									}'			
					)	
			);
			</pre>
			<pre class="brush:c-sharp;">	

			Blue Star
			
			<div class="wd-rating wd-rating-blue">
				//call widgets
			</div>
			
			
			$this->widget('widgets.rating.JLBDRatingInteractive',
				array(
					'name'=>'star3',
					'starCount'=>5,	
					'maxRating'=>5,
					'htmlOptions'	=>	array('class'=>'star'),						
					'callback'=>'
						function(){				
								$.ajax({
									type: "POST",
									url: "'.Yii::app()->createUrl('/controller/action').'",
									success: function(msg){
										//code ajax
										}}
										)
									}'			
					)	
			);
			</pre>
		</div>		
	</div>	
	<b>
		Vì Widgets này kế thừa từ lớp CStarRating của Yii nên muốn tìm hiểu thêm thì truy cập vào :
		http://www.yiiframework.com/doc/api/1.1/CStarRating
		<br>
		<span style='color:red'>
			Chú ý : thuộc tính callback là dùng để gọi ajax , các coder linh động chỗ này nhé.
		</span>
	</b>	
</div>
<h2>Rating Static (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:150px;">
	<div class="wd-rating">
	<?php	
		$this->widget('widgets.rating.JLBDRatingStatic',
			array('value'=>0.74)
		);
		
	?>
	</div>
	<br>
	<br>
	<br>
	<div>
	<div class="wd-rating wd-rating-green">
	<?php	
		$this->widget('widgets.rating.JLBDRatingStatic',
			array('value'=>1.75)
		);
		
	?>
	<div>		
	</div>
	<br>
	<br>
	<br>
	<div>
	<div class="wd-rating wd-rating-blue">
	<?php	
		$this->widget('widgets.rating.JLBDRatingStatic',
			array('value'=>'1')
		);
		
	?>			
	</div>
	<div>	

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
				<div class="wd-rating">
					
						//call widgets

				</div>
				
				<div class="wd-rating wd-rating-green" >
					
						//call widgets
					
				</div>
				
				<div class="wd-rating wd-rating-blue" >
					
						//call widgets
					
				</div>	

				//demo 1 trường hợp 
				$this->widget('widgets.rating.JLBDRatingStatic',
					array('value'=>'05')
				);			
			</pre>
		</div>
	</div>
</div>	
<h2>Rating Helpful (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:50px;">
<?php
	$this->widget('widgets.help-unhelp.JLBDHelpful',	
		array(
				'label'=>'People thought this was',
				'value'=>array(
					'helpful'=>5,
					'unhelpful'=>4,					
				),				
			)
	);	
	
	$this->widget('widgets.help-unhelp.JLBDHelpful',	
		array(
				'type'=>1,
				'id'=>10,
				'label'=>'Was this review',
				'value'=>array(
					'helpful'=>5,
					'unhelpful'=>4,					
				),
				'actionHelp'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",
				'actionUnHelp'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",				
			)
	);		
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
			<b style='color:green;'>
			Trường hợp chỉ hiển thị text
			</b>
			<pre class="brush:c-sharp;">
			$this->widget('widgets.help-unhelp.JLBDHelpful',	
				array(
						'label'=>'People thought this was',
						'value'=>array(
							'helpful'=>5,
							'unhelpful'=>4,					
						),				
					)
			);	
			</pre>
			<b style='color:green;'>
			Trường hợp  hiển thị button
			</b>			
			<pre class="brush:c-sharp;">
			$this->widget('widgets.help-unhelp.JLBDHelpful',	
				array(
						'type'=>1,
						'id'=>10,// id này sẽ lấy từ CSDL , sẽ là user_id
						'label'=>'Was this review',
						'value'=>array(
							'helpful'=>5,
							'unhelpful'=>4,					
						),
						'actionHelp'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",
						'actionUnHelp'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",				
					)
			);				
			</pre>
		</div>
	</div>
</div>	


<h2>Rating Status  (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:50px;">
<?php
	$this->widget('widgets.ok-good-fantastic.JLBDOkGoodFantastic',	
		array(
				'id'=>'10',
				'label'=>'People thought this was',
				'actionOk'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",
				'actionGood'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",
				'actionFantastic'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'", 
				'disable'=>array(
					'ok'=>false,
					'good'=>true,
					'fantastic'=>true,
				),				
			)
	);	
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
			$this->widget('widgets.ok-good-fantastic.JLBDOkGoodFantastic',	
				array(
						'id'=>'10',// id này sẽ lấy từ CSDL , sẽ là user_id
						'label'=>'People thought this was',
						'actionOk'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",
						'actionGood'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'",
						'actionFantastic'=>"'".Yii::app()->createUrl('/pattern/TestAjax')."'", 
						'disable'=>array(
							'ok'=>false,// nếu mỗi user chỉ được click 1 lần thì ta kiểm tra rồi truyền [true/false] tại đây
							'good'=>true,
							'fantastic'=>true,
						),				
					)
			);

			</pre>
		</div>
	</div>
</div>	
