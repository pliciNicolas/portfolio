<?php
/**
 * Description of User_Webservice
 *
 */
class User_Webservice {
	public $user = null;
	public $webservice = null;
	public $token = null;
	public $apiKey = null;
	

	/**
	 * Set object
	 * @param \User $user
	 * @param \Webservice $webservice
	 * @param string $token
	 * @param string $apiKey
	 */
	public function set(\User $user, \Webservice $webservice, $token, $apiKey) {
		$this->user = $user;
		$this->webservice = $webservice;
		$this->token = $token;
		$this->apiKey = $apiKey;
	}
}
