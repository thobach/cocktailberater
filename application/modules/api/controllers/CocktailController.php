<?php


/**
 * Class responsible for everything regarding cocktails and the REST API
 */

class Api_CocktailController extends Zend_Controller_Action {

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
		$this->_redirector->gotoUrl('http://wiki.cocktailberater.de/index.php?title=Cb-cocktail-get');
	}

	public function getAction() {
		try {
			if($this->_getParam('id')==''){
				// if the id parameter is missing, throw exception
				throw new CocktailException('Id_Missing');
			} else {
				// load cache from registry
				$cache = Zend_Registry::get('cache');
				// see if a cache already exists:
				if(!$cocktail = $cache->load('cocktail'.$this->_getParam('id'))) {
					// get cocktail, no search since id given
					$cocktail = new Cocktail($this->_getParam('id'));
					$cache->save ($cocktail,'cocktail'.$this->_getParam('id'));
				}
				// get xml representation of cocktail object
				$cocktail->toXml($this->xml, $this->rsp);
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
		// start counting time
		$queryTime = microtime(true);
		// load cache from registry
		$cache = Zend_Registry::get('cache');
		// create cocktails xml element
		$cocktails = $this->xml->createElement('cocktails');
		// see if getAll - list is already in cache
		if(!$cocktailListe = $cache->load('cocktaillist'.$this->_getParam('name'))) {
			$cocktailListe = Cocktail::listCocktails($this->_getParam('name'));
			$cache->save ($cocktailListe,'cocktaillist'.$this->_getParam('name'));
		}
		// add cocktails in xml representation to cocktails element
		if(is_array($cocktailListe)){
			foreach ($cocktailListe as $cocktail){
				$cocktail->toXml($this->xml,$cocktails);
			}
		}
		// set count for cocktails element according to number of cocktails found
		$cocktails->setAttribute('count',count($cocktailListe));
		// add the 'cocktails' element to the 'rsp' element
		$this->rsp->appendChild($cocktails);
		// set status for root element 'rsp' to 'ok'
		$this->rsp->setAttribute('status','ok');
		// finish counting time
		$queryTime = microtime(true)-$queryTime;
		$this->rsp->setAttribute('queryTime',$queryTime.'s');
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