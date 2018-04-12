<?php
class Performance_Portfolio_Inactive {
	public $shares = [];
	public $total = null;
	
	public function addShare($shares) {
		
		$this->total = new Performance_Portfolio_Inactive_Line();
		
		foreach($shares as $share) {
			$perf_port_inactive_share = new Performance_Portfolio_Inactive_Share();
			$perf_port_inactive_share->set($share);
			$this->shares[] = $perf_port_inactive_share;
			$this->total->addShare($share);
		}
		
	}
}
