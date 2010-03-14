<?php
/**
 * Context sensitive Controller for recipe matters
 *
 * @author thobach
 *
 */
require_once(APPLICATION_PATH.'/Wb/Controller/RestController.php');
class Website_RecipeController extends Wb_Controller_RestController {
	
	public function indexAction() {
		// auto-suggest for search
		if($this->_getParam('format', false)=='ajax'){			
			// only for AJAX HTTP Get calls, otherwise forward to main page
			if ('ajax' != $this->_getParam('format', false)) {
				return $this->_helper->redirector('index');
			}
			if ($this->getRequest()->isPost()) {
				return $this->_helper->redirector('index');
			}
			// get suggestions for the given search type
			$this->suggestions = array();
			$search = str_replace('*',null,$this->_getParam ( 'search' ));
			if ($this->_getParam ( 'search_type' ) == 'name' || !$this->_hasParam ( 'search_type' )) {
				$this->suggestions = Website_Model_Recipe::searchByName ( $search, 6 ) ;
				//Zend_Debug::dump($this->suggestions);
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
			$this->_helper->autoCompleteDojo($matches);
		}
		// search
		else if ($this->_hasParam('search_type')){
			switch ($this->_getParam('search_type')) {
				case 'alcoholic':
					$list = Website_Model_Recipe::searchByName('%',null,'alcoholic') ;
					$this->view->title = 'Alkoholische Cocktailrezepte';
					break;
				case 'non-alcoholic':
					$list = Website_Model_Recipe::searchByName('%',null,'non-alcoholic') ;
					$this->view->title = 'Alkoholfreie Cocktailrezepte';
					break;
				case 'top10':
					$list = Website_Model_Recipe::searchByName('%',10,'top10') ;
					$this->view->title = 'Top 10 Cocktailrezepte';
					$this->view->top10=true;
					break;
				case 'difficulty':
					$list = Website_Model_Recipe::searchByDifficulty($this->_getParam('search')) ;
					$this->view->title = 'Einfache Cocktailrezepte';
					break;
				case 'ingredient':
					$list = Website_Model_Recipe::searchByIngredient($this->_getParam('search')) ;
					$this->view->title = 'Cocktails mit der Zutat '.$this->_getParam('search');
					break;
				case 'image':
					$list = Website_Model_Recipe::searchByName('%',null,'with-image') ;
					$this->view->title = 'Cocktailrezepte mit Bildern';
					break;
				case 'tag':
					$list = Website_Model_Recipe::searchByTag($this->_getParam('search')) ;
					$this->view->title = 'Cocktailrezepte mit dem Tag '.$this->_getParam('search');
					break;
				case 'name':
					$list = Website_Model_Recipe::searchByName($this->_getParam('search')) ;
					$this->view->title = 'Cocktailrezepte mit dem Namen '.$this->_getParam('search');
					break;
			}
		}
		// list all
		else {
			$list = Website_Model_Recipe::searchByName('%') ;
			$this->view->title = 'Liste aller Cocktailrezepte';
		}
		$this->view->recipes = $list ;
	}

	public function getAction(){
		// wenn ein Cocktail angegeben wurde
		try {
			if($this->_getParam('id')=='' OR $this->_getParam('id')==0){
				// if the id parameter is missing, throw exception
				throw new Website_Model_RecipeException('Id_Missing');
			} else {			
				$recipe = new Website_Model_Recipe ( $this->_getParam ( 'id' ) ) ;
				// XML representation
				if($this->_getParam('rep','html5')=='xml'){
					// get xml representation of recipe object
					$recipe->toXml($this->xml, $this->rsp);
					// set status for root element 'rsp' to 'ok'
					$this->rsp->setAttribute('status','ok');
				}
				// HMTL5 representation
				else if($this->_getParam('rep','html5')=='html5'){
					$this->view->recipe = $recipe;
					$cocktail = new Website_Model_Cocktail($recipe->cocktailId);
					$this->view->xmlLink = array(
						'rel' => 'alternate',
						'type' => 'application/xml',
						'title' => 'cocktailberater - XML API',
						'href' => $this->view->url(array(
							'id'=>$this->_getParam('id'),
							'cocktail'=>$this->_getParam('cocktail'),'rep'=>'xml')));
					$this->view->headLink($this->view->xmlLink);
					$this->view->alternatives = $cocktail->getRecipes();
					$this->view->title = $this->view->recipe->name.' Cocktail Rezepte' ;
				} else if ($this->_getParam('rep','html5')=='rss' || $this->_getParam('rep','html5')=='atom'){
					$components = '';
					foreach ($recipe->getComponents() as $component){
						$components .= $component->amount.' '.$component->unit.' '.$component->getIngredient()->name.'<br />';
					}
					$entries[] = array(
						'title'       => $recipe->name,
						'link'        => $this->view->url(array(
							'id'=>$this->_getParam('id'),
							'cocktail'=>$this->_getParam('cocktail'),'rep'=>'xml')),
						'description' => $recipe->description."<br /><br />".$components."<br />".$recipe->instruction,
					);

					// Create the RSS array
					$rss = array(
				       'title'   => 'cocktailberater',
				       'link'    => 'http://www.cocktailberater.de',
				       'charset' => 'UTF-8',
				       'entries' => $entries
					);

					// Import the array
					$feed = Zend_Feed::importArray($rss, $this->_getParam('rep'));
					print $feed->saveXML();
					exit;
				}
			}
		} catch (Exception $e){
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}

}