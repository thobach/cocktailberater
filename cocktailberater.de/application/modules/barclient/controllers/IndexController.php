<?php

/**
 * Class responsible for everything regarding barclient
 */

class Barclient_IndexController extends Zend_Controller_Action {

	public function indexAction () {
		$this->_forward('index','orders');
	}

}

?>