<?php
/**
 * Description of User
 *
 */
class User {
	public $id = null;
	public $name = null;
	
	public $shares = [];
	public $total = null;
	public $portfolios = [];
	public $historic = [];
        
	public function __construct() {
		$this->total = new User_Share_Line();
	}
	
	/**
	 * Set object
	 * @param int $id
	 * @param string $name
	 */
	public function set($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
	
	/**
	 * Add a new transaction
	 * @param \Transaction $transaction
	 */
	public function addTransaction(\Transaction $transaction) {
		if (!array_key_exists($transaction->share->id, $this->shares)) {
			$this->shares[$transaction->share->id] = new User_Share();
		}
		
		$this->shares[$transaction->share->id]->addTransaction($transaction);
		switch($transaction->type) {
			case Transaction::TYPE_BUY : 
				// Total
				$this->total->addBuy($transaction->unit_price, $transaction->quantity, $transaction->fee_fixed, $transaction->fee_percent, $transaction->date);

				break;

			case Transaction::TYPE_SELL : 
				
				// Total
				$this->total->addSell($transaction->unit_price, $transaction->quantity, $transaction->fee_fixed, $transaction->fee_percent, $transaction->date);

				break;

			case Transaction::TYPE_DIVIDEND : 
				$dividend = $transaction->unit_price * $transaction->quantity;

				// Total
				$this->total->addDividend($dividend);

				break;
		}
		
		$this->historic[] = $transaction;
	}
}
