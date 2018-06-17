<?php

/**
 * Description of Tools
 *
 */
class Tools {
	
	static public function getColor($name) {
		$safeColors = ['00','33','66','99','cc','ff'];
		
		$hash = md5($name);
		$rgb_components = str_split($hash, 2);
		
		$return = '#';
		foreach($rgb_components as $rgb_component) {
			$return .= $safeColors[$rgb_component % 6];
		}
		
		return $return;
		
	}
}
