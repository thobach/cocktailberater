<?php

/**
 * Zend_View_Helper_ShortenText adds '...' after a certain string length if needed
 */

class Zend_View_Helper_ShortenText extends Zend_View_Helper_Abstract
{
	/**
	 * Adds '...' after a certain string length if needed
	 *
	 * @param 	string	text
	 * @param 	string	length
	 * @return 	string 	shortened string
	 */
	public function shortenText($text, $length) {
		if(strlen($text)>$length){
			return substr($text,0,$length).' ...';
		} else {
			return $text;
		}
	}
}