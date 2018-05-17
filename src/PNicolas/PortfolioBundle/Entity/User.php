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
	public $total = null;
	public $historic = [];
	
	public $apiKey = [];
        
	public function __construct() {
		$this->total = new User_Share_Line();
	}
	
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
		
		// Set by share 
		if (!array_key_exists($transaction->share->id, $this->shares)) {
			$this->shares[$transaction->share->id] = new User_Share();
			$this->shares[$transaction->share->id]->share = $transaction->share;
			$this->shares[$transaction->share->id]->total = new User_Share_Line();
			$this->shares[$transaction->share->id]->total->share = $transaction->share;
		}
		if (!array_key_exists($transaction->portfolio->id, $this->shares[$transaction->share->id]->portfolios)) {
			$this->shares[$transaction->share->id]->portfolios[$transaction->portfolio->id] = new User_Share_Line();
			$this->shares[$transaction->share->id]->portfolios[$transaction->portfolio->id]->share = $transaction->share;
			$this->shares[$transaction->share->id]->portfolios[$transaction->portfolio->id]->portfolio = $transaction->portfolio;
		}
		
		$this->shares[$transaction->share->id]->portfolios[$transaction->portfolio->id]->addTransaction($transaction);
		$this->shares[$transaction->share->id]->total->addTransaction($transaction);
		
		// Set by portfolio 
		if (!array_key_exists($transaction->portfolio->id, $this->portfolios)) {
			$this->portfolios[$transaction->portfolio->id] = new User_Portfolio();
			$this->portfolios[$transaction->portfolio->id]->portfolio = $transaction->portfolio;
			$this->portfolios[$transaction->portfolio->id]->total = new User_Share_Line();
			$this->portfolios[$transaction->portfolio->id]->total->portfolio = $transaction->portfolio;
		}
		
		if (!array_key_exists($transaction->share->id, $this->portfolios[$transaction->portfolio->id]->shares)) {
			$this->portfolios[$transaction->portfolio->id]->shares[$transaction->share->id] = new User_Share_Line();
			$this->portfolios[$transaction->portfolio->id]->shares[$transaction->share->id]->share = $transaction->share;
			$this->portfolios[$transaction->portfolio->id]->shares[$transaction->share->id]->portfolio = $transaction->portfolio;
		}
		
		$this->portfolios[$transaction->portfolio->id]->shares[$transaction->share->id]->addTransaction($transaction);
		$this->portfolios[$transaction->portfolio->id]->total->addTransaction($transaction);
		
		// Set Total
		$this->total->addTransaction($transaction);

		$this->historic[] = $transaction;
	}
	
	/**
	 * Return all code api code in json format
	 * @return string
	 */
	public function getWebserviceApiKey() {
		return json_encode($this->apiKey);
	}
}
