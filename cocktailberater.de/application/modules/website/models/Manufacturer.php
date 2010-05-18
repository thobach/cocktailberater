<?php
class Website_Model_Manufacturer {

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
	 * @return Website_Model_Manufacturer
	 */
	public function Website_Model_Manufacturer ( $idManufacturer = NULL ) {
		if (! empty ( $idManufacturer )) {
			$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','manufacturer');
			$manufacturer = $table->fetchRow ( 'id=' . $idManufacturer ) ;
			// if manufacturer does not exist
			if(!$manufacturer){
				throw new Website_Model_ManufacturerException('Id_Wrong');
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
	 * @return array Website_Model_Manufacturer
	 */
	public static function listManufacturer()
	{
		$manufacturerTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','manufacturer');
		foreach ($manufacturerTable->fetchAll(null,'name') as $manufacturer) {
			$manufacturerArray[] = Website_Model_CbFactory::factory('Website_Model_Manufacturer',$manufacturer->id);
		}
		return $manufacturerArray;
	}

	public static function exists($id) {
		$manufacturerTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'manufacturer') ;
		if($id>0){
			$manufacturer = $manufacturerTable->fetchRow('id='.$id);
		} else {
			$manufacturer = $manufacturerTable->fetchRow($manufacturerTable->select()
			->where('name = ?',rawurldecode(str_replace(array('_'),array(' '),$id))));
		}
		if ($manufacturer) {
			return $manufacturer->id;
		} else {
			return false;
		}
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
		Website_Model_CbFactory::destroy('Website_Model_Manufacturer',$this->id);
		unset($this);
	}

	public function getProducts (){
		// load cache from registry
		$cache = Zend_Registry::get('cache');
		// see if product - list is already in cache
		if(!$productArray = $cache->load('productsByManufacturerId'.$this->id)) {
			$productTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product');
			$products = $productTable->fetchAll('manufacturer='.$this->id);
			foreach($products as $product){
				$productArray[] = Website_Model_CbFactory::factory('Website_Model_Product',$product->id);
			}
			$cache->save($productArray,'productsByManufacturerId'.$this->id,array('model'));
		}
		return $productArray;
	}

	public function getRecipes(){
		// load cache from registry
		$cache = Zend_Registry::get('cache');
		// see if product - list is already in cache
		if(!$recipes = $cache->load('recipesByManufacturerId'.$this->id)) {
			$recipes = array();
			foreach ($this->getProducts() as $product){
				$componentsArray = Website_Model_Component::componentsByIngredientId($product->getIngredient()->id);
				if(is_array($componentsArray)){
					foreach ($componentsArray as $component){
						$recipes[$component->recipeId] = Website_Model_CbFactory::factory('Website_Model_Recipe',$component->recipeId);
					}
				}
			}
			$cache->save($recipes,'recipesByManufacturerId'.$this->id,array('model'));
		}
		return $recipes;
	}

	public function getUniqueName() {
		return rawurlencode(str_replace(array(' '),array('_'),$this->name));
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