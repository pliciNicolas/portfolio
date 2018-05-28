<?php
/**
 * Controller to avoid Cors
 *
 */

require_once(__DIR__.'/../Services/Curl.php');
class AvoidCorsController {
	
	public function __construct() {
		$curl = new Curl();
		
		$url = urldecode($_REQUEST['url']);
		$method = 'GET';
		$data_get = null;
		$data_post = null;
		$header_to_send =  array(
			'Content-Type:application/json;charset="utf-8"',
			'Accept:application/json',
			'Cache-Control: no-cache',
			'Pragma: no-cache',
        );
		$curl_options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_HEADER => 1,
            CURLOPT_CONNECTTIMEOUT => 0,
		);
		
		
		header('Content-Type: application/json;charset=utf-8');
		$result = $curl->performRequest($url, $method, $data_get, $data_post, $header_to_send, $curl_options);
		
		http_response_code($result['http_header']['response_code']);
		echo trim(json_encode($result));
		
		die();
	}
	
}
$request = new AvoidCorsController();