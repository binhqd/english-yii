<?php
class NamespaceGalleryWidget extends CWidget {
	public $namespaceID;
	
	public function init() {
		
	}
	public function run() {
		$this->render('namespace-gallery');
	}
}