	<?php 
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));
	?>
	
<div id="wd-footer-container">
	<div class="wd-center">
		<div class="wd-extra-footer">
			<div class="wd-block">
				<h2>About Justlook</h2>
				<ul>
					<li><a href="<?php echo JLRouter::createUrl('/publicPages/statics/aboutUs');?>">About Us</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/publicPages/statics/contact');?>">Contact Us</a></li>
					<li><a href="#">Feedback</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/publicPages/statics/FAQ');?>">FAQ</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/publicPages/statics/termsOfService');?>">Terms of Service</a></li>
				</ul>
			</div>
			<div class="wd-block">
				<h2>For Business</h2>
				<ul>
					<li><a href="<?php echo JLRouter::createUrl('createBusiness');?>">Add a business</a></li>
					<li><a href="<?php echo JLRouter::createUrl('createBusiness/contribute');?>">Contribute business</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/easyweb');?>">EasyWeb</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/product/introductory_package');?>">Choose a package</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/product/position_listing');?>">Improve seach ranking</a></li>
				</ul>
			</div>
			<div class="wd-block">
				<h2>About Me</h2>
				<ul>
					<li><a href="<?php echo JLRouter::createUrl('/landing');?>">My Welcome page</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/dashboard');?>">My Homepage</a></li>
					<li><a href="<?php echo JLRouter::createUrl('createBusiness');?>">Add your business</a></li>
				</ul>
			</div>
			<div class="wd-block">
				<h2>More</h2>
				<ul>
					<li><a href="<?php echo JLRouter::createUrl('/publicPages/statics/press');?>">Press</a></li>
					<li><a href="<?php echo JLRouter::createUrl('/publicPages/statics/careers');?>">Careers</a></li>
				</ul>
			</div>
			<div class="wd-block wd-new-letters">
				<h2>Weekly Newsletters</h2>
				<form id="newsletters-form" action="<?php echo JLRouter::createAbsoluteUrl('/user/newsLetters/index')?>" method="post">
				<fieldset>
						<div class="wd-input wd-location">
							<input type="text" id="JLUserNewsLetter_email" name="JLUserNewsLetter[email]" value="Your email address" onfocus="if(this.value=='Your email address') this.value='';" onblur="if(this.value=='') this.value='Your email address'">
						</div>
						<div class="wd-submit">
							<input type="submit" value="Subscribe" class="wd-news-letters">
						</div>
				</fieldset>
				</form>
				<a class="wd-link-facebook" href="http://facebook.com/justlook.com.au" target="_blank">
				connect to facebook

				</a>
			</div>
		</div>
		<div class="wd-bottom-footer">
			<p>Copyright © 2012 <a href="/">Justlook.com.au ™</a>. All rights reserved</p>
		</div>
	</div>
</div>
<!-- Piwik --> 

<!-- End Piwik Tracking Code -->