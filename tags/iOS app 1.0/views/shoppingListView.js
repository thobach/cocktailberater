/**
 * 
 */
var shoppingListWebView = Ti.UI.createWebView({url: 'http://www.cocktailberater.de/website/portal/meine-einkaufsliste'});
var shoppingListEntered = false;
if (Titanium.Platform.name == 'android') {
	var shoppingListInd = Titanium.UI.createActivityIndicator({
		bottom:10, 
		height:50,
		width:10,
		style:Titanium.UI.iPhone.ActivityIndicatorStyle.PLAIN,
		message:'Lade Einkaufsliste ...'
	});
	tabGroup.addEventListener('focus', function(e){
		setTimeout(function() {
			if(tabGroup.activeTab == 2 && shoppingListEntered == false) {
				shoppingListInd.show();
			}
		},100);
	});
	shoppingListWebView.addEventListener('load',function(e){
		shoppingListInd.hide();
		shoppingListEntered = true;
	});
}

//offline warning
tabGroup.addEventListener('focus', function(e){
	setTimeout(function() {
		if(e.index == 2 && !Titanium.Network.online) {
			shoppingListWebView.html = '<html><head>' + 
			'<style>' + 
			'* { font-family: "Lucida Grande", "Lucida Sans", Tahoma, Verdana, sans-serif; text-align: center; }' +
			'p { margin-bottom: 0.5em; margin-top: 0; } ' +  
			'</style>' + 
			'</head>' + 
			'<body style="text-align: center;"><p>Diese Funktion steht leider nur mit Internetverbindung zur Verfügung.</p></body>' + 
			'</html>';
		}
	},100);
});

shoppingListWindow.add(shoppingListWebView);