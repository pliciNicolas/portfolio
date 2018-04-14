<?php


class Portfolio {
	
	public $id = null;
	public $user = null;
	public $name = null;
	public $currency = null;
	public $share_code_benchmark = null;
	
	
	public function set($id, \User $user, $name, $currency, $share_code_benchmark) {
		$this->id = $id;
		$this->user = $user;
		$this->name = $name;
		$this->currency = $currency;
		$this->share_code_benchmark = $share_code_benchmark;
	}
}