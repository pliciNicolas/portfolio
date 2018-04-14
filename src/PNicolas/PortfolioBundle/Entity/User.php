<?php


class User {
	
	public $id = null;
	public $name = null;
	
	public $webservice= [];
	
	public function set($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
	
	public function addWebservice(\User_Webservice $webservice) {
		$this->webservice[$webservice->webservice->id] = $webservice;
	}
}
