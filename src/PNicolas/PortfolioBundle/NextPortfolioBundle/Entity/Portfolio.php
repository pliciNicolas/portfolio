<?php

/**
 * Description of Portfolio
 *
 */
class Portfolio {
	public $id = null;
	public $user = null;
	public $name = null;
	public $fee_fixed = null;
	public $fee_percent = null;
	public $currency = null;
	
	/**
	 * Set object
	 * @param int $id
	 * @param \User $user
	 * @param float $fee_fixed
	 * @param float $fee_percent
	 * @param string $currency
	 */
	public function set($id, \User $user, $name, $fee_fixed, $fee_percent, $currency) {
		$this->id = (int) $id;
		$this->user = $user;
		$this->name = $name;
		$this->fee_fixed = (float) $fee_fixed;
		$this->fee_percent = (float) $fee_percent;
		$this->currency = $currency;
	}
}
