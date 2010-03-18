<?php
class Website_Model_Product
{
	// attributes
	private $id;
	private $ingredientId;
	private $name;
	private $manufacturerId;
	private $size;
	private $unit;
	private $alcoholLevel;
	private $caloriesKcal;
	private $densityGramsPerCm3;
	private $fruitConcentration;
	private $color;
	private $insertDate;
	private $updateDate;

	// associations
	private $_ingredient;
	private $_manufacturer;
	
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
	 * resolve Association and return an object of Ingredient
	 *
	 * @return Ingredient
	 * @tested
	 */
	public function getIngredient()
	{
		if(!$this->_ingredient){
			$this->_ingredient = CbFactory::factory('Ingredient',$this->ingredientId);
		}
		return $this->_ingredient;
	}
	
	/**
	 * resolve Association and return an object of Manufacturer
	 *
	 * @return Manufacturer
	 * @tested
	 */
	public function getManufacturer()
	{
		if($this->manufacturerId!=null){
		if(!$this->_manufacturer){
			$this->_manufacturer = Website_Model_CbFactory::factory('Website_Model_Manufacturer',$this->manufacturerId);
		}
		return $this->_manufacturer;
		} else {
			return null;
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
	 * @tested
	 */
	public function __construct ($productId=NULL){
		if(!empty($productId)){
			$productTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
			$product = $productTable->fetchRow('id='.$productId);
			if(!$product){
				throw new ProductException('Id_Wrong');
			}
			$this->id					= $product['id'];
			$this->ingredientId			= $product['ingredient'];
			$this->name					= $product['name'];
			$this->manufacturerId		= $product['manufacturer'];
			$this->size					= $product['size'];
			$this->unit					= $product['unit'];
			$this->alcoholLevel			= $product['alcoholLevel'];
			$this->caloriesKcal			= $product['caloriesKcal'];
			$this->densityGramsPerCm3	= $product['densityGramsPerCm3'];
			$this->fruitConcentration	= $product['fruitConcentration'];
			$this->color				= $product['color'];
			$this->insertDate			= $product['insertDate'];
			$this->updateDate			= $product['updateDate'];
		}
	}
	
	/**
	 * @tested
	 */
	public static function productsByIngredientId($ingredient){
		$productTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
		$products = $productTable->fetchAll('ingredient='.$ingredient);
		$productArray = array();
		foreach ($products as $product){
			$productArray[] = Website_Model_CbFactory::factory('Website_Model_Product', $product['id']);
		}
		return $productArray;
	}
	
	/**
	 * returns an array of all Product objects
	 *
	 * @return array Product
	 * @tested
	 */
	public static function listProduct()
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
		foreach ($table->fetchAll() as $product) {
			$productArray[] = Website_Model_CbFactory::factory('Website_Model_Product',$product['id']);
		}
		return $productArray;
	}
	
	/**
	 *@tested
	 */
	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
			$this->id = $table->insert($data);
		}
		else {
			$table->update($this->databaseRepresentation(),'id='.$this->id);
		}
	}
	
	/**
	 *@tested
	 */
	public function delete (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product');
		$table->delete('id='.$this->id);
		CbFactory::destroy('Product',$this->id);
		unset($this);
	}
	
	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['ingredient'] = $this->ingredientId;
		$array['name'] = $this->name;
		$array['manufacturer'] = $this->manufacturerId;
		$array['size'] = $this->size;
		$array['unit'] = $this->unit;
		$array['alcoholLevel'] = $this->alcoholLevel;
		$array['caloriesKcal'] = $this->caloriesKcal;
		$array['densityGramsPerCm3'] = $this->densityGramsPerCm3;
		$array['fruitConcentration'] = $this->fruitConcentration;
		$array['color'] = $this->color;
		return $array;
	}
	
	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 * @tested
	 */
	public function toXml ( $xml , $ast ) {
		$product = $xml->createElement ( 'product' ) ;
		$product->setAttribute ( 'id', $this->id ) ;
		$product->setAttribute ( 'name', $this->name ) ;
		$product->setAttribute ( 'size', $this->size) ;
		$product->setAttribute ( 'unit', $this->unit) ;
		$product->setAttribute ( 'alcoholLevel', $this->alcoholLevel) ;
		$product->setAttribute ( 'caloriesKcal', $this->caloriesKcal) ;
		$product->setAttribute ( 'densityGramsPerCm3', $this->densityGramsPerCm3) ;
		$product->setAttribute ( 'fruitConcentration', $this->fruitConcentration) ;
		$product->setAttribute ( 'color', $this->color) ;
		$product->setAttribute ( 'insertDate', $this->insertDate) ;
		$product->setAttribute ( 'updateDate', $this->updateDate) ;
		
		$ingredient = $xml->createElement('ingredients');
		$this->getIngredient()->toXml( $xml, $ingredient);
		$product->appendChild($ingredient);
		if($this->getManufacturer()->id){
			$manufacturer = $xml->createElement('manufacturer');
			$this->getManufacturer()->toXml( $xml, $manufacturer);
			$product->appendChild($manufacturer);
		}
		$ast->appendchild ( $product ) ;
	}
}
?>