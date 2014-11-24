<!-- The template to display files available for upload -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<div class='gallery-item' ref='<?php echo IDHelper::uuidFromBinary($item->id, true)?>'>
		<img src='<?php echo $this->uri?>/fill/150-150/{%=file.name%}'/>
		<div class='delete'><img src='<?php echo $this->assetUrl?>img/delete-gallery-item.png'/></div>
	</div>
{% } %}
</script>