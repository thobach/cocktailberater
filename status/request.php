<?php
include "ListGenerator.php";
include "CheckNew.php";
include "Order.php";
include "Answer.php";

$action=$_GET["action"];
$type=($_GET["type"] == "ordered") ? "ordered" : "finished" ;
$party=$_GET["partyid"];
$firstID=$_GET["firstId"];

if ($action=="checknew") {

	if ($firstID=="") {
		$answer=new Answer();
		$answer->return_answer(true);
	}
	else {
		$cn=new CheckNew();
		echo $firstID;
		$cn->check($type,$firstID);
	}
}

else if ($action=="getList") {
	$lg=new ListGenerator();
	$lg->generateList($type,$partyid);
}
/*

$datumarray = getdate();
$second = $datumarray[seconds];

if($second > "20")
{
if($second > "40")
{
echo '<table width="100%" align="center" cellspacing="0" style = "font-family:Verdana;font-weight:bold;color:white;font-size:20">
<tr align="left" valign="middle">
<td width="3%" nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td width="35%" nowrap>Manfred</td>
<td width="2%" nowrap></td>
<td width="53%" nowrap>Swimming Pool</td>
<td width="7%" nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle" bgcolor="#993366">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td nowrap>BlaBlaBlaBla...</td>
<td nowrap></td>
<td nowrap>Long Island Ice Tea</td>
<td nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle" bgcolor="#993366">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td nowrap>Mr. Superman</td>
<td nowrap></td>
<td nowrap>Cairo Cocktail</td>
<td nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle" bgcolor="#993366">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td nowrap>BlaBlaBlaBla...</td>
<td nowrap></td>
<td nowrap>Long Island Ice Tea</td>
<td nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td nowrap>ThoBach</td>
<td nowrap></td>
<td nowrap>Long Island Ice Tea</td>
<td nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle" bgcolor="#993366">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>

<td nowrap>BlaBlaBlaBla...</td>
<td nowrap></td>
<td nowrap>Long Island Ice Tea</td>
<td nowrap> <div align="right">---</div></td>
</tr>
</table>';
}
else
{
echo '<table width="100%" align="center" cellspacing="0" style = "font-family:Verdana;font-weight:bold;color:white;font-size:20">
<tr align="left" valign="middle">
<td width="3%" nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td width="35%" nowrap>Manfred</td>
<td width="2%" nowrap></td>
<td width="53%" nowrap>Swimming Pool</td>
<td width="7%" nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle" bgcolor="#993366">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td nowrap>Mr. Superman</td>
<td nowrap></td>
<td nowrap>Cairo Cocktail</td>
<td nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td nowrap>ThoBach</td>
<td nowrap></td>
<td nowrap>Long Island Ice Tea</td>
<td nowrap> <div align="right">---</div></td>
</tr>

</table>';

}
}
else
{
echo '<table width="100%" align="center" cellspacing="0" style = "font-family:Verdana;font-weight:bold;color:white;font-size:20">
<tr align="left" valign="middle">
<td width="3%" nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td width="35%" nowrap>Manfred</td>
<td width="2%" nowrap></td>
<td width="53%" nowrap>Swimming Pool</td>
<td width="7%" nowrap> <div align="right">---</div></td>
</tr>
<tr align="left" valign="middle" bgcolor="#993366">
<td nowrap><img src="Bilder/haken.jpg" width="20" height="19"></td>
<td nowrap>Mr. Superman</td>
<td nowrap></td>
<td nowrap>Cairo Cocktail</td>
<td nowrap> <div align="right">---</div></td>
</tr>
</table>';
}
*/
?>