<?php
// TODO: do not use this adapter any more...

class Website_Model_CbAuthAdapter implements Zend_Auth_Adapter_Interface
{
	private $emailAdress;
	private $passwordHash;
	private $memberId;

	/**
	 * Sets login credentials for authentication
	 *
	 * @return void
	 */
	public function __construct($emailAdress, $passwordHash)
	{
		$this->emailAdress=$emailAdress;
		$this->passwordHash=$passwordHash;
	}

	/**
	 * Performs an authentication attempt
	 *
	 * @throws Zend_Auth_Adapter_Exception If authentication cannot be performed
	 * @return Zend_Auth_Result
	 */
	public function authenticate()
	{
		try {
			$this->memberId = Member::authenticate($this->emailAdress,$this->passwordHash);
			$auth1 = new Zend_Auth_Result(Zend_Auth_Result::SUCCESS,Website_Model_CbFactory::factory('Website_Model_Member',$this->memberId));
				
		}

		catch (CbAuthException $e) {
			$auth1 = new Zend_Auth_Result(Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,NULL);
		}
			
		return $auth1;

	}
}
?>