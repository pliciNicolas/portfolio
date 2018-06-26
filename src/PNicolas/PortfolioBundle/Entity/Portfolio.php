<?php

/**
 * Description of Portfolio
 *
 */
class Portfolio {
	public $id = null;
	public $name = null;
	public $fee_fixed = null;
	public $fee_percent = null;
	public $currency = null;
	public $color = null;
	public $logo = null;
	
	/**
	 * Set object
	 * @param int $id
	 * @param float $fee_fixed
	 * @param float $fee_percent
	 * @param string $currency
	 * @param string $color
	 * @param string $logo
	 */
	public function set($id, $name, $fee_fixed, $fee_percent, $currency, $color = null, $logo=null) {
		$this->id = (int) $id;
		$this->name = $name;
		$this->fee_fixed = (float) $fee_fixed;
		$this->fee_percent = (float) $fee_percent;
		$this->currency = $currency;
		$this->color = is_null($color)?Tools::getColor($name):$color;
		$this->logo = $logo;
	}
	
	/**
	 * Get label to display
	 * @return string
	 */
	/**
	 * Get label to display
	 * @return string
	 */
	public function getDisplay() {
		$return = '';
		if (!empty($this->logo)) {
			$return .= '<img src="web/image/portfolio/'.$this->logo.'" alt="'.$this->name.'" title="'.$this->name.'"/>';
		}
		
		return '<span style=color="'.$this->color.'">'.$return.'</span>';
	}
}
