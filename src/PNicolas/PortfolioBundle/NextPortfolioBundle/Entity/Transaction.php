<?php

/**
 * Description of Transaction
 *
 */
class Transaction {
	CONST TYPE_BUY='BUY';
	CONST TYPE_SELL='SELL';
	CONST TYPE_DIVIDEND='DIVIDEND';
	CONST TYPE_CASH='CASH';

	public $id = null;
	public $portfolio = null;
	public $date = null;
	public $share = null;
	public $type = null;
	public $quantity = null;
	public $unit_price = null;
	public $fee_fixed = null;
	public $fee_percent = null;
	
	public $fee = null;
	public $price = null;
	

	/**
	 * Set object
	 * @param int $id
	 * @param \Portfolio $portfolio
	 * @param \DateTime $date
	 * @param \Share $share
	 * @param string $type
	 * @param int $quantity
	 * @param float $unit_price
	 * @param float $fee_fixed
	 * @param float $fee_percent
	 * @throws Exception
	 */
	public function set($id, \Portfolio $portfolio, \DateTime $date, \Share $share, $type, $quantity, $unit_price, $fee_fixed, $fee_percent) {
		if (!in_array($type, $this->getTypeAllowed())) {
			throw new Exception('Type "'.$type.'" is not allowed. Values allowed : '.implode(', ',$this->getTypeAllowed()));
		}
		
		$this->id = (int) $id;
		$this->portfolio = $portfolio;
		$this->date = $date;
		$this->share = $share;
		$this->type = $type;
		$this->quantity = (int) $quantity;
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
	 * @return array
	 */
	public function getTypeAllowed() {
		$oClass = new ReflectionClass(__CLASS__);
		$const = $oClass->getConstants();

		return array_keys($const);
	}
}
