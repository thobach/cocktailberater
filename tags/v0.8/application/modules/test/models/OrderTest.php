<?php
class OrderTest extends PHPUnit_Framework_TestCase {

	private static $orderId;
	private static $orderDate;

	public function testOrderAddException()
	{
		$this->setExpectedException('OrderException','Required_Arguments_Missing');
		$order = new Order();
		$order->save();
		OrderTest::$orderId = $order->id;
	}

	public function testOrderDateException()
	{
		$this->setExpectedException('OrderException','Wrong_Date_Format');
		$order = new Order();
		$order->orderDate = time();
	}

	public function testOrderStatusException()
	{
		$this->setExpectedException('OrderException','Wrong_Order_Status');
		$order = new Order();
		$order->status = 'comp';
	}

	public function testOrderAdd($params=null)
	{
		$order = new Order();
		if($params['existingMemberId']==null){
			$memberTest = new MemberTest();
			$memberTest->testMemberAdd();
			$order->memberId = $memberTest->getMemberId();
		} else {
			$order->memberId = $params['existingMemberId'];
		}
		if($params['existingPartyId']==null){
			$partyTest = new PartyTest();
			$partyTest->testPartyAdd();
			$order->partyId = $partyTest->getPartyId();
		} else {
			$order->partyId = $params['existingPartyId'];
		}
		OrderTest::$orderDate = $order->orderDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);

		$order->recipeId = 1;
		$order->status = Order::ORDERED;
		$order->save();
		OrderTest::$orderId = $order->id;
		$this->assertType('int',OrderTest::$orderId);
	}
	
	public function testOrderLoad()
	{
		$order = CbFactory::factory('Order',OrderTest::$orderId);
		$this->assertEquals(OrderTest::$orderId, $order->id);
		$this->assertEquals(1, $order->recipeId);
	}
	
	public function testOrderGetMember()
	{
		$order = CbFactory::factory('Order',OrderTest::$orderId);
		$member = $order->getMember();
		$this->assertType('Member',$member);
		$this->assertEquals($order->memberId,$member->id);
	}
	
	public function testOrderGetParty()
	{
		$order = CbFactory::factory('Order',OrderTest::$orderId);
		$party = $order->getParty();
		$this->assertType('Party',$party);
		$this->assertEquals($order->partyId,$party->id);
	}

	public function testOrderGetRecipe()
	{
		$order = CbFactory::factory('Order',OrderTest::$orderId);
		$recipe = $order->getRecipe();
		$this->assertType('Recipe',$recipe);
		$this->assertEquals(1,$recipe->id);
	}

	public function testOrderToXml()
	{
		$xml = new DOMDocument();
		$branch = $xml->createElement('rsp');
		$order = CbFactory::factory('Order',OrderTest::$orderId);
		$order->toXml($xml,$branch);
		$xml->appendChild($branch);
		$output = $xml->saveXML();
		$this->assertXmlStringEqualsXmlString('<?xml version="1.0"?>
<rsp><order id="'.OrderTest::$orderId.'" comment="" status="ordered" member="'.$order->memberId.'" party="'.$order->partyId.'" recipeId="1" recipeName="Mojito - Original" price="2" orderDate="'.OrderTest::$orderDate.'" updateDate="'.OrderTest::$orderDate.'" completedDate="" paidDate=""/></rsp>
',$output);
	}

	public function testOrderList()
	{
		$order = CbFactory::factory('Order',OrderTest::$orderId);
		$orderListAll = Order::listOrders();
		$this->assertType('array',$orderListAll);
		$orderListParty = Order::listOrders(PartyTest::getPartyId());
		$this->assertType('array',$orderListParty);
		$orderListMember = Order::listOrders(NULL,NULL,$order->memberId);
		$this->assertType('array',$orderListMember);
		$orderListStatus = Order::listOrders(NULL,Order::ORDERED);
		$this->assertType('array',$orderListStatus);
	}

	public function testOrderDelete($deletePartyToo=true,$deleteMemberToo=true)
	{
		$order = CbFactory::factory('Order',OrderTest::$orderId);
		if($deletePartyToo){
			$order->getParty()->getHost()->delete();
		}
		if($deleteMemberToo) {
			$order->getMember()->delete();
		}
		$order->delete();
		$this->setExpectedException('OrderException','Id_Wrong');
		$order = CbFactory::factory('Order',OrderTest::$orderId);
	}

}