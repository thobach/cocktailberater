<?php

/**
 * CbFactory is a factory pattern implementation for cocktailberater
 *
 */
class Website_Model_CbFactory {

	// supporting variables
	private static $_hash;

	/**
	 * returns an object of typ $className, called with one or two parameters
	 *
	 * @param string $className
	 * @param mixed $firstParam
	 * @param mixed $secondParam
	 * @return Object
	 */
	public static function factory($className,$firstParam=NULL,$secondParam=NULL)
	{
		$log = Zend_Registry::get('logger');
		$log->log('Website_Model_CbFactory->factory ('.$className.','.$firstParam.','.$secondParam.')',Zend_Log::DEBUG);
		if($firstParam && $secondParam){
			if(!isset(self::$_hash[$className][$firstParam][$secondParam])){
				self::$_hash[$className][$firstParam][$secondParam] = new $className($firstParam,$secondParam);
			}
			return self::$_hash[$className][$firstParam][$secondParam];
		} else if($firstParam){
			if(!isset(self::$_hash[$className][$firstParam])){
				self::$_hash[$className][$firstParam] = new $className($firstParam);
			}
			return self::$_hash[$className][$firstParam];
		} else {
			if(!isset(self::$_hash[$className])){
				self::$_hash[$className] = new $className();
			}
			return self::$_hash[$className];
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