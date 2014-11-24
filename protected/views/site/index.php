<script type="text/javascript" src="/myzone_v1/js/greennet-autocomplete.js"></script>
<div class="wd-banner-yl">
	<div class="wd-center">
		<?php
		$time = time();
		if (!empty($_GET['time']) && strtotime($_GET['time'])) {
			$time = strtotime($_GET['time']);
		}
		$happyBirthdays = ZoneNodeRender::getBirthdayOnToday($time);
		?>
		<?php if ($happyBirthdays) : ?>
			<div class="wd-happy-birthday">
				<?php 
					$defaultAvatar = ZoneRouter::CDNUrl("/upload/gallery/fill/40-40/");
					GNAssetHelper::init(array(
						'image' => 'img',
						'css' => 'css',
						'script' => 'js',
					));
					GNAssetHelper::setBase('myzone_v1');
					GNAssetHelper::cssFile('birthday-contents');
					GNAssetHelper::scriptFile('jquery.easing.1.3.min');
					GNAssetHelper::scriptFile('jquery.mousewheel.min');
					GNAssetHelper::scriptFile('jquery.touchSwipe.min');
					GNAssetHelper::scriptFile('jquery.carouFredSel-6.2.0-packed');
					GNAssetHelper::scriptFile('greennet-autocomplete');
					
				?>
				<p class ="wd-birthday-status">Happy Birthday To You on <?php echo date('F d' , $time) ?></p>
				<div class="wd-gallery-birthday-content">
					<div class="wd-control-div">
						<span id="wd-prev-bd" class="wd-prev-bd">prev</span>
						<span id="wd-next-bd" class="wd-next-bd">next</span>
					</div>
					<ul class="wd-gallery-birthday">
						<?php foreach($happyBirthdays as $person) : ?>
							<?php
								$avatar = '';
								$link = dirname(Yii::app()->request->scriptFile) . '/upload/user-photos/%s/%s';
								$user = ZoneUser::model()->get($person['zone_id']);
								if($user && $user['id'] != '2d31'){
									$filename = sprintf($link , $user['hexID'],$user['profile']['image']);
									if(!empty($user['profile']['image']) && file_exists($filename) ) {
										$avatar = ZoneRouter::CDNUrl("/upload/user-photos/" . $user['hexID']
													. "/fill/40-40/" . $user['profile']['image'] . "?album_id=" . $user['hexID']);
									}
								}else{
									$user = ZoneInstanceRender::getResourceImage($person);
									if(!empty($user['image']['photo'])){
										$user = $user['image']['photo'];
										$albumID = !empty($user['album_id']) ? $user['album_id'] : @$user['object_id'];
										$avatar = ZoneRouter::CDNUrl("/upload/gallery/fill/40-40/"
														. $user['image'] . "?album_id=" . $albumID);
									}
								}
								if(empty($avatar)){
									continue;
									//$avatar = $defaultAvatar;
								}
							?>
							<li>
								<a title="<?php echo $person['name']; ?>" href="<?php echo '/zone/pages/detail?id=' . $person['zone_id']; ?>" class="wd-image wd-tooltip-hover">
									<img src="<?php echo $avatar?>" alt="<?php echo $person['name']; ?>" height="39" width="39" />
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
					<script type="text/javascript">
						jQuery(function(){
							$('.wd-gallery-birthday-content .wd-tooltip-hover').tipsy({gravity: 's'});
						});
					</script>
				</div>
			</div>
		<?php else : ?>
			<p class="wd-sologan">
				Let Your Voice Be Heard! Connect With Us! Build Your Culture Now!
			</p>
		<?php endif; ?>
		<fieldset class="wd-search-form">
			<form name="searchNode" method="GET" action="<?php echo GNRouter::createUrl("/search");?>">
				<div class="wd-input">
					<input type="text" class="wd-text-search youlook-text-search"  name="keyword" id="input-header-search">
					<?php
						Yii::import('ext.jqautocomplete.jqAutocomplete');

						$json_options = array(
							'script'=> GNRouter::createUrl('/zone/pages/search?'),
							'varName'=>'term',
							'showMoreResults'=>false,
							'valueSep' => null,
							'maxresults'=>16,
							'callback' =>'js:function(obj){ 
								window.location.href = "'.GNRouter::createUrl("/zone/pages/detail/?id=").'"+obj.id ;
							}',
							'submit' => 'js:function(e) {}'
						);
						jqAutocomplete::addAutocomplete('#input-header-search',$json_options);
					?>
					<input type="submit" class="wd-search-bt wd-submit wd-tooltip-hover" title="Search" value="">
				</div>
			</form>
		</fieldset>
		<div class="wd-signin-more">
			<?php 
				echo CHtml::link('Sign in with Facebook',ZoneRouter::createUrl('/facebook'),array(
					'class'=>'wd-sigin-facebook'
				));
			?>
		</div>
		<div class="wd-connect-facebook-used-st custom-like-facebook">
			<div class="wd-username-facebook" style="width:240px; margin:0 auto">
				
			</div>
			<div id="fb-root"></div>
			
		</div>
		
	</div>
</div>

<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
GNAssetHelper::setBase('myzone_v1');
// GNAssetHelper::scriptFile('jquery.pause', CClientScript::POS_HEAD);
Yii::import('application.modules.followings.models.ZoneFollowing');
$search = ZoneInstanceRender::search(null,66,0,InterestCondition::getValue(""));
$nodes = array();

$strImage = ZoneRouter::createUrl("/site/placehold?t=96x96-282828");
foreach($search as $key=>$node){
	$nodes[$key] = $node;
	
	$image = ZoneInstanceRender::getResourceImage($node);
	if(!empty($image['image'])){
		$nodes[$key]['avatar'] = ZoneRouter::CDNUrl("/upload/gallery/fill/96-96/".$image['image']['photo']['image'])."?album_id=".$image['image']['photo']['album_id'];
	}  else {
		$nodes[$key]['avatar'] = $strImage;
	}
	$nodes[$key]['count_follow'] = ZoneFollowing::model()->countFollowers(IDHelper::uuidToBinary($node['zone_id']));
}

?>


<!-- main content -->
<script>
Array.prototype.remove = function() {
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) {
            this.splice(ax, 1);
        }
    }
    return this;
};

;if(window.jQuery) (function($){
	window['zizag'] = {
		redirect: function(strUrl) {
			window.location = strUrl;
		},
		container:$("#container"),
		stop:false,
		nodes:{
			data:[],
			total:0
		},
		positionClearQueue:[],
	};
	window['Libs'] = {
		makeid : function(strLength) {
			if (typeof strLength == "undefined") strLength = 5;
			var text = "";
			var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			for( var i=0; i < strLength; i++ )
				text += possible.charAt(Math.floor(Math.random() * possible.length));
			return text;
		}
	}
})(jQuery);


;(function($, scope){
	scope['Actions'] = {
		init : function(options){
			
			if(typeof options == "object"){
				zizag.Actions.defaults = options;
				
			}
			
			if(zizag.Actions.defaults.data.length == 0){
				console.log("data not found");
				return false;
			}
			zizag.container = $("#container");

			zizag.Actions.createPoints(options);

			zizag.container.hover(function(){
				zizag.stop = true;
				zizag.Actions.getPositionItemStop();
			}).mouseleave(function(){
				zizag.stop = false;
				zizag.Actions.continueNode();
			});
			
			
			if(zizag.Actions.defaults.debug){
				$.each(zizag.Actions.data.plot,function(x,y){
					zizag.container.append('<div style="width:2px; height:2px ; background-color:green;position:absolute;left:'+y[0]+'px;top:'+y[1]+'px"></div>');
				});
				
			}
			
			$.each(zizag.Actions.data.plot,function(x,y){
				$.each(zizag.Actions.higherPeak,function(n,m){
					if(y[0] == m[0] && y[1] == m[1]){
						zizag.Actions.positionHigherPeak.push(x);
						
					}
				});
			});
			
			// refuse partial first for array && assign items node in line
			for(var i=1; i<zizag.Actions.higherPeak.length; i++){
				var classSize = "wd-avatar-size-"+3;//Math.floor((Math.random()*6)+1);
				var left = zizag.Actions.higherPeak[i][0];
				var top = zizag.Actions.higherPeak[i][1];
				var strId = Libs.makeid();
				if(i == 1) zizag.Actions.firstId = strId;
				zizag.Actions.objIdElement.push(strId);
				var html = '<div class="wd-bg-img-tt wd-avatar-of-node-1 item"  id="'+strId+'" style="position:absolute;opacity: 1; left:'+left+'px; top:'+top+'px;">'+
				'<a href="'+homeURL+'/zone/pages/detail?id='+zizag.Actions.defaults.data[i]['zone_id']+'" class="wd-avatar-of-node"><img src="'+zizag.Actions.defaults.data[i]['avatar']+'" alt="'+zizag.Actions.defaults.data[i]['name']+'" class="'+classSize+'"></a>'+
					'<div class="wd-node-info">'+
						'<h4><a href="'+homeURL+'/zone/pages/detail?id='+zizag.Actions.defaults.data[i]['zone_id']+'" class="wd-node-name">'+zizag.Actions.defaults.data[i]['name']+'</a></h4>'+
						'<p class="wd-folow-info">'+zizag.Actions.defaults.data[i]['count_follow']+' Followers</p>'+
					'</div>'+
				'</div>';
				zizag.container.append(html);
				$("#"+strId).css({width: $("#"+strId).find('img').width() +"px"});
				var widthItem = $("#"+strId).width();
				
				zizag.Actions.cssItem($("#"+strId));
				
				
				var t = (top - Math.ceil(widthItem/2));
				var l = (left - Math.ceil(widthItem/2));
				
				$("#"+strId).css({
					top:t+"px",
					left:l+"px",
				});
				
				delete zizag.Actions.defaults.data[i];
				
				zizag.Actions.realTimeRun(strId,i);
				
			}
			

		},
		cssItem:function(_this){
			/*var widthItem = _this.width();
			_this.find('.wd-node-info').css({
				width:"90px",
				'text-align':'center',
			});*/
		},
		templateItem:function(strId,left,top,classSize){
			var tmp = [];
			$.each(zizag.Actions.defaults.data,function(x,y){
				if(typeof y!="undefined"){
					tmp.push(y);
				}
			});
			zizag.Actions.defaults.data = tmp;
			if(zizag.Actions.defaults.data.length == 0){
				zizag.Actions.defaults.data = zizag.nodes.data;
			}
			zizag.Actions.defaults.data.sort();
			
			var html = '<div class="wd-bg-img-tt wd-avatar-of-node-1 item"  id="'+strId+'" style="position:absolute;opacity: 0; left:'+left+'px; top:'+top+'px;">'+
				'<a href="'+homeURL+'/zone/pages/detail?id='+zizag.Actions.defaults.data[0]['zone_id']+'" class="wd-avatar-of-node"><img src="'+zizag.Actions.defaults.data[0]['avatar']+'" alt="'+zizag.Actions.defaults.data[0]['name']+'" class="'+classSize+'"></a>'+
					'<div class="wd-node-info">'+
						'<h4><a href="'+homeURL+'/zone/pages/detail?id='+zizag.Actions.defaults.data[0]['zone_id']+'" class="wd-node-name">'+zizag.Actions.defaults.data[0]['name']+'</a></h4>'+
						'<p class="wd-folow-info">'+zizag.Actions.defaults.data[0]['count_follow']+' Followers</p>'+
					'</div>'+
				'</div>';
			zizag.Actions.cssItem($("#"+strId));
			delete zizag.Actions.defaults.data[0];
			return html;
		},
		continueNode:function(){
			// setTimeout(function(){
				$.each(zizag.Actions.objIdElement,function(x,y){
					if(typeof y!="undefined"){
						var _this = $("#"+y);
						var widthItem = _this.width();
						var position = _this.position();
						var leftItem = position.left+(widthItem/2);
						var total = 0;
						for(var i = zizag.Actions.data.plot.length - 1 ; i>=0 ; i--){
							if(typeof zizag.Actions.data.plot[i] != "undefined" && i!=zizag.Actions.defaults.dotInline-5){
								if(leftItem >= zizag.Actions.data.plot[i][0]){
									total++;
									var cnt = 0;
									var t = (zizag.Actions.data.plot[i][1] - (widthItem/2));
									var l = (zizag.Actions.data.plot[i][0] - (widthItem/2));
									_this.animate({
										top:t+"px",
										left:l+"px",
									},zizag.Actions.defaults.duration,function(e){
										cnt++;
										if(cnt == total){
											$("#"+zizag.Actions.firstId).animate({
												opacity:0,
											},100,function(e){
												var tmpFirstId = zizag.Actions.firstId;
												zizag.Actions.firstId = $("#"+zizag.Actions.firstId).next().attr('id');
												
												$("#"+tmpFirstId).remove();
												zizag.Actions.objIdElement.splice(0,1);
												zizag.Actions.loopItem();
												
											});
										}
									});
								}
							}
							
						}
					}
				});
			// },1000);
			
		},
		getPositionItemStop:function(){
			if(zizag.Actions.objIdElement.length == 0) return false;
			
			$.each(zizag.Actions.objIdElement,function(x,y){
				
				zizag.Actions.stopElement($("#"+y));
			});
		},
		stopElement:function(_this){
			_this.clearQueue();
			_this.stop();
			_this.off();
			$.each(zizag.Actions.objIdElement,function(x,y){
				$("#"+y).css({opacity:1});

			});
		},
		loopItem:function(){
			var classSize = "wd-avatar-size-"+3;//Math.floor((Math.random()*6)+1);
			if(typeof zizag.Actions.higherPeak[zizag.Actions.higherPeak.length-1] !="undefined"){
				
				var left = zizag.Actions.higherPeak[zizag.Actions.higherPeak.length-1][0];
				var top = zizag.Actions.higherPeak[zizag.Actions.higherPeak.length-1][1];
				var strId = Libs.makeid();
				zizag.Actions.objIdElement.push(strId);
				zizag.container.append(zizag.Actions.templateItem(strId,left,top,classSize));
				$("#"+strId).css({width: $("#"+strId).find('img').width() +"px"});
				var widthItem = $("#"+strId).width();
				var t = (top - Math.ceil(widthItem/2));
				var l = (left - Math.ceil(widthItem/2));
				$("#"+strId).css({
					top:t+"px",
					left:l+"px"
				});
				$("#"+strId).animate({opacity:1},800,function(e){
				});
				
				
				zizag.Actions.realTimeRun(strId,zizag.Actions.higherPeak.length-1);
				if(zizag.stop) {
					zizag.Actions.realTimeRun($("#"+strId));
				}
			}else{
				
			}
		},
		realTimeRun:function(id,i){
			var y = id;
			var _this = $("#"+y);
			var widthItem = _this.width();
			_this.css({'width':widthItem+"px"});
			if(i>0){
				for(var yy=zizag.Actions.positionHigherPeak[i];yy>=0; yy--){
					var cnt = 0;
					var xx = yy-1;
					if(typeof zizag.Actions.data.plot[xx] !="undefined" && xx!=zizag.Actions.defaults.dotInline-5){
						var t = (zizag.Actions.data.plot[xx][1] - (widthItem/2));
						var l = (zizag.Actions.data.plot[xx][0] - (widthItem/2));
						var cntStop = 0;
						_this.animate({
							top:t+"px",
							left:l+"px",
						},zizag.Actions.defaults.duration,function(e){
							cnt++;
							if(i==1){
								if(yy<0 && ((cnt+1) == zizag.Actions.positionHigherPeak[i])){
									$("#"+zizag.Actions.firstId).animate({
										opacity:0,
									},100,function(e){
										
										var tmpFirstId = zizag.Actions.firstId;
										zizag.Actions.firstId = $("#"+zizag.Actions.firstId).next().attr('id');
										
										$("#"+tmpFirstId).remove();
										zizag.Actions.objIdElement.splice(0,1);
										zizag.Actions.loopItem();
										
										
										
									});
									
								}
								
							}else{
								
								if(yy<0 && ((cnt+2) == zizag.Actions.positionHigherPeak[i])){
									$("#"+zizag.Actions.firstId).animate({
										opacity:0,
									},100,function(e){
										
										var tmpFirstId = zizag.Actions.firstId;
										zizag.Actions.firstId = $("#"+zizag.Actions.firstId).next().attr('id');
										
										$("#"+tmpFirstId).remove();
										zizag.Actions.objIdElement.splice(0,1);
										zizag.Actions.loopItem();
										
										
										
									});
									
								}
							}


						});
						
						
					}else{
						cnt--;
					}
					
				}
				
			}// end if 
			
			
		},
		calDuration:function(){
		
		},
		runNode:function(){
			$.each(zizag.Actions.objIdElement,function(x,y){
				var i = x;
				if(typeof zizag.Actions.higherPeak[i-1] !="undefined"){
					zizag.Actions.realTimeRun(y,i);
					
				}
			});
			for(var i=1; i<zizag.Actions.higherPeak.length; i++){
				// console.log(zizag.Actions.objIdElement);
			}
		},
		multiScreen:function(){
			
			if (($(window).width() > 1599)) {
				
				// console.log(">1599");
				
			}else if ($(window).width() > 1439 && $(window).width() < 1600  ){
				
				// console.log(">1439 <1600");
			}
			else if ($(window).width() > 1359 && $(window).width() < 1440  ){
				zizag.container.css({width:($(window).width()-100)+"px"});
				zizag.container.parent().css({width:($(window).width()-100)+"px"});
				// console.log(">1359 <1440");
			}
			else if ($(window).width() > 1279 && $(window).width() < 1360  ){
				zizag.container.parent().css({width:($(window).width()-12)+"px"});
				zizag.container.css({width:($(window).width()-12)+"px"});
				
				
				// console.log(">1279 <1360");
			}else if ($(window).width() > 1023 && $(window).width() < 1280  ){
				zizag.container.css({width:($(window).width()-160)+"px",'background-image':'url('+homeURL+'/myzone_v1/img/front/home/line-1280.png)'});
				zizag.container.parent().css({width:($(window).width()-160)+"px"});
				// console.log(">1023 <1280");
				return 2;
			}
			else if ($(window).width() > 767 && $(window).width() < 1024  ){
				zizag.container.css({width:($(window).width()-100)+"px",'background-image':'url('+homeURL+'/myzone_v1/img/front/home/line-1024.png)'});
				zizag.container.parent().css({width:($(window).width()-100)+"px"});
				// console.log(">767 <1024");
				return 3;
			}
			return 0;
		},
		createPoints:function(options){
			var lineCut = zizag.Actions.multiScreen();
			
			var cnt = 0;
			var tY = options.item.heightRec - 5;
			var pointLastLine = [];
			zizag.Actions.higherPeak.push([options.jump,tY]);
			// line 1
			for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
				cnt++;
				if(i==5) tY = options.item.heightRec - 5;
				else tY = tY - 2;
				var tmpSt = [options.jump*cnt,tY];
				if(i==zizag.Actions.defaults.dotInline) pointLastLine = tmpSt;
				
				zizag.Actions.data.plot.push(tmpSt);
			}
			
			// line 2
			var ps = pointLastLine;
			
			zizag.Actions.higherPeak.push([150,zizag.Actions.defaults.dotInline]);// 0
			zizag.Actions.higherPeak.push(ps);// 1
			
			for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
				ps[0] = ps[0]+3;
				ps[1] = ps[1]+(options.jump/2);
				if(i==zizag.Actions.defaults.dotInline){
					pointLastLine = [ps[0],ps[1]];
					
				}
				zizag.Actions.data.plot.push([ps[0],ps[1]]);
			}
			
			// line 3
			var ps = pointLastLine;
			
			zizag.Actions.higherPeak.push(ps);// 2
			
			for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
				ps[0] = ps[0]+(options.jump/2);
				ps[1] = ps[1]-(options.jump/2);
				if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
				zizag.Actions.data.plot.push([ps[0],ps[1]]);
			}
			
			// line 4 
			
			var ps = pointLastLine;
			
			zizag.Actions.higherPeak.push(ps);// 3
			for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
				ps[0] = ps[0]+(options.jump/2);
				ps[1] = ps[1]+(options.jump/2);
				if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
				zizag.Actions.data.plot.push([ps[0],ps[1]]);
			}
			if(lineCut!=3){
				// line 5
				var ps = pointLastLine;
				zizag.Actions.higherPeak.push(ps);// 4
				for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
					ps[0] = ps[0]+((options.jump+1)/2);
					ps[1] = ps[1]-(options.jump/2);
					if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
					zizag.Actions.data.plot.push([ps[0],ps[1]]);
				}
				// line 6
				var ps = pointLastLine;
				zizag.Actions.higherPeak.push(ps);// 5
				for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
					ps[0] = ps[0]+((options.jump+3)/2);
					ps[1] = ps[1]+(options.jump/2);
					if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
					zizag.Actions.data.plot.push([ps[0],ps[1]]);
				}
				// line 7
				var ps = pointLastLine;
				zizag.Actions.higherPeak.push(ps);// 6
				for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
					ps[0] = ps[0]+((options.jump+2)/2);
					ps[1] = ps[1]-(options.jump/2);
					if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
					zizag.Actions.data.plot.push([ps[0],ps[1]]);
				}
				// line 8
				var ps = pointLastLine;
				zizag.Actions.higherPeak.push(ps);// 7
				for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
					ps[0] = ps[0]+((options.jump+4)/2);
					ps[1] = ps[1]+(options.jump/2);
					if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
					zizag.Actions.data.plot.push([ps[0],ps[1]]);
				}
			}
			if(lineCut!=2){
				// line 9
				var ps = pointLastLine;
				zizag.Actions.higherPeak.push(ps);// 8
				for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
					ps[0] = ps[0]+((options.jump+2)/2);
					ps[1] = ps[1]-(options.jump/2);
					if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
					zizag.Actions.data.plot.push([ps[0],ps[1]]);
				}
				// line 10
				var ps = pointLastLine;
				zizag.Actions.higherPeak.push(ps);// 9
				for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
					ps[0] = ps[0]+((options.jump+4)/2);
					ps[1] = ps[1]+(options.jump/2);
					if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
					zizag.Actions.data.plot.push([ps[0],ps[1]]);
				}
			}
			// line last
			var ps = pointLastLine;
			zizag.Actions.higherPeak.push(ps);// 10
			for(var i = options.jump; i<=zizag.Actions.defaults.dotInline; i++){
				ps[0] = ps[0]+((options.jump+7)/2);
				ps[1] = ps[1]-(options.jump/2);
				if(i==zizag.Actions.defaults.dotInline) pointLastLine = [ps[0],ps[1]];
				zizag.Actions.data.plot.push([ps[0],ps[1]]);
			}
			zizag.Actions.tmpHigherPeak = zizag.Actions.higherPeak;
			$.each(zizag.Actions.higherPeak,function(x,y){
				var t = [];
				zizag.positionClearQueue.push(t);
			});
			
		},
		Events:{

		},

		data:{
			plot:[],
			xy:[0,0],
			partial:[]
		},
		objHtml:{
			zz:null
		},
		objIdElement:[],
		fistId:null,
		higherPeak:[],
		higherStopPeak:[],
		tmpHigherPeak:[],
		positionHigherPeak:[],
		color:['red','green','yellow','black','#0000A0','#92C7C7','#C79191'],
		defaults:{
			dotInline:40,
			duration:1000,
			angle:1,
			jump:5,
			item:{
				widthRec:98,
				heightRec:100,
				angle:45,
			},
			debug:true
		}
		
	}
})(jQuery, zizag);

// Function to get the Maximam value in Array
Array.max = function( array ){
	return Math.max.apply( Math, array );
};

// Function to get the Minimam value in Array
Array.min = function( array ){
	return Math.min.apply( Math, array );
};


zizag.nodes = <?php echo @CJSON::encode($nodes);?>;
$(document).ready(function(){
	

	zizag.Actions.init({
		duration:135,
		dotInline:35,
		angle:1,
		jump:5,
		item:{
			widthRec:100,
			heightRec:103,
			angle:45,
		},
		data:<?php echo @CJSON::encode($nodes);?>,
		debug:false
	});
	zizag.nodes.data = <?php echo CJSON::encode($nodes);?>;
	$(window).resize(function() {

	});
});
</script>
<div class="wd-main-content-wr2">
	<div id="wd-gallery-home-zizag" class="wd-gallery-home" style="width:1300px;position:relative;">
		<div class="wd-gallery-container" style="height:140px;opacity: 1;position:relative;width:1330px;z-index:1" id="container">
			
		</div>
		
	</div>
</div>
<style>
body{overflow-x:hidden}
#wd-gallery-home-zizag{
	overflow:visible;
	height: 160px;
	width: 1135px;
	margin: 0 auto 30px;
}
#container  .wd-avatar-of-node .wd-avatar-size-1{height:80px;width:80px}
#container  .wd-avatar-of-node .wd-avatar-size-2{height:70px;width:70px}
#container  .wd-avatar-of-node .wd-avatar-size-3{height:60px;width:60px}
#container  .wd-avatar-of-node .wd-avatar-size-4{height:50px;width:50px}
#container  .wd-avatar-of-node .wd-avatar-size-5{height:40px;width:40px}
#container  .wd-avatar-of-node .wd-avatar-size-6{height:50px;width:50px}
#container  .wd-avatar-of-node{display:inline-block;border:4px solid #fff;
-moz-border-radius:50%;-webkit-border-radius:50%;border-radius:50%; -moz-box-shadow:0px 2px 2px #c3c3c3;
-webkit-box-shadow:0px 2px 2px #c3c3c3;box-shadow:0px 2px 2px #c3c3c3;height:60px;width:60px;
-moz-transition: all 0.4s ease 0s;
-webkit-transition: all 0.4s ease 0;
-o-transition: all 0.4s ease 0;
transition: all 0.4s ease 0
}

#container  .wd-avatar-of-node img {display:inline-block;vertical-align:middle;-moz-border-radius:50%;-webkit-border-radius:50%;border-radius:50%;height:60px;width:60px;
-moz-transition: all 0.4s ease 0s;
-webkit-transition: all 0.4s ease 0;
-o-transition: all 0.4s ease 0;
transition: all 0.4s ease 0
}
#container  .wd-bg-img-tt:hover .wd-avatar-of-node,#container  .wd-bg-img-tt:hover .wd-avatar-of-node img{height:90px;width:90px;
-webkit-transform: scale(1,1);
     -ms-transform: scale(1,1);
     -moz-transform: scale(1,1);
       -o-transform: scale(1,1);
          transform: scale(1,1);
}
#container  .wd-node-name{color:#f26522}
#container  .wd-node-info{margin-top:7px;opacity:0;filter:alpha(opacity=0);
transition: opacity 0.4s ease-in-out 0s;
-webkit-transition: opacity 0.4s ease-in-out 0s;
-ms-transition: opacity 0.4s ease-in-out 0s;
-moz-transition: opacity 0.4s ease-in-out 0s;
-o-transition: opacity 0.4s ease-in-out 0s;
}
#container  .wd-bg-img-tt:hover .wd-node-info{opacity:1;filter:alpha(opacity=100);
transition: opacity 0.4s ease-in-out 0s;
-webkit-transition: opacity 0.4s ease-in-out 0s;
-ms-transition: opacity 0.4s ease-in-out 0s;
-moz-transition: opacity 0.4s ease-in-out 0s;
-o-transition: opacity 0.4s ease-in-out 0s;
}
#container .item .wd-node-info{width:90px; text-align:center}
.wd-gallery-container{background: url(<?php echo baseUrl();?>img/front/home/line.png) no-repeat;}
</style>
<!-- main content .end-->