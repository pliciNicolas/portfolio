<?php
/**
 * Description of User
 *
 */
class User {
	public $id = null;
	public $name = null;
	
	/**
	 * Set object
	 * @param int $id
	 * @param string $name
	 */
	public function set($id, $name) {
		$this->id = $id;
		$this->name = $name;
	}
}
