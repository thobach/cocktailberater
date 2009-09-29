<?php
class Website_Model_PhotoCategory extends Category {
	
	// attributes
	private $folder;
	
	// associations
	
	// supporting variables
	private static $_photoCategory;
	
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
	
	public static function getPhotoCategory($id)
	{
		if(!PhotoCategory::$_photoCategory[$id]){
			PhotoCategory::$_photoCategory[$id] = CbFactory::factory('PhotoCategory',$id);
		}
		return PhotoCategory::$_photoCategory[$id];
	}
	
	public function PhotoCategory($id = NULL)
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','photocategory');
		if(!empty($id)){
			$photoCategory = $table->fetchRow('id='.$id);
			$this->id = $photoCategory->id;
			$this->name = $photoCategory->name;
			$this->folder = $photoCategory->folder;
			$this->insertDate = $photoCategory->insertDate;
			$this->updateDate = $photoCategory->updateDate;
		}
	}
	
	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'photocategory');
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
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'photocategory');
		$table->delete('id='.$this->id);
		CbFactory::destroy('PhotoCategory',$this->id);
		unset($this);
	}
	
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	protected function dataBaseRepresentation() {
		$array['name'] = $this->name;
		$array['folder'] = $this->folder;
		return $array;
	}
	
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml ( $xml , $ast ) {
		$photoCategory = $xml->createElement ( 'photoCategory' ) ;
		$photoCategory->setAttribute ( 'id', $this->id ) ;
		$photoCategory->setAttribute ( 'name', $this->name ) ;
		$photoCategory->setAttribute ( 'folder', $this->folder ) ;
		$ast->appendchild ( $photoCategory ) ;
	}
	
}
?>