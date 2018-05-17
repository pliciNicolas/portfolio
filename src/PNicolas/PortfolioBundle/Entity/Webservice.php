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
	
	public $apiKey = null;
	
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
	 * Set api Key for user
	 * @param string $apiKey
	 */
	public function setApiKey($apiKey) {
		$this->apiKey = $apiKey;
		$this->url = str_replace('@api_key@', $this->apiKey, $this->url_raw);
	}
	
	/**
	 * Get url for a share
	 * @param string $share_id
	 * @return string
	 */
	public function getUrl($share_id) {
		$replace_data = [
			'@symbol@' => $share_id
			,'@date@' => date('Y-m-d')
		];
		return str_replace(array_keys($replace_data), array_values($replace_data), $this->url);
	}
}
