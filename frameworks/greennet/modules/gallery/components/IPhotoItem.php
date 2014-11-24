<?php
interface IPhotoItem {
	/**
	 * This method is used to load related info of an image
	 */
	public function loadRelatedInfo($loadPoster = true, $loadLikes = true, $loadComments = 5, $photo = null);
	public function cleanUp($uploader);
} 