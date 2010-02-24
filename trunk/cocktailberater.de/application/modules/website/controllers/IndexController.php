<?php

/**
 * Class responsible for everything regarding recipes
 */

class Website_IndexController extends Zend_Controller_Action {

	private $xml; // xml dom document
	private $rsp; // root element for xml document
	private $config; // config data from xml file
	private $error; // boolean - if error, don't do postDispatch

	public function __call($method, $args) {
		// if __call is called, the action was not found, therefore throw Exception
		throw new Zend_Controller_Action_Exception('Action not found');
	}

	public function indexAction () {
		$this->view->start = true ;
	}

	public function newsletterAction () {
	}

	public function newsletterSubscribeAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis1 = $newsletter->anmelden ( $this->_getParam ( 'email' ), $this->_getParam ( 'vorname' ), $this->_getParam ( 'nachname' ) ) ;
	}

	public function newsletterUnsubscribeAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis2 = $newsletter->abmelden ( $this->_getParam ( 'email' ) ) ;

	}

	public function newsletterSubscribeConfirmationAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis2 = $newsletter->anmeldungBestaetigen ( $this->_getParam ( 'email' ) ) ;
	}

	public function newsletterUnsubscribeConfirmationAction () {
		$newsletter = new Newsletter ( ) ;
		$this->view->hinweis2 = $newsletter->abmeldungBestaetigen ( $this->_getParam ( 'email' ) ) ;
	}



	/**
	 * Returns a Sitemap XML File for search engines
	 *
	 * @return String XML Sitemap
	 */
	public function sitemapAction()
	{
		// deactivate layout
		$this->_helper->layout->disableLayout();

		// load navigation nodes
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/modules/website/config/navigation.ini', 'nav');
		$pages = new Zend_Navigation($config);

		// add alcoholic cocktails
		$alcoholicCocktails = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'alcoholic' ) ;
		$alcoholics = array();
		foreach($alcoholicCocktails as $alcoholicCocktail){
			foreach ($alcoholicCocktail->getRecipes() as $recipe){
				$options = array();
				$options['label'] = $recipe->name;
				//$options['action'] = 'get';
				$options['module'] = 'website';
				$options['controller'] = 'recipe';
				$options['params'] = array();
				//$options['params']['cocktail'] = $recipe->name;
				$options['params']['id'] = $recipe->id;
				$alcoholics[$recipe->id] = new Zend_Navigation_Page_Mvc($options);
				$alcoholics[$recipe->id]->setRoute('rest');
			}
		}
		$alcoholic = $pages; //->findOneBy('action','alcoholic');
		$alcoholic->addPages($alcoholics);

		// add non-alcoholic cocktails
		$nonAlcoholicCocktails = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'non-alcoholic' ) ;
		$nonAlcoholics = array();
		foreach($nonAlcoholicCocktails as $nonAlcoholicCocktail){
			foreach ($nonAlcoholicCocktail->getRecipes() as $recipe){
				$options = array();
				$options['label'] = $recipe->name;
				//$options['action'] = 'get';
				$options['module'] = 'website';
				$options['params'] = array();
				//$options['params']['cocktail'] = $recipe->name;
				$options['params']['id'] = $recipe->id;
				$nonAlcoholics[$recipe->id] = new Zend_Navigation_Page_Mvc($options);
				$nonAlcoholics[$recipe->id]->setRoute('rest');
			}
		}
		$nonAlcoholic = $pages; //->findOneBy('action','non-alcoholic');
		$nonAlcoholic->addPages($nonAlcoholics);

		// add top10 cocktails
		$top10Cocktails = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'top10' ) ;
		$top10s = array();
		foreach($top10Cocktails as $top10Cocktail){
			foreach ($top10Cocktail->getRecipes() as $recipe){
				$options = array();
				$options['label'] = $recipe->name;
				$options['action'] = 'recipe';
				$options['module'] = 'website';
				$options['params'] = array();
				$options['params']['cocktail'] = $recipe->name;
				$options['params']['id'] = $recipe->id;
				$top10s[$recipe->id] = new Zend_Navigation_Page_Mvc($options);
				$top10s[$recipe->id]->setRoute('rest');
			}
		}
		$top10 = $pages; //->findOneBy('action','top10');
		$top10->addPages($top10s);

		// pass all page nodes to the view
		$this->view->pages = $pages;

		// set header to XML
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}

}

?>