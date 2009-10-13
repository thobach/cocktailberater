var sessionValues = window.name.split(';');

if (sessionValues[0]!='party') {
	window.name="";
	window.location.href="index.php";
}
