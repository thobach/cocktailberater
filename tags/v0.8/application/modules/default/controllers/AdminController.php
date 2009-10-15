<?php

/**
 * 
 * @author thobach
 *
 */
class AdminController extends Zend_Controller_Action{
	
	/**
	 * 
	 * @return void
	 */
	public function indexAction() {

	}
	
	/**
	 * 
	 * @return void
	 */
	public function disableAnalyticsAction() {
		$this->_helper->layout()->disableLayout();
	}
}

