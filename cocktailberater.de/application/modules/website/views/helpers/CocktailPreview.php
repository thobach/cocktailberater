<?php

/**
 * Zend_View_Helper_CocktailPreview prints a preview of a cocktail used by search etc
 */

class Zend_View_Helper_CocktailPreview extends Zend_View_Helper_Abstract {
	
	public static $alreadyDisplayed = array();
	
	/**
	 * prints a preview of a cocktail used by search etc
	 *
	 * @param 	Website_Model_Recipe	recipe
	 * @param	int						top 10 position or null
	 * @return 	string 					html content
	 */
	public function cocktailPreview(Website_Model_Recipe $recipe,$top10Position=null) {
		$log = Zend_Registry::get('logger');
		$log->log('cocktailPreview, ID: '.$recipe->id,Zend_Log::DEBUG);
		$this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
		$this->view->dojo()->enable()->requireModule('dijit.Tooltip');
		$photos = Website_Model_Photo::photosByRecipeId($recipe->id,1);
		Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id] = 
			Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id] + 1;
		
		$this->view->headScript()->captureStart(); ?>
dojo.addOnLoad(function() {
	recipe<?php print $recipe->id.'_'.
	Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id];?>Tooltip = new dijit.Tooltip({
		connectId: ["recipe<?php print $recipe->id.'_'.
	Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id] ;?>"],
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
<?php
$this->view->headScript()->captureEnd();
$log->log('cocktailPreview, middle 4',Zend_Log::DEBUG);
?>
<div class="cocktail" style="width: 107px; height: 150px"><a
	href="<?php $uniqueName = $recipe->getUniqueName();
	print $this->view->url(
	array('module'=>'website','controller'=>'recipe','action'=>'get','id'=>$uniqueName),null,true); ?>?format=<?php print Zend_Controller_Front::getInstance()->getRequest()->getParam('format'); ?>"><img
	style="height: 100px;" alt="<?php print $recipe->name; ?>"
	src="<?php print $this->view->baseUrl();
	if(isset($photos[0]) && $photos[0]->id){
		print '/img/recipes/'.$this->view->escape($photos[0]->fileName);
	} else { 
		print '/img/glasses/'.$recipe->getGlass()->getPhoto()->originalFileName;
	} ?>" /></a><a id="recipe<?php print $recipe->id.'_'.
	Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id]; ?>"
	href="<?php print $this->view->url(array('module'=>'website','controller'=>'recipe','action'=>'get','id'=>$uniqueName),null,true); ?>?format=<?php print Zend_Controller_Front::getInstance()->getRequest()->getParam('format'); ?>"
	title="<?php echo $this->view->escape(str_replace('\\','',$recipe->name)) ?>"><img alt="Info"
	src="<?php print $this->view->baseUrl(); ?>/img/info.png" style="vertical-align: top;" height="17" width="20" /></a><br />
<a
	href="<?php print $this->view->url(array('module'=>'website','controller'=>'recipe','action'=>'get','id'=>$uniqueName),null,true); ?>?format=<?php print Zend_Controller_Front::getInstance()->getRequest()->getParam('format'); ?>">
	<?php 
	if($top10Position) {
		print '#'.$top10Position.' - ';
	}
	echo $this->view->escape(str_replace('\\','',$recipe->name));
	if($recipe->isAlcoholic==0){
			echo ' (alkoholfrei)';
	}
	if($recipe->isOriginal==1){
		echo ' (original)';
	}
	if(isset($mode) && $mode=='top10') {
		//print ' - Bewertung: '.number_format($cocktail->bewertungs_summe/$cocktail->bewertungs_anzahl,2,',',NULL);
	}
	?></a></div><?php 
	$log->log('cocktailPreview, exiting',Zend_Log::DEBUG);
}

}
