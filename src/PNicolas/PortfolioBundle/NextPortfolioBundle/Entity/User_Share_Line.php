<?php

/**
 * Description of User_Share_Line
 *
 */
class User_Share_Line {

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


    // Real resume data
    public $gain_price = 0; // ??
    public $balance = 0; // Sum of all add and minus (Buying + Sell + Dividend)

    public $spend = 0;
    public $recieved = 0;
    // Current capital
    public $quantity = 0; // Current quantity
    public $unit_price = 0; // Current unit price
    public $capital = 0; // $quantity * $unit_price	

    
    public $gain_percent = 0;

    /**
     * Add a dividend operation
     * @param float $dividend
     */
    public function addDividend($dividend) {
        $this->dividend_price += $dividend;
        $this->dividend_count += 1;
        $this->dividend_average = round($this->dividend_price / $this->dividend_count, 2);

        $this->dividend_capital += $this->quantity * $this->unit_price;
        $this->dividend_percent = round($this->dividend_price / $this->dividend_capital, 2);
        $this->dividend_quantity += $this->quantity;

        $this->dividend_per_share_per_year = round($this->dividend_price / $this->dividend_quantity / $this->date_holding->format('Y'), 2);
        
        $this->balance += $dividend;


//		$this->recieved += $dividend;
//		$this->updateGain();
    }

    /**
     * Add sell operation
     * @param float $unit_price
     * @param int $quantity
     * @param float $fee_fixed
     * @param float $fee_percent
     */
    public function addSell($unit_price, $quantity, $fee_fixed, $fee_percent, $date) {

        $fee = $fee_fixed + round($fee_percent * $quantity * $unit_price, 2);
        $price = ($unit_price * $quantity) - $fee;

        $this->capitalGain_price += ($unit_price - $this->unit_price) * $quantity - $fee;
        $this->capitalGain_count++;
        $this->capitalGain_capital += $this->unit_price * $this->quantity;
        $this->capitalGain_percent = round($this->capitalGain_price / $this->unit_price * $this->quantity * 100, 2);
        
        $this->balance += $price;
        
        $this->quantity -= $quantity;
//		
//		$this->recieved += $price;
//		
//		
//		if ($this->unit_price && $this->quantity) {
//			$this->capitalGain_percent = $this->capitalGain_price / ($this->unit_price * $this->quantity); 
//		}
//		
//		$this->capital = $this->unit_price * $this->quantity;
//		
//		$this->updateGain();

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
    public function addBuy($unit_price, $quantity, $fee_fixed, $fee_percent, $date) {

        if (is_null($this->date_last_first_buying)) {
            $this->date_last_first_buying = $date;
        }

        $fee = $fee_fixed + round($fee_percent * $quantity * $unit_price, 2);
        $price = ($unit_price * $quantity) - $fee;

        $this->balance -= $price;
//		
//		$this->spend += $price;
//		$this->unit_price = round(
//						(
//							$price 
//							+
//							(
//								$this->unit_price 
//								* 
//								$this->quantity
//							)
//						) 
//						/ 
//						(
//							$quantity
//							+ 
//							$this->quantity
//						),2)
//		;
//		$this->quantity += $quantity;
//		
//		$this->capital = $this->unit_price * $this->quantity;
    }

    /**
     * Update gain values
     */
    protected function updateGain() {
        $this->gain_price = $this->dividend_price + $this->capitalGain_price;
        if ($this->unit_price && $this->quantity) {
            $this->gain_percent = $this->gain_price / ($this->unit_price * $this->quantity);
        }

        $this->balance = $this->recieved - $this->spend;
    }

}
