<?php
class Website_Model_DateFormat {
	const MYSQLTIMESTAMP = 'YYYY-MM-dd HH:mm:ss';
	const PHPDATE2MYSQLTIMESTAMP = 'Y-m-d H:i:s';

	public static function isValidMysqlTimestamp($mysqlTimestamp)
	{
		$erg = ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",$mysqlTimestamp);
		if($erg){
			return true;
		} else {
			return false;
		}
	}

}
?>