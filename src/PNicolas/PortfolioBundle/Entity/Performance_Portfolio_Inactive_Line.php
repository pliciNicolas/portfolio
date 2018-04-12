<?php
class Performance_Portfolio_Inactive_Line {
	public $dividend = 0;
	public $capitalGain = 0;
	public $total = 0;
	public $return = 0;
	
	protected $spend = 0;
	
	public function addShare(\PortfolioShare $portfolioShare) {
		$this->spend += $portfolioShare->spend;
		
		$this->dividend += (float) $portfolioShare->dividend;
		$this->capitalGain += (float) $portfolioShare->capitalGain;
		$this->total += $this->dividend + $this->capitalGain;
		$this->return = round(($this->total)/$this->spend*100, 2);
	}
}