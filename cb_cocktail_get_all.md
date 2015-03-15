**Funktion**

Liefert alle/gesuchte Cocktails mit Rezepten und detaillierten Infos


**Entwicklungsstatus**

**Priorität 1**


**Authentifizierung**

Für diese Methode ist keine Authentifizierung erforderlich.


**Argumente**

  * name (optional)
    * eine String, welcher den Namen repräsentiert
  * ingredient (optional)
    * eine String, welcher eine Zutat repräsentiert
  * tag (optional)
    * eine String, welcher einen Tag repräsentiert
  * category (optional)
    * eine String, welcher die Kategorie repräsentiert
  * alcoholic (optional)
    * 'yes' oder 'no'
  * language (optional)
    * eine dreistellige Zeichenkette, nach [IST 639-3](http://www.sil.org/iso639-3/codes.asp)
    * Standardwert: 'deu' für deutsch
  * api-key (optional)
    * ihr [API-Schlüssel](API_Key.md)

**Beispielanfrage**

http://api.cocktailberater.de/services/rest/cb-cocktail-get-all/name/Sex%20on%the%Beach oder /ingredient/Rum oder /tag/süß oder /category/fruchtig oder /alcoholic/yes


**Beispielantwort**

```
<rsp stat="ok">
   <cocktails count="2">
     <cocktail name="Sex on the Beach" idcocktail="21" date="2007-05-11" price="2.50">
       <recipes count="2">
         <recipe idrecipe="18" idcustomer="2" name="Sex on the Beach Basic" source="1000 Cocktails, Franz Brandl, südwest Verlag, 2007" work_min="5" difficulty_degree="2" is_base_recipe="0" is_alcoholic="1" alcohol_level="2.5" calories_kcal="220" volume_cl="19" price="2.50">
           <glass idglass="6" name="großer Tumbler" description="" glass_volume_cl="30">
             <photo idphoto="38" idphoto_category="2" url_img="http://www.cocktailberater.de/photos/glass/collins.png" name="Collins / Tumbler" description=""/>
           </glass>
           <instruction>
              Alle Zutaten auf Eis im Shaker mischen und anschließend in ein Longdrinkglas geben. Als Dekoration sind verschiedene Früchte möglich (z. B. Honigmelone).
           </instruction>
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
     <cocktail>
       ...
     </cocktail>
   </cocktails>
</rsp>
```


**Fehlercodes**

  * ...: ...
    * ...