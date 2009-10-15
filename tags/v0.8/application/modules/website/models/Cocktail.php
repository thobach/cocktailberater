<?php
/**
 * Cocktail Class which holds all the recipes
 *
 */
class Website_Model_Cocktail {

	// attributes
	private $id;
	private $name;
	private $insertDate;
	private $updateDate;

	// associations


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
	 * Constructor of Cocktail Class, to load a Cocktail object or create one
	 *
	 * @param integer $id
	 * @return Cocktail
	 */
	public function Website_Model_Cocktail($id = NULL) {
		// Get cocktail from DB
		if (!empty ($id)) {
			$cocktailTable = Website_Model_CbFactory::factory('Website_Model_CocktailTable',NULL);
			$cocktail = $cocktailTable->fetchRow('id=' . $id);
			// if cocktail does not exist
			if(!$cocktail){
				throw new Website_Model_CocktailException('Id_Wrong');
			}
			$this->id = $cocktail['id'];
			$this->name = $cocktail['name'];
			$this->insertDate = $cocktail['insertDate'];
			$this->updateDate = $cocktail['updateDate'];
		} 
	}

	/**
	 * checks whether the cocktail already exists
	 *
	 * @param String $name
	 * @return booloean | int False or ID for cocktail
	 */
	public static function exists($name) {
		$cocktailTable = Website_Model_CbFactory::factory('Website_Model_CocktailTable',NULL);
		$cocktail = $cocktailTable->fetchRow('name=\'' . $name . '\'');
		if ($cocktail) {
			return $cocktail->id;
		} else {
			return false;
		}

	}

	/**
	 * returns an array with cocktail objects according to a search string
	 *
	 * @param string $search search string
	 * @param int $limit number of results, default: 100
	 * @param string $option options: name (default), top10, zutat2, idonly, 
	 * 			id, tag2, tag, zutat, kategorie, volltextsuche, alcoholic, non-alcoholic
	 * @return array Cocktail
	 */
	static function listCocktails($search=NULL, $limit = 100, $option = 'name') {
		// check input parameters
		if(!is_string($search) AND !is_null($search)){
			throw new Website_Model_CocktailException('Website_Model_Cocktail::listCocktails, first parameter "search" must be a string');
		}
		if(!is_numeric($limit)){
			$limit = 100;
		}
		if($option=='' OR ($option !='name' AND $option !='top10'
		AND $option != 'idonly' AND $option != 'id' AND $option != 'tag2' AND $option != 'tag'
		AND $option != 'zutat' AND $option != 'kategorie'
		AND $option != 'volltextsuche' AND $option != 'alcoholic' AND $option != 'non-alcoholic')) {
			$option = 'name';
		}
		// process search
		try {
			$cocktailTable= Website_Model_CbFactory::factory('Website_Model_CocktailTable',NULL);
			$db = Zend_Db_Table::getDefaultAdapter();
			// top10
			if ($option == 'top10') {
				$result = $db->fetchAll('
					SELECT cocktail, average
					FROM (
						SELECT cocktail, ratingsSum / ratingsCount AS average
						FROM recipe
					) AS temp
					GROUP BY cocktail
					ORDER BY average DESC 
					LIMIT 10');
			}
			// Name
			// wird u.a. für suggest benutzt
			if ($option == 'name') {
				$result = $db->fetchAll('SELECT id as cocktail
												FROM cocktail
												WHERE (name LIKE ?)
												ORDER BY cocktail.name
												LIMIT ' . $limit, 
				array ( $search . '%'));
			}
			// Zutat -> suche execute
			if ($option == 'zutat2') {
				$result = $db->fetchAll('SELECT id as cocktail
												FROM cocktail
												NATURAL JOIN rezept
												NATURAL JOIN rezeptor
												NATURAL JOIN zutat
												WHERE (zutat_name LIKE ?)
												GROUP BY cocktail.id
												ORDER BY cocktail.name
												LIMIT ' . $limit, array (
				str_replace('\'',
					'\\\'',
				$search
				) . '%')); // evtl. das noch davor: '%'.
			}
			// ID
			if ($option == 'id') {
				$cocktails[0] = new Cocktail($search);
				return $cocktails;
			}
			// Tag -> suche execute
			if ($option == 'tag2') {
				$result = $db->fetchAll('SELECT id as cocktail
												FROM cocktail
												NATURAL JOIN rezept
												NATURAL JOIN rezept_hat_tag
												NATURAL JOIN tag
												WHERE tag LIKE \'%' . str_replace('\'', '\\\'', $search) . '%\'
												GROUP BY cocktail.id
												ORDER BY cocktail.name
												LIMIT ' . $limit);
			}
			// Suche nach Tag -> ID oder Name
			// in API verankert: /getCocktails/tag/(tagID|tagName)
			// wird von IndexController (cocktailWithTag) genutzt
			if ($option == 'tag') {
				$result = $db->fetchAll('SELECT DISTINCT (cocktail)
												FROM tag
												INNER JOIN recipe ON tag.recipe = recipe.id
												WHERE (tag.name LIKE ?) OR (tag.id = ?)
												ORDER BY recipe.name
												LIMIT ' . $limit, array (
				str_replace('\'',
					'\\\'',
				$search
				) . '%', str_replace('\'', '\\\'', $search)));
			}
			// Suche nach Zutat -> ID oder Name
			// in API verankert: /getCocktails/zutat/(zutatID|zutatName)
			if ($option == 'zutat') {
				$result = $db->fetchAll('SELECT DISTINCT (cocktail.id) as cocktail
												FROM cocktail
												INNER JOIN recipe ON cocktail.id = recipe.cocktail
												INNER JOIN component ON recipe.id = component.recipe
												INNER JOIN ingredient ON ingredient.id = component.ingredient
												WHERE (ingredient.name LIKE ?) OR (ingredient.id = ?)
												ORDER BY cocktail.name
												LIMIT ' . $limit, array (
				str_replace('\'',
					'\\\'',
				$search
				) . '%', str_replace('\'', '\\\'', $search)));
			}
			// Suche nach Kategorie -> ID oder Name
			// in API verankert: /getCocktails/kategorie/(kategorieID|kategorieName)
			if ($option == 'kategorie') {
				$result = $db->fetchAll('SELECT DISTINCT (id) as cocktail
												FROM cocktail
												NATURAL JOIN cocktail_hat_cocktail_kategorie
												NATURAL JOIN cocktail_kategorie
												WHERE (cocktail_kategorie.cocktail_kategorie_name LIKE ?) OR (cocktail_kategorie.id_kategorie = ?)
												ORDER BY cocktail.name
												LIMIT ' . $limit, array (
				str_replace('\'',
					'\\\'',
				$search
				) . '%', str_replace('\'', '\\\'', $search))); // evtl. das noch davor: '%'.
			}
			/*
			 * Volltextsuche über:
			 * Cocktailname, Cocktailbeschreibung, Cocktailkategorie,
			 * Rezeptname, Rezeptanweisung, Rezepttag, (Kommentartext->noch nicht),
			 * Zutatenname, Zutatenbeschreibung, Zutatenkategorie,
			 * Glasname
			 *
			 * TODO: Volltextsuche: Kommentartext (NATURAL JOIN schlägt hier fehl -> outer join!!
			 * TODO: Volltextsuche: überall statt NATURAL JOIN OUTER JOIN sofern sinnvoll
			 */
			// in API verankert: /getCocktails/volltextsuche/stichwort
			if ($option == 'volltextsuche') {
				$search = '%' . str_replace('\'', '\\\'', $search) . '%'; // evtl. das noch davor: '%'.
				$result = $db->fetchAll('SELECT DISTINCT (id) as cocktail
												FROM cocktail
												NATURAL JOIN rezept
												NATURAL JOIN rezept_hat_tag
												NATURAL JOIN tag
												NATURAL JOIN rezeptor
												NATURAL JOIN zutat
												NATURAL JOIN zutaten_kategorie
												NATURAL JOIN cocktail_hat_cocktail_kategorie
												NATURAL JOIN cocktail_kategorie

												NATURAL JOIN glas
												WHERE	(name LIKE ?) OR

														(cocktail_kategorie_name LIKE ?) OR
														(rezept_name LIKE ?) OR
														(rezept_anweisung LIKE ?) OR
														(tag LIKE ?) OR

														(zutat_name LIKE ?) OR
														(zutat_beschreibung LIKE ?) OR
														(zutaten_kategorie_name LIKE ?) OR
														(glas_name LIKE ?)
												ORDER BY cocktail.name
												LIMIT ' . $limit, array (
				$search,
				$search,
				$search,
				$search,
				$search,
				$search,
				$search,
				$search,
				$search,
				$search
				));
			}
			// alcoholic
			if ($option == 'alcoholic') {
				$result = $db->fetchAll('SELECT cocktail.id as cocktail
												FROM cocktail
												INNER JOIN recipe ON cocktail.id = recipe.cocktail
												WHERE (recipe.isAlcoholic = 1)
												ORDER BY cocktail.name
												LIMIT ' . $limit, 
				array ( $search . '%'));
			}
			// non-alcoholic
			if ($option == 'non-alcoholic') {
				$result = $db->fetchAll('SELECT cocktail.id as cocktail
												FROM cocktail								
												INNER JOIN recipe ON cocktail.id = recipe.cocktail
												WHERE (recipe.isAlcoholic = 0)
												ORDER BY cocktail.name
												LIMIT ' . $limit, 
				array ( $search . '%'));
			}
			$cocktailArray = array();
			if (is_array($result)) {
				foreach ($result as $cocktail) {
					$cocktailArray[] = Website_Model_CbFactory::factory('Website_Model_Cocktail',$cocktail['cocktail']);
				}
			}
			return $cocktailArray;
		} catch (Exception $e){
			throw new Exception($e);
		}
	}

	/**
	 * gibt ein indiziertes Array mit allen Cocktailnamen sortiert zurück
	 *
	 * @return array String Cocktailnamen
	 */
	public static function listCocktailNamesIndexed($search = NULL) {
		$tabelle = Website_Model_CbFactory::factory('Website_Model_CocktailTable',NULL);
		if ($search) {
			$search = "name LIKE '$search%'";
		}
		$cocktails = $tabelle->fetchAll($search, 'name');
		$cocktailArray = array ();
		foreach ($cocktails as $cocktail) {
			$cocktailArray[] = $cocktail->name;
		}
		return $cocktailArray;
	}

	public function getRecipes(){
		// load cache from registry
		$cache = Zend_Registry::get('cache');
		// see if recipe - list is already in cache
		if(!$recipes = $cache->load('recipesByCocktailId'.$this->id)) {
			$recipes = Website_Model_Recipe::recipesByCocktailId($this->id);
			$cache->save($recipes,'recipesByCocktailId'.$this->id);
		}
		return $recipes;
	}

	/**
	 * Dies ist die statische Vergleichsfunktion, basierend auf dem Cocktailnamen
	 *
	 * @param Cocktail $a
	 * @param Cocktail $b
	 * @return int
	 */
	public static function compareObject(Cocktail $a, Cocktail $b) {
		$al = strtolower($a->name);
		$bl = strtolower($b->name);
		if ($al == $bl) {
			return 0;
		}
		return ($al > $bl) ? +1 : -1;
	}

	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml($xml, $ast) {
		$cocktail = $xml->createElement('cocktail');
		$ast->appendChild($cocktail);
		$cocktail->setAttribute('id', $this->id);
		$cocktail->setAttribute('name', $this->name);
		$cocktail->setAttribute('insertDate', $this->insertDate);
		$cocktail->setAttribute('updateDate', $this->updateDate);
		$recipes = $xml->createElement('recipes');
		$cocktail->appendChild($recipes);
		if (is_array($_recipes = $this->getRecipes())) {
			foreach ($_recipes as $_recipe) {
				$_recipe->toXml($xml, $recipes);
			}
		}
	}

	public function delete (){
		$orderTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'cocktail');
		$orderTable->delete('id='.$this->id);
		CbFactory::destroy('Cocktail',$this->id);
		unset($this);
	}
	
	/**
	 * saves the object persistent into a database
	 *
	 * @return boolean|int on opdate true, on insert int (cocktailId)
	 */
	public function save() {
		$cocktailTable = CbFactory::factory('CocktailTable',NULL);
		$cocktailIdExists = Cocktail :: exists($this->name);
		if ($this->id) {
			$cocktailTable->update($this->dataBaseRepresentation(), 'id = ' . $this->id);
			$return = true;
		} elseif (Cocktail :: exists($this->name)) { // Insert als neues Rezept
			// da die Cocktailbeschreibung nicht verloren gehen soll -> InfoMail
			// TODO: Problem -> zu diesem Zeitpunkt wurde die Cocktailbeschreibung schon überschrieben
			$infomail = CbFactory::factory('InfoMail',NULL);
			$infomail->subject = 'Beschreibung für neues Rezept';
			$infomail->message = 'Ein neues Rezept für den Cocktail "' . $this->name . '" mit der ID ' . $cocktailIdExists . ' wurde soeben eingetragen.' . "\n\n" .
			'Die Beschreibung lautet:' . "\n\n" .
			// TODO: Kommentar entfernen
			// $infomail->send();
			$this->Cocktail($cocktailIdExists);
			//Zend_Debug::dump($this);
			$return = $this->id;
		} else {
			$this->id = $cocktailTable->insert($this->dataBaseRepresentation());
			$return = $this->id;
		}
		// abhängige Objekte speichern
		$recipes = $this->getRecipes();
		if(is_array($recipes)){
			foreach ($recipes as $recipe) {
				$recipe->cocktailId = $this->id;
				$recipe->save();
			}
		}
		//Zend_Debug::dump($this);
		//$this->kommentare->save();
		// Erfolg zurückgeben
		return $return;
	}

	/**
	 * gibt ein Array zurück um die Daten in eine Tabelle zu speichern
	 *
	 * @return array
	 */
	public function dataBaseRepresentation() {
		$array['name'] = $this->name;
		$array['insertDate'] = $this->insertDate;
		$array['updateDate'] = $this->updateDate;
		return $array;
	}

}
