<?php


/**
 * Class responsible for everything regarding partys and the REST API
 */

class Api_MemberController extends Zend_Controller_Action {

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
				throw new MemberException('Id_Missing');
			} 
			elseif (!Member::exists($this->_getParam('id'))) {
				throw new MemberException('Member_Id_Invalid');
			}
			else {
				$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('id'));
				// get xml representation of cocktail object
				$member->toXml($this->xml, $this->rsp);
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
		if(!$memberList = $cache->load('memberList')) {
			$memberList = Member::listMembers();
			$cache->save ($memberList,'memberList');
		}

		$members = $this->xml->createElement('members');

		if(is_array($memberList)){
			foreach ($memberList as $member){
				$member->toXml($this->xml,$members);
			}
		}
		// set count for orders element according to number of orders found
		$members->setAttribute('count',count($memberList));
		// add the 'orders' element to the 'rsp' element
		$this->rsp->appendChild($members);
		// set status for root element 'rsp' to 'ok'
		$this->rsp->setAttribute('status','ok');
		// finish counting time
		$queryTime = microtime(true)-$queryTime;
		$this->rsp->setAttribute('queryTime',$queryTime.'s');
		
	}

	/**
	 * add a new member to the system
	 *
	 */
	public function addAction() {
		 try{
			// check if all needed values are given
			if (!$this->_hasParam('passwordHash')) {
				throw new MemberException('Password_Hash_Missing');
			} elseif (!$this->_hasParam('firstname')) {
				throw new MemberException('Firstname_Missing');
			} elseif (!$this->_hasParam('lastname')) {
				throw new MemberException('Lastname_Missing');
			}elseif (!$this->_hasParam('birthday')) {
				throw new MemberException('Birthday_Missing');
			}elseif (!$this->_hasParam('email')) {
				throw new MemberException('Email_Missing');
			} elseif (Member::existsByEmail($this->_getParam('email'))){
				throw new MemberException('Email_Already_Registered');
			}
			else{
				$member = new Member();
				// add all required attributes as objects (associations)
				$member->passwordHash = $this->_getParam('passwordHash');
				$member->firstname = $this->_getParam('firstname');
				$member->lastname = $this->_getParam('lastname');
				$member->birthday = $this->_getParam('birthday');
				$member->email = $this->_getParam('email');
				// save to database
				$member->save();
				$member->toXml($this->xml, $this->rsp);
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

	
	public function postDispatch(){
		if(!$this->error){
			// save and display tree
			print $this->xml->saveXML();
		}
	}
	
	public function loginAction(){
	// TODO:  weitermachen und errormeldungen korrigieren
		try {
			if($this->_getParam('email')==''){
				// if the email parameter is missing, throw exception
				throw new MemberException('Email_Missing');
			} 
			elseif($this->_getParam('password-md5')==''){
				// if the passwordparameter is missing, throw exception
				throw new MemberException('Password_Missing');
			} 
			$member = Member::getMemberByEmail($this->_getParam('email'));
			if($member){
				if($member->authenticate($this->_getParam('password-md5'))){
					$member->toXml($this->xml, $this->rsp);
					$this->rsp->setAttribute('status','ok');
				} else {
					$this->rsp->setAttribute('status','fail');
					throw new MemberException('Password_Invalid');
				}
			} else {
				$this->rsp->setAttribute('status','fail');
				throw new MemberException('Email_Invalid');
			}
		} catch (Exception $e){
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}
	
	public function getInvoiceAction(){
		try {
			if($this->_getParam('party')== ''){
				throw new MemberException('Party_Id_Missing');
			} elseif($this->_getParam('member')== '') {
				throw new MemberException('Member_Id_Missing');
			} else {
				$party = CbFactory::factory('Party',$this->_getParam('party'));
				$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('member'));
				$invoice = $member->getInvoice($party->id);
				$invoiceXml = $this->xml->createElement("invoice");
				$ordersXml = $this->xml->createElement("orders");
				$this->rsp->appendChild($invoiceXml);
				$party->toXml($this->xml, $invoiceXml);
				$member->toXml($this->xml, $invoiceXml);
				$invoiceXml->appendChild($ordersXml);
				if(is_array($invoice)){
					foreach ($invoice as $order){
						$order->toXml($this->xml, $ordersXml);
						// TODO: preis der order hinzuf�gen
						$sum += $order->price;
					}
				}
				$invoiceXml->setAttribute('totalPrice',round($sum,2));
				$invoiceXml->setAttribute('items',count($invoice));
				$this->rsp->setAttribute('status','ok');
			}
		} catch (Exception $e){
			// if another error occurs, stop here (don't use postDispatch()) ...
			$this->error = true;
			// ...and go to error controller
			$this->_forward('error','error','api',array ('error' => $e));
		}
	}

}