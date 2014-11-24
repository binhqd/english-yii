	<h2>Jlbd.dialog.notify (v1)</h2>
	<h3 class="wd-title">Demo Notify</h3>	
	<div id="wd-demo">
		<div class="wd-headerline">
			<a href="#" class="notify">Demo notify : set timeOut</a><br>
		<a href="#" class="notify-1">Demo notify : no set timeOut</a><br>
		<a href="#" class="notify-de">Demo notify : Close Notify</a><br>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.notify').click(function() {
					var options = {					
						message	: 'Loading ...',
						autoHide : true,
						timeOut : 0.5
					}
					jlbd.dialog.notify(options);
					return false;
				});
				$('.notify-1').click(function() {
					var options = {					
						message	: 'Loading ...',
						autoHide : false
					}
					jlbd.dialog.notify(options);
					return false;
				});
				$('.notify-de').click(function() {
					jlbd.dialog.deNotify();
					return false;
				});
			});
		</script>
		</div>
		<div class="clear"></div>
	</div>

<h3 class="wd-title">How To Use</h3>	
<div class="wd-tab">
	<div class="wd-panel">
		<div class="wd-section" id="wd-fragment-1" style="display: block; ">
			<pre class="brush:c-sharp;">			
			
	$(document).ready(function(){
		$('.notify').click(function() {
			var options = {					
				message	: 'Loading ...',
				autoHide : true,
				timeOut : 0.5
			}
			jlbd.dialog.notify(options);
			return false;
		});
		$('.notify-1').click(function() {
			var options = {					
				message	: 'Loading ...',
				autoHide : false
			}
			jlbd.dialog.notify(options);
			return false;
		});
		$('.notify-de').click(function() {
			jlbd.dialog.deNotify();
			return false;
		});
	});
	
			</pre>
		</div>
	</div>
</div>