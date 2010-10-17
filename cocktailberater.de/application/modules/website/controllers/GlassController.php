<?php
/**
 * Context sensitive Controller for glass matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_GlassController extends Wb_Controller_RestController {

	/**
	 * displays a glass
	 * 
	 * @representation image
	 * @param id int or string with ID
	 */
	public function getAction(){
		// check if recipe id / name was given
		if($this->_getParam('id')==''){
			// if the id parameter is missing or empty, throw exception
			throw new Website_Model_GlassException('Id_Missing');
		}
		$this->view->glass = Website_Model_CbFactory::factory(
		'Website_Model_Glass',$this->_getParam('id'));
		
		// for image content-type
		$this->view->response = $this->getResponse();
	}

}