/**
 * 
 */
var loadingWindow = Titanium.UI.createWindow({
	title : 'cocktailberater', 
	fullscreen: false, 
	navBarHidden: true, 
	modal: false, 
	backgroundColor: '#FFF'
});

// image needs to be in a separate folder to work in iPhone
var imageView = Titanium.UI.createImageView({
	image:'images/default.png',
	top:-20
});
loadingWindow.add(imageView);
loadingWindow.open();

var ind;
if (Titanium.Platform.name == 'iPhone OS') {
	ind = Titanium.UI.createActivityIndicator({
		style:Titanium.UI.iPhone.ActivityIndicatorStyle.BIG,
		message: 'Lade Rezepte ...',
		color: '#000',
		bottom:10,
		height:50,
	    width:10
	});
	loadingWindow.add(ind);
} else {
	ind = Titanium.UI.createActivityIndicator({
		bottom:10, 
		height:50,
		width:10,
		style:Titanium.UI.iPhone.ActivityIndicatorStyle.PLAIN,
		message:'Lade Rezepte ...'
	});
}
ind.show();


var orientationModes = [ 
                		Titanium.UI.PORTRAIT, 
                		Titanium.UI.UPSIDE_PORTRAIT, 
                		Titanium.UI.LANDSCAPE_LEFT, 
                		Titanium.UI.LANDSCAPE_RIGHT, 
                		Titanium.UI.FACE_UP, 
                		Titanium.UI.FACE_DOWN
                	];

//create tab group
var tabGroup = Titanium.UI.createTabGroup();

// cocktails view (tab index=0)
var cocktailsWindow = Titanium.UI.createWindow({title : 'Cocktails'});
cocktailsWindow.orientationModes = [ Titanium.UI.PORTRAIT ];
var appiconView = Ti.UI.createImageView({ 
	image:'images/appicon.png',
	width:30,
	height:30
});
cocktailsWindow.rightNavButton = appiconView;
var cocktailTab = Titanium.UI.createTab({
	icon:'images/tab_cocktails_small.png',
	title : 'Cocktails',
	window : cocktailsWindow
});
tabGroup.addTab(cocktailTab);

// cocktails table
var search = Titanium.UI.createSearchBar({
	showCancel : true
});
var recipeTable = Titanium.UI.createTableView({
	data : [],
	search : search,
	autoHideSearch : true,
	filterAttribute : 'title'
});
cocktailsWindow.add(recipeTable);
Ti.include("/views/cocktailsView.js");

// hausbar view (tab index=1)
var hausbarWindow = Titanium.UI.createWindow({title : 'Hausbar'});
hausbarWindow.orientationModes = orientationModes;
var hausbarTab = Titanium.UI.createTab({
	icon : 'images/tab_housebar_small.png',
	title : 'Hausbar',
	window : hausbarWindow
});
tabGroup.addTab(hausbarTab);
Ti.include("/views/hausbarView.js");

// shopping list view (tab index=2)
var shoppingListWindow = Titanium.UI.createWindow({title : 'Einkaufsliste'});
shoppingListWindow.orientationModes = orientationModes;
var shoppingListTab = Titanium.UI.createTab({
	icon : 'images/tab_shoppinglist_small.png',
	title : 'Einkaufsliste',
	window : shoppingListWindow
});
tabGroup.addTab(shoppingListTab);
Ti.include("/views/shoppingListView.js");

// about view (tab index=3)
var aboutWindow = Titanium.UI.createWindow({title : 'Über cocktailberater'});
aboutWindow.orientationModes = orientationModes;
var aboutTab = Titanium.UI.createTab({
	icon : 'images/tab_about_small.png',
	title : 'Über cocktailberater',
	window : aboutWindow
});
tabGroup.addTab(aboutTab);
Ti.include("/views/aboutView.js");
