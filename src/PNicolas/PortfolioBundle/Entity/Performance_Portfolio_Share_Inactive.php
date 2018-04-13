<?php

class Performance_Portfolio_Share_Inactive extends Performance_Portfolio_Share {
	
	
	public $overAll = 0;
	
	
	public function set(\Performance_Portfolio_Share $portfolioShare) {

		parent::set($portfolioShare);

		$this->capitalGain = $this->recieved - $this->spend;
		$this->overAll = round(($this->totalGain) / $this->spend*100, 2);
	}
}
