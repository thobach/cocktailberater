<?php
include "View.php";
include "DataFetcher.php";


class ListGenerator {

	function generateList($type="ordered",$party) {
			
		//Verarbeitet die XML Daten und generiert ein Array of Orders.
		//Diese werden an den View weiter gegeben.
		$dataFetcher = new DataFetcher();

		$typeparam="ordered";
		if ($type=="finished") $typeparam="paid";

		$params = array( 	'party' => $party,
    						'apikey'  => $apikey,
							'status' => $typeparam 
		);

		$doc = $dataFetcher->getXML("order/get-all",$params);
		if ($doc!=null) {
			$docElement = $doc->documentElement;

			if ($docElement->getAttribute("status")=="ok") {

				$ordersElement = $docElement->getElementsByTagName("order");
				$i=0;
				foreach ($ordersElement as $orderElement) {
					$order = new Order();
					$orderList[] = $order;
					$order -> __set("id",$orderElement->getAttribute("id"));
					$order -> __set("cocktailname",$orderElement->getAttribute("recipeName"));
					$order -> __set("status",$orderElement->getAttribute("status"));
					$order -> __set("username",$orderElement->getAttribute("member"));
					$order -> __set("position",$i++);
					
				}
				
				
				
				
				
				
				$v= new View;
				if ($type=="ordered") {
					$v->showOrdered($orderList);
				}
				if ($type=="finished") {
					$v->showFinished($orderList);
				}
			} else {
					
				echo "XML Error";
			}
		} else {
			
			echo "XML Error";
		}

			/*
			 $orderList[]=new Order();
			 $orderList[]=new Order();
			 $orderList[]=new Order();


			 $orderList[0]->__set("id",2);
			 $orderList[1]->__set("id",5);
			 $orderList[2]->__set("id",4);

			 $orderList[0]->__set("username","peter");
			 $orderList[1]->__set("username","muh");
			 $orderList[2]->__set("username","hans");

			 $orderList[0]->__set("cocktailname","Sex on the Beach");
			 $orderList[1]->__set("cocktailname","Mai Tai");
			 $orderList[2]->__set("cocktailname","Tequila Sunrise");

			 $orderList[0]->__set("position",1);
			 $orderList[1]->__set("position",2);
			 $orderList[2]->__set("position",3);

			 $orderList[0]->__set("status",finished);
			 $orderList[1]->__set("status",finished);
			 $orderList[2]->__set("status",finished);

			 */


			//An den View übergeben
				
	}
}
?>