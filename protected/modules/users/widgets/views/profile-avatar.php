<div class="wd-person-img">
	<a class="wd-main-image" href="#">
		<img src="<?php echo GNRouter::createUrl("/upload/user-photos/".$user->hexID."/fill/193-193/" . $user->profile->image)?>" alt="<?php echo $user->displayname?>"/>
	</a>
	<ul class="wd-gallery-1">
		<?php foreach ($avatars as $item):?>
		<li class="wd-mlb-img wd-first-elm">
			<a href="#" class="wd-thumb-img">
				<img src="<?php echo GNRouter::createUrl("/upload/user-photos/".$user->hexID."/fill/91-91/" . $item->image)?>"/> 
			</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>