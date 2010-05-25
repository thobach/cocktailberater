<?php
/**
 * Context sensitive Controller for recipe matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_RecipeController extends Wb_Controller_RestController {

	/**
	 * lists all recipes, also used for search and search suggestion (format=ajax)
	 * 
	 * @representation html, xml, json, rss, atom, pdf, ajax
	 * @param search optional search phrase
	 * @param search_type optional
	 * 	for search suggestion (format=ajax) it can be name, ingredient or tag
	 * 	for all other search_type it can be alcoholic, non-alcoholic, top10, difficulty, menu, ingredient, image, tag or name
	 * 	if none is given, all recipies are returned
	 */
	public function indexAction() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_RecipeController->indexAction',Zend_Log::DEBUG);
		
		// auto-suggest for search
		if($this->_getParam('format', false)=='ajax'){
			$log->log('Website_RecipeController->indexAction -> ajax',Zend_Log::DEBUG);
			// get suggestions for the given search type
			$this->suggestions = array();
			$search = str_replace('*',null,$this->_getParam ( 'search' ));
			if ($this->_getParam ( 'search_type' ) == 'name' || !$this->_hasParam ( 'search_type' )) {
				$this->suggestions = Website_Model_Recipe::searchByName ( $search, 6 ) ;
			} elseif ($this->_getParam ( 'search_type' ) == 'ingredient') {
				$this->suggestions = Website_Model_Ingredient::listIngredients ($search, 6 ) ;
			} elseif ($this->_getParam ( 'search_type' ) == 'tag') {
				$this->suggestions = Website_Model_Tag::listTags ( $search,6,true) ;
			}
			// filter only names of the matches
			$matches = array();
			foreach ($this->suggestions as $suggestion) {
				$matches[] = $suggestion->name;
			}
			// return as json for dojo
			//$this->_helper->autoCompleteDojo($matches);
			$dojoHelper = $this->_helper->autoCompleteDojo;
			$dojoHelper->suppressExit = true;
			$dojoHelper->direct($matches); // kills the unit test
		}
		// search
		else if ($this->_hasParam('search_type')){
			switch ($this->_getParam('search_type')) {
				case 'alcoholic':
					$log->log('Website_RecipeController->indexAction -> alcoholic',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByName('%',null,'alcoholic') ;
					$this->view->title = 'Alkoholische Cocktailrezepte';
					break;
				case 'non-alcoholic':
					$log->log('Website_RecipeController->indexAction -> non-alcoholic',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByName('%',null,'non-alcoholic') ;
					$this->view->title = 'Alkoholfreie Cocktailrezepte';
					break;
				case 'top10':
					$log->log('Website_RecipeController->indexAction -> top10',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByName('%',10,'top10') ;
					$this->view->title = 'Top 10 Cocktailrezepte';
					$this->view->top10=true;
					break;
				case 'difficulty':
					$log->log('Website_RecipeController->indexAction -> difficulty',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByDifficulty($this->_getParam('search')) ;
					$this->view->title = 'Einfache Cocktailrezepte';
					break;
				case 'menu':
					$log->log('Website_RecipeController->indexAction -> menu',Zend_Log::DEBUG);
					$menu = Website_Model_CbFactory::factory('Website_Model_Menu',$this->_getParam('search'));
					$list = $menu->listRecipes();
					$this->view->title = 'Einfache Cocktailrezepte';
					break;
				case 'ingredient':
					$log->log('Website_RecipeController->indexAction -> ingredient',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByIngredient($this->_getParam('search')) ;
					$this->view->title = 'Cocktails mit der Zutat '.$this->_getParam('search');
					break;
				case 'image':
					$log->log('Website_RecipeController->indexAction -> image',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByName('%',null,'with-image') ;
					$this->view->title = 'Cocktailrezepte mit Bildern';
					break;
				case 'tag':
					$log->log('Website_RecipeController->indexAction -> tag',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByTag($this->_getParam('search')) ;
					$this->view->title = 'Cocktailrezepte mit dem Tag '.$this->_getParam('search');
					break;
				case 'name':
					$log->log('Website_RecipeController->indexAction -> name',Zend_Log::DEBUG);
					$list = Website_Model_Recipe::searchByName($this->_getParam('search')) ;
					$this->view->title = 'Cocktailrezepte mit dem Namen '.$this->_getParam('search');
					break;
			}
		}
		// list all
		else {
			$log->log('Website_RecipeController->indexAction -> list all',Zend_Log::DEBUG);
			$list = Website_Model_Recipe::searchByName('%') ;
			$this->view->title = 'Liste aller Cocktailrezepte';
		}
		$this->view->recipes = $list ;
		$log->log('Website_RecipeController->indexAction -> exiting',Zend_Log::DEBUG);
	}

	/**
	 * displays a recipe of a cocktail
	 * 
	 * @representation html, xml, json, atom, rss
	 * @param id int or string with ID or unique name
	 */
	public function getAction(){
		// check if recipe id / name was given
		if($this->_getParam('id')==''){
			// if the id parameter is missing or empty, throw exception
			throw new Website_Model_RecipeException('Id_Missing');
		} else if($this->_getParam('id')==0){
			// if the id parameter is textual, fetch matching id
			$this->_setParam('id',Website_Model_Recipe::exists($this->_getParam('id')));
			// throw exception if id could not be found
			if($this->_getParam('id')==false || $this->_getParam('id')<=0){
				throw new Website_Model_RecipeException('Incorrect_Id');
			}
		}
		if($this->_hasParam('comments')){
			$this->view->commentsOnly = true;
		}
		$this->view->recipe =  Website_Model_CbFactory::factory('Website_Model_Recipe',$this->_getParam('id'));
		
		// ??
		$this->view->xmlLink = array(
						'rel' => 'alternate',
						'type' => 'application/xml',
						'title' => 'cocktailberater - XML API',
						'href' => $this->view->url(array(
							'id'=>$this->_getParam('id'),
							'cocktail'=>$this->_getParam('cocktail'),'rep'=>'xml')));
		// ??
		$this->view->headLink($this->view->xmlLink);
		
		$this->view->alternatives = $this->view->recipe->getCocktail()->getRecipes();
		// html page title
		$this->view->title = $this->view->recipe->name.' Cocktailrezept' ;
		// for feed.phtml -> rss or atom
		$this->view->format = $this->_getParam('format');
	}

}