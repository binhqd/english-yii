<?php
/**
 * This interface is for caching data
 * @author BinhQD
 *
 */
interface IGNKeyValueCache {
	public function getItems();
	public function isCached($key);
	public function store($key, $value);
	public function retrieve($key);
	public function delete($key);
}