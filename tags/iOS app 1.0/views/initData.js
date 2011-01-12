/**
 * 
 */

var images = [];
var imagesRecipe = [];
var c=0;

// on first load save pre-calculated variables
if(!(Titanium.App.Properties.getList("recipeRows") && Titanium.App.Properties.getList("images") && Titanium.App.Properties.getList("imagesRecipe"))){
	var recipesFile = Titanium.Filesystem.getFile(Titanium.Filesystem.resourcesDirectory,'recipes.xml');
	var xml = Titanium.XML.parseString(recipesFile.read().text);

	var rows = [];
	var lastHeader = '';
	
	var doc = xml.documentElement;
	var elements = doc.getElementsByTagName("recipe");

	for ( var i = 0; i < elements.length; i++) {
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
		
		var photo = '';
		
		if(recipe.photos[0] != null) {
			photo = recipe.photos[0].url;
		} else {
			photo = recipe.glass.photos[0].url;
		}

		var row = {
			left: '50%',
			leftImage : photo,
			title: recipe.title,
			hasChild : true,
			recipe : recipe
		};
		
		if(lastHeader != recipe.title.charAt(0)){
			lastHeader = row.header = recipe.title.charAt(0);
		}
		
		rows[i] = row;
	}
		
	Titanium.App.Properties.setList("images",images);
	Titanium.App.Properties.setList("imagesRecipe",imagesRecipe);
	Titanium.App.Properties.setList("recipeRows",rows);
	
	loadingWindow.hide();
	ind.hide();
}

// load cached recipes
recipeTable.setData(Titanium.App.Properties.getList("recipeRows"));

// create coverflow view with images
var coverFlowWindow = Titanium.UI.createWindow({});
coverFlowWindow.orientationModes = [  
			                		Titanium.UI.LANDSCAPE_LEFT, 
			                		Titanium.UI.LANDSCAPE_RIGHT
			                	];

var imageList2 = Titanium.App.Properties.getList("images");
var imageNameList2 = Titanium.App.Properties.getList("imagesRecipe");

var coverFlowView = Titanium.UI.createCoverFlowView({
	images:imageList2,
	backgroundColor:'#FFF'
});
coverFlowWindow.add(coverFlowView);

var recipeNameLabel = Titanium.UI.createLabel({
	text: 'B52',
	width:300,
	height:15,
	bottom:20,
	color:'#000',
	textAlign:'center'
});
coverFlowWindow.add(recipeNameLabel);

// update label of recipe in cover flow view
coverFlowView.addEventListener('change',function(e){
	recipeNameLabel.text = 'lade Name';
	recipeNameLabel.text = imageNameList2[e.index].title;
});

Ti.Gesture.addEventListener('orientationchange',function(e) {
	if(e.source.isLandscape() && tabGroup.activeTab.title == cocktailTab.title){
		coverFlowWindow.open();
		recipeNameLabel.text = imageNameList2[coverFlowView.selected].title;
	}
	if(e.source.isPortrait() && tabGroup.activeTab.title == cocktailTab.title) {
		coverFlowWindow.close();
	}
});

//click listener - when image is clicked
coverFlowView.addEventListener('click',function(e) {
	cocktailTab.open(showRecipe(Titanium.App.Properties.getList("imagesRecipe")[e.index]), {
		animated : true
	});
	coverFlowWindow.close();
});

// switch views
ind.hide();
loadingWindow.hide();

// open tab group
tabGroup.open();

