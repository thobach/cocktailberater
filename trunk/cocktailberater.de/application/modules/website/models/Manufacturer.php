<?php
class Manufacturer {

	// attributes
	private $id ;
	private $name ;
	private $website;
	private $country;
	private $insertDate;
	private $updateDate;

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
	 * Manufacturer constructor returns a Manufacturer object by an id, or an empty one
	 *
	 * @param integer optional $idManufacturer
	 * @return Manufacturer
	 */
	public function Manufacturer ( $idManufacturer = NULL ) {
		if (! empty ( $idManufacturer )) {
			$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','manufacturer');
			$manufacturer = $table->fetchRow ( 'id=' . $idManufacturer ) ;
			// if manufacturer does not exist
			if(!$manufacturer){
				throw new ManufacturerException('Id_Wrong');
			}
			$this->id 			= $manufacturer->id ;
			$this->name 		= $manufacturer->name;
			$this->website		= $manufacturer->website;
			$this->country 		= $manufacturer->country;
			$this->insertDate 	= $manufacturer->insertDate;
			$this->updateDate 	= $manufacturer->updateDate;
		}
	}

	/**
	 * returns an array of all Manufacturer objects
	 *
	 * @return array Manufacturer
	 */
	public static function listManufacturer()
	{
		$manufacturerTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','manufacturer');
		foreach ($manufacturerTable->fetchAll() as $manufacturer) {
			$manufacturerArray[] = CbFactory::factory('Manufacturer',$manufacturer->id);
		}
		return $manufacturerArray;
	}

	public function save (){
		$manufacturerTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'manufacturer');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $manufacturerTable->insert($data);
		}
		else {
			$manufacturerTable->update($this->databaseRepresentation(),'id='.$this->id);
		}
	}
	
	public function delete (){
		$manufacturerTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'manufacturer');
		$manufacturerTable->delete('id='.$this->id);
		CbFactory::destroy('Manufacturer',$this->id);
		unset($this);
	}
	
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['website'] = $this->website;
		$array['name'] = $this->name;
		$array['country'] = $this->country;
		return $array;
	}
	
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml ( $xml , $ast ) {
		$manufacturer = $xml->createElement ( 'manufacturer' ) ;
		$manufacturer->setAttribute ( 'id', $this->id ) ;
		$manufacturer->setAttribute ( 'name', $this->name ) ;
		$manufacturer->setAttribute ( 'website', $this->website) ;
		$manufacturer->setAttribute ( 'country', $this->country) ;
		$ast->appendchild ( $manufacturer ) ;
	}
}
?>