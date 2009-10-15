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
		if (property_exists(get_class($this), $name)) {
			return $this->$name;
		} else {
			throw new Exception('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
	}

	/**
	 * getter for association member
	 * @tested
	 */
	public function getMember()
	{
		if(!$this->_member){
			$this->_member = Website_Model_CbFactory::factory('Website_Model_Member',$this->memberId);
		}
		return $this->_member;
	}

	/**
	 * getter for association party
	 * @tested
	 */
	public function getParty()
	{
		if(!$this->_party){
			$this->_party = CbFactory::factory('Party',$this->partyId);
		}
		return $this->_party;
	}

	/**
	 * getter for association recipe
	 * @tested
	 */
	public function getRecipe()
	{
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
		// check date to be the right format
		if(($name == 'orderDate' || $name == 'completedDate' || $name == 'paidDate' || $name == 'updateDate') && !DateFormat::isValidMysqlTimestamp($value) && $value != NULL){
			throw new OrderException('Wrong_Date_Format');
		}
		if(($name == 'orderDate' || $name == 'completedDate' || $name == 'paidDate' || $name == 'updateDate') && $value=='0000-00-00 00:00:00'){
			throw new OrderException('Invalid_Date');
		}
		// check status to be one of the predefined
		if($name == 'status' && $value != Order::ORDERED && $value != Order::PAID && $value != Order::CANCELED && $value != Order::COMPLETED){
			throw new OrderException('Wrong_Order_Status');
		}
		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}
	}

	/**
	 *
	 *@tested
	 */
	public function Order ($id=NULL){
		// TODO: one cocktail costs 2 Euro
		$this->price = 2;
		if(!empty($id)){
			$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','order');
			$order = $orderTable->fetchRow('id='.$id);
			if (!$order->id) {
				throw new OrderException('Id_Wrong');
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
		$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','order');
		$select = $orderTable->getAdapter()->select();

		$select->from('order');

		// error handling

		// partyId must be an integer if given
		if($partyId!=NULL AND $partyId<=0){
			throw new OrderException('PartyID must be an integer');
		} elseif($partyId!=NULL) {
			$select->where('party=?',$partyId);
		}

		// memberId must be an integer if given
		if($memberId!=NULL AND $memberId<=0){
			throw new OrderException('MemberId must be an integer');
		} elseif($memberId!=NULL) {
			$select->where('member=?',$memberId);
		}

		// status must be one of the following
		if($status == '') {
			// ignore
		}
		elseif($status!=Order::ORDERED AND $status!=Order::PAID AND $status!=Order::CANCELED AND $status!=Order::COMPLETED){
			throw new OrderException('Wrong_Order_Status');
		} elseif($status!=NULL) {
			$select->where('status=?',$status);
		}


		$stmt = $orderTable->getAdapter()->query($select);
		$orders = $stmt->fetchAll();

		foreach ($orders as $order){
			$orderArray[] = CbFactory::factory('Order',$order->id);
		}
		return $orderArray;
	}

	/**
	 *
	 *@tested
	 */
	public function save (){
		// check if all required attributes are filled
		if($this->memberId && $this->orderDate && $this->partyId && $this->recipeId && $this->status){
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
				$orderTable->update($this->databaseRepresentation(),array ('id'=>$this->id));
			}
		} else {
			throw new OrderException('Required_Arguments_Missing');
		}
	}

	/**
	 *
	 *@tested
	 */
	public function delete (){
		$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'order');
		$orderTable->delete('id='.$this->id);
		CbFactory::destroy('Order',$this->id);
		unset($this);
	}

	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['comment'] = $this->comment;
		$array['status'] = $this->status;
		$array['orderDate'] = $this->orderDate;
		if($this->completedDate){
			$array['completedDate'] = $this->completedDate;
		}
		if($this->paidDate){
			$array['paidDate'] = $this->paidDate;
		}
		$array['updateDate'] = $this->updateDate;
		$array['member'] = $this->memberId;
		$array['party'] = $this->partyId;
		$array['recipe'] = $this->recipeId;
		return $array;
	}

}
?>
