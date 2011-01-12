/**
 * random cocktail view - shake your own random cocktail
 */

var random_timestamp = 0;
var old_x = 0.0;
var old_y = 0.0;
var in_random_tab = false;

if (Titanium.Media.audioPlaying) {
	Titanium.Media.defaultAudioSessionMode = Titanium.Media.AUDIO_SESSION_MODE_AMBIENT;
}

//load shake sound
var sound = Titanium.Media.createSound({
	url:'sound/shaker.wav'
});

tabGroup.addEventListener('focus', function(e){
	setTimeout(function() {
		//Titanium.API.info("Tab: " + e.index);
		if(e.index == 1) {
			//Titanium.API.info("Zufallscocktail Tab geöffnet");	
			in_random_tab = true;
		} else {
			in_random_tab = false;
		}
	},100);
});

Ti.Gesture.addEventListener('shake',function(e) {
	
	//Titanium.API.info("shake");
	
	// open random cocktail when shaking in random cocktail tab
	if(in_random_tab){
		//Titanium.API.info("shake im Zufallscocktail Tab");
		
		// play shake sound
		sound.play();
		
		// find random recipe and show it in the random cocktail tab
		var numberOfRecipes = Titanium.App.Properties.getList("recipeRows").length;
		var randomRecipeNumber = Math.floor(Math.random()*numberOfRecipes);
		var randomRecipe = Titanium.App.Properties.getList("recipeRows")[randomRecipeNumber].recipe;
		var randomRecipeView = showRecipe(randomRecipe);
		randomTab.open(randomRecipeView);
		
	}
});

/*Ti.Accelerometer.addEventListener('update',function(e){
	if(in_random_tab){
		
		// check if movement in x or y direction (up/down or left/right)
		if( ( (old_x - e.x > 0.35) || (old_x + e.x > 0.35) || (old_y - e.y > 0.35) || (old_y + e.y > 0.35) ) && e.timestamp > (random_timestamp + 300) ) {
			
			Titanium.API.info("sound");
			
			// don't play song when opening random tab the first time
			if(old_x != 0.0){
				sound.play();
			}
			
			// update current position and time
			random_timestamp = e.timestamp;
			old_x = e.x;
			old_y = e.y;
		}
	}
});*/
