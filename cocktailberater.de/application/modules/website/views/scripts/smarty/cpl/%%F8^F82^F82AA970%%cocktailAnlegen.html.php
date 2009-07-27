<?php /* Smarty version 2.6.18, created on 2008-02-03 17:24:24
         compiled from admin/cocktailAnlegen.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_checkboxes', 'admin/cocktailAnlegen.html', 220, false),array('function', 'html_options', 'admin/cocktailAnlegen.html', 317, false),)), $this); ?>
<form action="" method="post" enctype="multipart/form-data" name="cocktailAnlegenForm">

<script type="text/javascript">

var Zeile = 0;

function ZeileEinfuegen ()  {
  
  if (Zeile == 0)
  document.getElementById("rezept_zutaten_table").deleteRow(0);
  var TR = document.getElementById("rezept_zutaten_table").insertRow(Zeile);
  Zeile += 1;
  var TD = document.createElement("td");
  TD.className = "tabledesign";
  
  // Zutatendropdown initialisieren
  
  var TD_zutaten = document.createElement("td");
  TD_zutaten.className = "tabledesign";
  var Opt2_Elem = document.createElement("select");
  var Opt2_Attr = document.createAttribute("id");
  var Opt2_Attr1 = document.createAttribute("onchange");
  var Opt2_Attr2 = document.createAttribute("name");
  Opt2_Attr1.nodeValue = 'updateEinheit()';
  Opt2_Attr.nodeValue = "zutatendropdown-"+Zeile;
  Opt2_Attr2.nodeValue = "zutat-"+Zeile;
  Opt2_Elem.setAttributeNode(Opt2_Attr); 
  Opt2_Elem.setAttributeNode(Opt2_Attr1); 
  Opt2_Elem.setAttributeNode(Opt2_Attr2); 
  var Opt21_ElemOpt = document.createElement("option");
  var Opt21_Attr = document.createAttribute("value");	
  Opt21_Attr.nodeValue = "-1";
  Opt21_ElemOpt.setAttributeNode(Opt21_Attr);
  var Opt21_Text = document.createTextNode("Auswahl...");
  Opt21_ElemOpt.appendChild(Opt21_Text);
  Opt2_Elem.appendChild(Opt21_ElemOpt);
  
  // Einheiten + Mengenfeld
  
	var TD_Menge = document.createElement("td");
	TD_Menge.className = "tabledesign";
	var TD_Menge_id = document.createAttribute("id");
	TD_Menge_id.nodeValue = "mengentab-"+Zeile;
	TD_Menge.setAttributeNode(TD_Menge_id);
	var Opt3_Elem = document.createElement("input");
	var Opt3_Attr = document.createAttribute("type");
	var Opt3_Attr1 = document.createAttribute("id");
	var Opt3_Attr2 = document.createAttribute("size");
	var Opt3_Attr3 = document.createAttribute("name");
	Opt3_Attr.nodeValue = "textfield";
	Opt3_Attr1.nodeValue = "menge-"+Zeile;
	Opt3_Attr3.nodeValue = "menge-"+Zeile;
	Opt3_Attr2.nodeValue = "5";
	Opt3_Elem.setAttributeNode(Opt3_Attr); 
	Opt3_Elem.setAttributeNode(Opt3_Attr1); 
	Opt3_Elem.setAttributeNode(Opt3_Attr2); 
	Opt3_Elem.setAttributeNode(Opt3_Attr3); 
	var Opt3_Text = document.createTextNode(" Menge");
  
  // Zutaten-Kategorien
  
  var Opt1_Elem = document.createElement("select");
  var Opt1_Attr = document.createAttribute("id");
  var Opt1_Attr2 = document.createAttribute("name");
  var Opt1_Attr1 = document.createAttribute("onchange");
  Opt1_Attr1.nodeValue = 'updateZutatenDropdown()';
  Opt1_Attr.nodeValue = "zutaten-kategorie-"+Zeile;
  Opt1_Attr2.nodeValue = "zutaten-kategorie-"+Zeile;
  Opt1_Elem.setAttributeNode(Opt1_Attr); 
  Opt1_Elem.setAttributeNode(Opt1_Attr1); 
  Opt1_Elem.setAttributeNode(Opt1_Attr2); 
  var Opt1_Options = new Array();
  var Opt1_Options_Values = new Array();

<?php $_from = $this->_tpl_vars['zutatenKategorien']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['foo']):
?>
	Opt1_Options[<?php echo $this->_tpl_vars['id']; ?>
] = "<?php echo $this->_tpl_vars['foo']['zutaten_kategorie_name']; ?>
";
	Opt1_Options_Values[<?php echo $this->_tpl_vars['id']; ?>
] = "<?php echo $this->_tpl_vars['foo']['idzutaten_kategorie']; ?>
";
<?php endforeach; endif; unset($_from); ?>  
 
  var count = 0;
		var Opt11_ElemOpt = document.createElement("option");
		var Opt11_Attr = document.createAttribute("value");	
		Opt11_Attr.nodeValue = "-1";
		Opt11_ElemOpt.setAttributeNode(Opt11_Attr);
		var Opt11_Text = document.createTextNode("Auswahl..");
		Opt11_ElemOpt.appendChild(Opt11_Text);
		Opt1_Elem.appendChild(Opt11_ElemOpt);
	
	while (Opt1_Options[count]) {
		var Opt11_ElemOpt = document.createElement("option");
		var Opt11_Attr = document.createAttribute("value");	
		var Opt11_Attr1 = document.createAttribute("id");	
		Opt11_Attr.nodeValue = Opt1_Options_Values[count];
		Opt11_Attr1.nodeValue = "zutatenkategorie_" + Zeile + "_" + Opt1_Options_Values[count];
		Opt11_ElemOpt.setAttributeNode(Opt11_Attr);
		Opt11_ElemOpt.setAttributeNode(Opt11_Attr1);
		var Opt11_Text = document.createTextNode(Opt1_Options[count]);
		Opt11_ElemOpt.appendChild(Opt11_Text);
		Opt1_Elem.appendChild(Opt11_ElemOpt);
		count++;
	}
	
	
	TD_Menge.appendChild(Opt3_Elem);	
	TD_Menge.appendChild(Opt3_Text);
	TD_zutaten.appendChild(Opt2_Elem);
	TD.appendChild(Opt1_Elem);
	TR.appendChild(TD);
	TR.appendChild(TD_zutaten);
	TR.appendChild(TD_Menge);
	
	
	//TR.appendChild(TDlast);
}

function updateZutatenDropdown ()  {

  // Zutatendropdown aktualisieren
  
  var zutatendropdown = document.getElementById("zutatendropdown-"+Zeile);
  var zutatenkategorie = document.getElementById("zutaten-kategorie-"+Zeile).selectedIndex;
  var count = 1;
  
  //  Selectbox leeren
  while (document.getElementById("zutat_" + Zeile + "_" + count)) {
   document.getElementById("zutatendropdown-"+Zeile).removeChild(document.getElementById("zutat_" + Zeile + "_" + count));
	 count++;
  }
  
  // Werte von Smarty übernehmen
  
    var Zutaten_Options = new Array();
	var Zutaten_Options_To_Kats = new Array();
	
<?php $_from = $this->_tpl_vars['zutaten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['foo']):
?>
	Zutaten_Options[<?php echo $this->_tpl_vars['foo']['idzutat']; ?>
] = "<?php echo $this->_tpl_vars['foo']['zutat_name']; ?>
";
<?php endforeach; endif; unset($_from); ?>  
 
<?php $_from = $this->_tpl_vars['zutatenToKategorien']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['foo']):
?>
	Zutaten_Options_To_Kats[<?php echo $this->_tpl_vars['foo']['idzutat']; ?>
] = "<?php echo $this->_tpl_vars['foo']['idzutaten_kategorie']; ?>
";
<?php endforeach; endif; unset($_from); ?> 

var Zutaten_Ausgabe = new Array();

// Ausgabearray füllen

for ( key in Zutaten_Options_To_Kats ) 
{
   if (Zutaten_Options_To_Kats[key] == zutatenkategorie) 
   {
	   Zutaten_Ausgabe[key] = Zutaten_Options[key];
   }
}

 // Selectbox erzeugen
 
 for ( key in Zutaten_Ausgabe ) {
		var Opt11_ElemOpt = document.createElement("option");
		var Opt11_Attr = document.createAttribute("value");	
		var Opt11_Attr1 = document.createAttribute("id");	
		Opt11_Attr.nodeValue = key;
		Opt11_Attr1.nodeValue = "zutat_" + Zeile + "_" + key;
		Opt11_ElemOpt.setAttributeNode(Opt11_Attr);
		Opt11_ElemOpt.setAttributeNode(Opt11_Attr1);
		var Opt11_Text = document.createTextNode(Zutaten_Ausgabe[key]);
		Opt11_ElemOpt.appendChild(Opt11_Text);
		zutatendropdown.appendChild(Opt11_ElemOpt);
		count++;
	}
	

  
}

function deleteRow ()  {
  
}

function updateEinheit ()  {
  
    // Werte von Smarty übernehmen
var zutatendropdown = document.getElementById("zutatendropdown-"+Zeile).selectedIndex;   
var td_menge = document.getElementById("mengentab-"+Zeile);
var Zutaten_To_Einheiten = new Array();
 
<?php $_from = $this->_tpl_vars['zutaten']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['foo']):
?>
	Zutaten_To_Einheiten[<?php echo $this->_tpl_vars['foo']['idzutat']; ?>
] = "<?php echo $this->_tpl_vars['foo']['zutat_einheit']; ?>
";
<?php endforeach; endif; unset($_from); ?> 
  td_menge.removeChild(td_menge.lastChild);
  //document.getElementById("menge-"+Zeile).removeChild(document.getElementById("menge-"+Zeile).lastChild);
  var Opt3_Text = document.createTextNode(Zutaten_To_Einheiten[zutatendropdown]);
  td_menge.appendChild(Opt3_Text);
}

</script>


<div>

<h2>Cocktail:</h2>

<?php $this->assign('feld', 'cocktail_name'); ?>
<div class="box">
<strong>Name:</strong></div>
<div>
<input type="text" name="<?php echo $this->_tpl_vars['feld']; ?>
" value="<?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
">
</div>

<?php $this->assign('feld', 'cocktail_beschreibung'); ?>
<div class="box">
<strong>Beschreibung:</strong></div>
<div><textarea name="<?php echo $this->_tpl_vars['feld']; ?>
" cols="50" rows="4"><?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
</textarea>
</div>
</div>


<?php $this->assign('feld', 'cocktail_kategorie'); ?>
<div class="box"><strong>Kategorie:</strong></div>
<div>
<?php echo smarty_function_html_checkboxes(array('name' => 'cocktailkategorie','options' => $this->_tpl_vars['cocktailkategorienoptions']), $this);?>


</div>

<br>




<div>  

<h2>Rezept:</h2> 
     
  <div class="box">
<?php $this->assign('feld', 'rezept_name'); ?>
<strong>Name:</strong>
<input type="text" name="<?php echo $this->_tpl_vars['feld']; ?>
" value="<?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
">
</div>
<br>
<div class="sectionborder">
<strong>Zutaten:</strong>
<br>
<?php $this->assign('feld', 'rezept_zutaten'); ?>


<br>
<table id="rezept_zutaten_table" width="100%" border="0" cellpadding="5" cellspacing="1">
<tr><td class="tabledesign">Zutatentabelle</td></tr>
</table>
<br>

<input type="button" value="Zutat hinzuf&uuml;gen " onclick="ZeileEinfuegen()"><br>


</div>


<?php $this->assign('feld', 'rezept_anweisung'); ?>
<div class="box">
<strong>Anweisung:</strong></div>
<div><textarea name="<?php echo $this->_tpl_vars['feld']; ?>
" cols="50" rows="4"><?php echo $this->_tpl_vars['felder'][$this->_tpl_vars['feld']]['value']; ?>
</textarea>
</div>
</div>

  <div class="box">
<?php $this->assign('feld', 'rezept_zeitaufwand'); ?>
<strong>Zeitaufwand:</strong>
<select name="<?php echo $this->_tpl_vars['feld']; ?>
">
      <option value="1">1 Min.</option>
	  <option value="2">2 Min.</option>
	  <option value="5">5 Min.</option>
	  <option value="7">7 Min.</option>
	  <option value="10">10 Min.</option>
	  <option value="15">15 Min.</option>
</select>
</div>

 <div class="box">
<?php $this->assign('feld', 'rezept_hat_alkohol'); ?>
<strong>Alkoholhaltig:</strong>
<input type="radio" name="<?php echo $this->_tpl_vars['feld']; ?>
" value="1"> ja 
<input type="radio" name="<?php echo $this->_tpl_vars['feld']; ?>
" value="0"> nein 
</div>

<div class="box"><strong>Glas:</strong>
<?php echo smarty_function_html_options(array('name' => 'glas','options' => $this->_tpl_vars['glaeseroptions']), $this);?>

</div>


 <div class="box">
<?php $this->assign('feld', 'rezept_schwierigkeitsgrad'); ?>
<strong>Schwierigkeitsgrad:</strong>
<input type="radio" name="<?php echo $this->_tpl_vars['feld']; ?>
" value="0">Anf&auml;nger 
<input type="radio" name="<?php echo $this->_tpl_vars['feld']; ?>
" value="1">Normal
<input type="radio" name="<?php echo $this->_tpl_vars['feld']; ?>
" value="2">Profi
</div>

 <div class="box">
<?php $this->assign('feld', 'rezept_foto'); ?>
<strong>Foto:</strong>
<input type="file" name="rezept_foto">
</div>

 <div class="box">
<?php $this->assign('feld', 'cocktail_anlegen'); ?>
<br><br>

<input type="submit" name="cocktail_anlegen" value="Cocktail speichern">
</div>

</div>
</form>