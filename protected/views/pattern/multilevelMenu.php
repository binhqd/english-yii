<h2>JLBD User Menu (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:50px;">
	<?php
	$arrMenu = array(
		array(
			'label'		=>	'user home',
			'url'		=>	'/myhtmlpattern/index',
			'itemOptions' => array('class' => 'wd-home'),
			'items'		=>	null,
		),
		array(
			'label'		=>	'reviews',
			'url'		=>	'/myhtmlpattern/reviews',			
			'items'		=>	null,
		),
		array(
			
			'label'		=>	'list',
			'url'		=>	'#',
			'items'		=>	null,
		),
		array(
			'label'		=>	'gallery',
			'url'		=>	'#',
			'items'		=>	null,
		),
		array(
			'active'	=> false,
			'label'		=>	'favourite',
			'url'		=>	'/myhtmlpattern/favourite',
			'items'		=>	null,
		),
		array(
			
			'label'		=>	'searches',
			'url'		=>	'#',
			'items'		=>	null,
		),
		array(
			'label'		=>	'friends',
			'url'		=>	'#',
			'items'		=>	array(
				array(
					'label'		=>	'recent activities',
					'url'		=>	'#',
					'items'		=>	null,
				),
				array(
					'label'		=>	'find friend',
					'url'		=>	'#',
					'items'		=>	null,
				),
				array(
					'label'		=>	'friend manager',
					'url'		=>	'/myhtmlpattern/friends',
					'items'		=>	null,
				),
				array(
					'label'		=>	'group',
					'url'		=>	'#',
					'items'		=>	array(
						array(
							'label'		=>	'recent activities',
							'url'		=>	'#',
							'items'		=>	null,
						),
						array(
							'label'		=>	'find friend',
							'url'		=>	'/myhtmlpattern/finds',
							'items'		=>	null,
						),
						array(
							'label'		=>	'friend manager',
							'url'		=>	'#',
							'items'		=>	null,
						),
						array(
							'label'		=>	'group',
							'url'		=>	'#',
							'items'		=>	null,
						),
					)
				),
			)
		),
		array(					
			'label'		=>	'reviewers',
			'url'		=>	'#',
			'items'		=>	null,
		),
		array(
			
			'label'		=>	'websites',
			'url'		=>	'#',
			'items'		=>	null,
		),
	);
	
		$this->widget('widgets.multi-menu.JLBDUserMenu', array(
			'htmlOptions' 	=> array("class" => 'wd-dropdownMenu'),
			'id'=>'wd-top-menu',
			'encodeLabel' => false,	
			'imgHome'	=>	'nav-avatar.jpg',
			'arrMenu' => $arrMenu,
			
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
			$this->widget('widgets.multi-menu.JLBDUserMenu', array(
				'htmlOptions' 	=> array("class" => 'wd-dropdownMenu'),
				'id'=>'wd-top-menu',
				'encodeLabel' => false,	
				'imgHome'	=>	'nav-avatar.jpg',// avatar của người dùng truyền vào tại đây
				'arrMenu' => $arrMenu,
			));	
			</pre>	
	<b>
		Các giá trị trong menu này sẽ được config tại đây
	</b>
			<pre class="brush:c-sharp;">
			$arrMenu = array(
				array(
					'label'		=>	'user home',
					'url'		=>	'/myhtmlpattern/index',
					'itemOptions' => array('class' => 'wd-home'),
					'items'		=>	null,
				),
				array(
					'label'		=>	'reviews',
					'url'		=>	'/myhtmlpattern/reviews',			
					'items'		=>	null,
				),
				array(
					
					'label'		=>	'list',
					'url'		=>	'#',
					'items'		=>	null,
				),
				array(
					'label'		=>	'gallery',
					'url'		=>	'#',
					'items'		=>	null,
				),
				array(
					'active'	=> false,
					'label'		=>	'favourite',
					'url'		=>	'/myhtmlpattern/favourite',
					'items'		=>	null,
				),
				array(
					
					'label'		=>	'searches',
					'url'		=>	'#',
					'items'		=>	null,
				),
				array(
					'label'		=>	'friends',
					'url'		=>	'#',
					'items'		=>	array(
						array(
							'label'		=>	'recent activities',
							'url'		=>	'#',
							'items'		=>	null,
						),
						array(
							'label'		=>	'find friend',
							'url'		=>	'#',
							'items'		=>	null,
						),
						array(
							'label'		=>	'friend manager',
							'url'		=>	'/myhtmlpattern/friends',
							'items'		=>	null,
						),
						array(
							'label'		=>	'group',
							'url'		=>	'#',
							'items'		=>	array(
								array(
									'label'		=>	'recent activities',
									'url'		=>	'#',
									'items'		=>	null,
								),
								array(
									'label'		=>	'find friend',
									'url'		=>	'/myhtmlpattern/finds',
									'items'		=>	null,
								),
								array(
									'label'		=>	'friend manager',
									'url'		=>	'#',
									'items'		=>	null,
								),
								array(
									'label'		=>	'group',
									'url'		=>	'#',
									'items'		=>	null,
								),
							)
						),
					)
				),
				array(					
					'label'		=>	'reviewers',
					'url'		=>	'#',
					'items'		=>	null,
				),
				array(
					
					'label'		=>	'websites',
					'url'		=>	'#',
					'items'		=>	null,
				),
			);			
			</pre>
		</div>		
	</div>	
	<b>
		Vì Widgets này sử dụng CMenu của Yii nên muốn tìm hiểu thêm thì truy cập vào :
		http://www.yiiframework.com/doc/api/1.1/CMenu
	</b>	
</div>
