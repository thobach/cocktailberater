<?php
class Website_Model_Party {

	// attributes
	private $id;
	private $name;
	private $date;
	private $insertDate;
	private $updateDate;
	private $hostId;
	private $barId;
	private $menuId; // onChange immediately saved

	// associations
	private $_host;
	private $_bar;

	// supporting variables
	private static $_party;

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
	public function __set($name, $value) {
		// check date to be the right format
		if(($name == 'insertDate' || $name == 'updateDate' || $name == 'date') && !DateFormat::isValidMysqlTimestamp($value)){
			throw new PartyException('Wrong_Date_Format');
		}
		if(($name == 'insertDate' || $name == 'updateDate') && $value=='0000-00-00 00:00:00'){
			throw new PartyException('Invalid_Date');
		}
		if($name == 'menuId'){
			$this->changeMenu($this->menuId,$value);
		}
		if (property_exists ( get_class($this), $name )) {
			$this->$name = $value ;
		} else {
			throw new Exception ( 'Class \''.get_class($this).'\' does not provide property: ' . $name . '.' ) ;
		}

	}

	/**
	 * resolve Association and return an object of Member
	 *
	 * @return Member
	 */
	public function getHost()
	{
		if(!$this->_host){
			$this->_host = Website_Model_CbFactory::factory('Website_Model_Member',$this->hostId);
		}
		return $this->_host;
	}

	/**
	 * resolve Association and return an object of Bar
	 *
	 * @return Bar
	 */
	public function getBar()
	{
		if(!$this->_bar){
			$this->_bar = Website_Model_CbFactory::factory('Website_Model_Bar',$this->barId);
		}
		return $this->_bar;
	}

	/**
	 * resolve Association and return an object of Menu
	 *
	 * @return Menu
	 */
	public function getMenu()
	{
		// TODO: write unit test
		if(!$this->_menu){
			$this->_menu = Website_Model_CbFactory::factory('Website_Model_Menu',$this->menuId);
		}
		return $this->_menu;
	}


	/**
	 * returns only the cocktails which are valid for this party(e.g. manually selected by the barkeeper)
	 *
	 * @return Array<Cocktail>
	 */
	public function getCocktails()
	{
		// TODO: write unit test
		// TODO: mit menu und recipe2menue arbeiten
		return Website_Model_Cocktail::listCocktails();
	}

	/**
	 * checks whether the party exists in DB
	 *
	 * @param String $id
	 * @return booloean | int False or ID for party
	 */
	public static function exists($id) {
		// TODO: write unit test
		$partyTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','party');
		$party = $partyTable->fetchRow('id='.$id);
		if ($party) {
			return $party->id;
		} else {
			return false;
		}
	}

	/**
	 *
	 *@tested
	 */
	public function __construct($id=NULL)
	{
		if(!empty($id)){
			$partyTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','party');
			$party = $partyTable->fetchRow('id='.$id);
			if(!$party){
				throw new PartyException('Id_Wrong');
			}
			// attributes
			$this->id = $party->id;
			$this->name = $party->name;
			$this->date = $party->date;
			$this->insertDate = $party->insertDate;
			$this->updateDate = $party->updateDate;
			$this->hostId = $party->host;
			$this->barId = $party->bar;

			$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','menu2party');
			$where = $table->select()->where('party=?',$this->id);
			$menu = $table->fetchRow($where);
			$this->menuId = $menu->menu;
		}
	}

	public static function listPartys($hostId=NULL,$barId=NULL)
	{
		// TODO: write unit test
		$partyTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','party');
		$select = $partyTable->getAdapter()->select();

		$select->from('party');

		// error handling

		// hostId must be an integer if given
		if($hostId!=NULL AND $hostId<=0){
			throw new Website_Model_PartyException('HostID must be an integer');
		} elseif($hostId!=NULL) {
			$select->where('host=?',$hostId);
		}

		// memberId must be an integer if given
		if($barId!=NULL AND $barId<=0){
			throw new Website_Model_PartyException('BarId must be an integer');
		} elseif($barId!=NULL) {
			$select->where('bar=?',$barId);
		}


		$stmt = $partyTable->getAdapter()->query($select);
		$partys = $stmt->fetchAll();

		foreach ($partys as $party){
			$partyArray[] = Website_Model_CbFactory::factory('Website_Model_Party',$party['id']);
		}
		return $partyArray;
	}

	public function changeMenu ($oldMenuId,$newMenuId){
		if(!$this->id){
			return;
		}
		// TODO: write unit test
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'menu2party');
		if (!$oldMenuId) {
			$data['menu'] = $newMenuId;
			$data['party'] = $this->id;
			$table->insert($data);
		}
		else {
			$data['menu'] = $newMenuId;
			$table->update($data,'menu='.$oldMenuId.' AND party='.$this->id);
		}
	}

	/**
	 *
	 *@tested
	 */
	public function save (){
		// check if all required attributes are filled
		if($this->barId && $this->name && $this->date && $this->hostId){
			$partyTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'party');
			if (!$this->id) {
				// Zend_Debug::dump($this->databaseRepresentation());exit;
				$data = $this->databaseRepresentation();
				$data['insertDate'] = $this->insertDate = date(Website_Model_DateFormat::PHPDATE2MYSQLTIMESTAMP);
				$this->id = $partyTable->insert($data);
				$this->changeMenu(null,$this->menuId);
			}
			else {
				$partyTable->update($this->databaseRepresentation(),'id='.$this->id);
			}
		} else {
			throw new Website_Model_PartyException('Required_Arguments_Missing');
		}
	}

	/**
	 *
	 *@tested
	 */
	public function delete (){
		$partyTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'party');
		$partyTable->delete('id='.$this->id);
		CbFactory::destroy('Party',$this->id);
		unset($this);
	}


	public function toXml($xml, $branch) {
		// TODO: write test
		$party = $xml->createElement('party');
		$branch->appendChild($party);
		$party->setAttribute('id', $this->id);
		$party->setAttribute('name', $this->name);
		$party->setAttribute('date', $this->date);
		$party->setAttribute('barId', $this->barId);
		$party->setAttribute('hostId', $this->hostId);
		$party->setAttribute('menuId', $this->menuId);
		$party->setAttribute('insertDate', $this->insertDate);
		$party->setAttribute('updateDate', $this->updateDate);
	}

	/*
	 * returns an array to save the object in a database
	 */
	public function dataBaseRepresentation() {
		$array['name'] = $this->name;
		$array['date'] = $this->date;
		$array['insertDate'] = $this->insertDate;
		$array['updateDate'] = $this->updateDate;
		$array['host'] = $this->hostId;
		$array['bar'] = $this->barId;
		return $array;
	}
}
?>