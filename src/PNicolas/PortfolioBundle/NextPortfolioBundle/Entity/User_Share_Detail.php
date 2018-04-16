<?php

/**
 * Description of User_Share_Detail
 *
 */
class User_Share_Detail {
	public $portfolios = [];
	public $total = null;
	
	public function addTransaction(\Transaction $transaction) {
		$this->addTransactionData($transaction->portfolio, $transaction->share, $transaction->type, $transaction->quantity, $transaction->unit_price, $transaction->fee_fixed, $transaction->fee_percent);
	}
	
	public function addTransactionData(\Portfolio $portfolio, \Share $share, $type, $quantity, $unit_price, $fee_fixed, $fee_percent) {
		if (is_null($this->total)) {
			$this->total = new User_Share();
		}
		
		if (!array_key_exists($portfolio->id, $this->portfolios)) {
			$this->portfolios[$portfolio->id] = new User_Share();
			$this->portfolios[$portfolio->id]->share = $share;
		}
		
		switch($type) {
//			case Transaction::TYPE_BUY : 
//				$this->all_shares[$transaction->share->id]->spend += $transaction->price;
//				$this->all_shares[$transaction->share->id]->unit_price = round(
//						(
//							$transaction->price 
//							+
//							(
//								$this->all_shares[$transaction->share->id]->unit_price 
//								* 
//								$this->all_shares[$transaction->share->id]->quantity
//							)
//						) 
//						/ 
//						(
//							$transaction->quantity 
//							+ 
//							$this->all_shares[$transaction->share->id]->quantity
//						),2)
//						;
//				$this->all_shares[$transaction->share->id]->quantity += $transaction->quantity;
//				$this->all_shares[$transaction->share->id]->last_fee_fixed = $transaction->fee_fixed;
//				$this->all_shares[$transaction->share->id]->last_fee_percent = $transaction->fee_percent;
//				break;
			case Transaction::TYPE_SELL : 
				// Portfolio
				
				// Total
				
//				$this->all_shares[$transaction->share->id]->recieved += $transaction->price;
//				$this->all_shares[$transaction->share->id]->quantity -= $transaction->quantity;
//				$this->all_shares[$transaction->share->id]->last_fee_fixed = $transaction->fee_fixed;
//				$this->all_shares[$transaction->share->id]->last_fee_percent = $transaction->fee_percent;
//				$this->all_shares[$transaction->share->id]->capitalGain += ($transaction->unit_price - $this->all_shares[$transaction->share->id]->unit_price) * $transaction->quantity - $transaction->fee;
				break;
//public $share = null;
//public $spend = 0;
//public $recieved = 0;
//public $quantity = 0;
//public $unit_price = 0;
//public $dividend_price = 0;
//public $dividend_count = 0;
//public $dividend_average = 0;
//public $dividend_percent = 0;
//public $capitalGain_price = 0;
//public $capitalGain_percent = 0;
//public $gain_price = 0;
//public $gain_percent = 0;
			case Transaction::TYPE_DIVIDEND : 
				// Portfolio
				$this->portfolios[$portfolio->id]->dividend_price += $unit_price * $quantity;
				$this->portfolios[$portfolio->id]->dividend_count += 1;
				$this->portfolios[$portfolio->id]->dividend_average = $this->total->dividend_price / $this->total->dividend_count;
				$this->portfolios[$portfolio->id]->gain_price = $this->total->dividend_price + $this->total->capitalGain_price;
				$this->portfolios[$portfolio->id]->gain_percent = $this->total->gain_price / $this->total_spend;

				// Total
				$this->total->dividend_price += $unit_price * $quantity;
				$this->total->dividend_count += 1;
				$this->total->dividend_average = $this->total->dividend_price / $this->total->dividend_count;
				$this->total->gain_price = $this->total->dividend_price + $this->total->capitalGain_price;
				$this->total->gain_percent = $this->total->gain_price / $this->total_spend;
				break;
		}
	}
}
