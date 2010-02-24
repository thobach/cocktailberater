<?php
/**
 * Context sensitive Controller for cocktail matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_BarController extends Wb_Controller_RestController {

	public function indexAction() {
		$list = Website_Model_Bar::listBars();
		$this->view->bars = $list ;
		$this->view->title = 'Barlist';
	}

}