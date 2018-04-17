<?php


/**
 * Description of Portfolio
 *
 */
class Portfolio {
	
	public function __construct() {
		$this->loadData();
	}
	
	protected function loadData() {
		$data_file = __DIR__.'/../app/data.php';
		$data = include $data_file;
		
		$users = [];
		if (!(array_key_exists('user', $data) && is_array($data['user']) && 0<count($data['user']))) {
			throw new Exception('Cant find user in data file');
		}
		foreach($data['user'] as $user_item) {
			$user = new User();
			$user->set($user_item['id'],$user_item['name']);
			$users[$user_item['id']] = $user;
		}
		
		// Load only first user
		$user = array_shift($users);
		
		$webservices = [];
		if (!(array_key_exists('webservice', $data) && is_array($data['webservice']) && 0<count($data['webservice']))) {
			throw new Exception('Cant find webservice in data file');
		}
		foreach($data['webservice'] as $webservice_item) {
			$webservice = new Webservice();
			$webservice->set($webservice_item['id'], $webservice_item['name'], $webservice_item['url']);
			$webservices[$webservice_item['id']] = $webservice;
		}
//		
//		$webservices = [];
//		if (!(array_key_exists('webservice', $data) && is_array($data['webservice']) && 0<count($data['webservice']))) {
//			throw new Exception('Cant find webservice in data file');
//		}
//		foreach($data['webservice'] as $webservice_item) {
//			$webservice = new Webservice();
//			$webservice->set($webservice_item['id'], $webservice_item['name'], $webservice_item['url']);
//			$webservices[$webservice_item['id']] = $webservice;
//		}
		
		$markets = [];
		if (!(array_key_exists('market', $data) && is_array($data['market']) && 0<count($data['market']))) {
			throw new Exception('Cant find market in data file');
		}
		foreach($data['market'] as $market_item) {
			$market = new Market();
			$market->set($market_item['id'], $market_item['name'], $market_item['openHours'], $webservices[$market_item['id_webservice']]);
			$markets[$market_item['id']] = $market;
		}
	}
}
