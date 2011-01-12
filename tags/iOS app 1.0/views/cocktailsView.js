/**
 * 
 */
function showRecipe (recipe){
	if (recipe) {
		var win = Titanium.UI.createWindow({
			title : recipe.title
		});

		var recipeTable = Titanium.UI.createTableView();
		win.add(recipeTable);

		var alcoholic = '';
		if (recipe.isAlcoholic == '0') {
			alcoholic = ' (alkoholfrei)';
		}

		var componentString = '';
		var components = recipe.components;
		for ( var i = 0; i < components.length; i++) {
			componentString += ' <li>' + components[i].amount + ' ' + components[i].unit + ' ' + components[i].name + '</li>';
		}

		if (recipe.photos != null
				& recipe.photos.length > 0) {

			var imageView = Titanium.UI.createImageView({
				image : recipe.photos[0].url,
				top : 20,
				bottom: 20,
				height : 200,
				canScale : true,
				preventDefaultImage : true,
				backgroundColor : '#FFF'
			});
		}
		
		var webview = Titanium.UI.createWebView({html: '<html><body>'+
			'<style>' + 
			'* { font-family: "Lucida Grande", "Lucida Sans", Tahoma, Verdana, sans-serif; }' +
			'ul { margin-left: 0.5em; padding-left: 1em; margin-top: 0; margin-bottom: 0.2em; } ' +
			'.attribut, dt { color: #cf0060; text-align: left; font-weight: normal; margin-bottom: 0.1em; } ' + 
			'div.wert p { margin-bottom: 0.2em; margin-top: 0; } ' + 
			'.wert, dd { margin-bottom: 0.6em; } ' + 
			'</style>' + 
			'<div id="content"><h2 style="margin:0;">' + recipe.title + alcoholic + '</h2>'+ 
			(recipe.photos[0] ? '<div style="text-align: center;"><img src="' + recipe.photos[0].url + '" id="recipe_image" '+
			'	style="width: 160px; margin-bottom: 5px;" /></div>' : '' ) +
			(recipe.description ? '	<div class="attribut">Beschreibung:</div>'+
			'	<div class="wert"><p>' + recipe.description + '</p></div>' : '' ) +
			'	<div class="attribut">Zutaten:</div>'+
			'	<div class="wert">'+
			'	<ul>' + componentString + '</ul></div>'+
			'	<div class="attribut">Zubereitung:</div>'+
			'	<div class="wert"><p>' + recipe.instruction + '</p></div>'+
			(recipe.source ? '	<div class="attribut">Rezeptquelle:</div>'+ 
			'	<div class="wert"><p>' + recipe.source + '</p></div>' : '' ) + 
			'	<div class="attribut" style="clear: both;">Weitere Informationen:</div>' +
			'	<div class="wert">' +
			'	<ul>' +
			'	<li>Zubereitungszeit: ' + recipe.workMin + ' min</li>' +
			'	<li>Schwierigkeitsgrad: ' + recipe.difficulty + '</li>' +
			'	<li>Glas: <img' +
			'		src="' + recipe.glass.photos[0].url + '" />' + recipe.glass.name + '</li>' +
			'		<li>Kalorien: ' + recipe.calories + ' kcal</li> ' +
			'	<li>Alkoholgehalt: ' + recipe.alcoholLevel + ' % Alkohol</li>' +
			'	<li>Volumen: ' + recipe.volume + ' cl</li>'+
			'	</ul>'+
			'	</div> '+
			'	</div>' + 
			'</body></html>'});
		win.add(webview);
		
		return win;
	}
}


//create recipeTable view event listener
recipeTable.addEventListener('click', function(e) {
	cocktailTab.open(showRecipe(e.rowData.recipe), {
		animated : true
	});
});
