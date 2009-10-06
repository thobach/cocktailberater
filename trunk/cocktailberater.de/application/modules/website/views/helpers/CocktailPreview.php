<?php

/**
 * Zend_View_Helper_CocktailPreview prints a preview of a cocktail used by search etc
 */

class Zend_View_Helper_CocktailPreview extends Zend_View_Helper_Abstract
{
	/**
	 * prints a preview of a cocktail used by search etc
	 *
	 * @param 	Website_Model_Recipe	recipe
	 * @return 	string 					html content
	 */
	public function cocktailPreview(Website_Model_Recipe $recipe) {
		$this->view->dojo()->enable()->requireModule('dijit.Tooltip');
		$photos = Website_Model_Photo::photosByRecipeId($recipe->id); ?>
		<?php $this->view->headScript()->captureStart(); ?>
		dojo.addOnLoad(function() {
			recipe<?php print $recipe->id ;?>Tooltip = new dijit.Tooltip({
	            connectId: ["recipe<?php print $recipe->id ;?>"],
	            label: "<div class=\"textLeft\"><?php
$components = $recipe->getComponents();
if (is_array ( $components )) {
	foreach ( $components as $component ) {
		print $component->amount ;
		print ' ' ;
		print $component->unit;
		print ' ' ;
		print $component->getIngredient()->name ;
		print '<br />' ;
	}
}
?></div>"
	        });
	    });
		<?php $this->view->headScript()->captureEnd(); ?>
<div id="cocktail" style="width: 100px; height: 150px"><a
	href="<?php print $this->view->url(
	array('module'=>'website','controller'=>'index','action'=>'recipe','cocktail'=>$recipe->name,'id'=>$recipe->id),null,true); ?>"><img
	style="height: 100px;"
	src="<?php print $this->view->baseUrl();
	if(isset($photos[0]) && $photos[0]->id){
		print '/img/recipes/'.$this->view->escape($photos[0]->fileName);
	} else { 
		print '/img/wikilogo.png';
	} ?>" /></a><a id="recipe<?php print $recipe->id; ?>"
	href="<?php print $this->view->url(array('module'=>'website','controller'=>'index','action'=>'recipe','cocktail'=>$recipe->name,'id'=>$recipe->id),null,true); ?>"
	title="<?php echo $this->view->escape(str_replace('\\','',$recipe->name)) ?>"><img
	src="<?php print $this->view->baseUrl(); ?>/img/info.png" align="top" /></a><br />
<a
	href="<?php print $this->view->url(array('module'=>'website','controller'=>'index','action'=>'recipe','cocktail'=>$recipe->name,'id'=>$recipe->id),null,true); ?>">
	<?php if(isset($mode) && $mode=='top10') {
		print '#'.$top.' - ';
	}
	echo $this->view->escape(str_replace('\\','',$recipe->name));
	if(isset($mode) && $mode=='top10') {
		//print ' - Bewertung: '.number_format($cocktail->bewertungs_summe/$cocktail->bewertungs_anzahl,2,',',NULL);
	}
	?></a></div><?php 

}

}
