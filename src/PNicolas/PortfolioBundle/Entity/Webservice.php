<?php


class Webservice {
	
	public $id = null;
	public $name = null;
	public $url = null;
	
	
	public function set($id, $name, $url) {
		$this->id = $id;
		$this->name = $name;
		$this->url = $url;
	}
}