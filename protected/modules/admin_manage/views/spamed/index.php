<div class="wd-left-content">
    <div class="wd-section">
		<?php
			$this->widget('widgets.registeredMenuLeft.JLBDRegisteredMenuLeft',array(
				'type'	=> 'registered',
				'statistics'	=> $statistics,
			));

		?>
    </div>
</div>
<div class="wd-main-content">
	<?php
		$this->widget('widgets.registeredBackend.JLBDRegisteredBackend',array(
			'type'	=> 'spammed',
			'data'			=> $data,
			//'type'			=> 'email-company',
			'statistics'	=> $statistics,
		));
	?>
</div>
<div class="clear"></div>