<ul class="jlbd-horizoltal-nemu">
	<li <?php echo (Yii::app()->controller->action->id) == "aboutUs" ? "class='jl-activite'" : "class=''";?>>
		<a href="<?php echo JLRouter::createUrl('/publicPages/statics/aboutUs')?>">About JustLook</a>
		<div class="arrow"></div>
	</li>
	<li <?php echo (Yii::app()->controller->action->id) == "press" ? "class='jl-activite'" : "class=''";?>>
		<a href="<?php echo JLRouter::createUrl('/publicPages/statics/press')?>">Press</a>
		<div class="arrow"></div>
	</li>
	<li <?php echo (Yii::app()->controller->action->id) == "careers" ? "class='jl-activite'" : "class=''";?>>
		<a href="<?php echo JLRouter::createUrl('/publicPages/statics/careers')?>">Careers</a>
		<div class="arrow"></div>
	</li>
	<li <?php echo (Yii::app()->controller->action->id) == "contact" ? "class='jl-activite'" : "class=''";?>>
		<a href="<?php echo JLRouter::createUrl('/publicPages/statics/contact')?>">Contact us</a>
		<div class="arrow"></div>
	</li>
	<li <?php echo (Yii::app()->controller->action->id) == "FAQ" ? "class='jl-activite'" : "class=''";?>>
		<a href="<?php echo JLRouter::createUrl('/publicPages/statics/FAQ')?>">F.A.Q.</a>
		<div class="arrow"></div>
	</li>
	<li <?php echo (Yii::app()->controller->action->id) == "termsOfService" ? "class='jl-activite'" : "class=''";?>>
		<a href="<?php echo JLRouter::createUrl('/publicPages/statics/termsOfService')?>">Terms of Service</a>
		<div class="arrow"></div>
	</li>
</ul>