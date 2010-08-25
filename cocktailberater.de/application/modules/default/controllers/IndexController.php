<?php

/**
 *
 * @author thobach
 *
 */
class IndexController extends Zend_Controller_Action{

	/**
	 * display website module with it's layout
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->_forward('index','index','website');	
	}

}

