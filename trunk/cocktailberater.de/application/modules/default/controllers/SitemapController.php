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
			$page = new Zend_Navigation_Page_Mvc($options);
			$page->set('lastmod',$cocktail->updateDate);
			$page->set('changefreq','monthly');
			$page->set('priority','0.8');
			$page->setRoute('rest');
			$cocktails2['c'.$cocktail->id] = $page;
			if(is_array($cocktail->getRecipes())){
				foreach ($cocktail->getRecipes() as $recipe){
					$options = array();
					$options['label'] = $recipe->name;
					$options['module'] = 'website';
					$options['controller'] = 'recipe';
					$options['params'] = array();
					$options['params']['id'] = $recipe->getUniqueName();
					$page = new Zend_Navigation_Page_Mvc($options);
					$page->set('lastmod',$recipe->updateDate);
					$page->set('changefreq','monthly');
					$page->set('priority','0.9');
					$page->setRoute('rest');
					$cocktails2['r'.$recipe->id] = $page;
				}
			}
		}
		$alcoholic = $pages; //->findOneBy('action','alcoholic');
		$alcoholic->addPages($cocktails2);
		
		// add ingredients
		$ingredients = Website_Model_Ingredient::listIngredients ( '%' ) ;
		$ingredients2 = array();
		foreach($ingredients as $ingredient){
			$options = array();
			$options['label'] = $ingredient->name;
			$options['module'] = 'website';
			$options['controller'] = 'ingredient';
			$options['params'] = array();
			$options['params']['id'] = $ingredient->getUniqueName();
			$page = new Zend_Navigation_Page_Mvc($options);
			$page->set('lastmod',$ingredient->updateDate);
			$page->set('changefreq','monthly');
			$page->set('priority','0.7');
			$page->setRoute('rest');
			$ingredients2['i'.$ingredient->id] = $page;
		}
		$ingredient = $pages; //->findOneBy('action','alcoholic');
		$ingredient->addPages($ingredients2);
		
		// add products
		$products = Website_Model_Product::listProduct() ;
		$products2 = array();
		foreach($products as $product){
			$options = array();
			$options['label'] = $product->name;
			$options['module'] = 'website';
			$options['controller'] = 'product';
			$options['params'] = array();
			$options['params']['id'] = $product->getUniqueName();
			$page = new Zend_Navigation_Page_Mvc($options);
			$page->set('lastmod',$product->updateDate);
			$page->set('changefreq','monthly');
			$page->set('priority','0.4');
			$page->setRoute('rest');
			$products2['i'.$product->id] = $page;
		}
		$product = $pages; //->findOneBy('action','alcoholic');
		$product->addPages($products2);
		
		// add manufacturers
		$manufacturers = Website_Model_Manufacturer::listManufacturer();
		$manufacturers2 = array();
		foreach($manufacturers as $manufacturer){
			$options = array();
			$options['label'] = $manufacturer->name;
			$options['module'] = 'website';
			$options['controller'] = 'manufacturer';
			$options['params'] = array();
			$options['params']['id'] = $manufacturer->getUniqueName();
			$page = new Zend_Navigation_Page_Mvc($options);
			$page->set('lastmod',$manufacturer->updateDate);
			$page->set('changefreq','monthly');
			$page->set('priority','0.5');
			$page->setRoute('rest');
			$manufacturers2['i'.$manufacturer->id] = $page;
		}
		$manufacturer = $pages; //->findOneBy('action','alcoholic');
		$manufacturer->addPages($manufacturers2);

		// pass all page nodes to the view
		$this->view->pages = $pages;

		// set header to XML
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
	}

}

