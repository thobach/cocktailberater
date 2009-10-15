<?php
/**
 * abstract Category class which is inherited by special categorys
 *
 */
abstract class Website_Model_Category {
	
	// attributes
	protected $id;
	protected $name;
	protected $insertDate;
	protected $updateDate;

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
	 * Category constructor returns a Category object by an id, or an empty one
	 *
	 * @param integer optional $id
	 * @return Bar
	 */
	public function __construct($id = NULL)
	{
		if(!empty($id)){

		}
	}
	
	abstract public function save();
	abstract public function delete();
	abstract protected function dataBaseRepresentation();
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml ( $xml , $ast ) {
		$category = $xml->createElement ( 'category' ) ;
		$category->setAttribute ( 'id', $this->id ) ;
		$category->setAttribute ( 'name', $this->name ) ;
		$category->setAttribute ( 'insertDate', $this->insertDate) ;
		$category->setAttribute ( 'updateDate', $this->updateDate) ;
		$ast->appendchild ( $category ) ;
	}
}
