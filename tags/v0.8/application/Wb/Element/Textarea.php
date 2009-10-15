<?php
/**
 * Textarea form element
 * 
 */
class Wb_Element_Textarea extends Zend_Form_Element_Xhtml
{
    /**
     * Use formTextarea view helper by default
     * @var string
     */
    protected $_defaultHelper = 'formTextarea';
    
    public function __construct($spec, $options = null)
    {
        if (is_string($spec)) {
            $this->setName($spec);
        }

        /**
         * Register ViewHelper decorator by default
         */
        $this->addDecorator('ViewHelper', array('helper' => $this->_defaultHelper))
             ->addDecorator(new Wb_Decorator_Description())
             ->addDecorator('Errors')
             ->addDecorator('HtmlTag', array('tag' => 'dd'))
             ->addDecorator('Label', array('tag' => 'dt'));

        if (is_array($spec)) {
            $this->setOptions($spec);
        } elseif ($spec instanceof Zend_Config) {
            $this->setConfig($spec);
        } elseif (is_array($options)) {
            $this->setOptions($options);
        } elseif ($options instanceof Zend_Config) {
            $this->setConfig($options);
        }

        if (null === $this->getName()) {
            throw new Zend_Form_Exception('Zend_Form_Element requires each element to have a name');
        }
    }
}
