<?php

class Controllers_OrderControllerTest extends ControllerTestCase
{

	public static $orderId;
	public static $hashCode;
	public static $memberId;

	public function setUp(){
		parent::setUp();

		// get valid session
		$member = Website_Model_Member::getMemberByEmail('max@thobach.de');
		if(!$member){
			// create new member
			$member = new Website_Model_Member();
			$member->email='max@thobach.de';
			$member->setPassword('test1');
			$member->firstname='Max';
			$member->lastname='Mustermann';
			$member->save();
		}
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("//member");
		$xmlAttr = $res[0]->attributes();
		OrderControllerTest::$hashCode = print_r($xmlAttr['hashCode']."",true);
		OrderControllerTest::$memberId = print_r($xmlAttr['id']."",true);
		
		$this->reset();
		parent::setUp();
		
		$this->request->setMethod('POST')->setPost(array('email'=>'thobach@web.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("//member");
		$xmlAttr = $res[0]->attributes();
		$hostHashCode = print_r($xmlAttr['hashCode']."",true);
		$hostId = print_r($xmlAttr['id']."",true);
		
				
		$this->reset();
		parent::setUp();
		
		// be sure that the member is a guest
		$this->request->setMethod('POST')->setPost(
			array('hashCode'=>$hostHashCode,
			'member'=>$hostId,
			'party'=>1,
			'guest'=>OrderControllerTest::$memberId));
		$this->dispatch('/website/guest/?format=xml');

		$this->reset();
		parent::setUp();
	}

	public function testPostAsXmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testPostAsXmlAction',Zend_Log::DEBUG);
		
		$this->request->setMethod('POST')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'recipe'=>'1','member'=>OrderControllerTest::$memberId,'party'=>'1'));
		$this->dispatch('/website/order/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("//order");
		$xmlAttr = $res[0]->attributes();
		OrderControllerTest::$orderId = print_r($xmlAttr['id']."",true);
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("get"); // redirect
		$this->assertResponseCode(201); // created
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('recipeName="Mojito"',$this->response->outputBody());
	}

	public function testIndexOfPartyAsHtmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testIndexOfPartyAsHtmlAction',Zend_Log::DEBUG);

		$this->dispatch('/website/order/party/1/member/'.OrderControllerTest::$memberId.'/hashCode/'.OrderControllerTest::$hashCode);

		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('Mojito für Max Mustermann',$this->response->outputBody());
	}

	public function testIndexAsHtmlAction() {
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testIndexAsHtmlAction',Zend_Log::DEBUG);

		$this->dispatch('/website/order/member/'.OrderControllerTest::$memberId.'/hashCode/'.OrderControllerTest::$hashCode);

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Party missing!'));
	}

	public function testIndexAsXmlAction() {
		$this->dispatch('/website/order/party/1/member/'.OrderControllerTest::$memberId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	public function testIndexAsRssAction() {
		$this->dispatch('/website/order/party/1/hashCode/'.OrderControllerTest::$hashCode.'?format=rss');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Member missing!'));
	}

	public function testIndexAsAtomAction() {
		$this->dispatch('/website/order/party/1/hashCode/abc/member/'.OrderControllerTest::$memberId.'?format=atom');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage(Website_Model_MemberException::INVALID_CREDENTIALS));
	}

	//public function testIndexAsJsonAction() {
	//	$this->setExpectedException('Website_Model_OrderException','Party missing!');
	//
	//	$this->dispatch('/website/order/?format=json');
	//}


	//public function testIndexAsPdfAction() {
	//	$this->setExpectedException('Website_Model_OrderException','Party missing!');
	//
	//	$this->dispatch('/website/order/?format=pdf');
	//}



	public function testIndexOfPartyAsXmlAction() {
		$this->dispatch('/website/order/party/1/member/'.OrderControllerTest::$memberId.'/hashCode/'.OrderControllerTest::$hashCode.'?format=xml');
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('recipeName="Mojito"',$this->response->outputBody());
	}

	//public function testIndexOfPartyAsJsonAction() {
	//	$this->dispatch('/website/order/party/1/?format=json');
	//
	//	$this->assertModule("website");
	//	$this->assertAction("index");
	//	$this->assertController("order");
	//	$this->assertResponseCode(200);
	//	$this->assertHeaderContains('Content-Type','application/json');
	//	$this->assertContains('"name":"Mojito"',$this->response->outputBody());
	//}

	public function testIndexOfPartyAsRssAction() {
		$this->dispatch('/website/order/party/1/member/'.OrderControllerTest::$memberId.'/hashCode/'.OrderControllerTest::$hashCode.'?format=rss');
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/rss+xml');
		$this->assertContains('<title><![CDATA[Mojito für Max Mustermann]]></title>',$this->response->outputBody());
	}


	public function testIndexOfPartyAsAtomAction() {
		$this->dispatch('/website/order/party/1/member/'.OrderControllerTest::$memberId.'/hashCode/'.OrderControllerTest::$hashCode.'?format=atom');
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/atom+xml');
		$this->assertContains('<title><![CDATA[Mojito für Max Mustermann]]></title>',$this->response->outputBody());
	}

	public function testGetAction() {
		$this->dispatch('/website/order/id/'.OrderControllerTest::$orderId.'/member/'.OrderControllerTest::$memberId.'/hashCode/'.OrderControllerTest::$hashCode);
		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertContains("Mojito für Max Mustermann",$this->response->outputBody());
	}

	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/order/id/0/member/'.OrderControllerTest::$memberId.'/hashCode/'.OrderControllerTest::$hashCode);

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Id_Wrong'));
	}

	public function testPostHashCodeMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('recipe'=>'1','member'=>OrderControllerTest::$memberId,'party'=>'1'));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	public function testPostInvalidHashCodeAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>'abc','recipe'=>'1','member'=>OrderControllerTest::$memberId,'party'=>'1'));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage(Website_Model_MemberException::INVALID_CREDENTIALS));
	}

	public function testPostUserMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'recipe'=>'1','party'=>'1'));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Member missing!'));
	}

	public function testPostPartyMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'recipe'=>'1','member'=>OrderControllerTest::$memberId));
		$this->dispatch('/website/order/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Party missing!'));
	}

	public function testPutAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'member'=>OrderControllerTest::$memberId,'status'=>Website_Model_Order::CANCELED));
		$this->dispatch('/website/order/'.OrderControllerTest::$orderId.'?format=xml');

		$this->assertModule("website");
		$this->assertController("order");
		$this->assertAction("get"); // redirect
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('status="'.Website_Model_Order::CANCELED.'"',$this->response->outputBody());
	}

	public function testPutInvalidHashCodeAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('member'=>OrderControllerTest::$memberId,'hashCode'=>'abc','status'=>'CANCELED'));
		$this->dispatch('/website/order/'.OrderControllerTest::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage(Website_Model_MemberException::INVALID_CREDENTIALS));
	}

	public function testPutUserIdMissingAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'status'=>'CANCELED'));
		$this->dispatch('/website/order/'.OrderControllerTest::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Member missing!'));
	}


	public function testPutHashCodeMissingAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('member'=>OrderControllerTest::$memberId,'status'=>'CANCELED'));
		$this->dispatch('/website/order/'.OrderControllerTest::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	public function testDeleteAsXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'member'=>OrderControllerTest::$memberId));
		$this->dispatch('/website/order/'.OrderControllerTest::$orderId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Delete not possible!'));
	}

	public function testGetGuestAction() {
		$this->request->setMethod('GET')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'member'=>OrderControllerTest::$memberId,'party'=>1,'guest'=>1));
		$this->dispatch('/website/guest/'.OrderControllerTest::$memberId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Seite wurde nicht gefunden.'));
	}
	
	public function testPutGuestAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>OrderControllerTest::$hashCode,'member'=>OrderControllerTest::$memberId,'party'=>1,'guest'=>1));
		$this->dispatch('/website/guest/'.OrderControllerTest::$memberId.'?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Seite wurde nicht gefunden.'));
	}
	
	public function testDeleteGuestAction(){
		$log = Zend_Registry::get('logger');
		$log->log('OrderControllerTest->testDeleteGuestAction',Zend_Log::DEBUG);
		
		$this->request->setMethod('POST')->setPost(array('email'=>'thobach@web.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("//member");
		$xmlAttr = $res[0]->attributes();
		$hostHashCode = print_r($xmlAttr['hashCode']."",true);
		$hostId = print_r($xmlAttr['id']."",true);
		
				
		$this->reset();
		parent::setUp();
		
		$log->log('OrderControllerTest->testDeleteGuestAction: remove member from party',Zend_Log::DEBUG);
		$this->request->setMethod('DELETE')->setPost(
			array('hashCode'=>$hostHashCode,
			'member'=>$hostId,
			'party'=>1,
			'guest'=>OrderControllerTest::$memberId));
		$this->dispatch('/website/guest/?format=xml');
		
		$this->assertFalse(Website_Model_CbFactory::factory('Website_Model_Party',1)->memberIsGuest(OrderControllerTest::$memberId));
	}
}
