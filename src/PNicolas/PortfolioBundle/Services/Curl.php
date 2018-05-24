<?php
/**
 * Made a curl request
 *
 */
class Curl {
	public function performRequest($url, $method, $data_get, $data_post, $header_to_send, $curl_options)
    {
        
		if ($data_get) {
			$url .= $data_get;
		}

        $data_string = json_encode($data_post);

        $ch = curl_init();

        $curl_options[CURLOPT_URL] = $url;
		if (is_array($header_to_send) && count($header_to_send)) {
			$curl_options[CURLOPT_HTTPHEADER] = $header_to_send;
		}
        curl_setopt_array($ch, $curl_options);

        switch (strtoupper($method)) {
            case 'GET':
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                break;
            case 'DELETE':
                 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                break;
        }

        $timestamp_start = microtime(true);

        $response = curl_exec($ch);

		$timestamp_end = microtime(true);

        $return = $this->getReturn($ch, $response, ($timestamp_end-$timestamp_start));

        curl_close($ch);

		return $return;
    }

    /**
     * Get return of curl exec.
     *
     * @param mixed  $ch
     * @param string $response
     * @param int $time
     *
     * @return array
     */
	protected function getReturn($ch, $response, $time) {
        $return = array(
							'error' => array(
								'error' => false,
								'message' => null,
							)
                            ,'curl' => array(
                                'error' => null,
                                'error_no' => null,
								'exec_time' => $time,
                            ),
                            'http_header' => array(
                                'response_code' => null,
                                'response_label' => null,
                                'content_type' => null,
                                'raw_headers' => array(),
                            ),
                            'data' => null,
        );
		
		// Fill curl information
		$return['curl']['error'] = curl_error($ch);
        $return['curl']['error_no'] = curl_errno($ch);

        // Then, after your curl_exec call:
        if (false !== $response) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $header = trim(substr($response, 0, $header_size));
            $body = substr($response, $header_size);

            // Fill http header informations
            $headers = $this->parseHeader($header);
            $return['http_header']['raw_headers'] = $headers['raw_headers'];
            $return['http_header']['response_code'] = $headers['response_code'];
            $return['http_header']['response_label'] = $headers['response_label'];
            $return['http_header']['content_type'] = $headers['content_type'];

            // Fill data
            if (false !== strpos($return['http_header']['content_type'], 'application/json')) {
                $body = json_decode($body, true);
            }
            $return['data'] = $body;
        }
		
		return json_encode($return);
	}
	
    /**
     * Parse headers to get usefull informations :
     * -raw_headers
     * -response_code
     * -response_label
     * -content_type.
     *
     * @param string $header
     *
     * @return array
     */
    protected function parseHeader($header)
    {
        $return = array(
                    'raw_headers' => null,
                    'response_code' => null,
                    'response_label' => null,
                    'content_type' => null,
        );

        $return['raw_headers'] = explode("\r\n", $header);
        $matchs_response = array();
        if (preg_match('@HTTP/[0-9.]*\s([0-9]*)\s(.*)@i', $header, $matchs_response)) {
            $return['response_code'] = (int) $matchs_response[1];
            $return['response_label'] = trim($matchs_response[2]);
        }
        $match_content_type = array();
        if (preg_match('@Content-Type:\s(.*)@i', $header, $match_content_type)) {
            $return['content_type'] = trim($match_content_type[1]);
        }

        return $return;
    }
}
