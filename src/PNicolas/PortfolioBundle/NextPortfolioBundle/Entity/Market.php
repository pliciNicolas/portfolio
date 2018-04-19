<?php

/**
 * Description of Market
 *
 */
class Market {
	CONST OPENHOURS_WEEK = 'week';
	CONST OPENHOURS_24H = '24h';
	
	public $id = null;
	public $name = null;
	public $openHours = null;
	public $webservice = null;

	/**
	 * Set object
	 * @param int $id
	 * @param string $name
	 * @param string $openHours
	 * @param \Webservice $webservice
	 * @throws Exception
	 */
	public function set($id, $name, $openHours, \Webservice $webservice) {
		if (!in_array($openHours, $this->getOpenHoursAllowed())) {
			throw new Exception('Open market hours"'.$openHours.'" is not allowed. Values allowed : '.implode(', ',$this->getOpenHoursAllowed()));
		}
		
		$this->id = (int) $id;
		$this->name = $name;
		$this->openHours = $openHours;
		$this->webservice = $webservice;
	}
	
	/**
	 * Search if type is allowed
	 * @return array
	 */
	public function getOpenHoursAllowed() {
		$oClass = new ReflectionClass(__CLASS__);
		$const = $oClass->getConstants();

		return array_values($const);
	}
}
