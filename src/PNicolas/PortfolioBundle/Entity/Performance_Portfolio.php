<?php
require (__DIR__.'/../Entity/Performance_Portfolio_Share.php');
require (__DIR__.'/../Entity/Performance_Portfolio_Share_Inactive.php');

class Performance_Portfolio {
	protected $all_shares = [];
	
	public $portfolio = null;
	public $active_shares = [];
	public $inactive_shares = ['share' => [], 'total' => null];
	public $total = ['cash' => 0];
	
	
	public function addTransaction(\Transaction $transaction) {
		
		if (!array_key_exists($transaction->share->id, $this->all_shares)) {
			$this->all_shares[$transaction->share->id] = new Performance_Portfolio_Share();
			$this->all_shares[$transaction->share->id]->share = $transaction->share;
		}
		
		switch($transaction->type) {
			case Transaction::BUY : 
				$this->all_shares[$transaction->share->id]->spend += $transaction->price;
				$this->all_shares[$transaction->share->id]->unit_price = round(
						(
							$transaction->price 
							+
							(
								$this->all_shares[$transaction->share->id]->unit_price 
								* 
								$this->all_shares[$transaction->share->id]->quantity
							)
						) 
						/ 
						(
							$transaction->quantity 
							+ 
							$this->all_shares[$transaction->share->id]->quantity
						),2)
						;
				$this->all_shares[$transaction->share->id]->quantity += $transaction->quantity;
				$this->all_shares[$transaction->share->id]->last_fee_fixed = $transaction->fee_fixed;
				$this->all_shares[$transaction->share->id]->last_fee_percent = $transaction->fee_percent;
				break;
			case Transaction::SELL : 
				$this->all_shares[$transaction->share->id]->recieved += $transaction->price;
				$this->all_shares[$transaction->share->id]->quantity -= $transaction->quantity;
				$this->all_shares[$transaction->share->id]->last_fee_fixed = $transaction->fee_fixed;
				$this->all_shares[$transaction->share->id]->last_fee_percent = $transaction->fee_percent;
				$this->all_shares[$transaction->share->id]->capitalGain += ($transaction->unit_price - $this->all_shares[$transaction->share->id]->unit_price) * $transaction->quantity - $transaction->fee;
				break;
			case Transaction::DIVIDEND : 
				$this->all_shares[$transaction->share->id]->dividend += $transaction->unit_price * $transaction->quantity;
				break;
			case Transaction::CASH : 
				$this->total['cash'] += $transaction->unit_price * $transaction->quantity;
				break;
		}
		$this->all_shares[$transaction->share->id]->totalGain = $this->all_shares[$transaction->share->id]->dividend + $this->all_shares[$transaction->share->id]->capitalGain;
	}
	
	
	public function sortShares() {
		$this->inactive_shares['total'] = new Performance_Portfolio_Share_Inactive();
		foreach($this->all_shares as $id_share => $share) {
			if ($share->quantity) {
				$this->active_shares[$id_share] = $share;
			} else {
				$inactive_share = new Performance_Portfolio_Share_Inactive();
				$inactive_share->set($share);
				$this->inactive_shares['shares'][$id_share] = $inactive_share;
				$this->inactive_shares['total']->spend += $inactive_share->spend;
				$this->inactive_shares['total']->dividend += $inactive_share->dividend;
				$this->inactive_shares['total']->capitalGain += $inactive_share->capitalGain;
				$this->inactive_shares['total']->totalGain += $inactive_share->totalGain;
				$this->inactive_shares['total']->overAll = round(($this->inactive_shares['total']->totalGain) / $this->inactive_shares['total']->spend*100, 2);
			}
		}
	}
	
}
