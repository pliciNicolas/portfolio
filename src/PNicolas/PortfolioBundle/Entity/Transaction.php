<?php


class Transaction {
	
	CONST BUY='BUY';
	CONST SELL='SELL';
	CONST DIVIDEND='DIVIDEND';
	CONST CASH='CASH';
	
	public $id = null;
	public $portfolio = null;
	public $date = null;
	public $share = null;
	public $portfolio_share_code = null;
	public $type = null;
	public $quantity = null;
	public $unit_price = null;
	public $fee_fixed = null;
	public $fee_percent = null;
	
	public $fee = null;
	public $price = null;
	
	public function set($id, \Portfolio $portfolio, \DateTime $date, \Share $share, $portfolio_share_code, $type, $quantity, $unit_price, $fee_fixed, $fee_percent) {
		if (!in_array($type, $this->getTypeAllowed())) {
			throw new Exception('Type "'.$type.'" is not allowed. Type available : '.implode(', ',$this->getTypeAllowed()));
		}
		
		$this->id = (int) $id;
		$this->portfolio = $portfolio;
		$this->date = $date;
		$this->share = $share;
		$this->portfolio_share_code = $portfolio_share_code;
		$this->type = $type;
		$this->quantity = $quantity;
		$this->unit_price = (float) $unit_price;
		$this->fee_fixed = (float) $fee_fixed;
		$this->fee_percent = (float) $fee_percent;
		
		$this->fee = $this->fee_fixed + round($this->fee_percent * $this->quantity * $this->unit_price, 2);
		
		$this->price = ($this->unit_price * $this->quantity) - $this->fee;
		if (self::BUY == $this->type) {
			$this->price = ($this->unit_price * $this->quantity) + $this->fee;
		}
	}
	
	
	/**
	 * Search if type is allowed
	 * @param string $type
	 * @return boolean
	 */
	public function getTypeAllowed() {
		return [
			self::BUY,
			self::SELL,
			self::DIVIDEND,
			self::CASH,
		];
	}
}