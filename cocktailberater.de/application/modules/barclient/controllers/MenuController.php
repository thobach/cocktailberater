<?php

/**
 * Class responsible for everything regarding menu
 */

class Barclient_MenuController extends Zend_Controller_Action {

	public function indexAction () {
		$this->view->recipes = Website_Model_Recipe::listRecipes();
	}

}

?>