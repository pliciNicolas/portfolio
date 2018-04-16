<?php

/**
 * Description of Share_Webservice
 *
 */
class Share_Webservice {
	public $webservice = null;
	public $share = null;
	public $symbol = null;
	
	/**
	 * Set object
	 * @param \Webservice $webservice
	 * @param \Share $share
	 * @param string $symbol
	 */
	public function set(\Webservice $webservice, \Share $share, $symbol) {
		$this->webservice = $webservice;
		$this->share = $share;
		$this->symbol = $symbol;
	}
}
