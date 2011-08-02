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
	private $offers;
	private $image;
	private $uniqueName;
	private static $_avgPrices;
	private static $_productsByIngredientId;
	private static $_numberOfRecipes;

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
	public function getIngredient() {
		if($this->_ingredient === NULL){
			$this->_ingredient = Website_Model_CbFactory::factory('Website_Model_Ingredient',$this->ingredientId);
		}
		return $this->_ingredient;
	}

	/**
	 * resolve Association and return an object of Manufacturer
	 *
	 * @return Website_Model_Manufacturer
	 */
	public function getManufacturer() {
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
	 * returns a google base url with query parameter
	 *
	 * @param	int		$limit
	 * @return	string	google base url
	 */
	private function getGoogleBaseUrl($limit=50){
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

		$url = 'http://www.google.com/base/feeds/snippets/?bq='.urlencode('("'.$this->name.'"'.$manufacturer.' ('.$size.'))').'[item%20type%3Aprodukte]&max-results='.$limit;
		//echo $url;
		return $url;
	}

	/**
	 * returns the unique name which is also rawurlencoded
	 * @return string
	 */
	public function getUniqueName() {
		if($this->uniqueName === NULL){
			$this->uniqueName = rawurlencode(
				str_replace(array(' '),array('_'),
				$this->id.'_'.$this->getManufacturer()->name.'_'.$this->name));
		}
		return $this->uniqueName;
		
	}

	/**
	 * get average price of this product (calls Google Base)
	 *
	 * @return double
	 */
	public function getAveragePrice(){
		
		return NULL;
		
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_Product->getAveragePrice',Zend_Log::DEBUG);
		// check if data is already calculated
		if(Website_Model_Product::$_avgPrices[$this->id] === NULL){
			// load cache module from registry
			$cache = Zend_Registry::get('cache');
			// internal cache not yet created
			if(!is_array(Website_Model_Product::$_avgPrices)){
				// load price information from cache
				Website_Model_Product::$_avgPrices = $cache->load('averageProductPrices');
			}
			// continue if cache does not contain the price information for this product
			if(Website_Model_Product::$_avgPrices[$this->id] === NULL) {
				// ask google base
				$service = new Zend_Gdata_Gbase();
				try{
					$feed = $service->getGbaseItemFeed($this->getGoogleBaseUrl());
				} catch (Zend_Gdata_App_HttpException $e){
					// if service is not available
					return NULL;
				}

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
				$avgPrice[$this->id] = 0.0;
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

				// any price information in local database or google base
				if($countPrice>0){
					Website_Model_Product::$_avgPrices[$this->id] = round($sumPrice/$countPrice,2);
				} else {
					Website_Model_Product::$_avgPrices[$this->id] = -1;
				}

				// persist new data in cache
				$cache->save(Website_Model_Product::$_avgPrices,'averageProductPrices',array('model'));
			}
		}
		$log->log('Website_Model_Product->getAveragePrice exiting',Zend_Log::DEBUG);
		if(Website_Model_Product::$_avgPrices[$this->id] === -1){
			return NULL;
		} else {
			return Website_Model_Product::$_avgPrices[$this->id];
		}
	}

	/**
	 * get all shops with prices of this product (calls Google Base)
	 *
	 * @return array keys: title, description, shop, shopUrl, price, imageUrl, brand
	 */
	public function getOffers($limit=50){
		
		return NULL;
		
		if($this->offers === NULL){
			// load cache from registry
			$cache = Zend_Registry::get('cache');

			// see if offer - list is already in cache
			$offerList = $cache->load('offersByProductId'.$this->id.'limit'.$limit);
			if($offerList === false) {
				$service = new Zend_Gdata_Gbase();
				$feed = $service->getGbaseItemFeed($this->getGoogleBaseUrl($limit));

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
				$cache->save($offerList,'offersByProductId'.$this->id.'limit'.$limit,array('model'));
			}
			$this->offers = $offerList;
		}
		return $this->offers;
	}

	/**
	 * Returns all recipes of this product via looking up its ingredient
	 *
	 * return array[int]Website_Model_Recipe
	 */
	public function getRecipes(){
		return $this->getIngredient()->getRecipes();
	}

	/**
	 * Returns the number of recipes of this product via looking up its ingredient
	 *
	 * return int
	 */
	public function getNumberOfRecipes(){
		// check if data is already calculated
		if(Website_Model_Product::$_numberOfRecipes[$this->id] === NULL){
			// load cache module from registry
			$cache = Zend_Registry::get('cache');
			// internal cache not yet created
			if(!is_array(Website_Model_Product::$_numberOfRecipes)){
				// load calories information from cache
				Website_Model_Product::$_numberOfRecipes = $cache->load('productNumberOfRecipes');
			}
			// continue if cache does not contain the price information for this recipe
			if(Website_Model_Product::$_numberOfRecipes[$this->id] === NULL) {
				Website_Model_Product::$_numberOfRecipes[$this->id] = $this->getIngredient()->getNumberOfRecipes();
				// persist new data in cache
				$cache->save(Website_Model_Product::$_numberOfRecipes,'productNumberOfRecipes',array('model'));
			}
		}
		return Website_Model_Product::$_numberOfRecipes[$this->id];
	}

	/**
	 * returns the image of the first product
	 *
	 * @return string image url
	 */
	public function getImage(){
		
		return NULL;
		
		if($this->image === NULL){
			// load cache from registry
			$cache = Zend_Registry::get('cache');

			// see if offer - list is already in cache
			$image = $cache->load('imageByProductId'.$this->id);
			if($image === false) {
				$service = new Zend_Gdata_Gbase();
				$feed = $service->getGbaseItemFeed($this->getGoogleBaseUrl(1));

				// create empty result set
				$image = null;
				foreach ($feed->entries as $entry) {
					$image = $entry->getGbaseAttribute('image_link');
					$image = $image[0]->text;
				}
				$cache->save($image,'imageByProductId'.$this->id,array('model'));
			}
			$this->image = $image;
		}
		return $this->image;
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
	 * loads the product with the given id from the persistent storage
	 *
	 * @param integer $productId, default NULL -> creates empty product object
	 */
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

	/**
	 * returns all products of an ingredient by id
	 *
	 * @param integer $ingredientId
	 * @return Website_Model_Product[]
	 */
	public static function productsByIngredientId($ingredientId){
		if(self::$_productsByIngredientId[$ingredientId] === NULL){
			$productTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
			$products = $productTable->fetchAll('ingredient='.$ingredientId);
			self::$_productsByIngredientId[$ingredientId] = array();
			foreach ($products as $product){
				self::$_productsByIngredientId[$ingredientId][] =
				Website_Model_CbFactory::factory('Website_Model_Product', $product['id']);
			}
		}
		return self::$_productsByIngredientId[$ingredientId];
	}

	/**
	 * returns the number of products of an ingredient by id
	 *
	 * @param	int	$ingredientId
	 * @return	int
	 */
	public static function numberOfProductsByIngredientId($ingredientId){
		if(self::$_productsByIngredientId[$ingredientId] === NULL){
			$productTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
			$products = $productTable->fetchAll('ingredient='.$ingredientId);
			$count = count($products);
		} else {
			$count = count(self::$_productsByIngredientId[$ingredientId]);
		}
		return $count;
	}

	/**
	 * returns an array of all Product objects
	 *
	 * @return Website_Model_Product[]
	 */
	public static function listProduct() {
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable','product');
		foreach ($table->fetchAll(null,'name') as $product) {
			$productArray[] = Website_Model_CbFactory::factory('Website_Model_Product',$product['id']);
		}
		return $productArray;
	}

	/**
	 * makes the product persistent
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
	 * deletes the product
	 */
	public function delete (){
		$table = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product');
		$table->delete('id='.$this->id);
		Website_Model_CbFactory::destroy('Product',$this->id);
		unset($this);
	}

	/**
	 * checks if the product exists in the persitent storage
	 *
	 * @param integer $id
	 * @return integer|boolean id if product exists, false otherwise
	 */
	public static function exists($id) {
		$productTable = Website_Model_CbFactory::factory('Website_Model_MysqlTable', 'product') ;
		if(((int)$id)>0){
			$product = $productTable->fetchRow('id='.(int)$id);
			return $product->id;
		} else {
			return false;
		}
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