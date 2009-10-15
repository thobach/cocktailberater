<?php


/**
 * Class responsible for everything regarding recipes and the REST API
 */

class Api_RecipeController extends Zend_Controller_Action {

	private $xml; // xml dom document
	private $rsp; // root element for xml document
	private $config; // config data from xml file
	private $error; // boolean - if error, don't do postDispatch

	public function preDispatch() {
		// do not automatically create a view object
		$this->_helper->viewRenderer->setNoRender();
		// disable layouts for this controller
		$this->_helper->layout->disableLayout();
		// set http header to xml
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
		// create a dom object
		$this->xml = new DOMDocument("1.0");
		// create root element 'rsp'
		$this->rsp = $this->xml->createElement("rsp");
		// add the 'rsp' element to the xml document
		$this->xml->appendChild($this->rsp);
		// load config data
		$this->config = Zend_Registry :: get('config');
	}

	public function __call($method, $args)
	{
		try {
			// if __call is called, the action was not found, therefore throw Exception
			throw new Zend_Controller_Action_Exception('Action not found');
		} catch (Exception $e) {
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}

	public function indexAction()
	{
		// if index action is called, forward to proper page in the wiki
		$this->_redirector = $this->_helper->getHelper('Redirector');
		$this->_redirector->gotoUrl('http://wiki.cocktailberater.de/index.php?title=Cb-recipe-get');
	}

	public function getAction() {
		try {
			if($this->_getParam('id')=='' OR $this->_getParam('id')==0){
				// if the id parameter is missing, throw exception
				throw new RecipeException('Id_Missing');
			} else {
				// TODO: weitermachen
				$recipe = new Recipe($this->_getParam('id'));
				// get xml representation of recipe object
				$recipe->toXml($this->xml, $this->rsp);
				// set status for root element 'rsp' to 'ok'
				$this->rsp->setAttribute('status','ok');
			}
		} catch (Exception $e){
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}

	}

	public function getAllAction() {
		$recipes = $this->xml->createElement('recipes');
		$recipeList = Recipe::listRecipes();
		if(is_array($recipeList)){
			foreach ($recipeList as $recipe){
				$recipe->toXml($this->xml,$recipes);
			}
		}
		// set count for recipe element according to number of recipes found
		$recipes->setAttribute('count',count($recipeList));
		// add the '$recipes' element to the 'rsp' element
		$this->rsp->appendChild($recipes);
		// set status for root element 'rsp' to 'ok'
		$this->rsp->setAttribute('status','ok');
	}

	public function addAction() {
		return false;
	}

	public function updateAction() {
		return false;
	}

	public function removeAction() {
		return false;
	}

	public function postDispatch(){
		if(!$this->error){
			// save and display tree
			print $this->xml->saveXML();
		}
	}

}