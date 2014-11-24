<h2>JLBD Review Box (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:auto;">
	Hot Water Maintenance
	<?php
		$this->widget('widgets.review.JLBDReviewForm', array(
			'strBizID' => '973C474652C511E1916E00270E356219',
			'intYourRate' => 3,
		));			
	?>
	<hr/>
	Doctor Plumbing
	<?php
		$this->widget('widgets.review.JLBDReviewForm', array(
			'strBizID' => '973C231D52C511E1916E00270E356219',
		));			
	?>

</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<b>
		Mỗi widget cho 1 business. Cần truyền vào ID của business và rating hiện tại của user.
	</b>
	<br>
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
				$this->widget('widgets.review.JLBDReviewForm', array(
					'strBizID' => '973C474652C511E1916E00270E356219',
					'intYourRate' => 3,
				));
				
				$this->widget('widgets.review.JLBDReviewForm', array(
					'strBizID' => '973C231D52C511E1916E00270E356219',
				));	
			</pre>		
		</div>		
	</div>	
	
</div>