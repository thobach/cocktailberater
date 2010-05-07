<?php
class Website_Model_Photo {
	
	// attributes
	private $id ;
	private $name ;
	private $description;
	private $fileName ;
	private $originalFileName ;
	private $insertDate;
	private $updateDate;
	private $photoCategoryId;
	
	// assiciations
	private $_photoCategory;

	// supporting variables
	private static $_photo;
	
	/**
	 * magic getter for all attributes
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		if(strpos($name,"_")===0) {
			throw new Website_Model_PhotoException ( 'Access to private property: ' . $name . ' not allowed.' ) ;
		} else if (property_exists(get_class($this), $name)) {
			return $this->$name;
		} else {
			throw new Exception('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
	}
	
	public function getPhotoCategory()
	{
		if(!$this->_photoCategory){
			$this->_photoCategory = Website_Model_PhotoCategory::getPhotoCategory($this->photoCategoryId);
		}
		return $this->_photoCategory;
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
	 * 
	 * @deprecated use factory instead
	 * @param int $id
	 */
	public static function getPhoto($id) {
		return Website_Model_CbFactory::factory('Website_Model_Photo',$id);
	}
	
	
	public function __construct ( $idfoto = NULL ) {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'photo');
		if (! empty ( $idfoto )) {
			$photo = $table->fetchRow ( 'id = ' . $idfoto ) ;
			$this->id = $photo->id;
			$this->fileName = $photo->fileName ;
			$this->originalFileName = $photo->originalFileName ;
			$this->name = $photo->name ;
			$this->description = $photo->description ;
			$this->photoCategoryId = $photo->photoCategory;
		}
	}
	
	public function getPhotoUrl(){
		return Zend_Controller_Front::getInstance()->getBaseUrl().'/img/'.
		$this->getPhotoCategory()->folder.'/'.
		$this->id.'.jpg';
	}
	
	/**
	 * 
	 * @param int $idrezept
	 * @param int $max number of photos required
	 */
	public static function photosByRecipeId ( $idrezept, $max = NULL ) {
		if($idrezept<1 || $idrezept === null){
			throw new Exception ( 'No ID given for '.get_class().'::photosByRecipeId().' ) ;
		}
		$photo2recipeTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'photo2recipe');
		$photo2recipes = $photo2recipeTable->fetchAll ( 'recipe=' . $idrezept , NULL, $max) ;
		$photoArray = array();
		foreach ( $photo2recipes as $photo2recipe ) {
			$photoArray [] = Website_Model_CbFactory::factory('Website_Model_Photo',$photo2recipe->photo);
		}
		return $photoArray ;
	}
	
	/**
	 * returns an array of all Photo objects
	 *
	 * @return array Photo
	 */
	public static function listPhotos()
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','photo');
		foreach ($table->fetchAll() as $photo) {
			$photoArray[] = Website_Model_CbFactory::factory('Website_Model_Photo',$photo->id);
		}
		return $photoArray;
	}

	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'photo');
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
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'photo');
		$table->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Website_Model_Photo',$this->id);
		unset($this);
	}
	
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['name'] = $this->name;
		$array['description'] = $this->description;
		$array['originalFileName'] = $this->originalFileName;
		$array['fileName'] = $this->fileName;
		$array['photoCategory'] = $this->photoCategoryId;
		return $array;
	}
	
	public function toXml ( $xml , $ast ) {	
		$photo = $xml->createElement ( 'photo' ) ;
		$photo->setAttribute ( 'id', $this->id ) ;
		$photo->setAttribute ( 'name', $this->name ) ;
		$photo->setAttribute ( 'description', $this->description ) ;
		// TODO: let it work for localhost
		$photo->setAttribute ( 'url', 'http://'.$_SERVER[SERVER_NAME].'/img/'.$this->getPhotoCategory()->folder.'/'.$this->fileName ) ;
		$photo->setAttribute ( 'originalFileName', $this->originalFileName ) ;
		$photo->setAttribute ( 'photoCategory', $this->photoCategoryId ) ;
		$ast->appendchild ( $photo ) ;
	}
}
?>