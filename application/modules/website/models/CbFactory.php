<?php

/**
 * CbFactory is a factory pattern implementation for cocktailberater
 *
 */
class Website_Model_CbFactory {

	// supporting variables
	private static $_hash;

	/**
	 * returns an objects of $className with some parameters
	 *
	 * @param string $className
	 * @param mixed $firstParam
	 * @param mixed $secondParam
	 * @return Object
	 */
	public static function factory($className,$firstParam=NULL,$secondParam=NULL)
	{
		if($firstParam && $secondParam){
			if(!isset(Website_Model_CbFactory::$_hash[$className][$firstParam][$secondParam])){
				Website_Model_CbFactory::$_hash[$className][$firstParam][$secondParam] = new $className($firstParam,$secondParam);
			}
			return Website_Model_CbFactory::$_hash[$className][$firstParam][$secondParam];
		} else if($firstParam){
			if(!isset(Website_Model_CbFactory::$_hash[$className][$firstParam])){
				Website_Model_CbFactory::$_hash[$className][$firstParam] = new $className($firstParam);
			}
			return Website_Model_CbFactory::$_hash[$className][$firstParam];
		} else {
			if(!isset(Website_Model_CbFactory::$_hash[$className])){
				Website_Model_CbFactory::$_hash[$className] = new $className();
			}
			return Website_Model_CbFactory::$_hash[$className];
		}
	}

	public static function destroy($className,$firstParam=NULL,$secondParam=NULL)
	{
		if($firstParam && $secondParam){
			unset(self::$_hash[$className][$firstParam][$secondParam]);
		} else if($firstParam){
			unset(self::$_hash[$className][$firstParam]);
		} else {
			unset(self::$_hash[$className]);
		}
	}

}