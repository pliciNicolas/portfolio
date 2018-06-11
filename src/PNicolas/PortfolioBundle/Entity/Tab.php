<?php


/**
 * Tab of dashboard
 *
 * @author pliciNicolas
 */
class Tab {
	public $id = null;
	public $name = null;
	public $type = 'tab';
	
	/* Tab may contain subtab or lines+total - Not twice */
	public $subTab = [];
	
	public $data = [];
	public $total = null;
	
	public $historic_lines = [];
	public $historic_total = null;
	
}
