<?php

class Performance_Portfolio {
	protected $all_shares = [];
	
	
	public $active_shares = ['shares' => [], 'total' => []];
	public $inactive_shares = ['shares' => [], 'total' => []];
	public $total = ['cash' => 0];
	
	
	public function addTransaction(\Transaction $transaction) {
		
		if (!array_key_exists($transaction->share->id, $this->all_shares)) {
			$this->all_shares[$transaction->share->id] = new Performance_Portfolio_Share();
			$this->all_shares[$transaction->share->id]->share = $transaction->share;
		}
		
		switch($transaction->type) {
			case Transaction::BUY : 
				$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['spend'] += $transaction->price;
				$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['unit_price'] = round(
						(
							$transaction->price 
							+
							(
								$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['unit_price'] 
								* 
								$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['quantity']
							)
						) 
						/ 
						(
							$transaction->quantity 
							+ 
							$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['quantity']
						),2)
						;
				$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['quantity'] += $transaction->quantity;
				$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['last_fee_fixed'] = $transaction->fee_fixed;
				$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['last_fee_percent'] = $transaction->fee_percent;
				$this->tabs['performance']['byPortfolio']['portfolios'][$transaction->portfolio->id]['shares']['all'][$transaction->share->id]['capitalGain'] -= $transaction->price;
				break;
			case Transaction::SELL : 
				$this->all_shares[$transaction->share->id]->recieved += $transaction->price;
				$this->all_shares[$transaction->share->id]->quantity -= $transaction->quantity;
				$this->all_shares[$transaction->share->id]->last_fee_fixed = $transaction->fee_fixed;
				$this->all_shares[$transaction->share->id]->last_fee_percent = $transaction->fee_percent;
				$this->all_shares[$transaction->share->id]->capitalGain += $transaction->price;
				break;
			case Transaction::DIVIDEND : 
				$this->all_shares[$transaction->share->id]->dividend += $transaction->unit_price * $transaction->quantity;
				break;
			case Transaction::CASH : 
				$this->total['cash'] += $transaction->unit_price * $transaction->quantity;
				break;
		}
	}
	
	
	public function sortShares() {
		foreach($this->all_shares as $id_share => $share) {
			if ($share['quantity']) {
				$this->active_shares[$id_share] = $share;
			} else {
				$this->inactive_shares[$id_share] = $share;
			}
		}
	}
	
}
