<?php

/**
 *
 * @author thobach
 *
 */
class SitemapController extends Zend_Controller_Action{

	/**
	 * Returns a Sitemap XML File for search engines
	 *
	 * @return String XML Sitemap
	 */
	public function indexAction() {
		// deactivate layout
		$this->_helper->layout->disableLayout();

		// load navigation nodes
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/modules/website/config/navigation.ini', 'nav');
		$pages = new Zend_Navigation($config);

		// add cocktails
		$cocktails = Website_Model_Cocktail::listCocktails ( NULL, NULL, 'name' ) ;
		$cocktails2 = array();
		foreach($cocktails as $cocktail){
			$options = array();
			$options['label'] = $recipe->name;
			$options['module'] = 'website';
			$options['controller'] = 'cocktail';
			$options['params'] = array();
			$options['params']['id'] = $cocktail->getUniqueName();
			$cocktails2['c'.$cocktail->id] = new Zend_Navigation_Page_Mvc($options);
			$cocktails2['c'.$cocktail->id]->setRoute('rest');
			foreach ($cocktail->getRecipes() as $recipe){
				$options = array();
				$options['label'] = $recipe->name;
				$options['module'] = 'website';
				$options['controller'] = 'recipe';
				$options['params'] = array();
				$options['params']['id'] = $recipe->getUniqueName();
				$cocktails2['r'.$recipe->id] = new Zend_Navigation_Page_Mvc($options);
				$cocktails2['r'.$recipe->id]->setRoute('rest');
			}
		}
		$alcoholic = $pages; //->findOneBy('action','alcoholic');
		$alcoholic->addPages($cocktails2);
		
		// add ingredients
		$ingredients = Website_Model_Ingredient::listIngredients ( '%' ) ;
		$ingredients2 = array();
		foreach($ingredients as $ingredient){
			$options = array();
			$options['label'] = $recipe->name;
			$options['module'] = 'website';
			$options['controller'] = 'ingredient';
			$options['params'] = array();
			$options['params']['id'] = $ingredient->getUniqueName();
			$ingredients2['i'.$ingredient->id] = new Zend_Navigation_Page_Mvc($options);
			$ingredients2['i'.$ingredient->id]->setRoute('rest');
		}
		$ingredient = $pages; //->findOneBy('action','alcoholic');
		$ingredient->addPages($ingredients2);

		// pass all page nodes to the view
		$this->view->pages = $pages;

		// set header to XML
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}

}

