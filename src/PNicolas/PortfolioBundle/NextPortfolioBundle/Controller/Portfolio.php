<?php


/**
 * Description of Portfolio
 *
 */
class Portfolio {
	
	public $user = null;
	
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
		$this->user = array_shift($users);
		
		$webservices = [];
		if (!(array_key_exists('webservice', $data) && is_array($data['webservice']) && 0<count($data['webservice']))) {
			throw new Exception('Cant find webservice in data file');
		}
		foreach($data['webservice'] as $webservice_item) {
			$webservice = new Webservice();
			$webservice->set($webservice_item['id'], $webservice_item['name'], $webservice_item['url']);
			$webservices[$webservice_item['id']] = $webservice;
		}
		
		$user_webservices = [];
		if (!(array_key_exists('user_webservice', $data) && is_array($data['user_webservice']) && 0<count($data['user_webservice']))) {
			throw new Exception('Cant find user_webservice in data file');
		}
		foreach($data['user_webservice'] as $user_webservicee_item) {
			$user_webservice = new User_Webservice();
			$user_webservice->set($webservices[$user_webservicee_item['id_webservice']], $user_webservicee_item['token'], $user_webservicee_item['api_key']);
                        if (!array_key_exists($user_webservicee_item['id_user'], $user_webservices)) {
                            $user_webservices[$user_webservicee_item['id_user']] = [];
                        }
			$user_webservices[$user_webservicee_item['id_user']][$user_webservicee_item['id_webservice']] = $user_webservice;
		}
		
		$markets = [];
		if (!(array_key_exists('market', $data) && is_array($data['market']) && 0<count($data['market']))) {
			throw new Exception('Cant find market in data file');
		}
		foreach($data['market'] as $market_item) {
			$market = new Market();
			$market->set($market_item['id'], $market_item['name'], $market_item['openHours'], $webservices[$market_item['id_webservice']]);
			$markets[$market_item['id']] = $market;
		}
		
		$shares = [];
		if (!(array_key_exists('share', $data) && is_array($data['share']) && 0<count($data['share']))) {
			throw new Exception('Cant find share in data file');
		}
		foreach($data['share'] as $share_item) {
			$share = new Share();
			$share->set($share_item['id'], $share_item['name'], $markets[$share_item['id_market']] );
			$shares[$share_item['id']] = $share;
		}
		
		$shares_webservice = [];
		if (!(array_key_exists('shares_webservice', $data) && is_array($data['shares_webservice']) && 0<count($data['shares_webservice']))) {
			throw new Exception('Cant find shares_webservice in data file');
		}
		foreach($data['shares_webservice'] as $share_webservice_item) {
			$share_webservice = new Share_Webservice();
			$share_webservice->set($webservices[$share_webservice_item['id_webservice']], $shares[$share_webservice_item['id_share']], $share_webservice_item['symbol'] );
                        if (!array_key_exists($share_webservice_item['id_webservice'], $shares_webservice)) {
                            $shares_webservice[$share_webservice_item['id_webservice']] = [];
                        }
			$shares_webservice[$share_webservice_item['id_webservice']][$share_webservice_item['id_share']] = $share_webservice;
		}
		
		$portfolios = [];
		if (!(array_key_exists('portfolio', $data) && is_array($data['portfolio']) && 0<count($data['portfolio']))) {
			throw new Exception('Cant find portfolio in data file');
		}
		foreach($data['portfolio'] as $portfolios_item) {
			$portfolio = new Portfolio();
			$portfolio->set($portfolios_item['id'], $portfolios_item['name'], $portfolios_item['fee_fixed'], $portfolios_item['fee_percent'], $portfolios_item['currency'] );
			$portfolios[$portfolios_item['id']] = $portfolio;
		}
                
		$transactions = [];
		if (!(array_key_exists('transaction', $data) && is_array($data['transaction']) && 0<count($data['transaction']))) {
			throw new Exception('Cant find transaction in data file');
		}
		foreach($data['transaction'] as $transaction_item) {
			$transaction = new Transaction();
                        $date = \DateTime::createFromFormat("Y-m-d", $transaction_item['date']);
			$transaction->set($transaction_item['id'], $portfolios[$transaction_item['id_portfolio']], $date, $shares[$transaction_item['id_share']], $transaction_item['quantity'], $transaction_item['unit_price'], $transaction_item['fee_fixed'], $transaction_item['fee_percent']);
			$transactions[$transaction_item['id']] = $transaction;
		}
                

	}
}
