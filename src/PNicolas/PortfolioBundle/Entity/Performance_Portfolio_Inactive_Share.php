<?php
class Performance_Portfolio_Inactive_Share extends Performance_Portfolio_Inactive_Line{
	public $share = null;
	
	public function set(\PortfolioShare $portfolioShare) {
		$this->share = $portfolioShare->share;
		parent::set($portfolioShare);
	}
}
