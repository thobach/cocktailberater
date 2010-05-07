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
	private $max_price;
	private $insertDate;
	private $updateDate;

	// associations
	private $_ingredient;
	private $_manufacturer;

	// calculated attributes
	private $averagePrice;
	private $offers;

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
	 * resolve Association and return an object of Website_Model_Ingredient
	 *
	 * @return Website_Model_Ingredient
	 */
	public function getIngredient()
	{
		if(!$this->_ingredient){
			$this->_ingredient = Website_Model_CbFactory::factory('Website_Model_Ingredient',$this->ingredientId);
		}
		return $this->_ingredient;
	}

	/**
	 * resolve Association and return an object of Manufacturer
	 *
	 * @return Manufacturer
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

	private function getGoogleBaseUrl(){
		// create filter to select only shops which offer the right size of the product
		$match = array();
		$match[0] = '"'.round($this->size,2).''.$this->unit.'"';
		$match[1] = '"'.round($this->size,2).' '.$this->unit.'"';
		$match[2] = '"'.str_replace('.',',',round($this->size,2)).''.$this->unit.'"';
		$match[3] = '"'.str_replace('.',',',round($this->size,2)).' '.$this->unit.'"';
		if($this->unit=='l'){
			$match[14] = '"'.round($this->size,2).' Ltr."';
			$match[15] = '"'.round($this->size,2).'Ltr."';
			$match[16] = '"'.str_replace('.',',',round($this->size,2)).' Ltr."';
			$match[17] = '"'.str_replace('.',',',round($this->size,2)).'Ltr."';
			$match[18] = '"'.round($this->size,2).' Liter"';
			$match[19] = '"'.round($this->size,2).'Liter"';
			$match[20] = '"'.str_replace('.',',',round($this->size,2)).' Liter"';
			$match[21] = '"'.str_replace('.',',',round($this->size,2)).'Liter"';
			$match[4] = '"'.round($this->size*100).'cl"';
			$match[5] = '"'.round($this->size*100).' cl"';
			$match[8] = '"'.round($this->size*1000).'ml"';
			$match[9] = '"'.round($this->size*1000).' ml"';
		}
		$match[10] = '"'.number_format(round($this->size,2),1,',','.').''.$this->unit.'"';
		$match[11] = '"'.number_format(round($this->size,2),1,',','.').' '.$this->unit.'"';
		$match[12] = '"'.number_format(round($this->size,2),2,',','.').''.$this->unit.'"';
		$match[13] = '"'.number_format(round($this->size,2),2,',','.').' '.$this->unit.'"';

		$size = implode ('|',$match);
		if($this->getManufacturer()->name){
			$manufacturer = ' "'.$this->getManufacturer()->name.'"';
		} else {
			$manufacturer = '';
		}

		$url = 'http://www.google.com/base/feeds/snippets/?bq='.urlencode('("'.$this->name.'"'.$manufacturer.' ('.$size.'))').'[item%20type%3Aprodukte]&max-results=100';
		//echo $url;
		return $url;
	}

	/**
	 * get average price of this product (calls Google Base)
	 *
	 * @return double
	 */
	public function getAveragePrice(){
		if($this->averagePrice === NULL){
			// load cache from registry
			$cache = Zend_Registry::get('cache');

			// see if offer - list is already in cache
			$avgPrice = $cache->load('averagePriceByProductId'.$this->id);
			if($avgPrice === false) {
				// ask google base
				$service = new Zend_Gdata_Gbase();
				$feed = $service->getGbaseItemFeed($this->getGoogleBaseUrl());

				// average price over all shops
				$priceMatrix = array();
				foreach ($feed->entries as $entry) {
					$price = $entry->getGbaseAttribute('preis');
					$priceMatrix[$entry->id->text] =  str_replace(' eur','',$price[0]->text);
				}

				// take average * 1.5 as max treshold
				if(count($priceMatrix)>0){
					$maxPriceThresholdOne = round((array_sum($priceMatrix)/count($priceMatrix))*1.5,2);
				} else {
					$maxPriceThresholdOne = NULL;
				}
				// delete all 'shops' where price is above max trashold
				foreach ($priceMatrix as $id => $price){
					if($price > $maxPriceThresholdOne || ($this->max_price > 0 && $price > $this->max_price)){
						unset($priceMatrix[$id]);
					}
				}

				// take average of cleaned up results * 1.5 as new max treshold
				if(count($priceMatrix)>0){
					$maxPriceThresholdTwo = round((array_sum($priceMatrix)/count($priceMatrix))*1.5,2);
				} else {
					$maxPriceThresholdTwo = NULL;
				}

				// average price of selection
				$avgPrice = 0.0;
				$sumPrice = 0.0;
				$countPrice = 0;

				foreach ($feed->entries as $entry) {
					$content = $entry->content->text;
					$preis = $entry->getGbaseAttribute('preis');
					$preis = str_replace(' eur','',$preis[0]->text);
					if($preis<=$maxPriceThresholdTwo && ($preis <= $this->max_price || $this->max_price <= 0)){
						// avg price calculation
						$countPrice++;
						$sumPrice += $preis;
					}
				}

				// ask local database
				$priceTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','price');
				$prices = $priceTable->fetchAll('product='.$this->id);

				foreach($prices as $price){
					$sumPrice += $price->price;
					$countPrice++;
				}
				if($countPrice>0){
					$avgPrice = round($sumPrice/$countPrice,2);
				} else {
					$avgPrice = NULL;
				}
				$cache->save($avgPrice,'averagePriceByProductId'.$this->id,array('model'));
			}
			$this->averagePrice = $avgPrice;
		}
		return $this->averagePrice;
	}

	/**
	 * get all shops with prices of this product (calls Google Base)
	 *
	 * @return array keys: title, description, shop, shopUrl, price, imageUrl, brand
	 */
	public function getOffers(){
		if($this->offers === NULL){
			// load cache from registry
			$cache = Zend_Registry::get('cache');

			// see if offer - list is already in cache
			$offerList = $cache->load('offersByProductId'.$this->id);
			if($offerList === false) {
				$service = new Zend_Gdata_Gbase();
				$feed = $service->getGbaseItemFeed($this->getGoogleBaseUrl());

				// average price over all shops
				$priceMatrix = array();
				foreach ($feed->entries as $entry) {
					$price = $entry->getGbaseAttribute('preis');
					$priceMatrix[$entry->id->text] =  str_replace(' eur','',$price[0]->text);
				}

				// take average * 1.5 as max treshold
				if(count($priceMatrix)>0){
					$maxPriceThresholdOne = round((array_sum($priceMatrix)/count($priceMatrix))*1.5,2);
				} else {
					$maxPriceThresholdOne = NULL;
				}
				// delete all 'shops' where price is above max trashold
				foreach ($priceMatrix as $id => $price){
					if($price>$maxPriceThresholdOne || ($price > $this->max_price && $this->max_price > 0)){
						unset($priceMatrix[$id]);
					}
				}
				// take average of cleaned up results * 1.5 as new max treshold
				if(count($priceMatrix)>0){
					$maxPriceThresholdTwo = round((array_sum($priceMatrix)/count($priceMatrix))*1.5,2);
				} else {
					$maxPriceThresholdTwo = NULL;
				}

				// create empty result set
				$offerList = array();
				foreach ($feed->entries as $entry) {
					$price = $entry->getGbaseAttribute('preis');
					$price = str_replace(' eur','',$price[0]->text);
					if($price <= $maxPriceThresholdTwo && ($this->max_price <= 0 || $price < $this->max_price)){
						$baseAttributes = $entry->getGbaseAttributes();
						$price = $entry->getGbaseAttribute('preis');
						$price = $price[0]->text;
						$brand = $entry->getGbaseAttribute('marke');
						$brand = $brand[0]->text;
						$imageLink = $entry->getGbaseAttribute('image_link');
						$imageLink = $imageLink[0]->text;
						$shop = $entry->author;
						$shop = $shop[0]->name->text;
						$link = $entry->link;
						$link = $link[0]->href;
						$offerList[] = array(
			  	'shop'=>$shop, 'shopUrl'=>$link,
			  	'price'=>str_replace(array('eur'),array(''),$price),
			  	'imageUrl'=>$imageLink,'title'=>$entry->title->text,
			  	'description'=>$entry->content->text,'brand'=>$brand);			  	
					}
				}
				$cache->save($offerList,'offersByProductId'.$this->id,array('model'));
			}
			$this->offers = $offerList;
		}
		return $this->offers;
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

	public function __construct ($productId=NULL){
		if(!empty($productId)){
			$productTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
			$product = $productTable->fetchRow('id='.$productId);
			if(!$product){
				throw new Website_Model_ProductException('Id_Wrong');
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
			$this->max_price			= $product['max_price'];
			$this->insertDate			= $product['insertDate'];
			$this->updateDate			= $product['updateDate'];
		}
	}

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
	 */
	public static function listProduct()
	{
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
		foreach ($table->fetchAll() as $product) {
			$productArray[] = Website_Model_CbFactory::factory('Website_Model_Product',$product['id']);
		}
		return $productArray;
	}

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