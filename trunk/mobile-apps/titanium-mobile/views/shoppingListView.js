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

shoppingListWindow.add(shoppingListWebView);