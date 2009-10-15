<?php

/**
 * Zend_View_Helper_Notice prints a notice box
 */

class Zend_View_Helper_Notice extends Zend_View_Helper_Abstract
{
	/**
	 * prints a notice box
	 *
	 * @param 	string	notice
	 * @return 	string 	notice box
	 */
	public function notice($notice) {
		if($notice!='') {
		return '<div id="hinweis">'.$notice.'</div>';
		}
	}
}