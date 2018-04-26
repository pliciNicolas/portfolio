<?php

/**
 * Description of User_Share_Line
 *
 */
class User_Share_Line {
	
	public $share = null;
	public $portfolio = null;
	
	public $spend = 0;
	public $recieved = 0;
	public $balance = 0;

	
	// Current capital
	public $quantity = 0; // Current quantity
	public $unit_price = 0: // Current unit price
	public $capital = 0; // $quantity * $unit_price	
	
	// Date
	protected $first_buying_date = null; // Last first buying date
	protected $holding = 0; // Last holding in second
	
	// Dividend
	public $dividend_price = 0; // Amount of dividend
	public $dividend_count = 0; // Number of dividend
	public $dividend_average = 0; // Average of dividend
	public $dividend_percent = 0;
	protected $dividend_capital = 0; // Usefull to define percent
	public $dividend_per_action_per_year = 0;
	
	// Capital Gain
	public $capitalGain_price = 0; // Sum of buying / sell share
	public $capitalGain_percent = 0;
	
	// Balance
	public $balance = 0; // Sum of all add and minus (Buying + Sell + Dividend)
	public $gain_price = 0;
	public $gain_percent = 0;
	
	/**
	 * Add a dividend operation
	 * @param float $dividend
	 */
	public function addDividend($dividend) {
		$this->dividend_price += $dividend;
		$this->dividend_count += 1;
		$this->dividend_average = round($this->dividend_price / $this->dividend_count,2);
		
		$this->recieved += $dividend;
		$this->updateGain();
	}

	/**
	 * Add sell operation
	 * @param float $unit_price
	 * @param int $quantity
	 * @param float $fee_fixed
	 * @param float $fee_percent
	 */
	public function addSell($unit_price, $quantity, $fee_fixed, $fee_percent) {
		
		$fee = $fee_fixed + round($fee_percent * $quantity * $unit_price, 2);
		$price = ($unit_price * $quantity) - $fee;
		
		$this->recieved += $price;
		$this->quantity -= $quantity;
		$this->capitalGain_price += ($unit_price - $this->unit_price) * $quantity - $fee; 
		if ($this->unit_price && $this->quantity) {
			$this->capitalGain_percent = $this->capitalGain_price / ($this->unit_price * $this->quantity); 
		}
		
		$this->capital = $this->unit_price * $this->quantity;
		
		$this->updateGain();
	}
	
	/**
	 * Add buy operation
	 * @param float $unit_price
	 * @param int $quantity
	 * @param float $fee_fixed
	 * @param float $fee_percent
	 */
	public function addBuy($unit_price, $quantity, $fee_fixed, $fee_percent) {
		
		$fee = $fee_fixed + round($fee_percent * $quantity * $unit_price, 2);
		$price = ($unit_price * $quantity) - $fee;
		
		$this->spend += $price;
		$this->unit_price = round(
						(
							$price 
							+
							(
								$this->unit_price 
								* 
								$this->quantity
							)
						) 
						/ 
						(
							$quantity
							+ 
							$this->quantity
						),2)
		;
		$this->quantity += $quantity;
		
		$this->capital = $this->unit_price * $this->quantity;
	}
	
	/**
	 * Update gain values
	 */
	protected function updateGain() {
		$this->gain_price = $this->dividend_price + $this->capitalGain_price;
		if ($this->unit_price && $this->quantity) {
			$this->gain_percent = $this->gain_price / ($this->unit_price * $this->quantity);
		}
		
		$this->balance = $this->recieved - $this->spend;
	}
}
