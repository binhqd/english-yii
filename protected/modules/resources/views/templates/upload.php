<!-- The template to display files available for download -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file;file=o.files[i]; i++) { %}
<div class='gallery-item template-upload fade' ref = '{%=file.name%}'>
	<div class="preview"><span class="fade"></span></div>
	<div class="btnCancel"><img src="/assets/default/img/delete-gallery-item.png"></div>
	<div class='overlay'>
		<label></label>
		<div class="progress progress-striped active">
			<div class="bar" style="width: 0%;">0%</div>
		</div>
	</div>
	<div class='start' style='display:none'><button class="btn btn-primary" style='display:none'></button></div>
	<div class="cancel" style='display:none'>
		<button class="btn btn-warning"></button>
	</div>
</div>
{% } %}
</script>