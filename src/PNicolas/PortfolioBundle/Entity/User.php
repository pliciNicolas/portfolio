<?php
/**
 * Description of User
 *
 */
class User {
	public $id = null;
	public $name = null;
	
	public $shares = [];
	public $portfolios = [];
	public $historic = [];
        
	
	/**
	 * Set object
	 * @param int $id
	 * @param string $name
	 */
	public function set($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
	
	/**
	 * Add a new transaction
	 * @param \Transaction $transaction
	 */
	public function addTransaction(\Transaction $transaction) {
		if (!array_key_exists($transaction->share->id, $this->shares)) {
			$this->shares[$transaction->share->id] = new User_Share();
		}
		
		$this->shares[$transaction->share->id]->addTransaction($transaction);
		
		$this->historic[] = $transaction;
	}
}
