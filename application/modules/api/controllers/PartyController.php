<?php


/**
 * Class responsible for everything regarding partys and the REST API
 */

class Api_PartyController extends Zend_Controller_Action {

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
		$this->_redirector->gotoUrl('http://wiki.cocktailberater.de/index.php?title=Cb-party-get');
	}

	public function getAction() {
		try {
			if($this->_getParam('id')=='' OR $this->_getParam('id')==0){
				// if the id parameter is missing, throw exception
				throw new PartyException('Id_Missing');
			} else {
				// TODO: weitermachen
				$order = new Party($this->_getParam('id'));
				// get xml representation of cocktail object
				$order->toXml($this->xml, $this->rsp);
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

		// see if orderlist is already in cache
		if(!$partyList = $cache->load('partyList'.'h'.$this->_getParam('host').'b'.$this->_getParam('bar'))) {
			$partyList = Party::listPartys(
			$this->_getParam('host'),
			$this->_getParam('bar'));
			$cache->save ($partyList,'partyList'.'h'.$this->_getParam('host').'b'.$this->_getParam('bar'));
		}

		$partys = $this->xml->createElement('partys');

		if(is_array($partyList)){
			foreach ($partyList as $party){
				$party->toXml($this->xml,$partys);
			}
		}
		// set count for orders element according to number of orders found
		$partys->setAttribute('count',count($partyList));
		// add the 'orders' element to the 'rsp' element
		$this->rsp->appendChild($partys);
		// set status for root element 'rsp' to 'ok'
		$this->rsp->setAttribute('status','ok');
		// finish counting time
		$queryTime = microtime(true)-$queryTime;
		$this->rsp->setAttribute('queryTime',$queryTime.'s');
	}

	public function getMenuAction() {
		try {
			if($this->_getParam('menu')=='' OR $this->_getParam('menu')==0){
				throw new PartyException('Menu_Id_Missing');
			} else {
				$menu = CbFactory::factory('Menu',$this->_getParam('menu'));
				$menuXml = $this->xml->createElement("menu");
				$this->rsp->appendChild($menuXml);
				$recipes = $menu->listRecipes();
				if(is_array($recipes)){
					foreach ($recipes as $recipe){
						$recipe->toXml($this->xml,$menuXml);
					}
				}
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
	 * add a new order with one cocktail from a member of a party
	 *
	 */
	public function addAction() {
		/*
		 * Req. Params:
		 *
		 * Name
		 * Date
		 * Host-ID
		 * Bar-ID
		 * Menu-ID
		 *
		 */

		try{
			// check if all needed values are given
			if (!$this->_hasParam('name') || $this->_getParam('name') == '') {
				throw new PartyException('Party_Name_Missing');
			} elseif (!$this->_hasParam('date')) {
				throw new PartyException('Party_Date_Missing');
			} elseif (!$this->_hasParam('host')) {
				throw new PartyException('Party_HostId_Missing');
			} elseif (!$this->_hasParam('bar')) {
				throw new PartyException('Party_BarId_Missing');
			} elseif (!$this->_hasParam('menu')) {
				throw new PartyException('Party_MenuId_Missing');
			}
			else{
				// create a new party
				$party = new Party();
				// add all required attributes as objects (associations)
				$party->name = $this->_getParam('name');
				$party->date = $this->_getParam('date');
				$party->hostId = $this->_getParam('host');
				$party->barId = $this->_getParam('bar');
				$party->menuId = $this->_getParam('menu');

				// check if all needed values are given and valid

				if (!Member::exists($party->hostId)) {
					throw new PartyException('Host_Id_Invalid');
				} elseif (!Bar::exists($party->barId)) {
					throw new PartyException('Bar_Id_Invalid');
				}elseif (!Menu::exists($party->menuId)) {
					throw new PartyException('Menu_Id_Invalid');
				}

				else {
					// save to database
					$party->save();
					$party->toXml($this->xml, $this->rsp);
				}
			}
		} catch (Exception $e){
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}

	public function updateAction() {
		return false;
	}

	public function updateMenuAction() {
		/*
		 * Req. Params:
		 *
		 * Party-ID
		 * Menu-ID
		 *
		 */

		try{
			// check if all needed values are given
			if (!$this->_hasParam('party')) {
				throw new PartyException('Party_Id_Missing');
			} elseif (!$this->_hasParam('menu')) {
				throw new PartyException('Menu_Id_Missing');
			}
			else{
				if (!Menu::exists($this->_getParam('menu'))) {
					throw new PartyException('Menu_Id_Invalid');
				} else{
					$party = CbFactory::factory('Party',$this->_getParam('party'));
					$party->menuId = $this->_getParam('menu'); // will automatically be saved to database
					$party->toXml($this->xml, $this->rsp);
				}
			}
		} catch (Exception $e){
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}

	public function cancelAction() {
		try{
			// check if all needed values are given
			if (!$this->_hasParam('order')) {
				throw new OrderException('Order_Id_Missing');
			} else{
				// create a new order
				$order = new Order($this->_getParam('order'));
				$order->status = Order::CANCELED;
				// save to database
				$order->save();
				$order->toXml($this->xml, $this->rsp);
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