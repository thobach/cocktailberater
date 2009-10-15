<?php
/**
 * Zend_Form_Decorator_Description
 */
class Wb_Decorator_Description extends Zend_Form_Decorator_Abstract
{
    /**
     * Render errors
     * 
     * @param  string $content 
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        return $content.'<p class="description">'.$element->getDescription().'</p>';
        
    }
}
