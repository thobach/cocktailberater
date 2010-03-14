<?php
/**
 * Context sensitive Controller for party matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_PartyController extends Wb_Controller_RestController {

	public function indexAction() {
		$list = Website_Model_Party::listPartys();
		$this->view->partys = $list ;
		$this->view->title = 'Partyliste';
	}

}