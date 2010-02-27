<?php
class Website_Model_Member {

	// attributes
	private $id;
	private $passwordHash;
	private $firstname;
	private $lastname;
	private $birthday;
	private $email;
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
		if (property_exists(get_class($this), $name)) {
		if($name=='passwordHash'){
				throw new Exception ( 'Class \''.get_class($this).'\' does not exhibit property: ' . $name . '.' ) ;
			} else {
				return $this->$name ;
			}
		} else {
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
		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}
	}

	/* depreciated!!
	 public static function getMember($id)
	 {
		if(!Member::$_member[$id]){
		Member::$_member[$id] = Website_Model_CbFactory::factory('Website_Model_Member', $id);
		}
		return Member::$_member[$id];
		}
		*/

	/**
	 * resolve Association and return an object of Photo
	 *
	 * @return Photo
	 */
	public function getPhoto()
	{
		// TODO: write unit test
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
		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$member = $memberTable->fetchRow('id = '.$id);
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
	 * @return booloean
	 */
	public static function existsByEmail($email) {
		// TODO: write unit test
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
	 * @tested
	 */
	public static function getMemberByEmail($email){
		$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
		$member = $memberTable->fetchRow('email = \''.$email.'\'');
		if ($member) {
			return Website_Model_CbFactory::factory('Website_Model_Member',$member->id);
		} else {
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
		if(!empty($id)){
			$memberTable  = Website_Model_CbFactory::factory('Website_Model_MysqlTable','member');
			$member = $memberTable->fetchRow('id = '.$id);
			if(!$member){
				throw new MemberException('Id_Wrong');
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
	 *@tested
	 */
	public static function listMembers()
	{
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
	 * deletes the Member from the database and memory
	 *
	 * @tested
	 */
	public function delete (){
		$memberTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'member');
		$memberTable->delete('id='.$this->id);
		CbFactory::destroy('Member',$this->id);
		unset($this);
	}

	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	public function dataBaseRepresentation() {
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
	public function authenticate ( $passwordHash ) {
		if ($this->passwordHash == $passwordHash)	{
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
	 * @tested
	 */
	public function authenticateByHashCode ( $passwordHash ) {
		$now = Zend_Date::now();
		$expiryDate = new Zend_Date($this->hashExpiryDate, Zend_Date::ISO_8601);
		if ($this->hashCode == $passwordHash)	{
			if($now->isEarlier($expiryDate)){
				return true;
			}	else {
				throw new MemberException('Member_HashCode_Expired');
			}
		} else {
			throw new MemberException('Member_HashCode_Invalid');
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
		$party = CbFactory::factory('Party',$partyId);
		$db = Zend_Db_Table::getDefaultAdapter();
		$res = $db->fetchAll ( 'SELECT id
			FROM `order` 
			WHERE party='.$party->id.' AND
			status=\'ordered\' AND
			member='.$this->id.'');
		foreach($res as $order){
			$orderArray[] = CbFactory::factory('Order',$order->id);
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