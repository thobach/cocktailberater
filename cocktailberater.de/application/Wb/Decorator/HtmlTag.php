<?php
/**
 * Zend_Tag_Cloud_Decorator_Tag
 */
class Wb_Decorator_HtmlTag extends Zend_Tag_Cloud_Decorator_HtmlTag
{
	/**
	 * @var Zend_View
	 */
	private $_view;

	public function setView($view){
		if($view instanceof Zend_View){
			$this->_view = $view;
		} else {
			throw new Exception('$view must be an instance of Zend_View');
		}
	}

	/**
	 * Render HtmlTags
	 *
	 * @param  Zend_Tag_ItemList $tags
	 * @return string html code
	 */
	public function render(Zend_Tag_ItemList $tags)
	{
		if(!$this->_view){
			throw new Exception('no view set');
		}

		if (null === ($weightValues = $this->getClassList())) {
			$weightValues = range($this->getMinFontSize(), $this->getMaxFontSize());
		}

		$tags->spreadWeightValues($weightValues);

		$result = array();

		foreach ($tags as $tag) {
			if (null === ($classList = $this->getClassList())) {
				$attribute = sprintf('style="font-size: %d%s;"', $tag->getParam('weightValue'), $this->getFontSizeUnit());
			} else {
				$attribute = sprintf('class="%s"', htmlspecialchars($tag->getParam('weightValue')));
			}

			$this->_view->headScript()->captureStart(); ?>
dojo.addOnLoad(function() { 
	tag<?php print str_replace(array('-',' ','.'),array('_','__','___'),$tag->getTitle());?>Tooltip = new dijit.Tooltip({
		connectId: ["tag<?php print str_replace(array('-',' ','.'),array('_','__','___'),$tag->getTitle());?>"], 
		label: "<?php $list = Website_Model_Recipe::getRecipesByTag ( $tag->getTitle(), 5); ?>
<h2 class=\"pink\" style=\"font-size: 1em; margin:0;\">Rezepte mit dem Tag &quot;<?php echo $tag->getTitle(); ?>&quot;:</h2><?php 
?><ul style=\"text-align:left; width: 14em;\" class=\"clear\"><?php
foreach ($list as $key => /* @var $recipe Website_Model_Recipe */$recipe) {
	$photos = $recipe->getPhotos(1);
	?><li style=\"width: 6em; height: 13em; padding: 0.2em; font-size: 0.7em; clear: none;\" class=\"left center\"><?php 
	?><img style=\"height: 80px; margin-right: 0.5em; margin-top: 0.2em; margin-bottom: 0.2em;\" <?php 
		?>src=\"<?php print $this->_view->baseUrl();
			if(isset($photos[0]) && $photos[0]->id){
				print '/img/recipes/'.$this->_view->escape($photos[0]->fileName);
			} else { 
				$photos[0] = $recipe->getGlass()->getPhoto();
				print '/img/glasses/'.$this->_view->escape($photos[0]->originalFileName);
			} ?>\" /><br /><?php echo $this->_view->escape(str_replace('\\','',$recipe->name)) ?><?php 
	?></li><?php 
} if(count($list)==5){ ?><li style=\"width: 60px; height: 140px; padding: 0.2em; font-size: 0.7em; clear: none;\" class=\"left center\"><?php 
		?><img style=\"height: 80px; margin-right: 0.5em; margin-top: 0.2em; margin-bottom: 0.2em;\" <?php 
		?>src=\"<?php print $this->_view->baseUrl(); ?>/img/wikilogo.png\" /><br />...</li><?php } ?><li></li></ul>" });
});
<?php $this->_view->headScript()->captureEnd();
			$tagHtml = sprintf('<a href="%s" %s id="tag%s" rel="tag"><span>Dieses Rezept wurde %s mal mit dem Schlagwort </span>%s<span> versehen.</span></a>', htmlSpecialChars($tag->getParam('url')), $attribute,str_replace(array('-',' ','.'),array('_','__','___'),$tag->getTitle()), $tag->getWeight(), $tag->getTitle());

			foreach ($this->getHtmlTags() as $key => $data) {
				if (is_array($data)) {
					$htmlTag    = $key;
					$attributes = '';

					foreach ($data as $param => $value) {
						$attributes .= ' ' . $param . '="' . htmlspecialchars($value) . '"';
					}
				} else {
					$htmlTag    = $data;
					$attributes = '';
				}

				$tagHtml = sprintf('<%1$s%3$s>%2$s</%1$s>', $htmlTag, $tagHtml, $attributes);
			}

			$result[] = $tagHtml;
		}

		return $result;

	}
}
