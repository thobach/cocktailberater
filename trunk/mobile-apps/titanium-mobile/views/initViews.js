/**
 * initViews.js
 * 
 * creates all windows at startup
 */

// loading window
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

// loading indicator
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
loadingWindow.open();
ind.show();

// create tab group
var tabGroup = Titanium.UI.createTabGroup();
tabGroup.allowUserCustomization = false;

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
	icon:'images/tab_cocktails_small_white.png',
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

// random cocktail view (tab index=1)
var randomWindow = Titanium.UI.createWindow({
	title : 'Schüttel deinen Zufallscocktail', 
	backgroundImage : 'images/shaker.jpg'});
randomWindow.orientationModes = [ Titanium.UI.PORTRAIT ];
var randomCocktailTabText = 'Zufallscocktail';
var randomTab = Titanium.UI.createTab({
	icon : 'images/tab_shaker_small_white.png',
	title : randomCocktailTabText,
	window : randomWindow
});
tabGroup.addTab(randomTab);
Ti.include("/views/randomView.js");

// hausbar view (tab index=2)
var hausbarWindow = Titanium.UI.createWindow({title : 'Hausbar'});
hausbarWindow.orientationModes = [ Titanium.UI.PORTRAIT ];
var hausbarTab = Titanium.UI.createTab({
	icon : 'images/tab_housebar_small_white.png',
	title : 'Hausbar',
	window : hausbarWindow
});
tabGroup.addTab(hausbarTab);
Ti.include("/views/hausbarView.js");

// shopping list view (tab index=3)
var shoppingListWindow = Titanium.UI.createWindow({title : 'Einkaufsliste'});
shoppingListWindow.orientationModes = [ Titanium.UI.PORTRAIT ];
var shoppingListTab = Titanium.UI.createTab({
	icon : 'images/tab_shoppinglist_small_white.png',
	title : 'Einkaufsliste',
	window : shoppingListWindow
});
tabGroup.addTab(shoppingListTab);
Ti.include("/views/shoppingListView.js");

// about view (tab index=4)
var aboutWindow = Titanium.UI.createWindow({title : 'Über cocktailberater'});
aboutWindow.orientationModes = [ Titanium.UI.PORTRAIT ];
var aboutTab = Titanium.UI.createTab({
	icon : 'images/tab_about_small_white.png',
	title : 'cocktailberater',
	window : aboutWindow
});
tabGroup.addTab(aboutTab);
Ti.include("/views/aboutView.js");

// quiz view (tab index=5)
var quizWindow = Titanium.UI.createWindow({title : 'Quiz', backgroundColor:'#FFFFFF'});
quizWindow.orientationModes = [ Titanium.UI.PORTRAIT ];
var quizTab = Titanium.UI.createTab({
	icon : 'images/tab_about_small_white.png',
	title : 'Quiz',
	window : quizWindow
});
tabGroup.addTab(quizTab);
Ti.include("/views/quizView.js");