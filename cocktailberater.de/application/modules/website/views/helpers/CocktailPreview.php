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
	 * @param 	boolean					true if select box should be displayed under the recipe
	 * @param	array					array with recipes which checkboxes' should be checked 
	 * @return 	string 					html content
	 */
	public function cocktailPreview(Website_Model_Recipe $recipe,$top10Position = null, $selectable = false, $preselected = null) {
		$log = Zend_Registry::get('logger');
		$log->log('cocktailPreview, ID: '.$recipe->id,Zend_Log::DEBUG);
		$format = Zend_Controller_Front::getInstance()->getRequest()->getParam('format');
		// no dojo/js for touch devices
		if($format != 'mobile'){
			$this->view->addHelperPath('Zend/Dojo/View/Helper/', 'Zend_Dojo_View_Helper');
			$this->view->dojo()->enable()->requireModule('dijit.Tooltip');
		}
		$photos = Website_Model_Photo::photosByRecipeId($recipe->id,1);
		Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id] = 
			Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id] + 1;
		
		if(!Wb_Controller_Plugin_Layout::requestFromTouchDevice()){
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
		} // end no JS for touch devices
		
$uniqueName = $recipe->getUniqueName();
?>
<div class="cocktail<?php if($selectable){ ?> center selectable<?php } ?>"><?php 
	if(!$selectable){ ?><a
		href="<?php 
			print $this->view->url(array('module'=>'website','controller'=>'recipe',
			'action'=>'get','id'=>$uniqueName),'recipe',true); ?>?format=<?php 
			print Zend_Controller_Front::getInstance()->getRequest()->getParam('format'); ?>"><?php 
	} else { ?><label for="recipe_<?php print $recipe->id; ?>"><?php } ?><img
	style="height: 100px;" alt="<?php print $recipe->name; ?>"
	src="<?php print $this->view->baseUrl();
	if(isset($photos[0]) && $photos[0]->id){
		print '/img/recipes/'.$this->view->escape($photos[0]->fileName);
	} else { 
		print '/img/glasses/'.$recipe->getGlass()->getPhoto()->originalFileName;
	} ?>" /><?php if(!$selectable){ ?></a><?php }
	if(!Wb_Controller_Plugin_Layout::requestFromTouchDevice() && !$selectable){ ?><a id="recipe<?php print $recipe->id.'_'.
	Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id]; ?>"
	href="<?php print $this->view->url(array('module'=>'website',
		'controller'=>'recipe','action'=>'get','id'=>$uniqueName),'recipe',true); ?>?format=<?php 
	print Zend_Controller_Front::getInstance()->getRequest()->getParam('format'); ?>"
	title="<?php echo $this->view->escape(str_replace('\\','',$recipe->name)) ?>"><img alt="Info"
	src="<?php print $this->view->baseUrl(); ?>/img/info.png" 
	style="vertical-align: top;" height="17" width="20" /></a><?php } ?><br /><?php 
	if(!$selectable){ ?><a
		href="<?php print $this->view->url(array('module'=>'website',
			'controller'=>'recipe','action'=>'get','id'=>$uniqueName),'recipe',true); ?>?format=<?php 
		print Zend_Controller_Front::getInstance()->getRequest()->getParam('format'); ?>"><?php 
	} else { 
	}
	if($top10Position) {
		print '#'.$top10Position.' - ';
	}
	echo $this->view->escape(str_replace('\\','',$recipe->name));
	if($recipe->isAlcoholic==0){
			echo ' (alkoholfrei)';
	}
	if($recipe->isOriginal==1){
		echo ' (orig.)';
	}
	if(isset($mode) && $mode=='top10') {
		//print ' - Bewertung: '.number_format($cocktail->bewertungs_summe/$cocktail->bewertungs_anzahl,2,',',NULL);
	}
	if(!$selectable){ 
		?></a><?php 
	} else {
		if($format != 'mobile'){ 
		?>&nbsp;<span id="recipe<?php 
		print $recipe->id.'_'.
	Zend_View_Helper_CocktailPreview::$alreadyDisplayed[$recipe->id]; ?>"
	href="<?php print $this->view->url(array('module'=>'website',
		'controller'=>'recipe','action'=>'get','id'=>$uniqueName),'recipe',true); ?>?format=<?php 
	print Zend_Controller_Front::getInstance()->getRequest()->getParam('format'); ?>"
	title="<?php echo $this->view->escape(str_replace('\\','',$recipe->name)) ?>" class="pointer"><img alt="Info"
		src="<?php print $this->view->baseUrl(); ?>/img/info.png" 
		style="vertical-align: top;" height="17" width="20" /></span><?php 
		}
		?></label><br />
		<input type="checkbox" name="recipe2menu[]" value="<?php print $recipe->id; ?>" 
		<?php if($preselected[$recipe->id]) { print ' checked="checked"'; } ?> id="recipe_<?php print $recipe->id; ?>" /><?php 
	} ?></div><?php 
	$log->log('cocktailPreview, exiting',Zend_Log::DEBUG);
}

}
