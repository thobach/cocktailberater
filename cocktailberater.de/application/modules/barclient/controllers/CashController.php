<?php

/**
 * Class responsible for everything regarding cashing guests
 */

class Barclient_CashController extends Zend_Controller_Action {

	public function indexAction () {
		$this->view->unpaidOrdersByGuests = Website_Model_Order::getUnpaidOrders(2);
		$this->view->partys = Website_Model_Party::listPartys(54);
		$this->view->currentParty = new Website_Model_Party(2); 
		// Zend_Debug::dump(Website_Model_Order::getUnpaidOrders(2));
	}

}

?>