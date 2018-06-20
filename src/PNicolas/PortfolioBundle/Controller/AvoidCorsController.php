<?php
/**
 * Controller to avoid Cors
 *
 */

require_once(__DIR__.'/../Services/Curl.php');
class AvoidCorsController {
	
	public function __construct() {
		$curl = new Curl();
		
		header('Content-Type: application/json;charset=utf-8');
		$url = urldecode($_REQUEST['url']);
		$result = $curl->doRequest($url);

		
		http_response_code($result['http_header']['response_code']);
		echo trim(json_encode($result));
		
		die();
	}
	
}
$request = new AvoidCorsController();