/**
 * 
 */
var webview = Titanium.UI.createWebView({html: '<html><body>'+
	'<style>' + 
	'* { font-family: "Lucida Grande", "Lucida Sans", Tahoma, Verdana, sans-serif; text-align: center; }' +
	'h1 { color: #cf0060; font-size: 1.5em; margin-top: 1em; } ' + 
	'p { margin-bottom: 0.5em; margin-top: 0; } ' +  
	'</style>' +
	'<h1>cocktailberater</h1>' +
	'<p>Der cocktailberater ist ein studentisches ' + 
	'Projekt um neue Technologien rund um das ' + 
	'Thema Cocktails auszuprobieren.</p>' + 
	'<p>Erfahre mehr auf <a href="http://www.cocktailberater.de">www.cocktailberater.de</a>.</p>' + 
	'</body></html>'});
aboutWindow.add(webview);
