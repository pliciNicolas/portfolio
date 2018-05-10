<?php

/**
 * Description of Share_Webservice
 *
 */
class Share_Webservice {
	
	public static $shares = [];


	/**
	 * Set object
	 * @param int $id_share
	 * @param int $id_webservice
	 * @param string $symbol
	 */
	static public function set($id_share, $id_webservice, $symbol) {
		if (!array_key_exists($id_share, self::$shares)) {
			self::$shares[$id_share] = [];
		}
		
		self::$shares[$id_share][$id_webservice] = $symbol;
	}
	
	/**
	 * Get symbol for a share in webservice
	 * @param int $id_share
	 * @param int $id_webservice
	 * @return string
	 */
	static public function get($id_share, $id_webservice) {
		if (!isset(self::$shares[$id_share][$id_webservice])) {
			throw new Exception('Can\'t find share '.$id_share.' for webservice '.$id_webservice);
		}
		return self::$shares[$id_share][$id_webservice];
	}
}
