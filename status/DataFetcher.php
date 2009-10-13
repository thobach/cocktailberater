<?php

class DataFetcher {

	function getXML($request,$params) {
		$base="http://api-stage.cocktailberater.de/";
		$query_string = "";

		

		foreach ($params as $key => $value) {
			$query_string .= "/$key/" . urlencode($value);
		}
		
		$url = "$base$request$query_string";
		echo $url;
		
		$output = file_get_contents($url);
	
		$doc = new DOMDocument();
		$doc->loadXML($output);
		return $doc;
	}
}
?>