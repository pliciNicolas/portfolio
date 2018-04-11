<?php

require (__DIR__.'/../Entity/User.php');
require (__DIR__.'/../Entity/Share.php');
require (__DIR__.'/../Entity/Portfolio.php');
require (__DIR__.'/../Entity/Transaction.php');

class DashBoardController {
	
	private $transactions = null;
	
	private $tabs = null;
	
	protected function loadData() {
		$data_file = __DIR__.'/../../../../app/data.php';
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
		
		
		$shares = [];
		if (!(array_key_exists('share', $data) && is_array($data['share']) && 0<count($data['share']))) {
			throw new Exception('Cant find share in data file');
		}
		
		foreach($data['share'] as $share_item) {
			$share = new Share();
			$share->set($share_item['id'],$share_item['name']);
			$shares[$share_item['id']] = $share;
		}
		
		$portfolios = [];
		if (!(array_key_exists('share', $data) && is_array($data['share']) && 0<count($data['share']))) {
			throw new Exception('Cant find share in data file');
		}
		
		foreach($data['portfolio'] as $portfolio_item) {
			$portfolio = new Portfolio();
			$portfolio->set($portfolio_item['id'], $users[$portfolio_item['id_user']], $portfolio_item['name'], $portfolio_item['webservice'], $portfolio_item['webservice_token'], $shares[$portfolio_item['share_code_benchmark']]);
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
		
		$this->loadData();
		
		foreach($this->transactions as $date => $transactions) {
			foreach($transactions as $transaction) {
				$this->tabs['historic'][] = $transaction;
			}
		}
		
echo '<pre>';
//var_dump($users);
//var_dump($shares);
//var_dump($portfolios);
//var_dump($this->transactions);
var_dump($this->tabs);
	}
}