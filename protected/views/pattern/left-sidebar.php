<h2>JLBD Avatar (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:300px;">
<?php

		$this->widget('widgets.left-sidebar.JLBDAvatar', array(
			'avatar'=>array(
					'action'=>JLRouter::createUrl('#'),
					'img'=>'avatar-big-01.jpg'
					),
			'actionViewAll'=>JLRouter::createUrl('/pattern/photo'),
			'actionAddPhoto'=>JLRouter::createUrl('/pattern/photo'),
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
			$this->widget('widgets.left-sidebar.JLBDAvatar', array(
				'avatar'=>array(
						'action'=>JLRouter::createUrl('#'),//action cho url của avatar
						'img'=>'avatar-big-01.jpg'//ảnh avatar
						),
				'actionViewAll'=>JLRouter::createUrl('/pattern/photo'),//action cho url view all
				'actionAddPhoto'=>JLRouter::createUrl('/pattern/photo'),//action cho url add photos
			));				
			</pre>
		</div>
	</div>
</div>
<h2>JLBD Infor (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:80px;">
<?php

		$this->widget('widgets.left-sidebar.JLBDInfor', array(
			'type'=>1,			
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
				$this->widget('widgets.left-sidebar.JLBDInfor', array(
					'type'=>1,	// [0] : mình xem mình  , [1] :  mình xem họ
		
				));			
			</pre>
		</div>
	</div>
</div>
<h2>JLBD Compliment & Flavours   (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:200px;">
<?php


		$this->widget('widgets.left-sidebar.JLBDCompliment', array(
			'label' => 'Compliments',
			'value'=>array(
					'group'=>10,
					'photo'=>10,
					'review'=>10,
					'list'=>10,					
					)		
		));	
?>
<br><br>
<br><br>
<?php
		$this->widget('widgets.left-sidebar.JLBDFlavours', array(
			'label' => 'With flavours',
			'value'=>array(
					'flavours-0'=>10,
					'flavours-1'=>10,
					'flavours-2'=>10,
					'flavours-3'=>10,					
					)		
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
				$this->widget('widgets.left-sidebar.JLBDCompliment', array(
					'label' => 'Compliments',
					'value'=>array(
							'group'=>10,
							'photo'=>10,
							'review'=>10,
							'list'=>10,					
							)			
				));				
			
			</pre>
			<pre class="brush:c-sharp;">	
			
			$this->widget('widgets.left-sidebar.JLBDFlavours', array(
				'label' => 'With flavours',
				'value'=>array(
						'flavours-0'=>10,
						'flavours-1'=>10,
						'flavours-2'=>10,
						'flavours-3'=>10,					
						)		
			));				
			
			</pre>			
		</div>
	</div>
</div>
<h2>JLBD Stat (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:400px;">
	<?php
		$this->widget('widgets.left-sidebar.JLBDStat', array(
			'reviews' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),
			'first' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			'lists' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			
			'friends' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),						
			'groups' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),						
			'favourites' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),						
			'compliments' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			'follows' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			'followers' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
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

		$this->widget('widgets.left-sidebar.JLBDStat', array(
			'reviews' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),
			'first' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			'lists' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			
			'friends' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),						
			'groups' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),						
			'favourites' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),						
			'compliments' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			'follows' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			'followers' 	=> array(
				'value'=>'10',
				'url'=>  JLRouter::createUrl('#')
				),			
			)			
		);				

			</pre>	
		</div>		
	</div>	

</div>
