<?php
class View {
	
	function getBGColor($nr) {
		if ($nr%2==0) {
			return 'bgcolor="#993366"';
		}
		return "";
	}
	
	function getColoredHook($nr) {
		if ($nr%2==0) {
			return '<img style="width:1em;height:1em" src="version2/Bilder/hakenhell2.jpg" alt="ok">';
		}
		return '<img style="width:1em;height:1em" src="version2/Bilder/hakendunkel2.jpg" alt="ok">';
	}

	function showOrdered($orderList) {
		//generiert den Code für die bestellten
		echo '<table width="100%" align="center" cellspacing="0" style = "font-family:Verdana;font-weight:bold;color:white;font-size:20">';
		foreach ($orderList as $order) {
			echo '<tr align="left" valign="middle" '.$this->getBGColor($order->__get("position")).'>
              <td nowrap>'.$order->__get("position").'.</td>
              <td nowrap>'.$order->__get("username").'</td>
              <td nowrap></td>
              <td nowrap>'.$order->__get("cocktailname").'</td>
              <td nowrap> <div align="right">---</div></td>
            </tr>';
		}
		echo '</table>';
		echo '<div id="lastIdOrdered" style="position:absolute;top:1;left:1;visibility:hidden">'.$orderList[0]->__get("id").'</div>';

	}

	function showFinished($orderList) {
		//generiert den Code für die fertigen
		echo '<table width="100%" align="center" cellspacing="0" style = "font-family:Verdana;font-weight:bold;color:white;font-size:20">';

		foreach ($orderList as $order) {

			echo '<tr align="left" valign="middle" '.$this->getBGColor($order->__get("position")).'>
              <td width="3%" nowrap="nowrap">'.$this->getColoredHook($order->__get("position")).'</td>
              <td width="35%" nowrap="nowrap">'.$order->__get("username").'</td>
              <td width="2%" nowrap="nowrap"></td>
              <td width="53%" nowrap="nowrap">'.$order->__get("cocktailname").'</td>
              <td width="7%" nowrap="nowrap"> <div align="right">---</div></td>
            </tr>';

		}
		echo '</table>';
		echo '<div id="lastIdFinished" style="position:absolute;top:1;left:1;visibility:hidden">'.$orderList[0]->__get("id").'</div>';


	}
}
?>