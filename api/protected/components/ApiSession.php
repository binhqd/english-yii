<?php
class ApiSession extends CHttpSession {
	public function open() {
		watch('here');
		parent::open();
	}
}