<?php

/**
 * Class responsible for everything regarding inventory
 */

class Barclient_InventoryController extends Zend_Controller_Action {

	public function indexAction () {
		$this->view->ingredientCategories = Website_Model_IngredientCategory::getIngredientCategories();
	}

}

?>