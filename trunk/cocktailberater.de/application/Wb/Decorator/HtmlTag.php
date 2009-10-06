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
	tag<?php print $tag->getTitle() ;?>Tooltip = new dijit.Tooltip({
		connectId: ["tag<?php print $tag->getTitle() ;?>"], 
		label: "<div><img src=\"<?php echo $this->_view->baseUrl(); ?>/img/loading2.gif\"> Loading...</div>" });
	dojo.xhrGet({ 
		url: "<?php print $this->_view->url(array('module'=>'website','controller'=>'index','action'=>'recipes-with-tag','tag'=>$tag->getTitle()),null,true); ?>", 
		load: function(data){ 
			tag<?php print $tag->getTitle() ;?>Tooltip.label=data;
		} 
	}); 
});
<?php $this->_view->headScript()->captureEnd();
			$tagHtml = sprintf('<a href="%s" %s id="tag%s" rel="tag"><span>this recipe is tagged %s times with: </span>%s</a>', htmlSpecialChars($tag->getParam('url')), $attribute,$tag->getTitle(), $tag->getWeight(), $tag->getTitle());

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
