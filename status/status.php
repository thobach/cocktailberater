<?php

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" src="script.js"></script>
<script type="text/javascript" src="jquery-1.2.3.pack.js"></script>

<script type="text/javascript"> 
var sessionValues = window.name.split(';');

$(document).ready(reLoad);


function reLoad() {
var lastIdFinished=$("#lastIdFinished").html();
var lastIdOrdered=$("#lastIdOrdered").html()

$.ajax({
type: "GET",
url: "request.php",
data: "partyid="+sessionValues[1]+"&type=finished&action=checknew&firstID="+lastIdFinished,
success:function(answer) {
  if (answer=='true'){
     updateFinished();
  }
}
});

$.ajax({
type: "GET",
url: "request.php",
data: "partyid="+sessionValues[1]+"&type=ordered&action=checknew",
success:function(answer) {
  if (answer=='true'){
     updateOrdered();
  }
}
});

setTimeout("reLoad()",4000);
}
  
function updateFinished() {
$.ajax({
type: "GET",
url: "request.php",
data: "partyid="+sessionValues[1]+"&type=finished&action=getList",
   success:function(html){
    $("#content").slideUp("slow").html(html).slideDown("slow");
  }
}); 
}

function updateOrdered() {
$.ajax({
type: "GET",
url: "request.php",
data: "partyid="+sessionValues[1]+"&type=ordered&action=getList",
   success:function(html){
    $("#content2").slideUp("slow").html(html).slideDown("slow");
  }
}); 
}

    </script>
</head>
<body background="version2/Bilder/screen-entwurf2_06.jpg">
<div style="text-align:right;width:100%;">
<a style="color:white;font-family:verdana;font-size:0.7em;text-decoration:none;" href="index.php" onclick="window.name=''">Logout</a>
</div>
<table align="center">
  <tr> 
    <td><img src="version2/Bilder/logo.jpg"><br>
      <br>
    </td>
    <td></td>
    <td rowspan="2" align="center" valign="top"> 
      <div align="center"><img src="version2/Bilder/bild.jpg" style="width:12em;height:16em"></div></td>
  </tr><tr><td>
  <table  cellpadding="0" cellspacing="0">
    <tr> 
      <td width="18" valign="top" background="version2/Bilder/oben_links.jpg" cellpadding="0" cellspacing="0"></td>
      <td ><table height="19" cellpadding="0" cellspacing="0">
          <tr> 
            <td bgcolor="#FFFFFF"><font color="#990066" size="5" face="Verdana, Arial, Helvetica, sans-serif"><strong>Fertig 
              </strong></font></td>
            <td width="4" bgcolor="#FFFFFF"></td>
            <td width="12" background="version2/Bilder/reiterrechts.jpg" cellpadding="0" cellspacing="0"></td>
          </tr>
        </table></td>
      <td width="10" ></td></td></tr>
    <tr> 
      <td background="version2/Bilder/screen-entwurf2_14.jpg" width="18" height="16" cellpadding="0" cellspacing="0"></td>
      <td background="version2/Bilder/screen-entwurf2_16.jpg"></td>
      <td background="version2/Bilder/screen-entwurf2_18.jpg" width="18" height="16" cellpadding="0" cellspacing="0"></td>
    </tr>
    <tr> 
      <td height="43" background="version2/Bilder/screen-entwurf2_19.jpg"></td>
      <td cellspacing="1">
	  
	  <div id="content" style = "width:40em"> 
	  <!---------------------------------------------------------------------------------//-->
         		<!---------------------------------------------------------------------------------//-->
        </div>
	
		</td></td>
      <td background="version2/Bilder/screen-entwurf2_21.jpg"></td>
    </tr>
    <tr> 
      <td background="version2/Bilder/screen-entwurf2_24.jpg" height="17" width="18" cellpadding="0" cellspacing="0"></td>
      <td height="17" background="version2/Bilder/screen-entwurf2_25.jpg"></td>
      <td background="version2/Bilder/screen-entwurf2_27.jpg" height="17" width="18" cellpadding="0" cellspacing="0"></td>
    </tr>
  </table></td>
  <td > 
  <tr align="center" valign="middle"> 
    <td colspan="3" hight="25">-</td>
  </tr></td></tr><tr><td>
  <table  cellpadding="0" cellspacing="0">
    <tr> 
      <td width="18" valign="top" background="version2/Bilder/oben_links.jpg" cellpadding="0" cellspacing="0"></td>
      <td ><table height="19" cellpadding="0" cellspacing="0">
          <tr> 
            <td bgcolor="#FFFFFF"><font color="#990066" size="5" face="Verdana, Arial, Helvetica, sans-serif"><strong>Bestellungen 
              </strong></font></td>
            <td width="4" bgcolor="#FFFFFF"></td>
            <td width="12" background="version2/Bilder/reiterrechts.jpg" cellpadding="0" cellspacing="0"></td>
          </tr>
        </table></td>
      <td width="10" ></td></td></tr>
    <tr> 
      <td background="version2/Bilder/screen-entwurf2_14.jpg" width="18" height="16" cellpadding="0" cellspacing="0"></td>
      <td background="version2/Bilder/screen-entwurf2_16.jpg"></td>
      <td background="version2/Bilder/screen-entwurf2_18.jpg" width="18" height="16" cellpadding="0" cellspacing="0"></td>
    </tr>
    <tr> 
      <td height="43" background="version2/Bilder/screen-entwurf2_19.jpg"></td>
      <td cellspacing="1"> <div id ="content2" style = "width:40em"> 
          
        </div></td></td>
      <td background="version2/Bilder/screen-entwurf2_21.jpg"></td>
    </tr>
    <tr> 
      <td background="version2/Bilder/screen-entwurf2_24.jpg" height="17" width="18" cellpadding="0" cellspacing="0"></td>
      <td height="17" background="version2/Bilder/screen-entwurf2_25.jpg"></td>
      <td background="version2/Bilder/screen-entwurf2_27.jpg" height="17" width="18" cellpadding="0" cellspacing="0"></td>
    </tr>
  </table></td>
  <td ></td>
    <td align="center" valign="top"> 
      <table border="0" align="center" cellpadding="5" cellspacing="2" bgcolor="#FFFFFF">
        <tr> 
          <td bgcolor="#990066"><strong><font color="#CCCCCC" size="2" face="Verdana, Arial, Helvetica, sans-serif">1. 
            Sex on the Beach<br>
            2. Cairo Cocktail<br>
      3. Long Island Ice Tea</font></strong></td>
  </tr>
  <tr> 
    <td bgcolor="#990066">
<div align="center"><strong><font color="#CCCCCC" size="3" face="Verdana, Arial, Helvetica, sans-serif"><br>
              Ab 23 Uhr<br>
              Happy Hour<br>
              Sex on the Beach</font><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><br>
              <br>
              </font></strong></div></td>
  </tr>
</table>
<strong><font size="3" face="Verdana, Arial, Helvetica, sans-serif"><br>
              <font color="#FFFFFF" size="6" face="Verdana, Arial, Helvetica, sans-serif">18:25</font></font></strong>
  
    </td>
  </tr>

  <tr> 
    <td><div align="center"></div></td>
    <td><div align="center"></div></td>
    <td><div align="center"></div></td>
  </tr>
</table>


</body>
</html>