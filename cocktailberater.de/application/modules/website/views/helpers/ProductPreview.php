<?php

/**
 * Zend_View_Helper_ProductPreview prints a preview of a product
 */

class Zend_View_Helper_ProductPreview extends Zend_View_Helper_Abstract {
	
	public static $alreadyDisplayed = array();
	
	/**
	 * prints a preview of a product
	 *
	 * @param 	Website_Model_Product	product
	 * @return 	string 					html content
	 */
	public function productPreview(Website_Model_Product $product) {
		$log = Zend_Registry::get('logger');
		$log->log('productPreview, ID: '.$product->id,Zend_Log::DEBUG);
		$format = Zend_Controller_Front::getInstance()->getRequest()->getParam('format');
		?>
		<li style="height: 80px; padding-top: 1em; padding-bottom: 1em;">
		<a href="<?php echo $this->view->url(array('controller'=>'product',
		'action'=>'get','module'=>'website','id'=>$product->getUniqueName())); ?>" 
		style="margin-right: 1em; width: 80px; height: 80px; display: block; float: left; text-align: center;"><img
		src="<?php echo ($product->getImage() ? $product->getImage() : '/img/bottle.png'); ?>"
		style="max-width: 80px; max-height: 80px;" 
		alt="<?php echo $product->getManufacturer()->name.' '.$product->name; ?>" /></a>
		
		<span class="pink">Produkt: </span><a href="<?php echo $this->view->url(
		array('controller'=>'product','action'=>'get','module'=>'website',
		'id'=>$product->getUniqueName())); ?>"><?php echo $product->name; ?></a><?php 
		if ($product->getManufacturer()){ ?>
			von <a href="<?php echo $this->view->url(
			array('controller'=>'manufacturer','action'=>'get','module'=>'website',
			'id'=>$product->getManufacturer()->getUniqueName())); ?>"><?php 
			echo $product->getManufacturer()->name; ?></a><?php
		}
		?>, Menge: <?php
		print number_format($product->size,2, ",", ".").' '.
			($product->unit=='l' ? 'Liter' : $product->unit); 
		if($product->caloriesKcal){ 
		?>, Kalorien: <?php print $product->caloriesKcal; ?> kcal<?php 
		}
		if($product->getAveragePrice()>0){ 
			?>, Durchschnittspreis: <?php echo number_format($product->getAveragePrice(),2, ",", "."); ?>&nbsp;€<?php 
		} ?>
		<br />
		<span class="pink">Zutat: </span><a href="<?php echo $this->view->url(
		array('controller'=>'ingredient','action'=>'get','module'=>'website',
		'id'=>$product->getIngredient()->getUniqueName())); ?>"><?php
		echo $product->getIngredient()->name; ?></a>
	</li><?php 
	$log->log('cocktailPreview, exiting',Zend_Log::DEBUG);
}

}
