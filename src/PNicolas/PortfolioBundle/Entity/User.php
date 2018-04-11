<?php


class User {
	
	public $id = null;
	public $name = null;
	
	public function set($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
}
