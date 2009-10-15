<?php


/**
 * Class responsible for forwarding to wiki
 */

class Api_IndexController extends Zend_Controller_Action {

	public function preDispatch() {
		$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_redirector->gotoUrl('http://wiki.cocktailberater.de/index.php?title=API');
	}

}