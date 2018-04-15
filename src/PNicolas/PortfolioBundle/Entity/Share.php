<?php


class Share {
	
	CONST OPENMARKET_WEEK = 'week';
	CONST OPENMARKET_24H = '24';
	
	public $id = null;
	public $name = null;
	public $open_market = null;
	
	public function set($id, $name, $open_market) {
		if (!in_array($open_market, $this->getOpenMarketAllowed())) {
			throw new Exception('Open Market "'.$open_market.'" is not allowed. Open market available : '.implode(', ',$this->getOpenMarketAllowed()));
		}
		
		$this->id = $id;
		$this->name = $name;
		$this->open_market = $open_market;
	}
	
	/**
	 * Search if type is allowed
	 * @param string $type
	 * @return boolean
	 */
	public function getOpenMarketAllowed() {
		return [
			self::OPENMARKET_WEEK,
			self::OPENMARKET_24H,
		];
	}
}