<?php

class Share_Webservice {
	
	static protected $share_symbol = [];
	
	static public function add(\Share $share, \Webservice $webservice, $symbol) {
		if (!array_key_exists($webservice->id, self::$share_symbol)) {
			self::$share_symbol[$webservice->id] = [];
		}
		self::$share_symbol[$webservice->id][$share->id] = [ 'share' => $share, 'webservice' => $webservice, 'symbol' =>$symbol];
	}
	
	static public function get(\Share $share, \Webservice $webservice) {
		return self::$share_symbol[$webservice->id][$share->id]['symbol'];
	}
}
