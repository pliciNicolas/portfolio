<?php
/**
 * Description of User
 *
 */
class User {
	public $id = null;
	public $name = null;
        
        public $webservices = [];
	
	/**
	 * Set object
	 * @param int $id
	 * @param string $name
	 */
	public function set($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
        
        public function addWebservice(\Webservice $webservice, \User_Webservice $user_webservice) {
            $this->webservices[$webservice->id] = ['webservice' => $webservice, 'user_webservice' => $user_webservice];
        }
}
