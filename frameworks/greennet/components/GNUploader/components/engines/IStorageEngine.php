<?php
interface IStorageEngine {
	public function store($info);
	public function remove($uuid);
}