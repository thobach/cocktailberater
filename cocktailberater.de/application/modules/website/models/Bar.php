<?php
/**
 * Bar class represents a cocktail bar in which partys take place
 *
 */
class Website_Model_Bar {
	
	// attributes
	private $id;
	private $name;
	private $location;
	private $description;
	private $country;
	private $insertDate;
	private $updateDate;
	private $ownerId;
	private $newsletterId;

	// associations
	private $_owner;
	private $_newsletter;

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
	 * resolve Association and return an object of Member
	 *
	 * @return Member
	 */
	public function getOwner()
	{
		if(!$this->_owner){
			$this->_owner = Website_Model_CbFactory::factory('Website_Model_Member',$this->ownerId);
		}
		return $this->_owner;
	}


	/**
	 * resolve Association and return an object of Newsletter
	 *
	 * @return Newsletter
	 */
	public function getNewsletter()
	{
		if(!$this->_newsletter){
			$this->_newsletter = Website_Model_CbFactory::factory('Website_Model_Newsletter',$this->newsletterId);
		}
		return $this->_newsletter;
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


	/**
	 * checks whether the bar exists in DB
	 *
	 * @param String $id
	 * @return booloean | int False or ID for bar
	 */
	public static function exists($id) {
		$barTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','bar');
		$bar = $barTable->fetchRow('id='.$id);
		if ($bar) {
			return $bar->id;
		} else {
			return false;
		}
	}
	
	/**
	 * Bar constructor returns a Bar object by an id, or an empty one
	 *
	 * @throws BarException(Id_Wrong)
	 * @param integer optional $id
	 * @return Bar
	 */
	public function __construct ($id=NULL){
		if(!empty($id)){
			$barTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','bar');
			$bar = $barTable->fetchRow('id='.$id);
			// if bar does not exist
			if(!$bar){
				throw new Website_Model_BarException('Id_Wrong');
			}
			// attributes
			$this->id 			= $bar->id;
			$this->name 		= $bar->name;
			$this->location 	= $bar->location;
			$this->description 	= $bar->description;
			$this->country 		= $bar->country;
			$this->insertDate 	= $bar->insertDate;
			$this->updateDate 	= $bar->updateDate;
			$this->ownerId 		= $bar->owner;
			$this->newsletterId	= $bar->newsletter;
		}
	}

	/**
	 * returns an array of all Bar objects
	 *
	 * @return array Bar
	 */
	public static function listBars()
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','bar');
		foreach ($table->fetchAll() as $bar) {
			$barArray[] = Website_Model_CbFactory::factory('Website_Model_Bar',$bar->id);
		}
		return $barArray;
	}
	
	public function addProduct ($idProduct){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product2bar');
		$data['bar'] = $this->id;
		$data['product'] = $idProduct;
		try{
		$table->insert($data);
		} catch (Zend_Db_Statement_Exception $e){
			Zend_Debug::dump("Fehler bei $idProduct");
			Zend_Debug::dump($e);
		}
	}
	
	public function getProducts (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product2bar');
		return $table->fetchAll('bar='.$this->id);
	}
	
	public function removeProducts (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product2bar');
		$table->delete('bar='.$this->id);
	}
	
	public function hasProduct ($idProduct){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product2bar');
		if($table->fetchRow('bar='.$this->id.' and product='.$idProduct)) {
			return true;
		} else {
			return false;
		}
	}

	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'bar');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $table->insert($data);
		}
		else {
			$table->update($this->databaseRepresentation(),'id='.$this->id);
		}
	}
	
	public function delete (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'bar');
		$table->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Website_Model_Bar',$this->id);
		unset($this);
	}
	
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['name'] = $this->name;
		$array['owner'] = $this->ownerId;
		$array['location'] = $this->location;
		$array['description'] = $this->description;
		$array['country'] = $this->country;
		$array['newsletter'] = $this->newsletterId;
		return $array;
	}
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml($xml, $branch) {
		$bar = $xml->createElement('bar');
		$branch->appendChild($bar);
		$bar->setAttribute('id', $this->id);
		$bar->setAttribute('name', $this->name);
		$bar->setAttribute('location', $this->location);
		$bar->setAttribute('description', $this->description);
		$bar->setAttribute('country', $this->country);
		$bar->setAttribute('newsletterId', $this->newsletterId);
		$bar->setAttribute('ownerId', $this->ownerId);
		$bar->setAttribute('insertDate', $this->insertDate);
		$bar->setAttribute('updateDate', $this->updateDate);
		$owner = $xml->createElement('owner');
		$this->getOwner()->toXml($xml, $owner);
		$bar->appendChild($owner);
	}

}