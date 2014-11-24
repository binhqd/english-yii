<?php foreach($items as $item):?>
<div class='gallery-item' ref='<?php echo IDHelper::uuidFromBinary($item->id, true)?>'>
	<img src='<?php echo $this->uri?>/fill/<?php echo $width.'-'.$height;?>/<?php echo $item->image?>'/>
	<?php // if ($this->showDeleteButton):?>
	<div class='delete'><img src='<?php echo $this->assetUrl?>img/delete-gallery-item.png'/></div>
	<?php // endif;?>
</div>
<?php endforeach;?>
