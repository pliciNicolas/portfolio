<?php

require (__DIR__.'/../Entity/User.php');
require (__DIR__.'/../Entity/Share.php');
require (__DIR__.'/../Entity/Share_Webservice.php');
require (__DIR__.'/../Entity/Portfolio.php');
require (__DIR__.'/../Entity/Transaction.php');
require (__DIR__.'/../Entity/Performance_Portfolio.php');
require (__DIR__.'/../Entity/Webservice.php');
require (__DIR__.'/../Entity/User_Webservice.php');

class DashBoardController {
	
	private $transactions = null;
	
	public $tabs = null;
	public $users = null;
	
	protected function loadData() {
		$data_file = __DIR__.'/../../../../app/data.php';
		$data = include $data_file;
		
		$this->users = [];
		if (!(array_key_exists('user', $data) && is_array($data['user']) && 0<count($data['user']))) {
			throw new Exception('Cant find user in data file');
		}
		
		foreach($data['user'] as $user_item) {
			$user = new User();
			$user->set($user_item['id'],$user_item['name']);
			$this->users[$user_item['id']] = $user;
		}
		
		$webservices = [];
		if (!(array_key_exists('webservice', $data) && is_array($data['webservice']) && 0<count($data['webservice']))) {
			throw new Exception('Cant find webservice in data file');
		}
		
		foreach($data['webservice'] as $webservice_item) {
			$webservice = new Webservice();
			$webservice->set($webservice_item['id'],$webservice_item['name'], $webservice_item['url']);
			$webservices[$webservice_item['id']] = $webservice;
		}
		
		if (!(array_key_exists('user_webservice', $data) && is_array($data['user_webservice']) && 0<count($data['user_webservice']))) {
			throw new Exception('Cant find user_webservice in data file');
		}
		
		foreach($data['user_webservice'] as $user_webservice_item) {
			$user_webservice = new User_Webservice;
			$user_webservice->set($webservices[$user_webservice_item['id_webservice']], $user_webservice_item['api_key'], $user_webservice_item['token']);
			$this->users[$user_webservice_item['id_user']]->addWebservice($user_webservice);
		}
		
		$shares = [];
		if (!(array_key_exists('share', $data) && is_array($data['share']) && 0<count($data['share']))) {
			throw new Exception('Cant find share in data file');
		}
		
		foreach($data['share'] as $share_item) {
			$share = new Share();
			$share->set($share_item['id'],$share_item['name']);
			$shares[$share_item['id']] = $share;
		}
		
		
		
		if (!(array_key_exists('share_webservice', $data) && is_array($data['share_webservice']) && 0<count($data['share_webservice']))) {
			throw new Exception('Cant find share_webservice in data file');
		}
		foreach($data['share_webservice'] as $share_webservice_item) {
			Share_Webservice::add($shares[$share_webservice_item['id_share']], $webservices[$share_webservice_item['id_webservice']], $share_webservice_item['symbol']);
		}

		$portfolios = [];
		if (!(array_key_exists('share', $data) && is_array($data['share']) && 0<count($data['share']))) {
			throw new Exception('Cant find share in data file');
		}
		
		foreach($data['portfolio'] as $portfolio_item) {
			$portfolio = new Portfolio();
			$portfolio->set($portfolio_item['id'], $this->users[$portfolio_item['id_user']], $portfolio_item['name'], $portfolio_item['currency'],  $shares[$portfolio_item['share_code_benchmark']]);
			$portfolios[$portfolio_item['id']] = $portfolio;
		}
		
		$this->transactions = [];
		if (!(array_key_exists('transaction', $data) && is_array($data['transaction']) && 0<count($data['transaction']))) {
			throw new Exception('Cant find transaction in data file');
		}
		
		foreach($data['transaction'] as $transaction_item) {
			$transaction = new Transaction();
			$date = \DateTime::createFromFormat("Y-m-d", $transaction_item['date']);
			$transaction->set($transaction_item['id'], $portfolios[$transaction_item['id_portfolio']], $date, $shares[$transaction_item['id_share']], $transaction_item['portfolio_share_code'], $transaction_item['type'], $transaction_item['quantity'], $transaction_item['unit_price'], $transaction_item['fee_fixed'], $transaction_item['fee_percent']);
			if (!array_key_exists($transaction_item['date'], $this->transactions)) {
				$this->transactions[$transaction_item['date']] = [];
			}
			$this->transactions[$transaction_item['date']][$transaction_item['id']] = $transaction;
		}

		sort($this->transactions);
	}
	
	public function generate() {
		$this->tabs = [];
		$this->tabs['historic'] = [];
		$this->tabs['performance'] = [
			'byPortfolio' => [
				'portfolios' => [
					
				]
			],
			
		];
		
		$this->loadData();

		foreach($this->transactions as $date => $transactions) {
			foreach($transactions as $transaction) {
				$this->tabs['historic'][] = $transaction;
				
				if (!array_key_exists($transaction->portfolio->id, $this->tabs['performance']['byPortfolio']['portfolios'])) {
					$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id] = new Performance_Portfolio();
					$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]->portfolio = $transaction->portfolio;
				}
				
				$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]->addTransaction($transaction);
			}
		}
		
		foreach($this->tabs['performance']['byPortfolio']['portfolios'] as &$portfolio) {
			$portfolio->sortShares();
		}
	}

}