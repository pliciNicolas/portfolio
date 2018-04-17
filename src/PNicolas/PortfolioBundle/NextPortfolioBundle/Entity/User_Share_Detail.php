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
			case Transaction::TYPE_BUY : 
				
				// Portfolio
				$this->portfolios[$portfolio->id]->addBuy($unit_price, $quantity, $fee_fixed, $fee_percent);
				
				// Total
				$this->total->addBuy($unit_price, $quantity, $fee_fixed, $fee_percent);

				break;

			case Transaction::TYPE_SELL : 
				// Portfolio
				$this->portfolios[$portfolio->id]->addSell($unit_price, $quantity, $fee_fixed, $fee_percent);
				
				// Total
				$this->total->addSell($unit_price, $quantity, $fee_fixed, $fee_percent);

				break;

			case Transaction::TYPE_DIVIDEND : 
				$dividend = $unit_price * $quantity;
				
				// Portfolio
				$this->portfolios[$portfolio->id]->addDividend($dividend);

				// Total
				$this->total->addDividend($dividend);

				break;
		}
	}
}
