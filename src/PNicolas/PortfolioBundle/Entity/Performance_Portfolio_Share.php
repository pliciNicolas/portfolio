<?php
class Performance_Portfolio_Share {
	
					
	public $share = null;
	public $spend = 0;
	public $recieved = 0;
	public $quantity = 0;
	public $unit_price = 0;
	public $dividend = 0;
	public $capitalGain = 0;
	public $totalGain = null;
	public $last_fee_fixed = 0;
	public $last_fee_percent = 0;
	
	public function set(\Performance_Portfolio_Share $portfolioShare) {
		$this->share = $portfolioShare->share;
		$this->spend = $portfolioShare->spend;
		$this->recieved = $portfolioShare->recieved;
		$this->quantity = $portfolioShare->quantity;
		$this->unit_price = $portfolioShare->unit_price;
		$this->dividend = $portfolioShare->dividend;
		$this->capitalGain = $portfolioShare->capitalGain;
		$this->totalGain = $portfolioShare->totalGain;
		$this->last_fee_fixed = $portfolioShare->last_fee_fixed;
		$this->last_fee_percent = $portfolioShare->last_fee_percent;
	}
	
}