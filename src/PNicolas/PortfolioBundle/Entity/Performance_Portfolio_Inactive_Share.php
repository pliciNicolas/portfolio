<?php
require (__DIR__.'/../Entity/Performance_Portfolio_Inactive_Line.php');

class Performance_Portfolio_Inactive_Share extends Performance_Portfolio_Inactive_Line{
	public $share = null;
	
	public function set(\Performance_Portfolio_Share $portfolioShare) {
		$this->share = $portfolioShare->share;
		parent::set($portfolioShare);
	}
}
