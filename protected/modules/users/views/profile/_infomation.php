<?php
if(!empty($propertiesInfomation)){
	$arrInfoDenied = array('username');
	foreach($propertiesInfomation as $key=>$propInfo){
		$pos = strpos($propInfo['expected'], 'location');
		if ($pos === false) {
			$tmpLabel = strtolower($propInfo['label']);
			$tmpLabel = str_replace(" ","",$tmpLabel);
			$tmpLabel = str_replace("-","",$tmpLabel);
			$tmpLabel = str_replace(",","",$tmpLabel);
			if(!empty($propInfo['label']) && !in_array($tmpLabel,$arrInfoDenied)){
?>			
			<li class="info<?php echo $tmpLabel;?>">
				<label><?php echo $propInfo['label'];?>: </label>
				<span>
					<?php
					// dump((array)$propInfo['value'],false);
					$tmpData = implode(",",(array)$propInfo['value']);
					if(!empty($propInfo['node'])){
						switch($propInfo['expected']){
							case "/people/gender":
								echo $tmpData;
							break;
							case "/type/datetime":
								
								$day = date("d",strtotime($tmpData));
								
								if($day<=3) echo date("F {$day}S,Y",strtotime($tmpData));
								else echo date("F {$day},Y",strtotime($tmpData));
							break;
							default:
								echo CHtml::link($tmpData,ZoneRouter::createUrl('/zone/pages/detail',array('id'=>$propInfo['node']['zone_id'])));
							break;
						}
						
						
					}else{
						if(MyZoneHelper::isValidURL($tmpData)) echo CHtml::link($tmpData,$tmpData);
						else{
							switch($propInfo['expected']){

								case "/type/datetime":
									
									$day = date("j",strtotime($tmpData));
									if($day<=3) echo date("M {$day}S,Y",strtotime($tmpData));
									else echo date("M {$day},Y",strtotime($tmpData));
								break;
								case "/type/float":
									echo MyZoneHelper::formatHeightValue($tmpData,$propInfo['label']);
									

								break;
								default:
									echo $tmpData;
								break;
								
							}
							
						}
					}
					?>
					
				</span>
			</li>
<?php
			}
		}else{
		
		
		}
	}
}
?>
		

