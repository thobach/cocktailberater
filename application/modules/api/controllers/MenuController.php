<?php


/**
 * Class responsible for everything regarding menues and the REST API
 */

class Api_MenuController extends Zend_Controller_Action {

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
		$this->_redirector->gotoUrl('http://wiki.cocktailberater.de/index.php?title=Cb-menue-get');
	}

	public function getAction() {
		try {
			if($this->_getParam('id')=='' OR $this->_getParam('id')==0){
				// if the id parameter is missing, throw exception
				throw new OrderException('Id_Missing');
			} else {
				$menu = CbFactory::factory('Menu',$this->_getParam('id'));
				// get xml representation of menu object
				$menu->toXml($this->xml, $this->rsp);
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
		try {
			$menues = Menu::listMenu();
			$menuesXml = $this->xml->createElement('menues');
			$this->rsp->appendChild($menuesXml);
			foreach ($menues as $menu){
				$menu->toXml($this->xml,$menuesXml);
			}
			// set status for root element 'rsp' to 'ok'
			$this->rsp->setAttribute('status','ok');
		} catch (Exception $e){
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}

	/**
	 * add a new recipe to a menue,
	 * authentication required (member & hashcode)
	 *
	 */
	public function addRecipeAction() {
		try{
			// check if all needed values are given
			if ($this->_getParam('menu')=='') {
				throw new MenuException('Menu_Id_Missing');
			} elseif ($this->_getParam('recipe')=='') {
				throw new MenuException('Recipe_Id_Missing');
			} elseif ($this->_getParam('hashcode')=='') {
				throw new MenuException('Member_HashCode_Missing');
			} elseif ($this->_getParam('member')=='') {
				throw new MenuException('Member_Id_Missing');
			}
			else{
				$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('member'));
				$member->authenticateByHashCode($this->_getParam('hashcode'));

				$recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$this->_getParam('recipe'));

				$menu = CbFactory::factory('Menu',$this->_getParam('menu'));
				$menu->addRecipe($recipe->id);

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

	/**
	 * delete a recipe from a menue
	 *
	 */
	public function removeRecipeAction() {
		try{
			// check if all needed values are given
			if ($this->_getParam('menu')=='') {
				throw new MenuException('Menu_Id_Missing');
			} elseif ($this->_getParam('recipe')=='') {
				throw new MenuException('Recipe_Id_Missing');
			}
			else{
				//$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('member'));
				//$member->authenticateByHashCode($this->_getParam('hashcode'));

				$recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$this->_getParam('recipe'));

				$menu = CbFactory::factory('Menu',$this->_getParam('menu'));
				$menu->removeRecipe($recipe->id);

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

	public function postDispatch(){
		if(!$this->error){
			// save and display tree
			print $this->xml->saveXML();
		}
	}

}