<?php
class Website_Model_Menu {

	// attributes
	private $id;
	private $name;
	private $insertDate;
	private $updateDate;
	
	// associations
	private $_recipes;


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
	 * @tested
	 */
	public function __construct ($id=NULL){
		if(!empty($id)){
			$menuTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'menu');
			$menu = $menuTable->fetchRow('id='.$id);
			if(!$menu){
				throw new MenuException('Menu_Id_Invalid');
			}
			// attributes
			$this->id = $menu->id;
			$this->name = $menu->name;
			//$this->insertDate = new Zend_Date($menu->insertDate,'YYYY-MM-dd hh:mm:ss');
			$this->insertDate = $menu->insertDate;
			//$this->updateDate = new Zend_Date($menu->updateDate,'YYYY-MM-dd hh:mm:ss');
			$this->updateDate = $menu->updateDate;
		}
	}
	
	/**
	 * checks whether the menu exists in DB
	 *
	 * @param String $id
	 * @return booloean | int False or ID for menu
	 * @tested
	 */
	public static function exists($id) {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','menu');
		$menu = $table->fetchRow('id='.$id);
		if ($menu) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * returns an array of all Menu objects
	 *
	 * @return array Manufacturer
	 * @tested
	 */
	public static function listMenu()
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','menu');
		foreach ($table->fetchAll() as $menu) {
			$menuArray[] = Website_Model_CbFactory::factory('Website_Model_Menu',$menu->id);
		}
		return $menuArray;
	}

	/**
	 * returns an array of all Menu objects
	 *
	 * @return array Manufacturer
	 * @tested
	 */
	public function listRecipes() {
		return $this->_recipes;
		/*
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','recipe2menue');
		$where = $table->select()->where('menue=?',$this->id);
		$recipes = $table->fetchAll($where);
		if(count($recipes)>0){
			foreach ($recipes as $recipe) {
				$menuArray[] = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipe->recipe);
			}
		}
		return $menuArray;
		*/
	}

	public function addRecipe ($recipeId){
		$this->_recipes[$recipeId] = Website_Model_CbFactory::factory('Website_Model_Recipe',$recipeId);
		/*
		// TODO: write unit test
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe2menue');
		$select = $table->select()->where('recipe=?',$recipeId)->where('menue=?',$this->id);
		$res = $table->fetchRow($select);
		if(count($res)==0){
			$data['recipe'] = $recipeId;
			$data['menue'] = $this->id;
			$table->insert($data);
		} else {
			throw new MenuException("Recipe_Alread_In_Menue");
		}
		*/
	}
	
	public function removeRecipe ($recipeId){
		// TODO: write unit test
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe2menue');
		$select = $table->select()->where('recipe=?',$recipeId)->where('menue=?',$this->id);
		$res = $table->fetchRow($select);
		if(count($res)==1){
			$res->delete();
		} else {
			throw new MenuException("Recipe_Not_In_Menue");
		}
	}
	
	public function removeRecipes(){
		$this->_recipes = null;
		// TODO: write unit test
		/*
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe2menue');
		$select = $table->select()->where('menue=?',$this->id);
		if(count($res)>0){
			$table->delete($select);
		} else {
			throw new Website_Model_MenuException("Id_Wrong");
		}
		*/
	}
	
	public function recipeInside($recipeId){
		// TODO: write unit test
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'recipe2menue');
		$select = $table->select()->where('recipe=?',$recipeId)->where('menue=?',$this->id);
		$res = $table->fetchRow($select);
		if(count($res)==1){
			return true;
		} else {
			return false;
		}
		
	}

	public function save (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'menu');
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
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'menu');
		$table->delete('id='.$this->id);
		CbFactory::destroy('Menu',$this->id);
		unset($this);
	}

	/**
	 * returns an array to save the object in a database
	 *
	 * @return array
	 */
	private function dataBaseRepresentation() {
		$array['name'] = $this->name;
		return $array;
	}

	public function toXml($xml, $branch) {
		$menu = $xml->createElement('menu');
		$branch->appendChild($menu);
		$menu->setAttribute('id', $this->id);
		$menu->setAttribute('name', $this->name);
		$menu->setAttribute('insertDate', $this->insertDate);
		$menu->setAttribute('updateDate', $this->updateDate);
		$recipes = $this->listRecipes();
		if(is_array($recipes)){
			foreach($recipes as $recipe){
				$recipe->toXml($xml,$menu);
			}
		}
	}




}
?>
