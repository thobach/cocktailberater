<?php
class HtmlPage {
	public static function postDispatch ( $_this ) {
		
	}
	
	public static function preDispatch ( $_this ) {
		
	}
	
	public static function call ( $_method , $_args , Zend_Controller_Action $_this ) {
		//throw new Zend_Controller_Action_Exception('Ungültiger Seitenaufruf');
	}
	
	public static function mergeTogether ( $ziel , $post ) {
		// Aus den 2 Objekten wird je ein objekt ReflectionClass erzeugt		//$refClass = new ReflectionClass($row);		$refClassZiel = new ReflectionClass ( $ziel ) ;
		if (! $post) {
			throw new Zend_Exception ( "POST leer" ) ;
		}
		foreach ( $post as $key => $value ) {
			// Spaltenname			// echo $key."<br>";			if ($refClassZiel->hasProperty ( $key )) {
				// Dieses Attribut ist in beiden Objekten				//	echo "gefunden: ".$name. "<br>";				//	echo "wert:".$row->$name."<br>";				//exit;				// Werte zuordnen				$ziel->$key = $post [ $key ] ;
			}
		}
		return $ziel ;
	}
	
	/*
	 * Übersetzt einen UTF-8 String korrekt für einen 7 Bit E-Mail Header Bereich
	 * @link http://www.webmasterpark.net/forum/showpost.php?p=618655&postcount=2
	 * @param string $input
	 * @param string $charset
	 */
	public static function encodeHeader ( $input , $charset = 'utf-8' ) {
		preg_match_all ( '/(\w*[\x80-\xFF]+\w*)/', $input, $matches ) ;
		foreach ( $matches [ 1 ] as $value ) {
			$replacement = preg_replace ( '/([\x80-\xFF])/e', '"=".strtoupper(dechex(ord("\1")))', $value ) ;
			$input = str_replace ( $value, '=?' . $charset . '?Q?' . $replacement . '?=', $input ) ;
		}
		return $input ;
	}
}
?>