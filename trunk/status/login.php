<?php

$partyid=$_GET["partyid"];

// Check ob diese Partyid wirklich existiert



?>
<html>
<head>
<script type="text/javascript">
var value="";
value+="party;";
value+="<?php echo $partyid; ?>";
window.name=value;
document.location.href="status.php";

</script>
</head>
</html>