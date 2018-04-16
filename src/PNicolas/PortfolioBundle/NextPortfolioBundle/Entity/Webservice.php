<?php

/**
 * Description of Webservice
 *
 */
class Webservice {
	public $id = null;
	public $name = null;
	public $url = null;
	
	/**
	 * Set object
	 * @param int $id
	 * @param string $name
	 * @param string $url
	 */
	public function set($id, $name, $url) {
		$this->id = $id;
		$this->name = $name;
		$this->url = $url;
	}
}
