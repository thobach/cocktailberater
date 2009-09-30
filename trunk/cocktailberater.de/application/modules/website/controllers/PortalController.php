<?php

/**
 * Class responsible for everything regarding the portal
 */

class Website_PortalController extends Zend_Controller_Action {

	public function indexAction () {

	}
	public function cocktailDerWocheAction () {

	}
	public function top10DrinksAction () {

	}
	public function forumAction () {

	}
	public function loginAction () {

	}
	
	public function meineHausbarAction () {
		//Zend_Debug::dump($this->_getAllParams());
		//fetch all products from the current bar
		$this->view->bar = $bar = new Website_Model_Bar(1);
		$this->view->currentProducts = $bar->getProducts();
		//Zend_Debug::dump($this->view->currentProducts);
		//if form was posted, insert into database
		if($this->getRequest()->isPost()){
			$bar->removeProducts();
			$this->view->message='Die abgewählten Produkte wurden aus Ihrer Hausbar entfernt.';
			$products = $this->_getParam('product2bar');
			if(is_array($products)) {
				foreach($products as $product) {
					$bar->addProduct($product);
				}
				//print 'neue produkte hinzugefügt<br />';
				$this->view->message='Die ausgewählten Produkte wurden in Ihrer Hausbar angelegt.';
			} else {
				//print 'keine produkte wieder hinzugefügt<br />';
			}
			//print 'post-methode<br />';
		} else {
			//print 'get-methode<br />';
		}
	}
	
	public function meineHausbarPrintAction () {
		//fetch all products from the current bar
		$this->view->bar = $bar = new Bar(1);
		$this->view->currentProducts = $bar->getProducts();
	}
	
	public function meineFavoritenAction () {

	}
	public function meineCocktailsAction () {

	}
	public function meinCocktailbuchAction () {

	}
	public function glasAction () {

	}
	public function utensilienAction () {

	}
	public function nutritionAction () {
		$this->view->cocktails = Website_Model_Cocktail::listCocktails('');
		//Zend_Debug::dump($cocktails);
	}
	public function mixtechnikenAction () {

	}
	public function grundausstattungAction () {

	}
	public function zutatenAction () {

	}
	public function buecherAction () {

	}
public function andereSeitenAction () {

	}

}

?>