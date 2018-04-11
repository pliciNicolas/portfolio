<?php


class Portfolio {
	
	public $id = null;
	public $user = null;
	public $name = null;
	public $webservice = null;
	public $webservice_token = null;
	public $share_code_benchmark = null;
	
	public function set($id, \User $user, $name, $webservice, $webservice_token, $share_code_benchmark) {
		$this->id = $id;
		$this->user = $user;
		$this->name = $name;
		$this->webservice = $webservice;
		$this->webservice_token = $webservice_token;
		$this->share_code_benchmark = $share_code_benchmark;
	}
}