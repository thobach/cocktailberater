<?php
class Website_Model_MysqlTable extends Zend_Db_Table_Abstract
{
	protected $_name;

	public function __construct($name) {
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_MysqlTable->__construct',Zend_Log::DEBUG);
		
		$this->_name=$name;
		parent::__construct();
		
		$log->log('Website_Model_MysqlTable->__construct exiting',Zend_Log::DEBUG);
	}

	public static function mergeTogether($ziel, $row) {
		// Aus den 2 Objekten wird je ein objekt ReflectionClass erzeugt
		//$refClass = new ReflectionClass($row);
		$refClassZiel = new ReflectionClass($ziel);
		//var_dump($refClassZiel->getProperties());
		if(!$row) return null;
		$rowArray = $row->toArray();
		//var_dump($rowArray);
		if (!$row) {
			throw new Zend_Exception("Row leer");
		}
		foreach ($rowArray as $key=>$value) {
			// Spaltenname
			// echo $key."<br>";
			if ($refClassZiel->hasProperty($key)) {
				// Dieses Attribut ist in beiden Objekten
				//	echo "gefunden: ".$key. "<br>";
				//	echo "wert:".$row->$name."<br>";
				//exit;
				// Werte zuordnen
				$ziel->$key = $row->$key;
			}
		}
		return $ziel;
	}

	public static function getEnumValues($tablename,$field) {
		$db = Zend_Db_Table::getDefaultAdapter();
		$row = $db->fetchAll("SHOW COLUMNS FROM `".$tablename."` LIKE '".$field."'");
		$erg = $row[0]['Type'];
		$regex = "/'(.*?)'/";
		preg_match_all( $regex , $erg, $enum_array );
		$enum_fields = $enum_array[1];
		return( $enum_fields );
	}

	public static function getUUID($prefix = "prefix") {
		return str_replace('.','',uniqid(md5($prefix),true));
	}


}
?>