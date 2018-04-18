<?php
/**
 * Description of User_Webservice
 *
 */
class User_Webservice {
	public $webservice = null;
	public $token = null;
	public $apiKey = null;
	

	/**
	 * Set object
	 * @param \Webservice $webservice
	 * @param string $token
	 * @param string $apiKey
	 */
	public function set(\Webservice $webservice, $token, $apiKey) {
		$this->webservice = $webservice;
		$this->token = $token;
		$this->apiKey = $apiKey;
	}
}
