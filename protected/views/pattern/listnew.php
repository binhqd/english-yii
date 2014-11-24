<h2>JLBD List New (v1)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:750px;">
<?php
	$models = array(
		'0' => array(
			'User' => array(
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'name' => 'demo1',
				'img' => 'user-thumb-02.jpg',
				'location' => 'Da Nang Viet Nam 1',
			),
			'Compliment' => array(				
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'user_id' => 'Ns+èIÈ£:ÀËÝVË',
				'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit',				
				'date'=> '19-09-2011',
			),
			'Review' => array(				
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'user_id' => 'Ns+èIÈ£:ÀËÝVË',
				'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
				'date'=> '19-09-2011',
			)				
		),
		'1' => array(
			'User' => array(
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'name' => 'demo2',
				'img' => 'user-thumb-09.jpg',
				'location' => 'Da Nang Viet Nam 2',
			),
			'Review' => array(				
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'user_id' => 'Ns+èIÈ£:ÀËÝVË',
				'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
				'date'=> '19-09-2011',
			)			
		),	
		'2' => array(
			'User' => array(
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'name' => 'demo3',
				'img' => 'user-thumb-08.jpg',
				'location' => 'Da Nang Viet Nam 3',
			),
			'List' => array(				
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'user_id' => 'Ns+èIÈ£:ÀËÝVË',
				'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 3',				
				'date'=> '20-09-2011',
			),
			'Review' => array(				
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'user_id' => 'Ns+èIÈ£:ÀËÝVË',
				'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
				'date'=> '19-09-2011',
			)				
		),
		'3' => array(
			'User' => array(
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'name' => 'demo4',
				'img' => 'user-thumb-07.jpg',
				'location' => 'Da Nang Viet Nam 4',
			),
			'Group' => array(				
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'user_id' => 'Ns+èIÈ£:ÀËÝVË',
				'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 4',
				'date'=> '20-09-2011',				
			),
			'Review' => array(				
				'id' => 'Ns+èIÈ£:ÀËÝVË',
				'user_id' => 'Ns+èIÈ£:ÀËÝVË',
				'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
				'date'=> '19-09-2011',
			)				
		)			
	);
?>
<div class="wd-list-news">
	<ul>
	<?php	
	if(isset($models)){	
		foreach ($models as $item){
	
		$this->widget('widgets.list-new.JLBDListNews',
						array(														
							'propertiesOption' 	=> array(
								array(
									'id'=>$item['User']['id'], 
									'name'=>$item['User']['name'], 
									'nameImg'=> $item['User']['img'],
									'location'=> $item['User']['location'],
									'target'=>  JLRouter::createUrl('controller/action/param1'),
									'type' => 0,
									'classNameImg' => 'wd-thumb',
									'classNameTitle' => 'wd-title',									
								),
							),	
						)
					);
		}//end for
		
		//link More .
		echo CHtml::openTag('li',array('class'=>'wd-view-more'));
			echo CHtml::link('More','#');
		echo CHtml::closeTag('li');
	}else{
		echo " <span style='color:red;'>\$models</span> is empty ";
	}
	?>	
	</ul>
</div>
<br>
<br>
<div class="wd-list-news">
	<ul>
	<?php	
	if(isset($models)){	
		foreach ($models as $item){
		
		$css	=	"";
		$title	=	"";
		if(isset($item['Compliment'])){
			$css	=	"wd-news-blue";
			$title	=	$item['Compliment']['title'];
		}
		if(isset($item['Group'])){
			$css 	=	"wd-news-green";
			$title	=	$item['Group']['title'];
		}
		if(isset($item['Review'])){
			$css 	=	"wd-news-pink";
			$title	=	$item['Review']['title'];
		}
		if(isset($item['List'])){
			$css	=	"wd-news-orange";
			$title	=	$item['List']['title'];
		}
		
		$this->widget('widgets.list-new.JLBDListNews',
						array(										
							'propertiesOption' 	=> array(
								array(		
									'id'=>$item['User']['id'],
									'nameImg'=> $item['User']['img'], 
									'title'=> $title, 
									'target'=>  JLRouter::createUrl('controller/action/param1'),
									'type' => 1,
									'classNameImg' => 'wd-thumb',
									'classNameBorder' => $css,
								),
							),
						)
					);
		}//end for
		
		//link More .
		echo CHtml::openTag('li',array('class'=>'wd-view-more'));
			echo CHtml::link('More','#');
		echo CHtml::closeTag('li');		
	}else{
		echo " <span style='color:red;'>\$models</span> is empty ";
	}
	?>
	
	</ul>
</div>
<br>
<br>
<div class="wd-list-news">
	<ul>
	<?php	
	if(isset($models)){	
		foreach ($models as $item){

		$date	=	"";
		if(isset($item['Review'])){			
			if(isset($item['Review']['date']))		$date	=	$item['Review']['date'];
		}		
		$this->widget('widgets.list-new.JLBDListNews',
						array(										
							'propertiesOption' 	=> array(
								array(
									'id'=>$item['User']['id'],
									'name'=>$item['User']['name'],
									'nameImg'=> $item['User']['img'],
									'date'=>$date,
									'target'=>  JLRouter::createUrl('controller/action/param1'),
									'targetReview'=>  JLRouter::createUrl('controller/action/param2'),
									'type' => 2,
									'classNameImg' => 'wd-thumb',//CSS của ảnh									
								),
							),	
						)
					);
		}//end for
		
		//link More .
		echo CHtml::openTag('li',array('class'=>'wd-view-more'));
			echo CHtml::link('More','#');
		echo CHtml::closeTag('li');		
	}else{
		echo " <span style='color:red;'>\$models</span> is null ";
	}	
	?>	
	</ul>
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
			<span style="color:green;">
				Giả sử ta có 1 mảng gồm các phần tử sau (mảng này có thể join nhiều bảng or chỉ 1 bảng tùy trường hợp mà query <span style="color:red;">'mảng chi mang tính chất demo'</span> ) :				
			</span>
			<pre class="brush:c-sharp;">

			$models = array(
				'0' => array(
					'User' => array(
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'name' => 'demo1',
						'img' => 'user-thumb-02.jpg',
						'location' => 'Da Nang Viet Nam 1',
					),
					'Compliment' => array(				
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'user_id' => 'Ns+èIÈ£:ÀËÝVË',
						'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit',				
						'date'=> '19-09-2011',
					),
					'Review' => array(				
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'user_id' => 'Ns+èIÈ£:ÀËÝVË',
						'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
						'date'=> '19-09-2011',
					)				
				),
				'1' => array(
					'User' => array(
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'name' => 'demo2',
						'img' => 'user-thumb-09.jpg',
						'location' => 'Da Nang Viet Nam 2',
					),
					'Review' => array(				
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'user_id' => 'Ns+èIÈ£:ÀËÝVË',
						'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
						'date'=> '19-09-2011',
					)			
				),	
				'2' => array(
					'User' => array(
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'name' => 'demo3',
						'img' => 'user-thumb-08.jpg',
						'location' => 'Da Nang Viet Nam 3',
					),
					'List' => array(				
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'user_id' => 'Ns+èIÈ£:ÀËÝVË',
						'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 3',				
						'date'=> '20-09-2011',
					),
					'Review' => array(				
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'user_id' => 'Ns+èIÈ£:ÀËÝVË',
						'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
						'date'=> '19-09-2011',
					)				
				),
				'3' => array(
					'User' => array(
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'name' => 'demo4',
						'img' => 'user-thumb-07.jpg',
						'location' => 'Da Nang Viet Nam 4',
					),
					'Group' => array(				
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'user_id' => 'Ns+èIÈ£:ÀËÝVË',
						'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 4',
						'date'=> '20-09-2011',				
					),
					'Review' => array(				
						'id' => 'Ns+èIÈ£:ÀËÝVË',
						'user_id' => 'Ns+èIÈ£:ÀËÝVË',
						'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit 2',				
						'date'=> '19-09-2011',
					)				
				)			
			);
			
			</pre>
			
			<span style="color:green;">
			Lấy từ bản HTML ta sẽ được mã sau :
			</span>
			<pre class="brush:c-sharp;">
			<div class="wd-list-news">
				<ul>	
					//gọi widgets
					...
				</ul>
			</div>			
			</pre>
			
			<span style="color:green;">
			Với JustLook hiện tại có 3 cách hiển thị list news , Ở đây ta demo theo thứ tự của bản HTML :
			</span>			
			
			<pre class="brush:c-sharp;">
			
			if(isset($models)){	
				foreach ($models as $item){
			
				$this->widget('widgets.list-new.JLBDListNews',
								array(														
									'propertiesOption' 	=> array(
										array(
											'id'=>$item['User']['id'], // giá trị này ta lấy theo mảng
											'name'=>$item['User']['name'], // giá trị này ta lấy theo mảng
											'nameImg'=> $item['User']['img'],// giá trị này ta lấy theo mảng
											'location'=> $item['User']['location'],// giá trị này ta lấy theo mảng
											'target'=>  JLRouter::createUrl('controller/action/param1'),// Đích đến khi click vào ảnh và name.
											'type' => 0,// loại hiển thị thứ 0 được lấy theo thứ tự của HTML
											'classNameImg' => 'wd-thumb', // css bọc ngoài ảnh của bản thiết kế
											'classNameTitle' => 'wd-title',// css cho tiêu đề của bản thiết kế
										),
									),	
								)
							);
				}//end for
				
				//link More .
				echo CHtml::openTag('li',array('class'=>'wd-view-more'));
					echo CHtml::link('More','#');
				echo CHtml::closeTag('li');
				
			}else{
			
				echo " <span style='color:red;'>\$models</span> is empty ";
				
			}
			
			</pre>	
			<span style="color:red;">
			Note : Trường hợp bên dưới vì type=1 nên theo HTML nó sẽ có các viền của ảnh <br>
			Chõ này các bạn kỹ thuật khi code chú ý sẽ truyền vào 1 biến số .
			</pre>
			
			<pre class="brush:c-sharp;">			

			if(isset($models)){	
				foreach ($models as $item){
				
				/**
				Start
					Đoạn mã này mang tính chất minh họa .
					Khi vào dự án sẽ tùy biến theo mảng của nó					
				**/
				
				$css	=	"";
				$title	=	"";
				if(isset($item['Compliment'])){
					$css	=	"wd-news-blue";
					$title	=	$item['Compliment']['title'];
				}
				if(isset($item['Group'])){
					$css 	=	"wd-news-green";
					$title	=	$item['Group']['title'];
				}
				if(isset($item['Review'])){
					$css 	=	"wd-news-pink";
					$title	=	$item['Review']['title'];
				}
				if(isset($item['List'])){
					$css	=	"wd-news-orange";
					$title	=	$item['List']['title'];
				}
				/**
				End
				**/
				
				$this->widget('widgets.list-new.JLBDListNews',
								array(										
									'propertiesOption' 	=> array(
										array(		
											'id'=>$item['User']['id'],// giá trị này ta lấy theo mảng
											'nameImg'=> $item['User']['img'], // giá trị này ta lấy theo mảng
											'title'=> $title, // giá trị này ta lấy theo mảng ( giá trị này tùy biến với đoạn code ở trên .)
											'target'=>  JLRouter::createUrl('controller/action/param1),// Đích đến khi click vào ảnh.
											'type' => 1,
											'classNameImg' => 'wd-thumb',
											'classNameBorder' => $css,// giá trị này tùy biến với đoạn code ở trên .
										),
									),
								)
							);
				}//end for
				
				//link More .
				echo CHtml::openTag('li',array('class'=>'wd-view-more'));
					echo CHtml::link('More','#');
				echo CHtml::closeTag('li');		
			}else{
				echo " <span style='color:red;'>\$models</span> is empty ";
			}
			
			</pre>	
			<pre class="brush:c-sharp;">			

			if(isset($models)){	
				foreach ($models as $item){

				/**
				Start
					Đoạn mã này mang tính chất minh họa .
					Khi vào dự án sẽ tùy biến theo mảng của nó					
				**/				
				$date	=	"";
				if(isset($item['Review'])){			
					if(isset($item['Review']['date']))		$date	=	$item['Review']['date'];
				}	
				/**
				End
				**/
				
				$this->widget('widgets.list-new.JLBDListNews',
								array(										
									'propertiesOption' 	=> array(
										array(
											'id'=>$item['User']['id'],
											'name'=>$item['User']['name'],
											'nameImg'=> $item['User']['img'],
											'date'=>$date,
											'target'=>  JLRouter::createUrl('controller/action/param1'),// Đích đến khi click vào ảnh.
											'targetReview'=>  JLRouter::createUrl('controller/action/param2'),// Đích đến khi click vào Review.
											'type' => 2,
											'classNameImg' => 'wd-thumb',//CSS của ảnh									
										),
									),	
								)
							);
				}//end for
				
				//link More .
				echo CHtml::openTag('li',array('class'=>'wd-view-more'));
					echo CHtml::link('More','#');
				echo CHtml::closeTag('li');		
			}else{
				echo " <span style='color:red;'>\$models</span> is null ";
			}			
			
			</pre>	
		</div>		
	</div>	
	<b	style='color:red'>
		Note : Về link More ở đây sẽ không giải thích gì thêm .<br>
		Chúng ta tìm hiểu tại trang chính của Yii. 
	</b>	
</div>
