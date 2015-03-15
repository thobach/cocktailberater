Im Trunk-Ordner des Subversion Repositoriums befindet sich pro [Teilprojekt](Teilprojekte.md) ein Ordner.

# Struktur #
```
|-branches (leer - bitte Branches vermeiden)
|-tags (momentan noch unbenutzt)
|-trunk (Stammverzeichnis mit allen Unterprojekten)
 |-barclient (Flex/Flash Client für die Küche mit Bestellungen und Kasse)
 |-bestellclient (.Net/C# Client für Küche/Bar und Bestellungen/Kunden)
 |-cocktailberater.de (PHP/ZendFramework Serverkomponente mit Web Portal und API)
 |-CocktailberaterServlet (Java/AppEngine Client der alle Rezepte anzeigt)
 |-status (PHP Statusanzeige für aktuelle Bestellungen)
|-wiki (Google Code Wiki)
```