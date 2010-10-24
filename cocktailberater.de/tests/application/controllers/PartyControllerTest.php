<?php

class Controllers_PartyControllerTest extends ControllerTestCase
{

	public static $partyId;
	public static $hashCode;
	public static $hostId;

	public function testIndexAsHtmlAction() {
		$this->dispatch('/website/party/');
		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertContains('tabas bar',$this->response->outputBody());
	}

	public function testIndexAsXmlAction() {
		$this->dispatch('/website/party/?format=xml');
		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('name="tabas bar"',$this->response->outputBody());
	}

	/*public function testIndexAsJsonAction() {
		// somehow breaks unit test since format=json is called the second time

		$this->dispatch('/website/party/?format=json');

		$this->assertModule("website");
		$this->assertAction("index");
		$this->assertController("party");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/json');
		$this->assertContains('"name":"tabas bar"',$this->response->outputBody());

		}*/

	public function testIndexAsRssAction() {
		$this->dispatch('/website/party/?format=rss');
		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/rss+xml');
		$this->assertContains('<title><![CDATA[tabas bar]]></title>',$this->response->outputBody());
	}


	public function testIndexAsAtomAction() {
		$this->dispatch('/website/party/?format=atom');
		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("index");
		$this->assertResponseCode(200);
		$this->assertHeaderContains('Content-Type','application/atom+xml');
		$this->assertContains('<title><![CDATA[tabas bar]]></title>',$this->response->outputBody());
	}

	public function testGetAction() {
		$this->dispatch('/website/party/1');
		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("get");
		$this->assertResponseCode(200);
		$this->assertXpathContentContains('/html/body/div[@id=\'wrapper\']/div[@id=\'homepage\']/div[@id=\'content\']/h2', "tabas bar");
	}

	public function testGetWithWrongIdAction() {
		$this->dispatch('/website/party/0');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Id_Missing'));
	}

	public function testPostAsXmlAction() {
		// get valid session
		$member = Website_Model_Member::getMemberByEmail('max@thobach.de');
		if(!$member){
			// create new member
			$member = new Website_Model_Member();
			$member->email='max@thobach.de';
			$member->setPassword('test1');
			$member->save();
		}
		$this->request->setMethod('POST')->setPost(array('email'=>'max@thobach.de','password'=>'test1'));
		$this->dispatch('/website/session/?format=xml');
		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("member");
		$xmlAttr = $res[0]->attributes();
		PartyControllerTest::$hashCode = print_r($xmlAttr['hashCode']."",true);
		PartyControllerTest::$hostId = print_r($xmlAttr['id']."",true);
		$this->reset();
		$this->setUp();

		// post new party
		$this->request->setMethod('POST')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'name'=>'test party','hostId'=>PartyControllerTest::$hostId,'barId'=>'1','date'=>'2010-12-31 18:30:00'));
		$this->dispatch('/website/party/?format=xml');

		$xml = simplexml_load_string(print_r($this->response->outputBody(),true));
		$res = $xml->xpath("party");
		$xmlAttr = $res[0]->attributes();
		PartyControllerTest::$partyId = print_r($xmlAttr['id']."",true);
		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("get"); // redirect
		$this->assertResponseCode(201); // created
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('name="test party"',$this->response->outputBody());
	}

	public function testPostHashCodeMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('name'=>'test party','hostId'=>PartyControllerTest::$hostId,'barId'=>'1','date'=>'2010-12-31 18:30:00'));
		$this->dispatch('/website/party/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	public function testPostInvalidHashCodeAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>'abc','name'=>'test party','hostId'=>PartyControllerTest::$hostId,'barId'=>'1','date'=>'2010-12-31 18:30:00'));
		$this->dispatch('/website/party/?format=xml');

		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("post");
		$this->assertResponseCode(401); // unauthorized
		$this->assertXpathCount('/rsp[@status="error"]',1);
	}

	public function testPostDateMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'name'=>'test party','hostId'=>PartyControllerTest::$hostId,'barId'=>'1'));
		$this->dispatch('/website/party/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Date missing!'));
	}

	public function testPostDateInvalidAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'name'=>'test party','hostId'=>PartyControllerTest::$hostId,'barId'=>'1','date'=>'31.13.2010 18:30:12'));
		$this->dispatch('/website/party/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Wrong_Date_Format'));
	}

	public function testPostHostMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'name'=>'test party','barId'=>'1','date'=>'2010-12-31 18:30:00'));
		$this->dispatch('/website/party/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Host missing!'));
	}

	public function testPostBarMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'name'=>'test party','hostId'=>'1','date'=>'2010-12-31 18:30:00'));
		$this->dispatch('/website/party/?format=xml');

		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Bar missing!'));
	}

	public function testPostNameMissingAsXmlAction() {
		$this->request->setMethod('POST')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'hostId'=>PartyControllerTest::$hostId,'barId'=>'1','date'=>'2010-12-31 18:30:00'));
		$this->dispatch('/website/party/?format=xml');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('Name missing!'));
	}

	public function testPutAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'userId'=>PartyControllerTest::$hostId,'name'=>'test party1','hostId'=>'1','barId'=>'3','date'=>'2011-01-01 02:30:00'));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');

		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("get"); // redirect
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertContains('name="test party1"',$this->response->outputBody());
	}

	public function testPutInvalidHashCodeAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('userId'=>PartyControllerTest::$hostId,'hashCode'=>'abc','name'=>'test party1'));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');

		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("put");
		$this->assertResponseCode(401); // unauthorized
		$this->assertXpathCount('/rsp[@status="error"]',1);
	}

	public function testPutUserIdMissingAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'name'=>'test party1'));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('UserId missing!'));
	}


	public function testPutHashCodeMissingAsXmlAction() {
		$this->request->setMethod('PUT')->setPost(array('userId'=>PartyControllerTest::$hostId,'name'=>'test party1'));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	public function testDeleteInvalidHashCodeXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('userId'=>PartyControllerTest::$hostId,'hashCode'=>'abc'));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');

		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("delete");
		$this->assertResponseCode(401); // unauthorized
		$this->assertXpathCount('/rsp[@status="error"]',1);
	}

	public function testDeleteHashCodeMissingXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('userId'=>PartyControllerTest::$hostId));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('HashCode missing!'));
	}

	public function testDeleteUserIdMissingAsXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'name'=>'test party1'));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');
		$this->assertTrue($this->getResponse()->hasExceptionOfMessage('UserId missing!'));
	}

	public function testDeleteAsXmlAction() {
		$this->request->setMethod('DELETE')->setPost(array('hashCode'=>PartyControllerTest::$hashCode,'userId'=>PartyControllerTest::$hostId));
		$this->dispatch('/website/party/'.PartyControllerTest::$partyId.'?format=xml');
		$this->assertModule("website");
		$this->assertController("party");
		$this->assertAction("delete");
		$this->assertHeaderContains('Content-Type','application/xml');
		$this->assertXpathCount('/rsp[@status="ok"]',1);
		$this->assertXpathCount('/rsp/*',0);
	}
}
