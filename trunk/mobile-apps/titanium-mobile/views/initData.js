/**
 * 
 */

var images = [];
var imagesRecipe = [];
var c=0;

// on first load save pre-calculated variables
if(!(Titanium.App.Properties.getList("recipes") && Titanium.App.Properties.getList("images") && Titanium.App.Properties.getList("imagesRecipe"))){
	var recipesFile = Titanium.Filesystem.getFile(Titanium.Filesystem.resourcesDirectory,'recipes.xml');
	var xml = Titanium.XML.parseString(recipesFile.read().text);

	var data = [];
	var doc = xml.documentElement;
	var elements = doc.getElementsByTagName("recipe");

	// create indicator
	var ind2;
	if (Titanium.Platform.name == 'iPhone OS') {
		ind2 = Titanium.UI.createProgressBar({
			min : 0,
			max : elements.length,
			value : 0,
			message : 'Lade Rezepte in den cocktailberater',
			style : Titanium.UI.iPhone.ProgressBarStyle.PLAIN,
			width: 120,
			height:50,
			bottom:10
		});
		loadingWindow.add(ind2);
	} else {
		ind2 = Titanium.UI.createActivityIndicator({
			location : Titanium.UI.ActivityIndicator.DIALOG,
			type : Titanium.UI.ActivityIndicator.DETERMINANT,
			message : 'Lade Rezepte in den cocktailberater',
			min : 0,
			max : elements.length,
			value : 0
		});
	}
	ind.hide();
	ind2.show();
	for ( var i = 0; i < elements.length; i++) {
		ind2.setValue(i);
		var recipe = {};
		recipe.title = elements.item(i).getAttribute("name");
		recipe.description = elements.item(i).getAttribute("description");
		recipe.instruction = elements.item(i).getAttribute("instruction");
		recipe.source = elements.item(i).getAttribute("source");
		recipe.calories = elements.item(i).getAttribute("caloriesKcal");
		recipe.volume = elements.item(i).getAttribute("volumeCl");
		recipe.workMin = elements.item(i).getAttribute("workMin");
		recipe.alcoholLevel = elements.item(i).getAttribute("alcoholLevel");
		if(elements.item(i).getAttribute("difficulty")=='advanced'){
			recipe.difficulty = 'Fortgeschritten'; 
		} else if(elements.item(i).getAttribute("difficulty")=='beginner'){
			recipe.difficulty = 'Anfänger'; 
		} else if(elements.item(i).getAttribute("difficulty")=='profi'){
			recipe.difficulty = 'Profi'; 
		}
		recipe.isAlcoholic = elements.item(i).getAttribute("isAlcoholic");
		recipe.glass = {};
		recipe.glass.name = elements.item(i).getElementsByTagName("glass")
				.item(0).getAttribute("name");

		recipe.components = [];
		var components = elements.item(i).getElementsByTagName("component");
		for ( var j = 0; j < components.length; j++) {
			recipe.components.push({
				amount : components.item(j).getAttribute("amount"),
				unit : components.item(j).getAttribute("unit"),
				name : components.item(j).getAttribute("name")
			});
		}

		recipe.photos = [];
		var photoContainer = elements.item(i).getElementsByTagName("photos");
		if (photoContainer != null && photoContainer.length > 0) {
			var photos = photoContainer.item(0).getElementsByTagName("photo");
			if (photos != null && photos.length > 0) {
				recipe.photos.push({
					url : 'images' + photos.item(0).getAttribute("url").substr(38),
					name : photos.item(0).getAttribute("name")
				});
				imagesRecipe[c] = recipe;
				images[c++] = 'images' + photos.item(0).getAttribute("url").substr(38);
			}
		}
		
		recipe.glass.photos = [];
		var glassPhotoContainer = elements.item(i).getElementsByTagName("glass");
		if (glassPhotoContainer != null && glassPhotoContainer.length > 0) {
			var glassPhotos = glassPhotoContainer.item(0).getElementsByTagName("photo");
			if (glassPhotos != null && glassPhotos.length > 0) {
				for ( var l = 0; l < glassPhotos.length; l++) {
					recipe.glass.photos.push({
						url : 'images' + glassPhotos.item(l).getAttribute("url").substr(38),
						name : glassPhotos.item(l).getAttribute("name")
					});
				}
			}
		}

		data.push({
			title : elements.item(i).getAttribute("name"),
			hasChild : true,
			recipe : recipe
		});
	}
		
	Titanium.App.Properties.setList("recipes",data);
	Titanium.App.Properties.setList("images",images);
	Titanium.App.Properties.setList("imagesRecipe",imagesRecipe);
	
	ind2.hide();
	loadingWindow.hide();
}

//load cached recipes
recipeTable.setData(Titanium.App.Properties.getList("recipes"));

// create coverflow view with images
var coverFlowWindow = Titanium.UI.createWindow({});
coverFlowWindow.orientationModes = [  
			                		Titanium.UI.LANDSCAPE_LEFT, 
			                		Titanium.UI.LANDSCAPE_RIGHT
			                	];
Ti.Gesture.addEventListener('orientationchange',function(e) {
	if(e.source.isLandscape() && tabGroup.activeTab.title == cocktailTab.title){
		var view = Titanium.UI.createCoverFlowView({
			images:Titanium.App.Properties.getList("images"),
			backgroundColor:'#000'
		});
		
		// click listener - when image is clicked
		view.addEventListener('click',function(e)
		{
			showRecipe(Titanium.App.Properties.getList("imagesRecipe")[e.index]);
			coverFlowWindow.close();
		});
		coverFlowWindow.add(view);
		coverFlowWindow.open();
	}
	
	if(e.source.isPortrait() && tabGroup.activeTab.title == cocktailTab.title) {
		coverFlowWindow.close();
	}
});

// switch views
ind.hide();
loadingWindow.hide();

// open tab group
tabGroup.open();

