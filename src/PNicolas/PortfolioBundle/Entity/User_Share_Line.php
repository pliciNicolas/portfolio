<?php

/**
 * Description of User_Share_Line
 *
 */
class User_Share_Line {

	public $name = null;
	public $display = null;
    public $share = null;
    public $portfolio = null;

    // Dividend
    public $dividend_price = 0; // Amount of dividend
    public $dividend_count = 0; // Number of dividend
    public $dividend_average = 0; // Average of dividend
    protected $dividend_capital = 0; // Usefull to define percent
    public $dividend_percent = 0; // Percent gain for capital
    protected $dividend_quantity = 0; // Usefull to define dividend per action per year
    public $dividend_per_share_per_year = 0; // Dividend by year holding for 1 share

    // Date
    protected $date_last_first_buying = null; // Last first buying date
    protected $date_holding = null; // DateInterval

    // Capital Gain
    public $capitalGain_price = 0;  // Previous capital gain
    public $capitalGain_count = 0; // Count number of capital gain
    protected $capitalGain_capital = 0; // Usefull to define capitalGain_percent
	public $capitalGain_percent = 0; // ??


    // Real resume data = Dividend + Capital Gain
    public $gain_price = 0; // Dividend + Gain Value
    protected $gain_capital = 0; // 
    public $gain_percent = 0; // 
	
    public $balance = 0; // Sum of all add and minus (Buying + Sell + Dividend)

	public $current_price = null;
	public $current_change = null;
	public $current_loose_gain = null;
	
	public $virtual_capital = null;
	public $virtual_capital_gain = null;
	public $virtual_capital_loose_gain = 'neutral';
	public $virtual_capital_percent = null;
	public $overall_gain = null;
	public $overall_loose_gain = 'neutral';
	public $overall_percent = null;
	
    // Current capital
    public $quantity = 0; // Current quantity
    public $unit_price = 0; // Current unit price
    public $capital = 0; // $quantity * $unit_price	

    
	/**
	 * Add transaction
	 * @param \Transaction $transaction
	 */
	public function addTransaction(\Transaction $transaction) {
		if (is_null($this->share)) {
			$this->share = $transaction->share;
			$this->current_price = $this->share->current_price;
			$this->current_change = round($this->share->current_change,2);
			$this->current_loose_gain = $this->getLooseOrGain($this->current_change);
		}
		if (is_null($this->portfolio)) {
			$this->portfolio = $transaction->portfolio;
		}
		switch($transaction->type) {
			case Transaction::TYPE_BUY : 
				$this->addBuy($transaction->unit_price, $transaction->quantity, $transaction->fee_fixed, $transaction->fee_percent, $transaction->date);
				break;
			case Transaction::TYPE_SELL : 
				$this->addSell($transaction->unit_price, $transaction->quantity, $transaction->fee_fixed, $transaction->fee_percent, $transaction->date);
				break;
			case Transaction::TYPE_DIVIDEND : 
				$dividend = $transaction->unit_price * $transaction->quantity;
				$this->addDividend($dividend);
				break;
		}
		
		$this->reloadVirtualData();
	}

    /**
     * Add a dividend operation
     * @param float $dividend
     */
    protected function addDividend($dividend) {
        $this->dividend_price += $dividend;
        $this->dividend_count += 1;
        $this->dividend_average = round($this->dividend_price / $this->dividend_count, 2);
        $this->dividend_capital += $this->capital;
		if ($this->dividend_capital) {
			$this->dividend_percent = round($this->dividend_price / $this->dividend_capital, 2);
		}
        $this->dividend_quantity += $this->quantity;
		if ($this->getYearHolding()) {
			$this->dividend_per_share_per_year = round($this->dividend_price / $this->dividend_quantity / $this->getYearHolding(), 2);
		}
        
		$this->gain_price = $this->dividend_price + $this->capitalGain_price;
		$this->gain_capital += $dividend;
		if ($this->gain_capital) {
			$this->gain_percent = round($this->gain_price / $this->gain_capital * 100 , 2); 
		}
        $this->balance += $dividend;
    }
	
	/**
	 * Number of year holding share
	 * @return int
	 */
	protected function getYearHolding() {
		if (is_null($this->date_last_first_buying)) {
			return 0;
		}
		if (is_null($this->date_holding)) {
			$now = new DateTime();
			$diff = $this->date_last_first_buying->diff($now);
			return max(1,$diff->format('Y'));
		}
		
		return $this->date_holding->format('Y');
	}
	
	public function getHolding() {
		if (is_null($this->date_last_first_buying)) {
			return '';
		}
		$data = $this->date_holding;
		if (is_null($this->date_holding)) {
			$now = new DateTime();
			$data = $this->date_last_first_buying->diff($now);
		}
		
		return '<span title="'.$data->format('%YY %mM %dD').'">'.$data->format('%a D').'</span>';
	}
			

    /**
     * Add sell operation
     * @param float $unit_price
     * @param int $quantity
     * @param float $fee_fixed
     * @param float $fee_percent
     */
    protected function addSell($unit_price, $quantity, $fee_fixed, $fee_percent, $date) {

        $fee = $fee_fixed + round($fee_percent * $quantity * $unit_price, 2);
        $price = ($unit_price * $quantity) - $fee;

        $this->capitalGain_price += ($unit_price - $this->unit_price) * $quantity - $fee;
        $this->capitalGain_count++;
        $this->capitalGain_capital += $this->capital;
		if( $this->capitalGain_capital ) {
			$this->capitalGain_percent = round($this->capitalGain_price / $this->capitalGain_capital * 100, 2);
		}
        
		$this->gain_price = $this->dividend_price + $this->capitalGain_price;
		$this->gain_capital += $price;
		$this->gain_percent = round($this->gain_price / $this->gain_capital * 100 , 2); 
        $this->balance += $price;
        
        $this->quantity -= $quantity;
		$this->capital = $this->unit_price * $this->quantity;
		
        if (0 == $this->quantity) {
            $new_diff = $this->date_last_first_buying->diff($date);
            if (is_null($this->date_holding)) {
                $this->date_holding = $new_diff;
            } else {
                $e = new DateTime('00:00');
                $f = clone $e;
                $e->add($this->date_holding);
                $e->add($new_diff);
                $this->date_holding = $f->diff($e);
            }
            $this->date_last_first_buying = null;
        }
    }

    /**
     * Add buy operation
     * @param float $unit_price
     * @param int $quantity
     * @param float $fee_fixed
     * @param float $fee_percent
     * @param DateTime $date
     */
    protected function addBuy($unit_price, $quantity, $fee_fixed, $fee_percent, $date) {
        if (is_null($this->date_last_first_buying)) {
            $this->date_last_first_buying = $date;
        }

        $fee = $fee_fixed + round($fee_percent * $quantity * $unit_price, 2);
        $price = ($unit_price * $quantity) + $fee;

        $this->balance -= $price;

		$this->quantity += $quantity;
		$this->unit_price = round(
						(
							$price 
							+
							$this->capital 
						) 
						/ 
							$this->quantity
						,2)
		;
		
		$this->capital = $this->unit_price * $this->quantity;
    }
	
	/**
	 * Get name of portfolio / share
	 * @param string $type
	 * @return string
	 */
	public function getName($type) {
		if ('portfolio' == $type) {
			return $this->portfolio->name;
		}
		
		return $this->share->name;
	}
	
	/**
	 * Reload virtual and overall information
	 */
	protected function reloadVirtualData() {
		$this->virtual_capital = round(($this->current_price * $this->quantity * (1-$this->portfolio->fee_percent)) - $this->portfolio->fee_fixed,2);
		$this->virtual_capital_gain = round($this->virtual_capital - $this->capital,2);
		$this->virtual_capital_percent = 0;
		if ($this->capital) {
			$this->virtual_capital_percent = round($this->virtual_capital_gain / $this->capital * 100,2);
		}
		$this->virtual_capital_loose_gain = $this->getLooseOrGain($this->virtual_capital_percent);
		$this->overall_gain = round($this->virtual_capital_gain + $this->gain_price,2);
		$this->overall_percent = 0;
		if ($this->capital) {
			$this->overall_percent = round($this->overall_gain / $this->capital * 100,2);
		}
		$this->overall_loose_gain = $this->getLooseOrGain($this->overall_gain);
	}
	
	/**
	 * Return if win or loose tag
	 * @param float $value
	 * @return string
	 */
	protected function getLooseOrGain($value) {
		if ($value < 0.0) {
			return 'loose';
		}
		if ($value > 0.0) {
			return 'gain';
		}
		
		return 'neutral';
	}
}
