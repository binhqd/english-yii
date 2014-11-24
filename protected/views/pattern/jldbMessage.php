	<h2>Show modal Message</h2>
	<h3 class="wd-title">Demo show modal message</h3>	
	<div id="wd-demo">
		<div class="wd-headerline">
			<a href="#" class="modalmessage">jlbd.dialog.message</a><br>
			Or: <a href="#" class="modalmessage1">jlbd.dialog.modalMessage</a>
		</div>
		<div class="clear"></div>
	</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
				$('.modalmessage').click(function(){
					var options = {
						width:	250,
						content:	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
						height : 200,
					}
					jlbd.dialog.message(options, true);
					return false;
				});
			</pre>
		</div>
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">Or :
			<pre class="brush:c-sharp;">			
				$('.modalmessage1').click(function(){
					var options = {
						width:	250,
						content:	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
						height : 200,
					}
					jlbd.dialog.modalMessage(options);
					return false;
				});
			</pre>
		</div>
	</div>
</div>
<!--confirm-->
<h2>Show Message</h2>
	<h3 class="wd-title">Demo Confirm</h3>	
	<div id="wd-demo">
		<div class="wd-headerline">
			<a href="#" class="showmessage" >Demo Show Message</a>
		</div>
		<div class="clear"></div>
	</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
			$('.showmessage').click(function(){
				var options = {
					width:	250,
					content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
					height : 200,
					top : 900,
					left : 450,
					background : '#fff',
					color: 'blue'
				};
				jlbd.dialog.message(options,false);	
				return false;
			});
			</pre>
		</div>
	</div>
</div>
<!--prompt-->
<h2>Show message box</h2>
	<h3 class="wd-title">Demo show message box</h3>	
	<div id="wd-demo">
		<div class="wd-headerline">
			<ul>
				<li><a href="#" class="showmessagebox1" >Demo Show Messagebox (topLeft)</a></li>
				<li><a href="#" class="showmessagebox2" >Demo Show Messagebox (topCenter)</a></li>
				<li><a href="#" class="showmessagebox3" >Demo Show Messagebox (topRight)</a></li>
				<li><a href="#" class="showmessagebox4" >Demo Show Messagebox (middleLeft)</a></li>
				<li><a href="#" class="showmessagebox5" >Demo Show Messagebox (middleRight)</a></li>
				<li><a href="#" class="showmessagebox6" >Demo Show Messagebox (bottomLeft)</a></li>
				<li><a href="#" class="showmessagebox7" >Demo Show Messagebox (bottomCenter)</a></li>
				<li><a href="#" class="showmessagebox8" >Demo Show Messagebox (bottomRight)</a></li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
			$('.showmessagebox1').click(function(){
				var options = {
					position : 'topLeft', //Or selected : topCenter | topRight | middleLeft | middleRight | bottomLeft | bottomCenter | bottomRight
					width:	250,
					content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
				};
				jlbd.messagebox.message($(this),options);	
				return false;
			});
			</pre>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".alert").click(function(){
			jlbd.dialog.alert('Tets alert', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum');
		});	
		$(".confirm").click(function(){
			jlbd.dialog.confirm('Title confirm', 'Content message', 
				function(r){
					jlbd.dialog.alert('', 'Result : '+r);
				}
			);
		});
		$(".prompt").click(function(){
			jlbd.dialog.prompt('Title dialog', 'Input this', 'Content text... ',function(r){
				if (r != false) {
					jlbd.dialog.alert('', 'Text input : '+r);
				} else {
					jlbd.dialog.alert('Error !','You selected button Cancel');
				}
			});
		});
		//Demo jlbd.dialog.message
		$('.modalmessage').click(function(){
			var options = {
				width:	250,
				content:	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
				height : 200,
			}
			jlbd.dialog.message(options, true);
			return false;
		});
		$('.modalmessage1').click(function(){
			var options = {
				width:	250,
				content:	'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
				height : 200,
			}
			jlbd.dialog.modalMessage(options);
			return false;
		});
		$('.showmessage').click(function(){
			var options = {
				width:	250,
				content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
				height : 200,
				top : 900,
				left : 450,
				background : '#fff',
				color: 'blue'
			};
			jlbd.dialog.message(options,false);	
			return false;
		});
		$('.showmessagebox1').click(function(){
			var options = {
				position : 'topLeft',
				width:	250,
				content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
			};
			jlbd.messagebox.message($(this),options);	
			return false;
		});
		$('.showmessagebox2').click(function(){
			var options = {
				position : 'topCenter',
				width:	250,
				content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
			};
			jlbd.messagebox.message($(this),options);	
			return false;
		});
		$('.showmessagebox3').click(function(){
			var options = {
				position : 'topRight',
				width:	250,
				content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
			};
			jlbd.messagebox.message($(this),options);	
			return false;
		});
		$('.showmessagebox4').click(function(){
			var options = {
				position : 'middleLeft',
				width:	250,
				content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
			};
			jlbd.messagebox.message($(this),options);	
		return false;
		});
		$('.showmessagebox5').click(function(){
			var options = {
				position : 'middleRight',
						width:	250,
						content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
					};
					jlbd.messagebox.message($(this),options);	
				return false;
			});
			$('.showmessagebox6').click(function(){
					var options = {
						position : 'bottomLeft',
						width:	250,
						content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
					};
					jlbd.messagebox.message($(this),options);	
				return false;
			});
			$('.showmessagebox7').click(function(){
				var options = {
					position : 'bottomCenter',
					width:	250,
					content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
				};
				jlbd.messagebox.message($(this),options);	
				return false;
			});
			$('.showmessagebox8').click(function(){
					var options = {
						position : 'bottomRight',
						width:	250,
						content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
					};
					jlbd.messagebox.message($(this),options);	
				return false;
			});
		});
	</script>
