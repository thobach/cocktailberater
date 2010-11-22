/**
 * 
 */
var hausbarWebView = Ti.UI.createWebView({url: 'http://www.cocktailberater.de/website/portal/meine-hausbar'});
var hausbarEntered = false;
if (Titanium.Platform.name == 'android') {
	var hausbarInd = Titanium.UI.createActivityIndicator({
		bottom:10, 
		height:50,
		width:10,
		style:Titanium.UI.iPhone.ActivityIndicatorStyle.PLAIN,
		message:'Lade Hausbar ...'
	});
	tabGroup.addEventListener('focus', function(e){
		setTimeout(function() {
			if(tabGroup.activeTab == 1 && hausbarEntered == false) {
				hausbarInd.show();
			}
		},100);
	});
	hausbarWebView.addEventListener('load',function(e){
		hausbarInd.hide();
		hausbarEntered = true;
	});
}

hausbarWindow.add(hausbarWebView);
