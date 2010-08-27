<?php
/**
 * Context sensitive Controller for product matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_ProductController extends Wb_Controller_RestController {

	public function indexAction(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_ProductController->indexAction',Zend_Log::DEBUG);

		$this->view->products = Website_Model_Product::listProduct();
		$this->view->title = 'Liste aller Produkte für Cocktailzutaten';
	}

	public function getAction(){
		if ($this->_hasParam ( 'id' )) {
			$realId = Website_Model_Product::exists($this->_getParam('id'));
			// throw exception if id could not be found
			if(!$realId){
				throw new Website_Model_ProductException('Incorrect_Id');
			}
			$this->view->product = Website_Model_CbFactory::factory('Website_Model_Product',$realId);
			$this->view->title = $this->view->product->getManufacturer()->name.' '.$this->view->product->name.' Cocktailrezepte und Produktdetails';
			$this->view->placeholder('label')->set($this->view->product->name);
		}
	}

}