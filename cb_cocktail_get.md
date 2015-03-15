**Funktion**

Liefert einen Cocktail mit Rezepten und detaillierten Infos anhand der ID


**Entwicklungsstatus**

**Priorität 1**


**Authentifizierung**

Für diese Methode ist keine Authentifizierung erforderlich.


**Argumente**

  * idcocktail (erforderlich)
    * eine Ganzzahl, welche die ID des Cocktails repräsentiert


**Beispielanfrage**

http://api.cocktailberater.de/services/rest/cb-cocktail-get/idcategory/12


**Beispielantwort**

```
<rsp stat="ok">
     <cocktail name="Sex on the Beach" idcocktail="21" insertdate="2007-05-11" updatedate="2007-05-11">
       <recipes count="2">
         <recipe idrecipe="18" idcustomer="2" name="Sex on the Beach Basic" description="leckerer Cocktail aus ..."  insertdate="2007-05-11" updatedate="2007-05-11" source="1000 Cocktails, Franz Brandl, südwest Verlag, 2007" work_min="5" difficulty_degree="2" is_base_recipe="0" is_alcoholic="1" alcohol_level="2.5" calories_kcal="220" volume_cl="19" price="2.50" instruction="Alle Zutaten auf Eis im Shaker mischen und anschließend in ein Longdrinkglas geben. Als Dekoration sind verschiedene Früchte möglich (z. B. Honigmelone).">
           <glass idglass="6" name="großer Tumbler" description="" glass_volume_cl="30">
             <photo idphoto="38" idphoto_category="2" url_img="http://www.cocktailberater.de/photos/glass/collins.png" name="Collins / Tumbler" description=""/>
           </glass>
           <ingredients count="4">
             <ingredient idingredient="1" amount="4" unit="cl" name="Wodka"/>
             <ingredient idingredient="19" amount="3" unit="cl" name="Pfirsichlikör"/>
             <ingredient idingredient="16" amount="6" unit="cl" name="Ananassaft"/>
             <ingredient idingredient="15" amount="6" unit="cl" name="Cranberrysaft"/>
           </ingredients>
           <photos count="1">
             <photo idphoto="18" idphoto_category="1" url_img="http://www.cocktailberater.de/photos/recipe/18_original.jpg" name="Sex on the Beach Basic" description=""/>
           </photos>
           <videos count="1">
             <video idvideo="3" description="cocktail Sex on the beach" url_swf="http://www.youtube.com/v/AaWIvUmw1XY"/>
           </videos>
           <tags count="3">
             <tag idtag="1" description="fruchtig"/>
             <tag idtag="2" description="süß"/>
             <tag idtag="46" description="Wodka"/>
           </tags>
         </recipe>
         <recipe ... price="3.20">
           ...
         </recipe>
       </recipes>
       <description>
         Sinneserlebnis der besonderen Art! Der Cocktail holt dir die schönste Nebensache der Welt vom Strand ins Wohnzimmer...
       </description>
       <categories>
         <category name="fruchtig" idcocktail_category="2"/>
         <category name="süß" idcocktail_category="6"/>
       </categories>
     </cocktail>
</rsp>
```


**Fehlercodes**

  * ...: ...
    * ...