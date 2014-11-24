<div class='hidden' style='display:none'>
	<?php
		$this->widget('widgets.review.JLBDReviewForm', array(
			//'strBizID' => $biz['attrs']['uuid'],
			'isShowLink' => false,
			//'intYourRate' => $userRates[$index],
		));
	?>
	
	<div id='avg-rating-prototype'>
	<?php
		$this->widget('widgets.rating.JLBDRatingInteractive', array(
			'name'	=> 'avg-rating-' . uniqid(),
			//'htmlOptions' => array('class'=>'star star-'.$biz['attrs']['uuid']),
			'starCount' => 5,
			'maxRating' => 10
		));
	?>
	</div>
</div>

<h2>JLBD Compliment & Flavours   (v2)</h2>
<h3 class="wd-title">Demo</h3>	
<div id="wd-demo" style="height:auto;">
	<div class="wd-container-tags">
		<?php
			$model = unserialize(file_get_contents('example/data/search-businesses'));
				
			$this->widget('widgets.search.JLBDSearchItems', array(
				'model' => $model,
			));	
		?>
	</div>
</div>
<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<b>
		$model là mảng search sphinx từ server của anh Trọng
	</b>
	<br>
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
		<pre class="brush:c-sharp;">	
			$model = array
			(
				'3881347523859586518' => array
				(
					'weight' => '284589',
					'attrs' => array
					(
						'reviews' => '3',
						'number_ratings' => '1',
						'avg_ratings' => 4,
						'latitude' => -33.951507568359,
						'longtitude' => 151.01724243164,
						'uuid' => '973C474652C511E1916E00270E356219',
						'name' => 'Hot Water Maintenance',
						'categories' => 'Heating, Hot Water & Ventilating Engineers',
						'jl_categories' => 'Miscellaneous',
						'keywords' => '',
						'description' => '',
						'address' => '297 Canterbury Rd',
						'location' => '2212 REVESBY NSW',
						'url' => '',
						'national_phone' => '',
						'landline' => '(02) 9771 5900',
						'mobile' => '',
						'fax' => '',
						'email' => '',
						'slogan' => '',
						'state' => 'NSW',
						'suburb' => 'REVESBY',
						'region' => 'Sydney OuterÂ (SO)',
						'lga' => 'Bankstown City Council',
					),
				),
				'7958228920329237312' => array
				(
					'weight' => '284589',
					'attrs' => array
					(
						'reviews' => '0',
						'number_ratings' => '1',
						'avg_ratings' => 3,
						'latitude' => -33.111274719238,
						'longtitude' => 151.13885498047,
						'uuid' => '973C49DA52C511E1916E00270E356219',
						'name' => 'Hot Water Maintenance',
						'categories' => 'Heating, Hot Water & Ventilating Engineers',
						'jl_categories' => 'Miscellaneous',
						'keywords' => '',
						'description' => '',
						'address' => '87 Pacific Hwy',
						'location' => '2250 GOSFORD WEST NSW',
						'url' => '',
						'national_phone' => '',
						'landline' => '(02) 4324 4105',
						'mobile' => '',
						'fax' => '',
						'email' => '',
						'slogan' => '',
						'state' => 'NSW',
						'suburb' => 'GOSFORD WEST',
						'region' => 'Sydney SurroundsÂ (SS)',
						'lga' => 'Gosford City Council',
					),
				),
				'3706531470105495339' => array
				(
					'weight' => '218583',
					'attrs' => array
					(
						'reviews' => '0',
						'number_ratings' => '0',
						'avg_ratings' => 0,
						'latitude' => -33.900398254395,
						'longtitude' => 151.20614624023,
						'uuid' => '973C231D52C511E1916E00270E356219',
						'name' => 'Doctor Plumbing',
						'categories' => 'Heating, Hot Water & Ventilating Engineers',
						'jl_categories' => 'Miscellaneous',
						'keywords' => '',
						'description' => '',
						'address' => '',
						'location' => '2017 WATERLOO NSW',
						'url' => 'http://www.doctorplumbing.com.au',
						'national_phone' => '',
						'landline' => '0402 195 867',
						'mobile' => '',
						'fax' => '(02) 9341 0110',
						'email' => 'enquiries@doctorplumbing.com.au',
						'slogan' => '',
						'state' => 'NSW',
						'suburb' => 'WATERLOO',
						'region' => 'Sydney InnerÂ (SI)',
						'lga' => 'Council of the City of Sydney',
					),
				),
			);
			
			$this->widget('widgets.search.JLBDSearchItems', array(
				'model' => $model,
			));
		</pre>
		</div>
	</div>
</div>