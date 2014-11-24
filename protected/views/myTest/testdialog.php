<style type="text/css">
	.jldb-alert-test-stile{color:green;}
	.wd-popup-demo{margin:20px;padding:10px;}
	.wd-popup-hd{margin:20px; font-size:13px;padding-top:20px;}
	.wd-popup-hd ul.wd-popup-hd1 li{font-weight:bold;}
	.wd-popup-hd ul.wd-popup-hd2 li{font-weight:normal;margin-left:35px;padding-left:5px;color:green;list-style-type:circle;}
</style>
<div class="wd-popup-hd">
<ul class="wd-popup-hd1">
<li>
	Alert : 
	<ul class="wd-popup-hd2">
		<li><pre>jlbd.dialog.alert('title alert', 'content message');</pre></li>
		<li>Demo alert: <input type="button" class="alert" value="Alert"/></li>
	</ul>
</li>
<li>
	Confirm :
	<ul class="wd-popup-hd2">
		<li><pre>jlbd.dialog.confirm('title confirm','content message',function(r){
	if ( r == true ) {
	...;
	}
	else {
	... ;
	}
});
		</pre>
		</li>
		<li>Demo confirm : <input type="button" class="confirm" value="Confirm"/></li>
	</ul>
</li>
<li>
	Prompt :
	<ul class="wd-popup-hd2">
		<li><pre>jlbd.dialog.prompt('title prompt', 'content message','default value text', function(r)	{
	jlbd.dialog.alert('Test prompt','Text input :'+r);
});
			</pre>
		</li>
		<li>Demo prompt : <input type="button" class="prompt" value="Prompt"/></li>
	</ul>
</li>
<li>
Show ModalMessage : 
	<ul class="wd-popup-hd2">
		<li>Call function<pre>jlbd.dialog.message(options, true);</pre></li>
		<li>Or<pre>jlbd.dialog.modalMessage(options);</pre></li>
		<li>Attributes:<pre>var options = {
	width		: 'value',
	height		: 'value',
	top		: 'value',
	left		: 'value',
	background	: 'value',
	color		: 'value',
	content		: 'content',
};</pre></li>
		<li>Demo : <a href="#" class="modalmessage">Demo Show Modal Message</a></li>
	</ul>
</li>
<li>
Show Message : 
	<ul class="wd-popup-hd2">
		<li>Call function : jlbd.dialog.message(options); </li>
		<li>Options<pre>var options = {
	width:	250,
	content: '. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.<p style="color:green;"><i>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In ultricies felis eu tortor rutrum interdum.</i></p>',
	height : 200,
	top : 800,
	left : 450,
	background : '#fff',	// default value #ffc;
	color: 'blue'			//default value #000;
};
</pre></li>
	<li>Demo : <a href="#" class="showmessage" >Demo Show Message</a></li>
	</ul>
</li>
<li>
Show Messagebox : 
	<ul class="wd-popup-hd2">
		<li>Call function : jlbd.messagebox.message($(this), options);</li>

		<li>Options<pre>var options = {
	[position	: 'topLeft | topCenter | topRight | middleLeft | middleRight | bottomLeft | bottomCenter | bottomRight',]
	width		: 'value',
	height		: 'value',
	top		: 'value',
	left		: 'value',
	background	: 'value',
	color		: 'value',
	content		: 'content',
};
<b>if position is null or '' then default value position of is 'topCenter'</b>
</pre></li>
	<li>Demo : <br><ul>
		<li><a href="#" class="showmessagebox1" >Demo Show Messagebox (topLeft)</a></li>
		<li><a href="#" class="showmessagebox2" >Demo Show Messagebox (topCenter)</a></li>
		<li><a href="#" class="showmessagebox3" >Demo Show Messagebox (topRight)</a></li>
		<li><a href="#" class="showmessagebox4" >Demo Show Messagebox (middleLeft)</a></li>
		<li><a href="#" class="showmessagebox5" >Demo Show Messagebox (middleRight)</a></li>
		<li><a href="#" class="showmessagebox6" >Demo Show Messagebox (bottomLeft)</a></li>
		<li><a href="#" class="showmessagebox7" >Demo Show Messagebox (bottomCenter)</a></li>
		<li><a href="#" class="showmessagebox8" >Demo Show Messagebox (bottomRight)</a></li>
		</ul></li>
	</ul>
</li>
</ul>
</div>	
    <!-- script.end -->
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
					//alert('You select Cancel');
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