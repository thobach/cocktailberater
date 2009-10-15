<?php


/**
 * Class responsible for everything regarding orders and the REST API
 */

class Api_OrderController extends Zend_Controller_Action {

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
		$this->_redirector->gotoUrl('http://wiki.cocktailberater.de/index.php?title=Cb-order-get');
	}

	public function getAction() {
		try {
			if($this->_getParam('id')=='' OR $this->_getParam('id')==0){
				// if the id parameter is missing, throw exception
				throw new OrderException('Id_Missing');
			} else {
				// TODO: weitermachen
				$order = new Order($this->_getParam('id'));
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
		// $cache = Zend_Registry::get('cache');
		// ! CACHING DISABLED !
		// see if orderlist is already in cache
		// if(!$orderList = $cache->load('orderList'.'p'.$this->_getParam('party').'s'.$this->_getParam('status').'m'.$this->_getParam('member'))) {
		$orderList = Order::listOrders(
		$this->_getParam('party'),
		$this->_getParam('status'),
		$this->_getParam('member'));
		//	$cache->save ($orderList,'orderList'.'p'.$this->_getParam('party').'s'.$this->_getParam('status').'m'.$this->_getParam('member'));
		// }

		$orders = $this->xml->createElement('orders');

		if(is_array($orderList)){
			foreach ($orderList as $order){
				$order->toXml($this->xml,$orders);
			}
		}
		// set count for orders element according to number of orders found
		$orders->setAttribute('count',count($orderList));
		// add the 'orders' element to the 'rsp' element
		$this->rsp->appendChild($orders);
		// set status for root element 'rsp' to 'ok'
		$this->rsp->setAttribute('status','ok');
		// finish counting time
		$queryTime = microtime(true)-$queryTime;
		$this->rsp->setAttribute('queryTime',$queryTime.'s');
	}

	/**
	 * add a new order with one cocktail from a member of a party
	 *
	 */
	public function addAction() {
		try{
			// check if all needed values are given
			if ($this->_getParam('member')=='') {
				throw new OrderException('Member_Id_Missing');
			} elseif ($this->_getParam('party')=='') {
				throw new OrderException('Party_Id_Missing');
			} elseif ($this->_getParam('recipe')=='') {
				throw new OrderException('Recipe_Id_Missing');
			} elseif ($this->_getParam('hashcode')==''){
				throw new OrderException('Member_HashCode_Missing');
			}
			else{
				$member = Website_Model_CbFactory::factory('Website_Model_Member',$this->_getParam('member'));
				$member->authenticateByHashCode($this->_getParam('hashcode'));
				
				// create a new order
				$order = new Order();
				// add all required attributes as objects (associations)
				$order->memberId = $this->_getParam('member');
				$order->partyId = $this->_getParam('party');
				$order->recipeId = $this->_getParam('recipe');

				// check if all needed values are given and valid
				if (!Member::exists($order->memberId)) {
					throw new OrderException('Member_Id_Invalid');
				} elseif (!Party::exists($order->partyId)) {
					throw new OrderException('Party_Id_Invalid');
				} elseif (!Recipe::exists($order->recipeId)) {
					throw new OrderException('Recipe_Id_Invalid');
				}
				else{

					// optional comment
					if($this->_hasParam('comment')){
						$order->comment = $this->_getParam('comment');
					}
					// set order-dates
					$order->orderDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
					$order->completedDate = NULL;
					$order->paidDate = NULL;
					//$order->updateDate = new Zend_Date(NULL,DateFormat::MYSQLTIMESTAMP);
					$order->status = Order::ORDERED;
					// save to database
					$order->save();
					$order->toXml($this->xml, $this->rsp);
					// set status for root element 'rsp' to 'ok'
					$this->rsp->setAttribute('status','ok');
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