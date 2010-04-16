<?php
class Website_Model_Glass {

	// attributes
	private $id ;
	private $name ;
	private $description;
	private $volumeMl;
	private $insertDate;
	private $updateDate;
	private $photoId;

	// associations
	private $_photo;

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
	 * resolve Association and return an object of Photo
	 *
	 * @return Website_Model_Photo
	 */
	public function getPhoto()
	{
		if(!$this->_photo){
			$this->_photo = Website_Model_Photo::getPhoto($this->photoId);
		}
		return $this->_photo;
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
	 * Glass constructor returns a Glass object by an id, or an empty one
	 *
	 * @param integer optional $id
	 * @return Glass
	 */
	public function __construct ( $idGlass = NULL ) {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','glass');
		if (! empty ( $idGlass )) {
			$glass = $table->fetchRow ( 'id=' . $idGlass ) ;
			// if glass does not exist
			if(!$glass){
				throw new Website_Model_GlassException('Id_Wrong');
			}
			$this->id 			= $glass->id ;
			$this->name 		= $glass->name;
			$this->description	= $glass->description;
			$this->volumeMl 	= $glass->volumeMl;
			$this->photoId 		= $glass->photo;
			$this->insertDate 	= $glass->insertDate;
			$this->updateDate 	= $glass->updateDate;
		}
	}

	/**
	 * returns an array of all glass objects
	 *
	 * @return array Glasses
	 */
	public static function listGlasses()
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','glass');
		foreach ($table->fetchAll() as $glass) {
			$glassArray[] = Website_Model_CbFactory::factory('Website_Model_Glass',$glass->id);
		}
		return $glassArray;
	}

	public function save (){
		$glassTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'glass');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $glassTable->insert($data);
		}
		else {
			$glassTable->update($this->databaseRepresentation(),array ('id'=>$this->id));
		}
	}
	
	public function delete (){
		$glassTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'glass');
		$glassTable->delete('id='.$this->id);
		CbFactory::destroy('Glass',$this->id);
		unset($this);
	}
	
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['photo'] = $this->photoId;
		$array['name'] = $this->name;
		$array['description'] = $this->description;
		$array['volumeMl'] = $this->volumeMl;
		return $array;
	}
	
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml ( $xml , $ast ) {
		$glass = $xml->createElement ( 'glass' ) ;
		$glass->setAttribute ( 'id', $this->id ) ;
		$this->getPhoto()->toXml ( $xml, $glass ) ;
		$glass->setAttribute ( 'name', $this->name ) ;
		$glass->setAttribute ( 'description', $this->description) ;
		$glass->setAttribute ( 'volumeMl', $this->volumeMl) ;
		$ast->appendchild ( $glass ) ;
	}
}
?>