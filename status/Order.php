<?php

class Order {
	var $id;
	var $username;
	var $cocktailname;
	var $pictureURL;
	var $status; // finished ordered
      var $position; 

	public function __set($key,$val) {
		$this->$key=$val;
	}
	public function __get($key) {
		return $this->$key;
	}

}
?>