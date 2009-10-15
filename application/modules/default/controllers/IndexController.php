<?php

/**
 * 
 * @author thobach
 *
 */
class IndexController extends Zend_Controller_Action{
	
	/**
	 * forward to website module with it's layout
	 * 
	 * @return void
	 */
	public function indexAction() {
		$this->_helper->layout()->setLayoutPath(APPLICATION_PATH.'/modules/website/layouts/');
		$this->_forward('index','index','website');
	}
	
}

