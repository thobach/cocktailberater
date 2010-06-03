<?php
/**
 * Statistic class represents a counter for several entities like recipes or
 * cocktails, seperated by representation format
 *
 */
class Website_Model_Statistic {

	// attributes
	private $id;
	private $resourceType;
	private $resourceId;
	private $format;
	private $views;
	private $lastViewDate;

	// supported resource types
	const RESOURCE_RECIPE 		= 'recipe';
	const RESOURCE_INGREDIENT 	= 'ingredient';
	const RESOURCE_COCKTAIL 	= 'cocktail';
	const RESOURCE_MANUFACTURER	= 'manufacturer';
	const RESOURCE_PRODUCT		= 'product';

	// supported resource formats
	const FORMAT_HTML	= 'html';
	const FORMAT_XML 	= 'xml';
	const FORMAT_JSON 	= 'json';
	const FORMAT_RDF 	= 'rdf';
	const FORMAT_PDF	= 'pdf';
	const FORMAT_RSS	= 'rss';
	const FORMAT_ATOM	= 'atom';
	const FORMAT_MOBILE	= 'mobile';

	// associations
	private $_resource;

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
			throw new Website_Model_StatisticException('Class \''.get_class($this).'\' does not provide property: ' . $name . '.');
		}
	}

	/**
	 * resolve association and return an object of the resource type
	 *
	 * @return mixed like Website_Model_Recipe or Website_Model_Ingredient etc.
	 */
	public function getResource() {
		if(!$this->_resource){
			$this->_resource = Website_Model_CbFactory::factory($this->mapResourceTypToClassName($this->resourceType),
			$this->resourceId);
		}
		return $this->_resource;
	}

	/**
	 * increases the view counter
	 */
	public function addView() {
		$this->views = $this->views + 1;
		$this->save();
	}

	/**
	 * maps the resource type of the database to the internal class name
	 *
	 * @param string $resourceType class name
	 */
	private function mapResourceTypToClassName($resourceType){
		switch ($resourceType){
			case self::RESOURCE_COCKTAIL:
				return 'Website_Model_Cocktail';
			case self::RESOURCE_INGREDIENT:
				return 'Website_Model_Ingredient';
			case self::RESOURCE_MANUFACTURER:
				return 'Website_Model_Manufacturer';
			case self::RESOURCE_PRODUCT:
				return 'Website_Model_Product';
			case self::RESOURCE_RECIPE:
				return 'Website_Model_Recipe';
			default:
				throw new Website_Model_StatisticException('resource type not supportet');
		}
	}

	/**
	 * Magic Setter Function, is accessed when setting an attribute
	 *
	 * @param mixed $name
	 * @param mixed $value
	 */
	public function __set ( $name , $value ) {
		throw new Website_Model_StatisticException ( 'Class \''.get_class($this).'\' does not allow editing properties' );
	}

	/**
	 * checks whether a statistics entry for the given resource type, resource id and format exists
	 * @param unknown_type $resourceType
	 * @param unknown_type $resourceId
	 * @param unknown_type $format
	 * @return booloean | int false or id for statistics object
	 */
	public static function exists($resourceType,$resourceId,$format) {
		$statisticTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','statistic');
		$where = $statisticTable->select()
		->where('resourceType = ?', $resourceType)
		->where('resourceId = ?', $resourceId)
		->where('format = ?', $format);
		$statistic = $statisticTable->fetchRow($where);
		if ($statistic) {
			return $statistic->id;
		} else {
			return false;
		}
	}

	/**
	 * Statistic constructor returns a Website_Model_Statistic object by an id,
	 * or an empty one - alternatively returns a Website_Model_Statistic object
	 * by an resource type, id and format
	 *
	 * @throws Website_Model_StatisticException
	 * @param int $idOrResourceType optional
	 * @param int $resourceId optional
	 * @param int $format optional
	 * @return Website_Model_Statistic
	 */
	public function __construct ($idOrResourceType=NULL,$resourceId=NULL,$format=NULL){
		if($idOrResourceType && $resourceId && !$format ||
		!$idOrResourceType && $resourceId && !$format){
			throw new Website_Model_StatisticException('Format_Missing');
		} else if($idOrResourceType && !$resourceId && $format){
			throw new Website_Model_StatisticException('Id_Missing');
		} else if(!$idOrResourceType && $resourceId && $format){
			throw new Website_Model_StatisticException('ResourceType_Missing');
		}
		// load statistic by composite primary key
		if($idOrResourceType && $resourceId && $format){
			if(!($format == self::FORMAT_ATOM || $format == self::FORMAT_HTML ||
			$format == self::FORMAT_JSON || $format == self::FORMAT_PDF ||
			$format == self::FORMAT_RDF || $format == self::FORMAT_RSS ||
			$format == self::FORMAT_XML || $format == self::FORMAT_MOBILE)){
				throw new Website_Model_StatisticException('Format_Wrong');
			}
			// check if resourceId of resourceTyp exists
			try{
				Website_Model_CbFactory::factory($this->mapResourceTypToClassName($idOrResourceType),
				$resourceId);
			} catch(Exception $e){
				throw new Website_Model_StatisticException('Resource_Or_Id_Wrong');
			}
			// load statistic from database
			$statisticTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','statistic');
			$where = $statisticTable->select()
			->where('resourceType = ?', $idOrResourceType)
			->where('resourceId = ?', $resourceId)
			->where('format = ?', $format);
			$statistic = $statisticTable->fetchRow($where);
			// if statistic does not exist, create new
			if(!$statistic){
				$this->resourceType = $idOrResourceType;
				$this->resourceId = $resourceId;
				$this->format = $format;
				$this->save();
				$statistic = $statisticTable->fetchRow($where);
			}
			// map database attributes to internal attributes
			$this->id 			= $statistic->id;
			$this->resourceType	= $statistic->resourceType;
			$this->resourceId 	= $statistic->resourceId;
			$this->format 		= $statistic->format;
			$this->views		= $statistic->views;
			$this->lastViewDate	= $statistic->lastViewDate;
		}
		// load statistic by artificial primary key
		elseif(!empty($idOrResourceType)){
			$statisticTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','statistic');
			$statistic = $statisticTable->find($idOrResourceType)->current();
			// if statistic does not exist
			if(!$statistic){
				throw new Website_Model_StatisticException('Id_Wrong');
			}
			// attributes
			$this->id 			= $statistic->id;
			$this->resourceType = $statistic->resourceType;
			$this->resourceId 	= $statistic->resourceId;
			$this->format 		= $statistic->format;
			$this->views 		= $statistic->views;
			$this->lastViewDate	= $statistic->lastViewDate;
		}
	}

	/**
	 * returns an array of all statistic objects
	 *
	 * @return array[int]Website_Model_Statistic
	 */
	public static function listStatistics(){
		$statisticTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','statistic');
		$statisticArray = array();
		foreach ($statisticTable->fetchAll() as $statistic) {
			$statisticArray[] = Website_Model_CbFactory::factory('Website_Model_Statistic',$statistic->resourceType,
			$statistic->resourceId,$statistic->format);
		}
		return $statisticArray;
	}

	/**
	 * returns an array of statistics objects of the given recipe id
	 *
	 * @param int $recipeId
	 * @return array[int]Website_Model_Statistic
	 */
	public static function statisticsByRecipeId($recipeId){
		$statisticTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','statistic');
		$where = $statisticTable->select()
		->where('resourceType = ?', 'recipe')
		->where('resourceId = ?', $recipeId);
		$statistics = $statisticTable->fetchAll($where);
		$statisticsArray = array();
		foreach($statistics as $statistic){
			$statisticsArray[] = Website_Model_CbFactory::factory(
				'Website_Model_Statistic',$statistic->resourceType,
			$statistic->resourceId,$statistic->format);
		}
		return $statisticsArray;
	}

	/**
	 * persists the object (insert or update)
	 */
	public function save (){
		$statisticTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','statistic');
		if (!$this->id) {
			$data = $this->databaseRepresentation();
			$this->id = $statisticTable->insert($data);
		}
		else {
			$statisticTable->update($this->databaseRepresentation(),'id='.$this->id);
		}
	}

	/**
	 * deletes the statistic object from persistence layer and local cache (factory)
	 */
	public function delete (){
		$statisticTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','statistic');
		$statisticTable->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Website_Model_Statistic',$this->resourceType,$this->resourceId,$this->format);
	}

	/**
	 * returns an array to save the object in a database
	 *
	 * @return array[string]string
	 */
	private function dataBaseRepresentation() {
		$array['resourceType'] = $this->resourceType;
		$array['resourceId'] = $this->resourceId;
		$array['format'] = $this->format;
		if($this->views === null){
			$this->views = 0;
		}
		$array['views'] = $this->views;
		$array['lastViewDate'] = $this->lastViewDate;
		return $array;
	}

	/**
	 * adds the xml representation of the object to a xml branch
	 *
	 * @param DomDocument $xml
	 * @param XmlElement $branch
	 */
	public function toXml($xml, $branch) {
		$statistic = $xml->createElement('statistic');
		$branch->appendChild($statistic);
		$statistic->setAttribute('id', $this->id);
		$statistic->setAttribute('resourceType', $this->resourceType);
		$statistic->setAttribute('resourceId', $this->resourceId);
		$statistic->setAttribute('format', $this->format);
		$statistic->setAttribute('views', $this->views);
		$statistic->setAttribute('lastViewDate', $this->lastViewDate);
	}

}
