<?php
/**
 * Members can be guests of a party, barkeepers
 * 
 * @author thobach
 */
class Website_Model_Member {

	// attributes
	private $id; // required
	private $passwordHash; // required
	private $firstname; // required
	private $lastname; // required
	private $birthday;
	private $email; // required
	private $lastLoginDate;
	private $hashCode;
	private $hashExpiryDate;
	private $apiKey;
	private $insertDate;
	private $updateDate;
	private $photoId;
	private $testDatabase;

	// associations
	private $_photo;
	private $_bars;

	// supporting variables
	//private static $_member; depreciated
	
	private $logger;

	/**
	 * magic getter for all attributes
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		// reload from registry, otherwise stream is closed (strange)
		$logger = Zend_Registry::get('logger');
		$logger->log('Website_Model_Member->__get',Zend_Log::DEBUG);

		if (property_exists(get_class($this), $name)) {
			if($name=='passwordHash'){
				$this->logger->log('Website_Model_Member->__get: Exception (property not exhibited)',Zend_Log::WARN);
				throw new Exception ( 'Class \''.get_class($this).'\' does not exhibit property: ' . $name . '.' ) ;
			} else {
				return $this->$name ;
			}
		} else {
			$this->logger->log('Website_Model_Member->__get: Exception (invalid property)',Zend_Log::WARN);
			throw new Exception('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
	}

	/**
	 * Magic Setter Function, is accessed when setting an attribute
	 *
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function __set ( $name , $value ) {
		$this->logger->log('Website_Model_Member->__set',Zend_Log::DEBUG);

		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			$this->loggerlog('Website_Model_Member->__set: Exception (invalid property)',Zend_Log::WARN);
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}
	}

	/**
	 * resolve Association and return an object of Photo
	 *
	 * @return Website_Model_Photo
	 */
	public function getPhoto() {
		$this->logger->log('Website_Model_Member->getPhoto',Zend_Log::DEBUG);

		if(!$this->_photo && $this->photoId){
			$this->_photo = Website_Model_CbFactory::factory('Website_Model_Photo',$this->photoId);
		} else {
			$this->_photo = NULL;
		}
		return $this->_photo;
	}

	/**
	 * resolve Association and return all Bar objects
	 *
	 * @return array[int]Website_Model_Bar
	 */
	public function getBars() {
		// reload from registry, otherwise stream is closed (strange)
		$logger = Zend_Registry::get('logger');
		$logger->log('Website_Model_Member->getBars',Zend_Log::DEBUG);
		if(!$this->_bars){
			$barTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','bar');
			$bars = $barTable->fetchAll('owner = '.$this->id);
			foreach($bars as $bar){
				$this->_bars[] = Website_Model_CbFactory::factory('Website_Model_Bar',$bar->id);
			}
		}
		return $this->_bars;
	}

	/**
	 * checks whether member exists in DB by given id
	 *
	 * @param String $id
	 * @return booloean
	 */
	public static function exists($id) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->exists',Zend_Log::DEBUG);

		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$member = $memberTable->fetchRow('id = '.$id);
		if ($member) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Sets the hashCode for the Member in order to perform logins, updates etc.
	 * 
	 * @param $hashCode
	 * @return Website_Model_Member fluent interface
	 * @throws Website_Model_MemberException('HashCode_Empty') $hashCode was empty
	 */
	public function setHashCode($hashCode){
		$this->logger->log('Website_Model_Member->setHashCode',Zend_Log::DEBUG);
		if(!empty($hashCode)){
			$this->hashCode = $hashCode;
		} else {
			$this->logger->log('Website_Model_Member->loggedIn: Website_Model_MemberException (HashCode_Empty)',Zend_Log::INFO);
			throw new Website_Model_MemberException('HashCode_Empty');
		}
		return $this;
	}

	/**
	 * Checks whether Member is logged in, prior to this check the hashCode 
	 * needs to be set via $member->setHashCode(...)
	 *
	 * @return booloean true if Member has non-expired hashCode, false if not 
	 * @throws Website_Model_MemberException('HashCode_Missing') if 
	 * 			$member->setHashCode() was not called before  
	 */
	public function loggedIn() {
		$this->logger->log('Website_Model_Member->loggedIn',Zend_Log::DEBUG);
		if(!$this->hashCode){
			$this->logger->log('Website_Model_Member->loggedIn: Website_Model_MemberException (HashCode_Missing)',Zend_Log::WARN);
			throw new Website_Model_MemberException('HashCode_Missing');
		}
		// do table lookup for member with hashCode and expiry
		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$select = $memberTable->select()->where('id = ?',$this->id)
					->where('hashCode = ?',$this->hashCode)
					->where('hashExpiryDate >= ?',date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP));
		$member = $memberTable->fetchRow($select);
		if ($member) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * checks whether email exists in DB
	 *
	 * @param String $email
	 * @return booloean true if member exists, false if not
	 */
	public static function existsByEmail($email) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->existsByEmail',Zend_Log::DEBUG);

		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$member = $memberTable->fetchRow('email = \''.$email.'\'');
		if ($member) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Checks whether the member exists in DB via email
	 * and returns the member object or false if the member
	 * does not exist
	 *
	 * @param String $email
	 * @return Website_Model_Member if member exists, booloean otherwise
	 */
	public static function getMemberByEmail($email){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->getMemberByEmail',Zend_Log::DEBUG);

		$memberTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$log->log('Website_Model_Member->getMemberByEmail: got memberTable',Zend_Log::DEBUG);
		$member = $memberTable->fetchRow($memberTable->select()->where('email=?',$email));
		$log->log('Website_Model_Member->getMemberByEmail: got member',Zend_Log::DEBUG);
		if ($member) {
			$log->log('Website_Model_Member->getMemberByEmail: true',Zend_Log::DEBUG);
			return Website_Model_CbFactory::factory('Website_Model_Member',$member->id);
		} else {
			$log->log('Website_Model_Member->getMemberByEmail: false',Zend_Log::INFO);
			return false;
		}
	}

	/**
	 * Creates a new Member object or loads an exisiting Member from the 
	 * database by the given id
	 *
	 * @return Website_Model_Member
	 * @throws Website_Model_MemberException(Id_Wrong)
	 */
	public function __construct ($id=NULL){
		// init logger
		$this->logger = Zend_Registry::get('logger');
		$this->logger->log('Website_Model_Member->__construct',Zend_Log::DEBUG);
		// load existing member from DB, don't load passwordHash, hashCode and hashExpiryDate
		if(!empty($id)){
			$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
			$member = $memberTable->fetchRow('id = '.$id);
			// if id was not found in the DB
			if(!$member){
				$this->logger->log('Website_Model_Member->__construct: Website_Model_MemberException (Id_Wrong)',Zend_Log::INFO);
				throw new Website_Model_MemberException('Id_Wrong');
			}
			$this->id = (int) $member->id;
			$this->firstname  =$member->firstname;
			$this->lastname = $member->lastname;
			$this->birthday = $member->birthday;
			$this->email = $member->email;
			$this->lastLoginDate = $member->lastLoginDate;
			$this->apiKey = $member->apiKey;
			$this->insertDate = $member->insertDate;
			$this->updateDate = $member->updateDate;
			$this->photoId = $member->photo;
		}
	}

	/**
	 * Lists all Members from the database
	 *
	 * @return array[int]Website_Model_Member
	 */
	public static function listMembers() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->listMembers',Zend_Log::DEBUG);

		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$members = $memberTable->fetchAll();

		foreach ($members as $member){
			$memberArray[] = Website_Model_CbFactory::factory('Website_Model_Member',$member->id);
		}
		return $memberArray;
	}

	/**
	 * Saves the member persistent to the database
	 *
	 * @return int|boolean if insert (int id), if update (boolean true)
	 */
	public function save (){
		$this->logger->log('Website_Model_Member->save',Zend_Log::DEBUG);

		if(empty($this->firstname)){
			$this->logger->log('Website_Model_Member->save: Website_Model_MemberException (Firstname missing!)',Zend_Log::INFO);
			throw new Website_Model_MemberException('Firstname missing!');
		}
		if(empty($this->lastname)){
			$this->logger->log('Website_Model_Member->save: Website_Model_MemberException (Lastname missing!)',Zend_Log::INFO);
			throw new Website_Model_MemberException('Lastname missing!');
		}
		if(empty($this->email)){
			$this->logger->log('Website_Model_Member->save: Website_Model_MemberException (Email missing!)',Zend_Log::INFO);
			throw new Website_Model_MemberException('Email missing!');
		}
		// password only required for registration
		if(!$this->id && empty($this->passwordHash)){
			$this->logger->log('Website_Model_Member->save: Website_Model_MemberException (Password missing!)',Zend_Log::INFO);
			throw new Website_Model_MemberException('Password missing!');
		}
		$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'member');
		// new member (insert)
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = Zend_Date::now()->toString(Website_Model_DateFormat::MYSQLTIMESTAMP);
			$this->id = $orderTable->insert($data);
			return $this->id;
		}
		// existing member (update)
		else {
			$orderTable->update($this->databaseRepresentation(),'id='.$this->id);
			return true;
		}
	}

	/**
	 * Sets the passwordHash of the Member
	 * 
	 * @throws Website_Model_MemberException(Not_Authorized) if member is not logged in
	 */
	public function setPassword($password){
		$this->logger->log('Website_Model_Member->setPassword',Zend_Log::DEBUG);
		
		// if user wants to change his password, he needs to be loggedIn
		// or when setting up a new user (id not yet set) he wants to set his 
		// password for the first time
		
		// order is important, since loggedIn would throw an exception otherwise
		if(!$this->id || $this->loggedIn()){ 
			$this->passwordHash = md5($password);
		} else {
			$this->logger->log('Website_Model_Member->setPassword: Website_Model_MemberException (Not_Authorized)',Zend_Log::WARN);
			throw new Website_Model_MemberException('Not_Authorized');
		}
	}

	/**
	 * deletes the Member from the database and memory
	 *
	 */
	public function delete (){
		$this->logger->log('Website_Model_Member->delete',Zend_Log::DEBUG);

		$memberTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'member');
		$memberTable->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Website_Model_Member',$this->id);
		unset($this);
	}

	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	public function dataBaseRepresentation() {
		$this->logger->log('Website_Model_Member->dataBaseRepresentation',Zend_Log::DEBUG);

		if($this->passwordHash){
			$array['passwordHash'] = $this->passwordHash;
		}
		$array['firstname'] = $this->firstname;
		$array['lastname'] = $this->lastname;
		$array['birthday'] = $this->birthday;
		$array['email'] = $this->email;
		$array['photo'] = $this->photoId;
		$array['hashCode'] = $this->hashCode;
		$array['hashExpiryDate'] = $this->hashExpiryDate;
		$array['apiKey'] = $this->apiKey;
		$array['testDatabase'] = $this->testDatabase;
		return $array;
	}

	/**
	 * Logs in the Member, creates a hashCode for further authentification 
	 * valid for 24 hours and creates a session under the namespace 'member'
	 * which holds the Member object
	 *
	 * @param string $passwordHash (md5 with prefix)
	 * @return boolean true = authentification successful, false = if password 
	 * 			is invalid or persistence error
	 */
	public function login ( $password ) {
		$this->logger->log('Website_Model_Member->login',Zend_Log::DEBUG);
		
		// check if passwordHash is valid
		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$select = $memberTable->select()->where('id = ?',$this->id)
					->where('passwordHash= ?',md5($password));
		$member = $memberTable->fetchRow($select);
		if ($member) {
			// create hashCode
			$this->hashCode = md5('9z1rhfxasf'.rand(20,2));
			// create new expiry date
			$date = Zend_Date::now();
			$date->add('24:00:00', Zend_Date::TIMES);
			$this->hashExpiryDate = $date->toString(Website_Model_DateFormat::MYSQLTIMESTAMP);
			// persist session and create PHP session
			if($this->save()){
				// create session
				$session = new Zend_Session_Namespace('member');
				$session->member = $this;
				return true;
			}
			// update affected no rows
			else {
				$this->logger->log('Website_Model_Member->login: DB update failed',Zend_Log::WARN);
				return false;	
			}
		} 
		// invalid password
		else {
			$this->logger->log('Website_Model_Member->login: invalid password',Zend_Log::INFO);
			return false;
		}
	}

	/**
	 * authenticates the member by the previous generated hashCode
	 *
	 * @param string $hashCode
	 * @return boolean true = authentification successful
	 * @throws MemberException
	 * @deprecated should not be necessary since we use sessions now
	 */
	public function authenticateByHashCode ( $passwordHash ) {
		$this->logger->log('Website_Model_Member->authenticateByHashCode',Zend_Log::DEBUG);

		$now = Zend_Date::now();
		$expiryDate = new Zend_Date($this->hashExpiryDate, Zend_Date::ISO_8601);
		if ($this->hashCode == $passwordHash)	{
			if($now->isEarlier($expiryDate)){
				return true;
			}	else {
				$this->logger->log('Website_Model_Member->authenticateByHashCode: Website_Model_MemberException (Member_HashCode_Expired)',Zend_Log::INFO);
				throw new Website_Model_MemberException('Member_HashCode_Expired');
			}
		} else {
			$this->logger->log('Website_Model_Member->authenticateByHashCode: Website_Model_MemberException (Member_HashCode_Invalid)',Zend_Log::INFO);
			throw new Website_Model_MemberException('Member_HashCode_Invalid');
		}
	}

	/**
	 * Performs a logout for a member if he is currently logged in (valid
	 * session)
	 *
	 * @return boolean true if session was destroyed, false if user is not
	 * logged in or destruction could not be completed
	 */
	public function logout(){
		$this->logger->log('Website_Model_Member->logout',Zend_Log::DEBUG);

		if($this->loggedIn()){
			$this->hashCode = null;
			$this->hashExpiryDate = null;
			// delete session
			$session = new Zend_Session_Namespace('member');
			$session->unsetAll();
			return ($this->save() ? true : false);
		} else {
			return false;
		}
	}

	/**
	 * returns an array of Orders by the member and party
	 *
	 * @param int $partyId
	 * @return array<Order>
	 * @tested
	 */
	public function getInvoice ( $partyId ){
		$this->logger->log('Website_Model_Member->getInvoice',Zend_Log::DEBUG);

		$party = Website_Model_CbFactory::factory('Website_Model_Party',$partyId);
		$db = Zend_Db_Table::getDefaultAdapter();
		$res = $db->fetchAll ( 'SELECT id
			FROM `order` 
			WHERE party='.$party->id.' AND
			status=\'ordered\' AND
			member='.$this->id.'');
		foreach($res as $order){
			$orderArray[] = Website_Model_CbFactory::factory('Website_Model_Order',$order->id);
		}
		return $orderArray;
	}

	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 * @tested
	 */
	public function toXml($xml, $branch,$withHash = false) {
		$this->logger->log('Website_Model_Member->getInvoice',Zend_Log::DEBUG);

		$member = $xml->createElement('member');
		$branch->appendChild($member);
		$member->setAttribute('id', $this->id);
		// never expose passwordHash
		$member->setAttribute('firstname', $this->firstname);
		$member->setAttribute('lastname', $this->lastname);
		$member->setAttribute('birthday', $this->birthday);
		$member->setAttribute('email', $this->email);
		if($withHash){
			$member->setAttribute('hashCode', $this->hashCode);
			$member->setAttribute('hashExpiryDate', $this->hashExpiryDate);
		}
		$member->setAttribute('apiKey', $this->apiKey);
		$member->setAttribute('email', $this->email);
		$member->setAttribute('insertDate', $this->insertDate);
		$member->setAttribute('updateDate', $this->updateDate);
		if($this->getPhoto()){
			$photo = $xml->createElement('photo');
			$this->getPhoto()->toXml($xml, $photo);
			$member->appendChild($photo);
		}
	}
}
?>