<?php

/**
 * Description of Portfolio
 *
 */
class DashboardController {
	public $user = null;
	public $tabs = null;
	
	public function setUser(\User $user) {
		$this->user = $user;
	}
	
	public function loadData() {
		$data_file = __DIR__.'/../../../../app/data.php';
		$data = include $data_file;
		
		
		// Load portfolio
		$portfolios = [];
		if (!(array_key_exists('portfolio', $data) && is_array($data['portfolio']) && 0<count($data['portfolio']))) {
			throw new Exception('Cant find portfolio in data file');
		}
		foreach($data['portfolio'] as $portfolios_item) {
			if ($this->user->id == $portfolios_item['id_user']) {
				$portfolio = new Portfolio();
				$portfolio->set($portfolios_item['id'], $portfolios_item['name'], $portfolios_item['fee_fixed'], $portfolios_item['fee_percent'], $portfolios_item['currency'] );
				$portfolios[$portfolios_item['id']] = $portfolio;
			}
		}
		
		$webservices = [];
		if (!(array_key_exists('webservice', $data) && is_array($data['webservice']) && 0<count($data['webservice']))) {
			throw new Exception('Cant find webservice in data file');
		}
		foreach($data['webservice'] as $webservice_item) {
			$webservice = new Webservice();
			$webservice->set($webservice_item['id'], $webservice_item['name'], $webservice_item['url'], $webservice_item['codeName']);
			$webservices[$webservice_item['id']] = $webservice;
		}
		
		if (!(array_key_exists('user_webservice', $data) && is_array($data['user_webservice']) && 0<count($data['user_webservice']))) {
			throw new Exception('Cant find user_webservice in data file');
		}
		
		foreach($data['user_webservice'] as $user_webservicee_item) {
			$webservices[$user_webservicee_item['id_webservice']]->setApiKey($user_webservicee_item['api_key']);
			$this->user->apiKey[$user_webservicee_item['id_webservice']] = $user_webservicee_item['api_key'];
		}
		
		$markets = [];
		if (!(array_key_exists('market', $data) && is_array($data['market']) && 0<count($data['market']))) {
			throw new Exception('Cant find market in data file');
		}
		foreach($data['market'] as $market_item) {
			$market = new Market();
			$market->set($market_item['id'], $market_item['name'], $market_item['openHours'], $webservices[$market_item['id_webservice']]);
			$markets[$market_item['id']] = $market;
		}
		

		if (!(array_key_exists('share_webservice', $data) && is_array($data['share_webservice']) && 0<count($data['share_webservice']))) {
			throw new Exception('Cant find share_webservice in data file');
		}
		foreach($data['share_webservice'] as $share_webservice_item) {
			Share_Webservice::set($share_webservice_item['id_share'], $share_webservice_item['id_webservice'], $share_webservice_item['symbol']);
		}

		
		$shares = [];
		if (!(array_key_exists('share', $data) && is_array($data['share']) && 0<count($data['share']))) {
			throw new Exception('Cant find share in data file');
		}
		foreach($data['share'] as $share_item) {
			$share = new Share();
			$share->set($share_item['id'], $share_item['name'], $markets[$share_item['id_market']], Share_Webservice::get($share_item['id'], $markets[$share_item['id_market']]->webservice->id) );
			$shares[$share_item['id']] = $share;
		}


		$transactions = [];
		if (!(array_key_exists('transaction', $data) && is_array($data['transaction']) && 0<count($data['transaction']))) {
			throw new Exception('Cant find transaction in data file');
		}
		foreach($data['transaction'] as $transaction_item) {
			$transaction = new Transaction();
			$date = \DateTime::createFromFormat("Y-m-d", $transaction_item['date']);
			$transaction->set(
					$transaction_item['id']
					, $portfolios[$transaction_item['id_portfolio']]
					, $date
					, $shares[$transaction_item['id_share']]
					, $transaction_item['type']
					, $transaction_item['quantity']
					, $transaction_item['unit_price']
					, $transaction_item['fee_fixed']
					, $transaction_item['fee_percent']);
			if (!array_key_exists($date->getTimestamp(), $transactions)) {
				$transactions[$date->getTimestamp()] = [];
			}
			$transactions[$date->getTimestamp()][] = $transaction;
		}
		
		ksort($transactions);

		// Load current price

		
		$this->tabs = new Tab();
		$this->tabs->id = 'root';
		$this->tabs->name = 'Nicolas SOTRON';
		$this->tabs->type = 'root';

		$this->tabs->subTab['dashboard'] = new Tab();
		$this->tabs->subTab['dashboard']->id = 'dashboard';
		$this->tabs->subTab['dashboard']->name = 'Dashboard';
		$this->tabs->subTab['dashboard']->total = new User_Share_Line();
		$this->tabs->subTab['dashboard']->total->name = 'Total';
		
		$this->tabs->subTab['shares'] = new Tab();
		$this->tabs->subTab['shares']->id = 'shares';
		$this->tabs->subTab['shares']->name = 'Shares';
		
		$this->tabs->subTab['shares']->subTab['resume'] = new Tab();
		$this->tabs->subTab['shares']->subTab['resume']->id = 'shares_resume';
		$this->tabs->subTab['shares']->subTab['resume']->name = 'Resume';
		$this->tabs->subTab['shares']->subTab['resume']->total = new User_Share_Line();
		$this->tabs->subTab['shares']->subTab['resume']->total->name = 'Total';
		
		$this->tabs->subTab['shares']->subTab['active'] = new Tab();
		$this->tabs->subTab['shares']->subTab['active']->id = 'shares_active';
		$this->tabs->subTab['shares']->subTab['active']->name = 'Active';
		
		$this->tabs->subTab['shares']->subTab['active']->subTab['resume'] = new Tab();
		$this->tabs->subTab['shares']->subTab['active']->subTab['resume']->id = 'shares_active_resume';
		$this->tabs->subTab['shares']->subTab['active']->subTab['resume']->name = 'Resume';
		$this->tabs->subTab['shares']->subTab['active']->subTab['resume']->total = new User_Share_Line();
		$this->tabs->subTab['shares']->subTab['active']->subTab['resume']->total->name = 'Total';
		
		$this->tabs->subTab['shares']->subTab['unactive'] = new Tab();
		$this->tabs->subTab['shares']->subTab['unactive']->id = 'shares_unactive';
		$this->tabs->subTab['shares']->subTab['unactive']->name = 'Unactive';
		
		$this->tabs->subTab['shares']->subTab['unactive']->subTab['resume'] = new Tab();
		$this->tabs->subTab['shares']->subTab['unactive']->subTab['resume']->id = 'shares_unactive_resume';
		$this->tabs->subTab['shares']->subTab['unactive']->subTab['resume']->name = 'Resume';
		$this->tabs->subTab['shares']->subTab['unactive']->subTab['resume']->total = new User_Share_Line();
		$this->tabs->subTab['shares']->subTab['unactive']->subTab['resume']->total->name = 'Total';
		
		$this->tabs->subTab['portfolios'] = new Tab();
		$this->tabs->subTab['portfolios']->id = 'portfolios';
		$this->tabs->subTab['portfolios']->name = 'Portfolios';
		
		$this->tabs->subTab['portfolios']->subTab['resume'] = new Tab();
		$this->tabs->subTab['portfolios']->subTab['resume']->id = 'portfolios_resume';
		$this->tabs->subTab['portfolios']->subTab['resume']->name = 'Resume';
		$this->tabs->subTab['portfolios']->subTab['resume']->total = new User_Share_Line();
		$this->tabs->subTab['portfolios']->subTab['resume']->total->name = 'Total';
		
		$this->tabs->subTab['portfolios']->subTab['active'] = new Tab();
		$this->tabs->subTab['portfolios']->subTab['active']->id = 'portfolios_active';
		$this->tabs->subTab['portfolios']->subTab['active']->name = 'Active';
		
		$this->tabs->subTab['portfolios']->subTab['active']->subTab['resume'] = new Tab();
		$this->tabs->subTab['portfolios']->subTab['active']->subTab['resume']->id = 'portfolios_active_resume';
		$this->tabs->subTab['portfolios']->subTab['active']->subTab['resume']->name = 'Resume';
		$this->tabs->subTab['portfolios']->subTab['active']->subTab['resume']->total = new User_Share_Line();
		$this->tabs->subTab['portfolios']->subTab['active']->subTab['resume']->total->name = 'Total';
		
		$this->tabs->subTab['portfolios']->subTab['unactive'] = new Tab();
		$this->tabs->subTab['portfolios']->subTab['unactive']->id = 'portfolios_unactive';
		$this->tabs->subTab['portfolios']->subTab['unactive']->name = 'Unactive';
		
		$this->tabs->subTab['portfolios']->subTab['unactive']->subTab['resume'] = new Tab();
		$this->tabs->subTab['portfolios']->subTab['unactive']->subTab['resume']->id = 'portfolios_unactive_resume';
		$this->tabs->subTab['portfolios']->subTab['unactive']->subTab['resume']->name = 'Resume';
		$this->tabs->subTab['portfolios']->subTab['unactive']->subTab['resume']->total = new User_Share_Line();
		$this->tabs->subTab['portfolios']->subTab['unactive']->subTab['resume']->total->name = 'Total';
		
		$this->tabs->subTab['historic'] = new Tab();
		$this->tabs->subTab['historic']->id = 'historic';
		$this->tabs->subTab['historic']->name = 'Historic';
		$this->tabs->subTab['historic']->type = 'historic';
		
		$tmp_share = [];
		$tmp_portfolio = [];
		foreach ($transactions as $date_transaction) {
			foreach($date_transaction as $transaction) {
				$this->user->addTransaction($transaction);
				
				// New tab system
					// Shares
				if (!array_key_exists($transaction->share->id, $this->tabs->subTab['shares']->subTab['resume']->data)) {
					$this->tabs->subTab['shares']->subTab['resume']->data[$transaction->share->id] = new User_Share_Line();
					$this->tabs->subTab['shares']->subTab['resume']->data[$transaction->share->id]->name = $transaction->share->name;
					$tmp_share[$transaction->share->id] = new User_Share_Line();
				}
				$this->tabs->subTab['shares']->subTab['resume']->data[$transaction->share->id]->addTransaction($transaction);
				$this->tabs->subTab['shares']->subTab['resume']->total->addTransaction($transaction);
				$tmp_share[$transaction->share->id]->addTransaction($transaction);
				
					// Portfolio
				if(!array_key_exists($transaction->portfolio->id, $this->tabs->subTab['portfolios']->subTab['resume']->data)) {
					$this->tabs->subTab['portfolios']->subTab['resume']->data[$transaction->portfolio->id] = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab['resume']->data[$transaction->portfolio->id]->name = $transaction->portfolio->name;
					$tmp_portfolio[$transaction->portfolio->id] = ['active_status' => null, 'shares' => []];
				}
				if (!array_key_exists($transaction->share->id, $tmp_portfolio[$transaction->portfolio->id]['shares'])) {
					$tmp_portfolio[$transaction->portfolio->id]['shares'][$transaction->share->id] = new User_Share_Line();
				}
				$this->tabs->subTab['portfolios']->subTab['resume']->data[$transaction->portfolio->id]->addTransaction($transaction);
				$this->tabs->subTab['portfolios']->subTab['resume']->total->addTransaction($transaction);
				$tmp_portfolio[$transaction->portfolio->id]['shares'][$transaction->share->id]->addTransaction($transaction);
				$portfolio_active = true;
				foreach($tmp_portfolio[$transaction->portfolio->id]['shares'] as $id_share => $share_detail) {
					$portfolio_active = $portfolio_active && $share_detail->quantity;
				}
				$tmp_portfolio[$transaction->portfolio->id]['active_status'] = $portfolio_active?'active':'unactive';
				
				
					// Historic
				$this->tabs->subTab['historic']->data[] = $transaction;
			}
		}
		
		// Filter by active / unactive 
		foreach ($transactions as $date_transaction) {
			foreach($date_transaction as $transaction) {
				
				// Shares tabs
				$share_status = 'unactive';
				if ($tmp_share[$transaction->share->id]->quantity) {
					$share_status = 'active';
				}
				
				$line_dashboard_id = $transaction->share->id.'_'.$transaction->portfolio->id;
				if (!array_key_exists($line_dashboard_id, $this->tabs->subTab['dashboard']->data)) {
					$this->tabs->subTab['dashboard']->data[$line_dashboard_id] = new User_Share_Line();
					$this->tabs->subTab['dashboard']->data[$line_dashboard_id]->name = $transaction->share->name.' / '.$transaction->portfolio->name;
				}
				$this->tabs->subTab['dashboard']->data[$line_dashboard_id]->addTransaction($transaction);
				$this->tabs->subTab['dashboard']->total->addTransaction($transaction);
				
				if (!array_key_exists($transaction->share->id, $this->tabs->subTab['shares']->subTab['resume']->data)) {
					$this->tabs->subTab['shares']->subTab['resume']->data[$transaction->share->id] = new User_Share_Line();
					$this->tabs->subTab['shares']->subTab['resume']->data[$transaction->share->id]->name = $transaction->share->name;
				}
				$this->tabs->subTab['shares']->subTab['resume']->data[$transaction->share->id]->addTransaction($transaction);
				$this->tabs->subTab['shares']->subTab['resume']->total->addTransaction($transaction);


				$tab_share_id = 'shares_'.$share_status.'_'.$transaction->share->id;
				if (!array_key_exists($transaction->share->id, $this->tabs->subTab['shares']->subTab[$share_status]->subTab['resume']->data)) {
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab['resume']->data[$tab_share_id] = new User_Share_Line();
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab['resume']->data[$tab_share_id]->name = $transaction->share->name;
					
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id] = new Tab();
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->id = $tab_share_id;
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->name = $transaction->share->name;
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->total = new User_Share_Line();
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->total->name = 'Total';
				}
				$this->tabs->subTab['shares']->subTab[$share_status]->subTab['resume']->data[$tab_share_id]->addTransaction($transaction);
				$this->tabs->subTab['shares']->subTab[$share_status]->subTab['resume']->total->addTransaction($transaction);
				$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->total->addTransaction($transaction);
				
				if (!array_key_exists($transaction->portfolio->id, $this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->data)) {
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->data[$transaction->portfolio->id] = new User_Share_Line();
					$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->data[$transaction->portfolio->id]->name = $transaction->portfolio->name;
				}
				$this->tabs->subTab['shares']->subTab[$share_status]->subTab[$tab_share_id]->data[$transaction->portfolio->id]->addTransaction($transaction);
				
				
				// Portfolios tabs
				$portfolio_status = 'unactive';
				if ($tmp_portfolio[$transaction->portfolio->id]) {
					$portfolio_status = 'active';
				}
				$tab_portfolio_id = 'portfolios_'.$portfolio_status.'_'.$transaction->portfolio->id;
				if (!array_key_exists($transaction->portfolio->id, $this->tabs->subTab['portfolios']->subTab['resume']->data)) {
					$this->tabs->subTab['portfolios']->subTab['resume']->data[$transaction->portfolio->id] = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab['resume']->data[$transaction->portfolio->id]->name = $transaction->portfolio->name;
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab['resume']->data[$transaction->portfolio->id] = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab['resume']->data[$transaction->portfolio->id]->name = $transaction->portfolio->name;
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id] = new Tab();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->id = $tab_portfolio_id;
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->name = $transaction->portfolio->name;
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume'] = new Tab();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->id = $tab_portfolio_id.'_resume';
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->name = 'Resume';
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->total = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->total->name = 'Total';
					
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['active'] = new Tab();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['active']->id = $tab_portfolio_id.'_active';
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['active']->name = 'Active';
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['active']->total = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['active']->total->name = 'Total';
					
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['unactive'] = new Tab();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['unactive']->id = $tab_portfolio_id.'_unactive';
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['unactive']->name = 'Unactive';
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['unactive']->total = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['unactive']->total->name = 'Total';
				}
				$this->tabs->subTab['portfolios']->subTab['resume']->data[$transaction->portfolio->id]->addTransaction($transaction);
				$this->tabs->subTab['portfolios']->subTab['resume']->total->addTransaction($transaction);
//				$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab['resume']->data[$transaction->portfolio->id]->addTransaction($transaction);
//				$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab['resume']->total->addTransaction($transaction);
				
				$portfolio_share_status = 'unactive';
				if ($tmp_portfolio[$transaction->portfolio->id]['shares'][$transaction->share->id]->quantity) {
					$portfolio_share_status = 'active';
				}
				if (!array_key_exists($transaction->share->id, $this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->data)) {
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->data[$transaction->share->id] = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->data[$transaction->share->id]->name = $transaction->share->name;
				}
//				$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->data[$transaction->portfolio->id]->addTransaction($transaction);
//				$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab['resume']->total->addTransaction($transaction);
				
				if (!array_key_exists($transaction->share->id, $this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab[$portfolio_share_status]->data)) {
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab[$portfolio_share_status]->data[$transaction->share->id] = new User_Share_Line();
					$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab[$portfolio_share_status]->data[$transaction->share->id]->name = $transaction->share->name;
				}
//				$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab[$portfolio_share_status]->data[$transaction->share->id]->addTransaction($transaction);
//				$this->tabs->subTab['portfolios']->subTab[$portfolio_status]->subTab[$transaction->portfolio->id]->subTab[$portfolio_share_status]->total->addTransaction($transaction);
				
				
			}
		}
		
	}

	public function printDebugTabs($tab) {
		$html = '
<div style="border: 1px solid #0f0f0f; margin-left: 10px;">
	<h1>'.$tab->name.' ('.$tab->id.', '.$tab->type.') </h1>';
		if (count($tab->subTab)) {
			foreach ($tab->subTab as $id_subtab => $subtab) {
				$html .= "\n<div>Include <em>".$id_subtab."</em></div>";
				$html .= "\n". $this->printDebugTabs($subtab);
			}
		} else {
			$html .= "\n<div>Number of data <em>".count($tab->data)."</em></div>";
			$html .= "\n <div>".implode(",", array_keys($tab->data))."</div>";
		}
		$html .= '
</div>';
		return $html;
	}
}
