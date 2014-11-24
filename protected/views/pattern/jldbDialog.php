	<h2>Jlbd.dialog.alert (v1)</h2>
	<h3 class="wd-title">Demo Alert</h3>	
	<div id="wd-demo">
		<div class="wd-headerline">
			<input type="button" class="alert" value="Alert"/>
		</div>
		<div class="clear"></div>
	</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
			$(".alert").click(function(){
				jlbd.dialog.alert('Tets alert', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum');
			});
			</pre>
		</div>
	</div>
</div>
<!--confirm-->
<h2>Jlbd.dialog.confirm (v1)</h2>
	<h3 class="wd-title">Demo Confirm</h3>	
	<div id="wd-demo">
		<div class="wd-headerline">
			<input type="button" class="confirm" value="Confirm"/>
		</div>
		<div class="clear"></div>
	</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
			$(".confirm").click(function(){
				jlbd.dialog.confirm('Title confirm', 'Content message', 
					function(r){
						jlbd.dialog.alert('', 'Result : '+r);
					}
				);
			});
			</pre>
		</div>
	</div>
</div>
<!--prompt-->
<h2>Jlbd.dialog.prompt (v1)</h2>
	<h3 class="wd-title">Demo prompt</h3>	
	<div id="wd-demo">
		<div class="wd-headerline">
			<input type="button" class="prompt" value="Prompt"/>
		</div>
		<div class="clear"></div>
	</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
			$(".prompt").click(function(){
				jlbd.dialog.prompt('Title dialog', 'Input this', 'Content text... ',function(r, callData){						
					if(r==true) {
						jlbd.dialog.alert('My test prompt','Data input : ' + callData);						
					} else {
						jlbd.dialog.alert('My test prompt','You are selected :' + r);
					}
				});
			});
			</pre>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".alert").click(function(){
			jlbd.dialog.alert('Tets alert', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum');
		});	
		$(".confirm").click(function(){
			jlbd.dialog.confirm('Title confirm', 'Content message', 
				function(r){
					jlbd.dialog.alert('', 'Result : '+r);
				}
			);
		});
		$(".prompt").click(function(){
			jlbd.dialog.prompt('Title dialog', 'Input this', 'Content text... ',function(r, callData){						
				if(r==true) {
					jlbd.dialog.alert('My test prompt','Data input : ' + callData);						
				} else {
					jlbd.dialog.alert('My test prompt','You are selected :' + r);
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
		$('.showmessage').click(function(){
			var options = {
				width:	250,
				content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
				height : 200,
				top : 1000,
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
