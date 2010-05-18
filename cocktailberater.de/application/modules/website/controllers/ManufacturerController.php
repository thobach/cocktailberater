<?php
/**
 * Context sensitive Controller for manufacturer matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_ManufacturerController extends Wb_Controller_RestController {

	public function indexAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_ManufacturerController->indexAction',Zend_Log::DEBUG);

		$this->view->manufacturers = Website_Model_Manufacturer::listManufacturer();
		$this->view->title = 'Liste aller Hersteller/Marken von Cocktailzutaten';
	}

	public function getAction(){
		if ($this->_hasParam ( 'id' )) {
			$realId = Website_Model_Manufacturer::exists($this->_getParam('id'));
			// throw exception if id could not be found
			if(!$realId){
				throw new Website_Model_ManufacturerException('Incorrect_Id');
			}
			$this->view->manufacturer = Website_Model_CbFactory::factory('Website_Model_Manufacturer',$realId);
			$this->view->title = $this->view->manufacturer->name.' Produkte und Cocktailrezepte';
		}
	}

}