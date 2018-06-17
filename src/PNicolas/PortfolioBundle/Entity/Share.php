<?php

/**
 * Description of Share
 *
 */
class Share {
	public $id = null;
	public $name = null;
	public $market = null;
	public $symbol = null;
	public $color = null;
	public $logo = null;
	
	public $current_price = null;
	public $open_price = null;
	
	/**
	 * Set object
	 * @param string $id
	 * @param string $name
	 * @param \Market $market
	 * @param string $symbol
	 * @param string $color
	 * @param string $logo
	 */
	public function set($id, $name, \Market $market, $symbol, $color = null, $logo=null) {
		$this->id = $id;
		$this->name = $name;
		$this->market = $market;
		$this->symbol = $symbol;
		$this->color = is_null($color)?Tools::getColor($name):$color;
		$this->logo = $logo;
	}
	
	/**
	 * Get label to display
	 * @return string
	 */
	public function getDisplay() {
		$return = '';
		if (!empty($this->logo)) {
			$return .= '<img src="web/image/share/'.$this->logo.'" alt="'.$this->name.'" title="'.$this->name.'"/>';
		}
		
		return $return;
	}
}
