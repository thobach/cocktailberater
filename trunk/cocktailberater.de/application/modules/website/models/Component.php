<?php
/**
 * Component class is the aggregation of the amount and ingredient for recipes
 *
 */
class Website_Model_Component {

	// attributes
	private $amount;
	private $unit;
	private $ingredientId;
	private $recipeId;

	// associations
	private $_ingredient;
	private $_recipe;

	// constants
	const CENTILITRE = 'cl';
	const MILLILITRE = 'ml';
	const LITRE = 'l';
	const GRAM = 'g';
	const KILOGRAM = 'kg';
	const PIECE = 'Stück';
	const TEASPOON = 'TL';
	const FLUID_OUNCE = 'fl. oz';
	
	// supporting variables
	private static $_recipes;
	
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
	 */
	public function getIngredient()
	{
		if(!$this->_ingredient){
			$this->_ingredient = Website_Model_CbFactory::factory('Website_Model_Ingredient',$this->ingredientId);
		}
		return $this->_ingredient;
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
	 * Component constructor returns a Component object by an IngredientId and RecipeId, or an empty one
	 *
	 * @param integer optional $id
	 * @return Component
	 */
	public function __construct ( $idIngredient = NULL , $idRecipe = NULL ) {
		if (! empty ( $idIngredient ) && ! empty ( $idRecipe )) {
			$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','component');
			$component = $table->fetchRow ( 'ingredient=' . $idIngredient . ' AND recipe=' . $idRecipe ) ;
			// if component does not exist
			if(!$component){
				throw new Website_Model_ComponentException('Id_Wrong');
			}
			$this->recipeId = $component->recipe;
			$this->ingredientId = $component->ingredient;
			$this->amount = $component->amount ;
			$this->unit = $component->unit ;
		} else if($idIngredient && !$idRecipe){
			throw new Website_Model_ComponentException('Id_Missing');
		} else if(!$idIngredient && $idRecipe){
			throw new Website_Model_ComponentException('Id_Missing');
		}
	}
		
	public function getAmountInLiter (){
	/*
	1 kg = 1000 g
	1 l = 100 cl
	1 l = 1000 ml
	1 piece -> weight of whole ingredient
	1 tsp = 5 ml
	1 fl. oz. (fluid ounce) = 29 ml
	1 cm³ = 1 ml
	*/
		// TODO: umrechnungn prüfen
		if($this->unit==Component::LITRE){ // l -> l
			return $this->amount;
		} elseif($this->unit==Component::CENTILITRE){ // 100 cl -> 1 l
			return ($this->amount/100);
		} elseif($this->unit==Component::MILLILITRE){ // 1000 ml -> 1 l
			return ($this->amount/1000);
		} elseif($this->unit==Component::GRAM){ // 1 g / density [g/cm³] / 1000 -> 1 l
			$density = $this->getIngredient()->getAverageDensityGramsPerCm3();
			return (($this->amount/$density)/1000);
		} elseif($this->unit==Component::KILOGRAM){ // 1 kg / density [g/cm³] -> 1 l 
			$density = $this->getIngredient()->getAverageDensityGramsPerCm3();
			return ($this->amount/$density);
		} elseif($this->unit==Component::PIECE){
			// piece is only possible in component, not in product 
			// and works only with solid ingredients that are measured in weight (g/kg)
			$density = $this->getIngredient()->getAverageDensityGramsPerCm3();
			$weight = $this->getIngredient()->getAverageWeightGram();
			$unitMostUsed = $this->getIngredient()->getMostUsedUnit();
			//print "unit: $unitMostUsed weight: $weight density: $density";
			if($density!=null && $weight!=null && $unitMostUsed!=null){
				if($unitMostUsed == Component::KILOGRAM){
					$weight = $weight;
				} elseif($unitMostUsed == Component::GRAM){
					$weight = $weight/1000;
				} elseif($unitMostUsed == Component::PIECE){
					// avgWeight*avgDensity
					$density = $this->getIngredient()->getAverageDensityGramsPerCm3();
					$weight = $this->getIngredient()->getAverageWeightGram()/1000;
				} elseif($unitMostUsed == Component::MILLILITRE 
					|| $unitMostUsed == Component::CENTILITRE 
					|| $unitMostUsed == Component::LITRE 
					|| $unitMostUsed == Component::TEASPOON 
					|| $unitMostUsed == Component::FLUID_OUNCE){
					// all volume measures
					$volume = $this->getIngredient()->getAverageVolumeLitre();
					return ($this->amount*$volume);
				}
				return ($this->amount*$weight*$density);
			} else {
				return null;
			}
		} elseif($this->unit==Component::TEASPOON){
			return ($this->amount/200);
		} elseif($this->unit==Component::FLUID_OUNCE){
			// Durchschnittswert der verschiedenen Unzen ~ 29ml
			return round($this->amount/34.5,4);
		} else{
			// TODO: exception prüfen und übersetzen
			throw new ComponentException("Provided_Unit_Not_Found");
		}
	}
	
	/**
	 * checks whether a Component exists
	 *
	 * @param int $idrecipe
	 * @param int $idingredient
	 * @return booloean
	 */
	public static function exists ( $idrecipe, $idingredient ) {
		if($idrecipe && $idingredient){
			$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','component');
			$component = $table->fetchRow ( 'ingredient=' . $idingredient . ' AND recipe=' . $idrecipe ) ;
			if ($component) {
				return true;
			} else {
				return false ;
			}
		} else {
			return false;
		}
	}

	/**
	 * returns all Ingredients as Component objects by a RecipeId
	 *
	 * @param int $idrecipe
	 * @return array Component
	 */
	public static function componentsByRecipeId ( $idrecipe ) {
		
		if(!$idrecipe){
			throw new Website_Model_ComponentException('Id_Missing');
		}
		// load cache from registry
		$cache = Zend_Registry::get('cache');

		// see if getAll - list is already in cache
		if(!$componentsArray = $cache->load('componentsByRecipeId'.$idrecipe)) {
			
			$componentTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','component');
			$components = $componentTable->fetchAll ( 'recipe=' . $idrecipe ) ;
			foreach ( $components as $component ) {
				$componentsArray [] = Website_Model_CbFactory::factory('Website_Model_Component',$component->ingredient,$component->recipe);
			}
			$cache->save ($componentsArray,'componentsByRecipeId'.$idrecipe);
		}
		return $componentsArray ;	
	}
	
	/**
	 * returns all Recipes as Component objects by a IngredientId
	 *
	 * @param int $idrecipe
	 * @return array Component
	 */
	public static function componentsByIngredientId ( $idingredient ) {
		
		if(!$idingredient){
			throw new ComponentException('Id_Missing');
		}
		// load cache from registry
		$cache = Zend_Registry::get('cache');

		// see if getAll - list is already in cache
		if(!$componentsArray = $cache->load('componentsByIngredientId'.$idingredient)) {
			$componentTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','component');
			$components = $componentTable->fetchAll ( 'ingredient=' . $idingredient ) ;
			foreach ( $components as $component ) {
				$componentsArray [] = CbFactory::factory('Component',$component->ingredient,$component->recipe);
			}
			$cache->save ($componentsArray,'componentsByIngredientId'.$idingredient);
		}
		return $componentsArray ;	
	}

	/**
	 * returns an array of all possible units
	 *
	 * @return array string with units
	 */
	public static function getUnits()
	{
		return MysqlTable::getEnumValues('component','unit');
	}

	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml ( $xml , $ast ) {
		$component = $xml->createElement ( 'component' ) ;
		$component->setAttribute ( 'recipe', $this->recipeId) ;
		$component->setAttribute ( 'ingredient', $this->ingredientId) ;
		$component->setAttribute ( 'amount', $this->amount ) ;
		$component->setAttribute ( 'unit', $this->unit ) ;
		$component->setAttribute ( 'name', $this->getIngredient()->name ) ;
		$ast->appendchild ( $component ) ;
	}

	/**
	 * saves an object persistent into a database 
	 *
	 * @return (boolean | array) if update => true, if insert => recipe and ingredient id as array
	 */
	public function save () {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','component');
		//if (Component::exists($this->recipeId, $this->ingredientId)) {
		if (Component::exists($this->recipeId, $this->ingredientId)) {
			// Update
			$table->update ( $this->dataBaseRepresentation (), 'recipe = ' . $this->recipeId . ' AND ingredient = ' . $this->ingredientId ) ;
			$return = true ;
		} else {
			$data = $this->databaseRepresentation();
			// Insert als neues Rezept
			$return = $idArray = $table->insert ( $data ) ;
			$this->recipeId = $idArray['recipe'];
			$this->ingredientId = $idArray['ingredient']; 
		}
		// Erfolg zurückgeben
		return $return ;
	}
	
	public function delete (){
		$componentTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'component');
		$componentTable->delete('ingredient='.$this->ingredientId.' AND recipe='.$this->recipeId);
		CbFactory::destroy('Component',$this->ingredientId,$this->recipeId);
		unset($this);
	}

	/**
	 * returns an array containing the data to be saved in the database
	 *
	 * @return array
	 */
	public function dataBaseRepresentation () {
		$array [ 'ingredient' ] = $this->ingredientId;
		$array [ 'recipe' ] = $this->recipeId;
		$array [ 'amount' ] = $this->amount;
		$array [ 'unit' ] = $this->unit ;
		return $array ;
	}

}
