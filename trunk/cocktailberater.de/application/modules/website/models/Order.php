<?php
class Website_Model_Order {

	// attributes
	private $id;
	private $comment;
	private $status;
	private $orderDate;
	private $completedDate;
	private $paidDate;
	private $updateDate;
	private $memberId;
	private $partyId;
	private $recipeId;
	private $price;

	// associations
	private $_member;
	private $_party;
	private $_recipe;

	// constants
	const ORDERED = 'ordered';
	const PAID = 'paid';
	const CANCELED = 'canceled';
	const COMPLETED = 'completed';

	// supporting variables
	private static $_order;

	/**
	 * magic getter for all attributes
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->__get',Zend_Log::DEBUG);

		if (property_exists(get_class($this), $name)) {
			return $this->$name;
		} else {
			$log->log('Website_Model_Order->__get: Exception (invalid property)',Zend_Log::DEBUG);
			throw new Exception('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
	}

	/**
	 * getter for association member
	 * @tested
	 */
	public function getMember(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->getMember',Zend_Log::DEBUG);

		if(!$this->_member){
			$this->_member = Website_Model_CbFactory::factory('Website_Model_Member',$this->memberId);
		}
		return $this->_member;
	}

	/**
	 * getter for association party
	 *
	 * @return Website_Model_Party
	 */
	public function getParty(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->getParty',Zend_Log::DEBUG);

		if(!$this->_party){
			$this->_party = Website_Model_CbFactory::factory('Website_Model_Party',$this->partyId);
		}
		return $this->_party;
	}

	/**
	 * getter for association recipe
	 */
	public function getRecipe(){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->getRecipe',Zend_Log::DEBUG);

		if(!$this->_recipe){
			$this->_recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$this->recipeId);
		}
		return $this->_recipe;
	}

	/**
	 * Magic Setter Function, is accessed when setting an attribute
	 *
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function __set($name, $value) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->__set',Zend_Log::DEBUG);

		// check date to be the right format
		if(($name == 'orderDate' || $name == 'completedDate' || $name == 'paidDate' || $name == 'updateDate') && !Website_Model_DateFormat::isValidMysqlTimestamp($value) && $value != NULL){
			$log->log('Website_Model_Order->__set: Website_Model_OrderException (Wrong_Date_Format)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Wrong_Date_Format');
		}
		if(($name == 'orderDate' || $name == 'completedDate' || $name == 'paidDate' || $name == 'updateDate') && $value=='0000-00-00 00:00:00'){
			$log->log('Website_Model_Order->__set: Website_Model_OrderException (Invalid_Date)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Invalid_Date');
		}
		// check status to be one of the predefined
		if($name == 'status' && $value != Website_Model_Order::ORDERED && $value != Website_Model_Order::PAID && $value != Website_Model_Order::CANCELED && $value != Website_Model_Order::COMPLETED){
			$log->log('Website_Model_Order->__set: Website_Model_OrderException (Wrong_Order_Status)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Wrong_Order_Status');
		}
		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			$log->log('Website_Model_Order->__set: Exception (invalid property)',Zend_Log::DEBUG);
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}
	}

	/**
	 *
	 *@tested
	 */
	public function __construct ($id=NULL){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->__construct',Zend_Log::DEBUG);

		// TODO: one cocktail costs 2 Euro
		$this->price = 2;
		if(!empty($id)){
			$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','order');
			$order = $orderTable->fetchRow('id='.$id);
			if (!$order->id) {
				$log->log('Website_Model_Order->__construct: Website_Model_OrderException (Id_Wrong)',Zend_Log::DEBUG);
				throw new Website_Model_OrderException('Id_Wrong');
			}
			// load attributes
			$this->id = $order->id;
			$this->comment = $order->comment;
			$this->status = $order->status;

			$this->orderDate = $order->orderDate;

			if ($order->completedDate && $order->completedDate!='0000-00-00 00:00:00') {
				$this->completedDate = $order->completedDate;
			}
			if ($order->paidDate && $order->paidDate!='0000-00-00 00:00:00') {
				$this->paidDate = $order->paidDate;
			}
			$this->updateDate = $order->updateDate;
			$this->memberId = $order->member;
			$this->partyId = $order->party;
			$this->recipeId = $order->recipe;
		}
	}

	/**
	 *
	 *@tested
	 */
	public function toXml($xml, $branch) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->toXml',Zend_Log::DEBUG);

		$order = $xml->createElement('order');
		$branch->appendChild($order);
		$order->setAttribute('id', $this->id);
		$order->setAttribute('comment', $this->comment);
		$order->setAttribute('status', $this->status);
		$order->setAttribute('member', $this->memberId);
		$order->setAttribute('party', $this->partyId);
		$order->setAttribute('recipeId', $this->recipeId);
		$order->setAttribute('recipeName', $this->getRecipe()->name);
		$order->setAttribute('price', $this->price);
		$order->setAttribute('orderDate', $this->orderDate);
		$order->setAttribute('updateDate', $this->updateDate);
		if($this->completedDate){
			$order->setAttribute('completedDate', $this->completedDate);
		} else {
			$order->setAttribute('completedDate', '');
		}
		if($this->completedDate){
			$order->setAttribute('paidDate', $this->paidDate);
		} else {
			$order->setAttribute('paidDate', '');
		}
	}

	/**
	 *
	 *@tested
	 */
	public static function listOrders($partyId=NULL,$status=NULL,$memberId=NULL)
	{
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->listOrders',Zend_Log::DEBUG);

		$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','order');
		$select = $orderTable->getAdapter()->select();

		$select->from('order');

		// error handling

		// partyId must be an integer if given
		if($partyId!=NULL AND $partyId<=0){
			$log->log('Website_Model_Order->listOrders: Website_Model_OrderException (PartyID must be an integer)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('PartyID must be an integer');
		} elseif($partyId!=NULL) {
			$select->where('party=?',$partyId);
		}

		// memberId must be an integer if given
		if($memberId!=NULL AND $memberId<=0){
			$log->log('Website_Model_Order->listOrders: Website_Model_OrderException (MemberId must be an integer)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('MemberId must be an integer');
		} elseif($memberId!=NULL) {
			$select->where('member=?',$memberId);
		}

		// status must be one of the following
		if($status == '') {
			// ignore
		}
		elseif($status!=Website_Model_Order::ORDERED AND $status!=Website_Model_Order::PAID AND $status!=Website_Model_Order::CANCELED AND $status!=Website_Model_Order::COMPLETED){
			$log->log('Website_Model_Order->listOrders: Website_Model_OrderException (Wrong_Order_Status)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Wrong_Order_Status');
		} elseif($status!=NULL) {
			$select->where('status=?',$status);
		}
		$select->order('updateDate DESC');
		$stmt = $orderTable->getAdapter()->query($select);
		$orders = $stmt->fetchAll();

		foreach ($orders as $order){
			$orderArray[] = Website_Model_CbFactory::factory('Website_Model_Order',$order['id']);
		}

		return $orderArray;
	}

	/**
	 * Checks if the given member has access to an order 
	 * (must be either host of the party of the order or the member of the order)
	 *
	 * @param int $memberId
	 * @return boolean true if member has access, false if not
	 */
	public function memberHasAccess($memberId){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->memberHasAccess',Zend_Log::DEBUG);
		
		if($this->getParty()->hostId==$memberId || $this->memberId == $memberId){
			return true;
		} else {
			return false;
		}
	}


	/**
	 * tries to save the order if all data is given and correct (user must be logged in -> hashCode)
	 *
	 * @param String $hashCode
	 * @return unknown_type nothing
	 * @throws Website_Model_OrderException if arguments are missing or user is not logged in (hashCode)
	 * @todo: write unit test with hashCode
	 */
	public function save ($hashCode){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->save',Zend_Log::DEBUG);

		// check if all required attributes are filled
		if(!$this->partyId){
			$log->log('Website_Model_Order->save: Website_Model_OrderException (Party missing)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Party missing!');
		}
		elseif(!$this->recipeId){
			$log->log('Website_Model_Order->save: Website_Model_OrderException (Recipe missing)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Recipe missing!');
		}
		elseif(!$hashCode){
			$log->log('Website_Model_Order->save: Website_Model_OrderException (HashCode missing)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('HashCode missing!');
		}
		elseif(!$this->memberId){
			$log->log('Website_Model_Order->save: Website_Model_OrderException (Member missing)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Member missing!');
		}
		elseif(!$this->orderDate){
			$log->log('Website_Model_Order->save: Website_Model_OrderException (OrderDate missing)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('OrderDate missing!');
		}
		elseif(!$this->status){
			$log->log('Website_Model_Order->save: Website_Model_OrderException (Status missing)',Zend_Log::DEBUG);
			throw new Website_Model_OrderException('Status missing!');
		}
		// check if the hashCode is valid and belongs to a guest or host
		elseif(!(Website_Model_Member::loggedIn($this->memberId,$hashCode) ||
		Website_Model_Member::loggedIn($this->getParty()->getHost()->id,$hashCode))){
			$log->log('Website_Model_Order->save: Website_Model_MemberException (Not_Authorized)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException('Not_Authorized');
		}
		else {
			// get mysql table
			$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'order');
			// insert a new order
			if (!$this->id) {
				// insert the order and save generated id
				$this->id = (int)$orderTable->insert($this->databaseRepresentation());
			}
			// update an existing order
			else{
				// update the order
				$orderTable->update($this->databaseRepresentation(), 'id='.$this->id);
			}
		}
	}

	/**
	 *
	 *@tested
	 */
	public function delete (){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->delete',Zend_Log::DEBUG);

		$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'order');
		$orderTable->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Order',$this->id);
		unset($this);
	}

	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Order->dataBaseRepresentation',Zend_Log::DEBUG);

		$array['comment'] = $this->comment;
		$array['status'] = $this->status;
		$array['orderDate'] = $this->orderDate;
		if($this->completedDate){
			$array['completedDate'] = $this->completedDate;
		}
		if($this->paidDate){
			$array['paidDate'] = $this->paidDate;
		}
		// $array['updateDate'] = $this->updateDate;
		$array['member'] = $this->memberId;
		$array['party'] = $this->partyId;
		$array['recipe'] = $this->recipeId;
		return $array;
	}

}
?>
