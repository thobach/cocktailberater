<?php
/**
 * Context sensitive Controller for menue matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_MenueController extends Wb_Controller_RestController {

	public function indexAction(){
		$this->view->menues =  Website_Model_Menu::listMenu();
	}

	public function getAction(){
		if(Website_Model_Menu::exists($this->_getParam('id'))){
			$this->view->menue =  Website_Model_CbFactory::factory('Website_Model_Menu',$this->_getParam('id'));
		}
	}
	
}