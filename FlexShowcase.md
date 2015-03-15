## Flex Showcase ##

Kleines Beispiel für Flex
```
	<mx:Canvas width="100%" height="100%" xmlns:mx="http://www.adobe.com/2006/mxml">
		<mx:Script>
				<![CDATA[
				import mx.rpc.events.ResultEvent;
				import mx.controls.Alert;
				
				public function newCocktail(s:String):void {
					userRequestGet.url="http://thobach.dyndns.org/cocktailberater.de/html/api/cocktail/get/id/"+s;
					userRequestGet.send();
				}

				]]>
		</mx:Script>
		<mx:VBox width="100%" height="100%">
			<mx:HBox><mx:Label id="l_name" text="Name" /><mx:Label id="l_name_value" data="{userRequestGet.lastResult.cocktail.@name}" /></mx:HBox>
			<mx:HBox><mx:Label id="l_recipe" text="Recipe" /><mx:Label id="l_recipe_value" data="{userRequestGet.lastResult.cocktail.recipes.recipe.@name}"/></mx:HBox>
			<mx:HBox><mx:Label id="l_glass" text="Glass" /><mx:Label id="l_glass_value" data="{userRequestGet.lastResult.cocktail.recipes.recipe.glass.@name}"/><mx:Image source="{userRequestGet.lastResult.cocktail.recipes.recipe.glass.photo.@url}" /></mx:HBox>
			<mx:DataGrid dataProvider="{userRequestGet.lastResult.cocktail.recipes.recipe.components.component}" columnCount="15">
				<mx:columns>
					<mx:DataGridColumn dataField="@name" headerText="Component" />
					<mx:DataGridColumn dataField="@amount" headerText="Amount" />
					<mx:DataGridColumn dataField="@unit" headerText="Unit" />
				</mx:columns>
			</mx:DataGrid>
			<mx:TextArea width="100%" height="100%" text="{userRequestGet.lastResult.cocktail.recipes.recipe.@instruction}" />
		</mx:VBox>
		

	<mx:HTTPService id="userRequestGet" resultFormat="e4x" useProxy="false" method="GET">
    </mx:HTTPService>	
		
		
	</mx:Canvas>
```

Das oben gezeigte code Beispiel bekommt von der Tabelle links ein Event wenn eine andere Bestellung gewählt wurde (newCocktail) und zeigt dann das Rezept an.

![http://wiki.cocktailberater.de/images/6/68/Flex_test.jpg](http://wiki.cocktailberater.de/images/6/68/Flex_test.jpg)

Der Code für den Rest (die Tabs, das DataGrid mit den Bestellungen usw):

```
<?xml version="1.0"?>
<mx:Application xmlns:mx="http://www.adobe.com/2006/mxml" xmlns:MyComp="*">
	<mx:TabNavigator width="100%" height="100%" borderStyle="solid" >
		<mx:VBox label="Cocktails ausliefern" width="100%" height="100%">
			<mx:HBox width="99%">
            <mx:DataGrid id="dg1" width="45%" change="{cocktailView1.newCocktail(dg1.selectedItem.Id.toString())}"> 
				<mx:ArrayCollection>
				 <mx:Object>
					<mx:Id>15</mx:Id>
					<mx:Cocktail>Magic Queen</mx:Cocktail>
					<mx:Gast>Hans</mx:Gast>
				 </mx:Object>
				 <mx:Object>
					<mx:Id>17</mx:Id>
					<mx:Cocktail>Pina Colada</mx:Cocktail>
					<mx:Gast>Sepp</mx:Gast>
				 </mx:Object>
				</mx:ArrayCollection>
				<mx:columns>
					<mx:DataGridColumn dataField="Cocktail" headerText="Cocktail" />
					<mx:DataGridColumn dataField="Gast" headerText="Gast" />
				</mx:columns>
			</mx:DataGrid>
			
			<MyComp:CocktailView  width="100%" id="cocktailView1" />
				
		</mx:HBox>	
        </mx:VBox>
		<mx:VBox label="Kasse" 
            width="100%" 
            height="100%">
        </mx:VBox>

	</mx:TabNavigator >
</mx:Application>
```