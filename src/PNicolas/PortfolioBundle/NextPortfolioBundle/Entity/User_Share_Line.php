<?php

/**
 * Description of User_Share_Line
 *
 */
class User_Share_Line {
	public $spend = 0;
	public $recieved = 0;
	public $quantity = 0;
	public $unit_price = 0;
	public $dividend_price = 0;
	public $dividend_count = 0;
	public $dividend_average = 0;
	public $dividend_percent = 0;
	public $capitalGain_price = 0;
	public $capitalGain_percent = 0;
	public $gain_price = 0;
	public $gain_percent = 0;
	
	/**
	 * Add a dividend operation
	 * @param float $dividend
	 */
	public function addDividend($dividend) {
		$this->dividend_price += $dividend;
		$this->dividend_count += 1;
		$this->dividend_average = $this->dividend_price / $this->dividend_count;
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
	}
	
	/**
	 * Update gain values
	 */
	protected function updateGain() {
		$this->gain_price = $this->dividend_price + $this->capitalGain_price;
		if ($this->unit_price && $this->quantity) {
			$this->gain_percent = $this->gain_price / ($this->unit_price * $this->quantity);
		}
	}
}
