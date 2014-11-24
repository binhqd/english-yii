<!-- The template to display files available for upload -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	{% if (file.error) { %}
	
	{% } else { %}
	<div class='gallery-item' ref='{%=file.fileid%}'>
		<img src='{%=fileUri%}/fill/{%=width%}-{%=height%}/{%=file.name%}'/>
		<div class='delete'><img src='{%=assetUrl%}img/delete-gallery-item.png'/></div>
	</div>
	{% } %}
{% } %}
</script>