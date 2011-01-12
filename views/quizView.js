/**
 * 
 */

function getRandomRecipe() {
	var recipeRows = Titanium.App.Properties.getList("recipeRows");
	return recipeRows[Math.floor(Math.random() * recipeRows.length)].recipe;
}

var randomRecipe;

var correctAnswerClicked = false;

var positionOfSolution = 0;
var positionOfFalseAnswer1 = 0;
var positionOfFalseAnswer2 = 0;

var solution1 = Titanium.UI.createButton({
	title : 'lade Lösung',
	bottom : 20 + positionOfSolution * 60,
	height : 40,
	width : 260
});
quizWindow.add(solution1);

var solution2 = Titanium.UI.createButton({
	title : 'lade Lösung',
	bottom : 20 + positionOfFalseAnswer1 * 60,
	height : 40,
	width : 260
});
quizWindow.add(solution2);

var solution3 = Titanium.UI.createButton({
	title : 'lade Lösung',
	bottom : 20 + positionOfFalseAnswer2 * 60,
	height : 40,
	width : 260
});
quizWindow.add(solution3);

function recalcPosition(){
	positionOfSolution = Math.round(Math.random() * 3.0);
	positionOfFalseAnswer1 = 0;
	positionOfFalseAnswer2 = 0;
	if (positionOfSolution == 2) {
		positionOfFalseAnswer1 = 0;
		positionOfFalseAnswer2 = 1;
	} else if (positionOfSolution == 1) {
		positionOfFalseAnswer1 = 0;
		positionOfFalseAnswer2 = 2;
	} else if (positionOfSolution == 0) {
		positionOfFalseAnswer1 = 1;
		positionOfFalseAnswer2 = 2;
	}
	
	solution1.bottom = 20 + positionOfSolution * 60;
	solution2.bottom = 20 + positionOfFalseAnswer1 * 60;
	solution3.bottom = 20 + positionOfFalseAnswer2 * 60;
}

recalcPosition();

var a = Titanium.UI.createAlertDialog({
	title : 'Richtig!',
	message : 'Nächste Frage?'
});

var questionText = Ti.UI.createLabel({
	text : "Wer bin ich?",
	font : {
		fontSize : 18
	},
	width : 280,
	height : 'auto',
	top : 10
});

var questionText2 = Ti.UI.createLabel({
	text : '',
	font : {
		fontSize : 16
	},
	width : 260,
	top : 60,
	height : 'auto'
});

quizWindow.add(questionText);
quizWindow.add(questionText2);

function loadQuiz() {
	
	randomRecipe = getRandomRecipe();
	solution1.title = randomRecipe.title;
	
	falseAnswer1 = getRandomRecipe().title;
	solution2.title = falseAnswer1;
	
	falseAnswer2 = getRandomRecipe().title;
	solution3.title = falseAnswer2;
	
	recalcPosition();

	var componentString = '';
	var components = randomRecipe.components;
	for ( var i = 0; i < components.length; i++) {
		componentString += components[i].name;
		if (i != components.length - 1) {
			componentString += ", ";
		}
	}

	questionText2.text = componentString;
	
}

a.addEventListener('click', function(e) {
	if(e.index == 0 && correctAnswerClicked == true){
		loadQuiz();
	}
});

solution1.addEventListener('click', function() {
	correctAnswerClicked = true;
	a.title = 'Gratulation';
	a.message = 'Richtig! Nächste Frage?';
	a.buttonNames = ['Ja','Nein'];
	a.cancel = 1;
	a.show();
});

solution2.addEventListener('click', function() {
	correctAnswerClicked = false;
	a.title = 'Schade';
	a.message = 'Leider Falsch!';
	a.buttonNames = ['OK'];
	a.cancel = 0;
	a.show();
});

solution3.addEventListener('click', function() {
	correctAnswerClicked = false;
	a.title = 'Schade';
	a.message = 'Leider Falsch!';
	a.buttonNames = ['OK'];
	a.cancel = 0;
	a.show();
});

tabGroup.addEventListener('focus', function(e) {
	setTimeout(function() {
		if (e.index == 5) {
			loadQuiz();
		}
	}, 100);
});
