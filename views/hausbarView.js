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

// offline warning
tabGroup.addEventListener('focus', function(e){
	setTimeout(function() {
		if(e.index == 1 && !Titanium.Network.online) {
			hausbarWebView.html = '<html><head>' + 
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

hausbarWindow.add(hausbarWebView);
