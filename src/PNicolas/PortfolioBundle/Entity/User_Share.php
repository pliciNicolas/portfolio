<?php

/**
 * Description of User_Share
 *
 */
class User_Share {
	public $share = null;
	public $portfolios = [];
	public $total = null;
	
	
	/**
	 * is Share is active ?
	 * @return boolean
	 */	
	public function isActive() {
		return 0 < $this->total->quantity;
		
	}
}
