<?php


class User_Webservice {
	
	public $webservice = null;
	public $api_key = null;
	public $token = null;
	
	public $url =null;
	
	
	public function set(\Webservice $webservice, $api_key, $token) {
		$this->webservice = $webservice;
		$this->api_key = $api_key;
		$this->token = $token;
		
		$this->url = str_replace(['@token@', '@api_key@'], [$this->token, $this->api_key], $this->webservice->url);
	}
}