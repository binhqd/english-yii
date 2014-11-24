<h2>JLBD Link Extra (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:300px;">
	
	<?php
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a review',
			'className'=>'wd-link-text wd-write-review',			
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('review')
                            // code ajax
                            }})						
						"				
		));	
	?>
	
	<br>
	<br>
	
	<?php
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a compliment',
			'className'=>'wd-link-text wd-write-compliment',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('compliment')
                            //code ajax
                            }})						
						"				
		));	
	?>
		
	<br>
	<br>
	
	<?php
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a comment',
			'className'=>'wd-link-text wd-write-comment',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('comment')
                            //code ajax
                            }})						
						"				
		));	
	?>
	
		<br>
	<br>
	
	<?php
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a message',
			'className'=>'wd-link-text wd-send-message',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('message')
                            //code ajax
                            }})						
						"				
		));	
	?>
	
	<br>
	<br>
	
	<?php
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a recommendation',
			'className'=>'wd-link-text wd-write-recommendation',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('recommendation')
                            //code ajax
                            }})						
						"				
		));	
	?>
	
	<br>
	<br>
	
	<?php
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Bookmark this list',
			'className'=>'wd-link-text wd-bookmark-list',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('Bookmark this list')
                            //code ajax
                            }})						
						"				
		));	
	?>
		
	
	<br>
	<br>
	<?php
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Send email to friends',
			'className'=>'wd-link-text',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('Send email to friends')
                            //code ajax
                            }})						
						"				
		));			
	?>
	<br><br>
	<?php

		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'type'=>'social-networking',
			'position'=>'right',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('Send email to friends1')
                            //code ajax
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
		<span style='color:green'>
		Sau đây là cấu trúc trình bày của các widget trên theo dự án .
		</span>
			<pre class="brush:c-sharp;">			

				<div class="wd-container-link">
					
						//gọi các widgets 
					
					<p class="wd-link-extra wd-link-right">
						//gọi các widgets social network
						
				</div>			
			
			</pre>				
			<span style='color:green'>
			Cách gọi widgets [link extra function]  trừ widgets social network
			</span>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a review',
			'className'=>'wd-link-text wd-write-review',			
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('review')
                            // code ajax
                            }})						
						"				
		));						
			</pre>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a compliment',
			'className'=>'wd-link-text wd-write-compliment',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('compliment')
                            //code ajax
                            }})						
						"				
		));						
			</pre>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a comment',
			'className'=>'wd-link-text wd-write-comment',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('comment')
                            //code ajax
                            }})						
						"				
		));						
			</pre>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a message',
			'className'=>'wd-link-text wd-send-message',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('message')
                            //code ajax
                            }})						
						"				
		));						
			</pre>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Write a recommendation',
			'className'=>'wd-link-text wd-write-recommendation',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('recommendation')
                            //code ajax
                            }})						
						"				
		));						
			</pre>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Bookmark this list',
			'className'=>'wd-link-text wd-bookmark-list',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('Bookmark this list')
                            //code ajax
                            }})						
						"				
		));						
			</pre>
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'name'=>'Send email to friends',
			'className'=>'wd-link-text',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('Send email to friends')
                            //code ajax
                            }})						
						"				
		));						
			</pre>												
			<span style='color:green'>
			Cách gọi widgets social network
			</span>		
			<pre class="brush:c-sharp;">
		$this->widget('widgets.link-extra.JLBDLinkExtra', array(			
			'type'=>'social-networking',
			'position'=>'right',
			'callback'=>"
					$.ajax({
                        type: 'POST',
                        url: '".Yii::app()->createUrl('/pattern/index')."',
                        success: function(msg){
							alert('Send email to friends1')
                            //code ajax
                            }})						
						"						
		));	
			</pre>			
		</div>		
	</div>	
	<b>

	</b>	
</div>
