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
	public $current_change = null;
	
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
		
		$this->setPrice();
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
		
		return '<span style=color="'.$this->color.'">'.$return.'</span>';
	}
	
	public function setPrice() {
		$url = str_replace('@symbol@', $this->symbol, $this->market->webservice->url);
		$curl = new Curl();
		$result = $curl->doRequest($url);

		if (!$result['error']['error']) {
			switch ($this->market->webservice->codeName) {
				case 'quandl' : 
					if (
						!array_key_exists('quandl_error', $result['data'])
						&& count($result['data']['dataset']['data']) 
					) {
						foreach ($result['data']['dataset']['column_names'] as $ind => $column) {
							switch ($column) {
								case 'Open' :
									$this->open_price = $result['data']['dataset']['data'][0][$ind];
									break;
								case 'Last' :
									$this->current_price = $result['data']['dataset']['data'][0][$ind];
									break;
							}
						}
					}
					break;
				case 'cryptoCompare' :
					if (array_key_exists('RAW', $result['data'])) {
						$this->current_price = $result['data']['RAW'][$this->symbol]['EUR']['PRICE'];
						$this->open_price = $result['data']['RAW'][$this->symbol]['EUR']['OPEN24HOUR'];
					}
					break;
			}
			if (!is_null($this->open_price)) {
				$this->current_change = ($this->open_price - $this->current_price) / $this->open_price * 100;
			}
		}
	}
}
