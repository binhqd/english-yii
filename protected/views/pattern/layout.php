<!-- wrapper -->
<div id="wd-wrapper">

	<h2>Layouts</h2>
	<div class="wd-tab">
		<ul class="wd-item clearfix">
			<li><a href="#wd-fragment-1">Layout Home Page</a></li>
			<li><a href="#wd-fragment-2">Layout Dashboard</a></li>
			<li><a href="#wd-fragment-3">Layout Search</a></li>
		</ul>
		<div class="wd-panel">
			<div class="wd-section" id="wd-fragment-1">
				<h3>XHTML</h3>
<pre>
&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"&gt;
&lt;html xmlns="http://www.w3.org/1999/xhtml"&gt;
&lt;head&gt;
	&lt;meta http-equiv="Content-Type" content="text/html;charset=utf-8" /&gt;
	&lt;title&gt; Layout Justlook &lt;/title&gt;
	&lt;link href="css/reset.css" media="screen" rel="stylesheet" type="text/css" /&gt;
	&lt;link href="css/common.css" media="screen" rel="stylesheet" type="text/css" /&gt;
	&lt;!--[if lte IE 6]&gt;
		&lt;style type="text/css" media="all"&gt;@import "css/ie6.css";&lt;/style&gt;
	&lt;![endif]--&gt;
	&lt;!--[if IE 7]&gt;
		&lt;style type="text/css" media="all"&gt;@import "css/ie7.css";&lt;/style&gt;
	&lt;![endif]--&gt;
	&lt;!--[if IE 8]&gt;
		&lt;style type="text/css" media="all"&gt;@import "css/ie8.css";&lt;/style&gt;
	&lt;![endif]--&gt;
	&lt;!--[if IE 9]&gt;
		&lt;style type="text/css" media="all"&gt;@import "css/ie9.css";&lt;/style&gt;
	&lt;![endif]--&gt;
&lt;/head&gt;
&lt;body&gt;
	&lt;div id="wd-head-container"&gt;
		&lt;div class="wd-center"&gt;
			Header
		&lt;/div&gt;
	&lt;/div&gt;
	&lt;div id="wd-content-container"&gt;
		&lt;div class="wd-center"&gt;
			&lt;div class="wd-header-line"&gt;
				&lt;div class="wd-section"&gt;
					Header Line
				&lt;/div&gt;
			&lt;/div&gt;	
			&lt;div class="wd-left-content"&gt;
				&lt;div class="wd-section"&gt;
					Left Content
				&lt;/div&gt;	
			&lt;/div&gt;
			&lt;div class="wd-right-content"&gt;
				&lt;div class="wd-section"&gt;
					Right Content
				&lt;/div&gt;
			&lt;/div&gt;
			&lt;div class="wd-main-content"&gt;
				&lt;div class="wd-section"&gt;
					Main Content
				&lt;/div&gt;	
			&lt;/div&gt;
			&lt;div class="wd-extras"&gt;
				&lt;div class="wd-section"&gt;
					Extras
				&lt;/div&gt;	
			&lt;/div&gt;
		&lt;/div&gt;
	&lt;/div&gt;
	&lt;div id="wd-footer-container"&gt;
		&lt;div class="wd-center"&gt;
			&copy Copyright 2011 Justlook
		&lt;/div&gt;
	&lt;/div&gt;
	&lt;!-- Start Script --&gt;
		&lt;script src="js/jquery-1.5.1.min.js" type="text/javascript"&gt;&lt;/script&gt;
		&lt;!--[if lt IE 7]&gt;
			&lt;script src="js/IE7.js"&gt;&lt;/script&gt;
		&lt;![endif]--&gt;
	&lt;!-- End Script --&gt;
&lt;/body&gt;
&lt;/html&gt;
</pre>					
				<h3>CSS in file common.css</h3>
<pre>
/*----- Header -----*/
.wd-center{margin:0 auto;width:958px;font-size:0.75em}
#wd-head-container{background-color:#0F69A8;color:#fff;min-width:980px;}
#wd-head-container .wd-center{position:relative;padding:20px}
/*----- Content -----*/
#wd-content-container{background-color:#eee;min-width:980px}
#wd-content-container .wd-center{padding:15px 0}
.wd-left-content{float:left;width:200px;padding-right:10px;vertical-align:top;}
.wd-right-content{float:right;width:240px;padding-left:10px;vertical-align:top}
.wd-main-content{vertical-align:top;overflow:hidden;height:100%}
.wd-extras,.wd-header-line{clear:both;margin:15px 0}
.wd-section{background-color:#fff;padding:10px}
/*----- Footer -----*/
#wd-footer-container{clear:left;color:#fff;background-color:#0F69A8;min-width:980px}
#wd-footer-container .wd-center{padding:20px;height:1%}
</pre>				
				<h3>js in file IE7.js</h3>
<pre>
<a href="js/IE7.js">Browser support smaller version of IE7</a>
</pre>
			</div>
			<div class="wd-section" id="wd-fragment-2">
				<h3>XHTML</h3>
				<h3>CSS in file common.css</h3>
			</div>
			<div class="wd-section" id="wd-fragment-3">
				<h3>XHTML</h3>
				<h3>CSS in file common.css</h3>
			</div>
		</div>
	</div>

</div>
<!-- wrapper.end -->

