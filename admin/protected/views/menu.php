<div id="sidebar">
	<a href="<?php echo GNRouter::createUrl("/contacts");?>" class="visible-phone"><i class="icon icon-envelope"></i> <?php echo Yii::t("admin", "Contacts")?></a>
	<ul>
		<li class="active"><a href="<?php echo GNRouter::createUrl("/contacts");?>"><i class="icon icon-envelope"></i> <span><?php echo Yii::t("admin", "Contacts")?></span></a></li>
		<li class="submenu">
			<a href="#"><i class="icon icon-th-list"></i> <span>News</span> <span class="label">3</span></a>
			<ul>
				<li><a href="<?php echo GNRouter::createUrl('/articles/categories/create')?>">New Categories</a></li>
				<li><a href="<?php echo GNRouter::createUrl('/articles/create')?>">New Article</a></li>
			</ul>
		</li>
	</ul>

</div>