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
	
	/**
	 * Set object
	 * @param string $id
	 * @param string $name
	 * @param string $symbol
	 * @param \Market $market
	 */
	public function set($id, $name, \Market $market, $symbol) {
		$this->id = $id;
		$this->name = $name;
		$this->market = $market;
	}
}
