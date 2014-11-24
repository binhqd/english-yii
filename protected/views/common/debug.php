<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
	<title>[:. Layout Full Background .:]</title>
	<link rel="stylesheet" href="css/common.css" type="text/css" media="screen" />
	<!--[if lte IE 6]>
		<style type="text/css" media="all">@import "css/ie6.css";</style>
	<![endif]-->
	<!--[if IE 7]>
		<style type="text/css" media="all">@import "css/ie7.css";</style>
	<![endif]-->
	<!--[if IE 8]>
		<style type="text/css" media="all">@import "css/ie8.css";</style>
	<![endif]-->
	<!--[if IE 9]>
		<style type="text/css" media="all">@import "css/ie9.css";</style>
	<![endif]-->
</head>
<style>
/*----- reset -----*/
html, body, div, span, h1, h2, h3, h4, h5, h6, p, blockquote, a, address, em, img, strong, dl, dt, dd, ol, ul, li, fieldset,
form, label, legend, table, thead, tbody, tfoot, tr, th, td{border:0;font-family:inherit;font-size:100%;margin:0;outline:0;padding:0;}
:focus{outline:0;}
ol, ul{list-style:none;}
/*----- common -----*/

h1 {
	font: normal 18pt "Verdana";
	color: #f00;
	margin-bottom: .5em;
}

h2 {
	font: normal 12pt "Verdana";
	color: #800000;
	margin-bottom: .5em;
}

h3 {
	font: bold 11pt "Verdana";
}

pre {
	font: normal 10px Menlo, Consolas, "Lucida Console";
}

pre span.error {
	display: block;
	background: #fce3e3;
}

pre span.ln {
	color: #999;
	padding-right: 0.5em;
	border-right: 1px solid #ccc;
}

pre span.error-ln {
	font-weight: bold;
}

.container {
	margin: 5px;
}

.version {
	color: gray;
	font-size: 8pt;
	border-top: 1px solid #aaa;
	padding-top: 1em;
	margin-bottom: 1em;
}

.message {
	color: #000;
	padding: 1em;
	font-size: 11pt;
	background: #f3f3f3;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	margin-bottom: 1em;
	line-height: 160%;
}

.source {
	margin-bottom: 1em;
}

.code pre {
	background-color: #ffe;
	margin: 0.5em 0;
    padding: 0.5em;
    line-height: 125%;
    border: 1px solid #eee;
}

.source .file {
	margin-bottom: 1em;
	font-weight: bold;
}

.traces {
	margin: 5px 0px 5px 0px;
}

.trace {
	margin: 0.5em 0;
	padding: 0.5em;
}

.trace.app {
	border: 1px dashed #c00;
}

.trace .number {
	text-align: right;
	width: 2em;
	padding: 0.5em;
}

.trace .content {
	padding: 0.5em;
}

.trace .plus,
.trace .minus {
	display:inline;
	vertical-align:middle;
	text-align:center;
	border:1px solid #000;
	color:#000;
	font-size:10px;
	line-height:10px;
	margin:0;
	padding:0 1px;
	width:10px;
	height:10px;
}

.trace.collapsed .minus,
.trace.expanded .plus,
.trace.collapsed pre {
	display: none;
}

.trace-file {
	cursor: pointer;
	padding: 0.2em;
}

.trace-file:hover {
	background: #f0ffff;
}

body{background-color:#fff;color:#000;font:normal normal 100% Arial, Helvetica, sans-serif;line-height:1.5em;font-size:11px;}
input, select, textarea{color:#38444f;font:normal normal 1em Arial, Helvetica, sans-serif;}
a:link, a:visited{color:#114285;text-decoration:none}
a:hover{color:#94c03d}
.clear{clear:both}
.wd-center{margin:0 auto;width:100%}
a.wd-view-detail{color:#65b200;font-size:0.95em;float:right;padding:3px 0 3px 18px;background:url(../img/front/arrow_white_green.png) no-repeat scroll 0 8px}
a:hover.wd-view-detail{color:#5D5D5D}
/*----- Header -----*/
#wd-head-container{background-color:#339900;color:#fff;min-width:100%;}
#wd-head-container .wd-center{position:relative;padding:20px}
/*----- Content -----*/
#wd-content-container{background-color:#eee;min-width:100%}
.wd-left-content{float:left;width:200px;padding-right:10px;vertical-align:top;}
.wd-right-content{float:right;width:400px;padding-left:10px;vertical-align:top;background:#fff;overflow:auto;font-size:10px;}
.wd-main-content{vertical-align:top;overflow:auto;height:100%;background:#fff;}
.wd-extras,.wd-header-line{margin:15px 0;clear:both}
.wd-section{background-color:#fff;padding:10px}
.file-indicator {font-size: 13px;font-weight:bold;display:block;font-family:Tahoma !important;}
.stack-trace-file {font-size:10px;display:block;padding:3px;}
.odd {background: #eee;}

#tabs {margin:0;padding:3px;list-style: none;background:#F9EBC1;clear:both;display:block;width:100%;min-height:24px;margin-bottom:5px;}
#tabs li.tab-item {float: left;padding:4px 4px 4px 4px;margin-right:4px;background: #FFF;font-weight:bold;}
#tabs li.tab-item:hover {background: #c0c1fa;cursor:pointer;}
.clear {clear:both;}
.debugTab h2.title {font-size: 14px; font-weigth:bold;margin:5px;}

.watches .code-block {height:18px;overflow:hidden;}
.watches .string {height:auto !important;overflow:scroll !important;}
.watches .minus {display:none;}
.watches .minus, .watches .plus {cursor: pointer;}

/*----- Footer -----*/
</style>
<?php 
$backtrace = debug_backtrace();
?>
<body>
	<div id="wd-content-container">
		<div class="wd-center">
			<div class="wd-right-content" id="rightContent">
				<ul id='tabs'>
					<li class='tab-item tab-file-trace'>File Trace</li>
					<li class='tab-item yii-file-trace'>Yii Trace</li>
				</ul>
				<div class='clear'> </div>
				<div class='debugTab' id='file-trace-container'>
				<h2 class='title'>File traces:</h2>
				<ul>
				<?php
				$cnt = 0; 
				foreach ($backtrace as $item):
				
				if ($cnt == 0) {$cnt++;continue;};
				?>
					<?php if (isset($item['file']) && isset($item['line'])):
					$cnt++;
					$class = $cnt % 2 ? "even" : "odd";
					?>
					<li class='<?php echo $class?>'><span class='stack-trace-file'><?php echo "{$item['file']} ({$item['line']})"?></span></li>
					<?php else:?>
					<li class='sub-item'><span class='stack-trace-file'><?php echo "{$item['class']}{$item['type']}{$item['function']}"?></span></li>
					<?php endif;?>
				<?php endforeach;?>
				</ul>
				
				<div class='debugTab trace watches'>
					<h2 class='title'>Watches:</h2>
					<?php 
					$cnt = 0;
					foreach (JLDebugger::$watch as $item):
					$hasCode = in_array(gettype($item[0]), array("array", "object"));
					?>
					#<?php echo ++$cnt; ?>
					
					<div class='code-block <?php echo !$hasCode ? "string" : ""?>'>
						<?php if($hasCode): ?>
							<div class="plus">+</div>
							<div class="minus">–</div>
						<?php endif; ?>
						<?php CVarDumper::dump($item[0]);?>
						<?php if($item[2]): ?>
						<?php echo $item[1];?>
						<?php endif;?>
					</div>
					<div style="border-bottom:1px dashed #444;height:2px;"></div>
					<?php endforeach;?>
				</div>
				</div>
			</div>
			<div class="wd-main-content" id='mainContent'>
				<span class='file-indicator'><?php echo "{$backtrace[1]['file']} ({$backtrace[1]['line']})"?></span>
				<br/>
				<?php CVarDumper::dump($obj);?>
			</div>
		</div>
	</div>
	
	<script src='/justlook/js/jquery-1.7.1.min.js'></script>
	<script language='javascript'>
	$(document).ready(function() {
		$('#mainContent').height($(window).height());
		$('#rightContent').height($(window).height());

		$('.watches .plus').click(function() {
			var parent = $(this).parent();
			parent.css('height', 'auto');

			// hide plus image
			$(this).hide();
			parent.find('.minus').css('display', 'inline');
		});

		$('.watches .minus').click(function() {
			var parent = $(this).parent();
			parent.css('height', '15px');

			// hide plus image
			$(this).hide();
			parent.find('.plus').css('display', 'inline');
		});
	});
	</script>
</body>
</html>