<?php
class UserController extends PHPUnit_Framework_TestCase {

	function setUp() {
		// Prepare the database
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->query('DELETE FROM user');
	}

	function testMainUseCase() {
		// User opens http://localhost/main/user/register
		$front = Zend_Controller_Front::getInstance();
		$request = new Zend_Controller_Request_Http('http://localhost/main/user/register');
		$response = new Zend_Controller_Response_Http();
		$front->returnResponse(true)->setRequest($request)->setResponse($response);
		// System shows registration screen by invoking registerAction().
		// This action renders the "register.phtml" View script.
		$front->dispatch();
		$this->assertContains('</form>', $response->getBody());
		// User enters desired name and password into the form fields
		// and submits form.
		$request = new Zend_Controller_Request_Http('http://localhost/main/user/register.do');
		$request->setParams(array('name' => 'joe', 'password' => 'secret'));
		$response = new Zend_Controller_Response_Http();
		$front->returnResponse(true)->setRequest($request)->setResponse($response);
		// System sends data to registerDo action and then forwards request
		// to the "message" action with operation status.
		$front->dispatch();
		$this->assertContains('User is registered', $response->getBody());
	}

	function testAnotherUseCase() {
		// ...
	}

}