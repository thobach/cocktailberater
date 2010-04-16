<?php
class Website_Model_Video
{
	// attributes
	private $id;
	private $name;
	private $description;
	private $url;
	private $recipeId;
	private $insertDate;
	private $updateDate;

	// associations
	private $_recipe;

	// private $table;


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
	 * resolve Association and return an object of Recipe
	 *
	 * @return Recipe
	 */
	public function getRecipe()
	{
		if(!$this->_recipe){
			$this->_recipe = Website_Model_CbFactory::factory('Website_Model_Recipe',$this->recipeId);
		}
		return $this->_recipe;
	}

	public function __construct($idvideo=NULL)
	{
		if($idvideo){
			$table 				= Website_Model_CbFactory::factory('Website_Model_MysqlTable','video');
			$video 				= $table->fetchRow('id='.$idvideo);
			$this->id			= $video->id;
			$this->name			= $video->name;
			$this->description	= $video->description;
			$this->url			= $video->url;
			//$this->insertDate 	= new Zend_Date($video->insertDate,Website_Model_DateFormat::MYSQLTIMESTAMP);
			//$this->updateDate 	= new Zend_Date($video->updateDate,Website_Model_DateFormat::MYSQLTIMESTAMP);
			$this->insertDate 	= $video->insertDate;
			$this->updateDate 	= $video->updateDate;
			$this->recipeId 	= $video->recipe;
		}
	}
	static function videosByRecipeId($id){
		$videoTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','video');
		$videos = $videoTable->fetchAll('recipe='.$id);
		$videoArray = array();
		foreach ($videos as $video){
			$videoArray[] = Website_Model_CbFactory::factory('Website_Model_Video',$video->id);
		}
		return $videoArray;
	}


	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'video');
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
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'video');
		$table->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Website_Model_Video',$this->id);
		unset($this);
	}

	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['recipe'] 		= $this->recipeId;
		$array['name'] 			= $this->name;
		$array['description'] 	= $this->description;
		$array['url'] 			= $this->url;
		return $array;
	}

	public function toXml ($xml,$ast){
		$video = $xml->createElement('video');
		$video->setAttribute('id',$this->id);

		//$recipe = $xml->createElement('recipe');
		//$this->getVideo()->toXml($this->xml, $recipe);
		//$video->appendChild($recipe);

		$video->setAttribute('name',$this->name);
		$video->setAttribute('description',$this->description);
		$video->setAttribute('url',$this->url);
		$video->setAttribute('insertDate',$this->insertDate);
		$video->setAttribute('updateDate',$this->updateDate);
		$ast->appendchild($video);
	}
}
?>