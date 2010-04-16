<?php
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

	// supporting variables
	//private static $_member; depreciated


	/**
	 * magic getter for all attributes
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->__get',Zend_Log::DEBUG);
		
		if (property_exists(get_class($this), $name)) {
			if($name=='passwordHash'){
				$log->log('Website_Model_Member->__get: Exception (property not exhibited)',Zend_Log::DEBUG);
				throw new Exception ( 'Class \''.get_class($this).'\' does not exhibit property: ' . $name . '.' ) ;
			} else {
				return $this->$name ;
			}
		} else {
			$log->log('Website_Model_Member->__get: Exception (invalid property)',Zend_Log::DEBUG);
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
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->__set',Zend_Log::DEBUG);
		
		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			$log->log('Website_Model_Member->__set: Exception (invalid property)',Zend_Log::DEBUG);
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}
	}

	/**
	 * resolve Association and return an object of Photo
	 *
	 * @return Photo
	 */
	public function getPhoto() {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->getPhoto',Zend_Log::DEBUG);

		if(!$this->_photo){
			$this->_photo = Website_Model_CbFactory::factory('Website_Model_Photo',$this->photoId);
		}
		return $this->_photo;
	}

	/**
	 * checks whether member exists in DB
	 *
	 * @param String $id
	 * @return booloean
	 * @tested
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
	 * checks whether member is logged in
	 *
	 * @param String $id
	 * @param String $hashCode
	 * @return booloean
	 * @todo: write unit test
	 */
	public static function loggedIn($id,$hashCode) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->loggedIn',Zend_Log::DEBUG);
		
		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$member = $memberTable->fetchRow('id = '.$id.' AND hashCode = \''.$hashCode.'\' AND hashExpiryDate>= \''.date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP).'\'');
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
	 * checks whether the member exists in DB via email
	 * and returns the member object or false if the member
	 * does not exist
	 *
	 * @param String $email
	 * @return Website_Model_Member if member exists, booloean otherwise
	 */
	public static function getMemberByEmail($email){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->getMemberByEmail',Zend_Log::DEBUG);
		
		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$member = $memberTable->fetchRow('email = \''.$email.'\'');
		if ($member) {
			return Website_Model_CbFactory::factory('Website_Model_Member',$member->id);
		} else {
			$log->log('Website_Model_Member->getMemberByEmail: false',Zend_Log::DEBUG);
			return false;
		}
	}

	/**
	 *creates an empty member or loads an exisiting member by id
	 *
	 *@return Member
	 *@throws MemberException(Id_Wrong)
	 *@tested
	 */
	public function __construct ($id=NULL){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->__construct',Zend_Log::DEBUG);
		
		if(!empty($id)){
			$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
			$member = $memberTable->fetchRow('id = '.$id);
			if(!$member){
				$log->log('Website_Model_Member->__construct: Website_Model_MemberException (Id_Wrong)',Zend_Log::DEBUG);
				throw new Website_Model_MemberException('Id_Wrong');
			}
			$this->id = (int) $member->id;
			$this->passwordHash = $member->passwordHash;
			$this->firstname  =$member->firstname;
			$this->lastname = $member->lastname;
			$this->birthday = $member->birthday;
			$this->email = $member->email;
			$this->lastLoginDate = $member->lastLoginDate;
			$this->hashCode = $member->hashCode;
			$this->hashExpiryDate = $member->hashExpiryDate;
			$this->apiKey = $member->apiKey;
			$this->insertDate = $member->insertDate;
			$this->updateDate = $member->updateDate;
			$this->photoId = $member->photo;
		}
	}

	/**
	 *lists all Members from the database
	 *
	 *@return array<Member>
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
	 *saves the member persistent to the database
	 *
	 *@return int|boolean if insert (int id), if update (boolean true)
	 *@tested
	 */
	public function save (){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->save',Zend_Log::DEBUG);
		
		if(empty($this->firstname)){
			$log->log('Website_Model_Member->save: Website_Model_MemberException (Firstname missing!)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException('Firstname missing!');
		}
		if(empty($this->lastname)){
			$log->log('Website_Model_Member->save: Website_Model_MemberException (Lastname missing!)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException('Lastname missing!');
		}
		if(empty($this->email)){
			$log->log('Website_Model_Member->save: Website_Model_MemberException (Email missing!)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException('Email missing!');
		}
		if(empty($this->passwordHash)){
			$log->log('Website_Model_Member->save: Website_Model_MemberException (Password missing!)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException('Password missing!');
		}
		$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'member');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = Zend_Date::now()->toString(Website_Model_DateFormat::MYSQLTIMESTAMP);
			$this->id = $orderTable->insert($data);
			return $this->id;
		}
		else {
			$orderTable->update($this->databaseRepresentation(),'id='.$this->id);
			return true;
		}
	}

	/**
	 * Sets the passwordHash of the Member
	 */
	public function setPassword($password){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->setPassword',Zend_Log::DEBUG);
		
		$this->passwordHash = md5($password);
	}

	/**
	 * deletes the Member from the database and memory
	 *
	 */
	public function delete (){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->delete',Zend_Log::DEBUG);
		
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
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->dataBaseRepresentation',Zend_Log::DEBUG);
		
		$array['passwordHash'] = $this->passwordHash;
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
	 * authenticates the member and creates a hashCode for
	 * further authentification valid for 24 hours
	 *
	 * @param string $passwordHash (md5 with prefix)
	 * @return boolean true = authentification successful, false = failed
	 * @throws boolean false if password is invalid, true otherwise
	 * @tested
	 */
	public function authenticate ( $password ) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->authenticate',Zend_Log::DEBUG);
		
		if ($this->passwordHash == md5($password))	{
			$this->hashCode = md5('9z1rhfxasf'.rand(20,2));
			$date = Zend_Date::now();
			$date->add('24:00:00', Zend_Date::TIMES);
			$this->hashExpiryDate = $date->toString(Website_Model_DateFormat::MYSQLTIMESTAMP);
			$this->save();
			return true;
		} else {
			return false;
		}
	}

	/**
	 * authenticates the member by the previous generated hashCode
	 *
	 * @param string $hashCode
	 * @return boolean true = authentification successful
	 * @throws MemberException
	 */
	public function authenticateByHashCode ( $passwordHash ) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->authenticateByHashCode',Zend_Log::DEBUG);
		
		$now = Zend_Date::now();
		$expiryDate = new Zend_Date($this->hashExpiryDate, Zend_Date::ISO_8601);
		if ($this->hashCode == $passwordHash)	{
			if($now->isEarlier($expiryDate)){
				return true;
			}	else {
				$log->log('Website_Model_Member->authenticateByHashCode: Website_Model_MemberException (Member_HashCode_Expired)',Zend_Log::DEBUG);
				throw new Website_Model_MemberException('Member_HashCode_Expired');
			}
		} else {
			$log->log('Website_Model_Member->authenticateByHashCode: Website_Model_MemberException (Member_HashCode_Invalid)',Zend_Log::DEBUG);
			throw new Website_Model_MemberException('Member_HashCode_Invalid');
		}
	}

	/**
	 * Performs a logout for a member if he is currently logged in (valid
	 * session)
	 *
	 * @param String $hashCode
	 * @return boolean true if session was destroyed, false if user is not
	 * logged in or destruction could not be completed
	 */
	public function logout($hashCode){
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->logout',Zend_Log::DEBUG);
		
		if($this->hashCode == $hashCode){
			$this->hashCode = null;
			$this->hashExpiryDate = null;
			return $this->save();
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
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->getInvoice',Zend_Log::DEBUG);
		
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
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Member->getInvoice',Zend_Log::DEBUG);
		
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
		if($this->getPhoto()->id){
			$photo = $xml->createElement('photo');
			$this->getPhoto()->toXml($xml, $photo);
			$member->appendChild($photo);
		}
	}
}
?>