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
		//$this->_helper->layout()->setLayoutPath(APPLICATION_PATH.'/modules/website/layouts/');
		$this->_redirect($this->view->url(array(
							'module'=>'website',
							'controller'=>'index',
							'action'=>'index'),null,true).'?format='.$this->_getParam('format','html'));
		/* 
		 * a simple forward made the url() helper work wrong (didn't accept 
		 * the actions)
		 * $this->_forward('index','index','website');
		 */ 
	}

}

