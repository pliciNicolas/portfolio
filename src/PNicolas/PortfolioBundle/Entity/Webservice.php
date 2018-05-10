<?php

/**
 * Description of Webservice
 *
 */
class Webservice {
	public $id = null;
	public $name = null;
	protected $url_raw = null;
	public $url = null;
	public $codeName = null;
	
	/**
	 * Set object
	 * @param int $id
	 * @param string $name
	 * @param string $url
	 * @param string $codeName
	 */
	public function set($id, $name, $url, $codeName) {
		$this->id = $id;
		$this->name = $name;
		$this->url_raw = $url;
		$this->codeName = $codeName;
	}
	
	/**
	 * Set parameters
	 * @param string $api_key
	 * @param string $token
	 */
	public function setUrlParameters($api_key, $token) {
		$this->url = str_replace(['@api_key@', '@token@'], func_get_args(), $this->url_raw);
	}
	
	/**
	 * Get url for a share
	 * @param string $share_id
	 * @return string
	 */
	public function getUrl($share_id) {
		return str_replace('@symbol@', $share_id, $this->url);
	}
}
