<div id="wd-header">
	<div class="wd-top-header">
		<div class="wd-center">
			
			<?php $this->widget('widgets.common.MenuCategory',array(
				'page'=>'homepage'
			));?>
			<ul class="wd-topmenu-right">
				<li><a href="#">What is YouLook?</a></li>
				<li><a href="#wd-signup-popup" class="wd-open-popup">Join Today</a></li>
				<li><a href="#wd-signin-popup" class="wd-open-popup">Sign In</a></li>
				<!--<li><a href="<?php echo Yii::app()->createUrl('/users/login');?>" class="wd-open-popup">Sign In</a></li>-->
			</ul>
		</div>
	</div>
</div>