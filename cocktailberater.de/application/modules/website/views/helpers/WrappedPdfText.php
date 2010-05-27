<?php

/**
 * Zend_View_Helper_WrappedPdfText returns an array of line for PDF
 */
class Zend_View_Helper_WrappedPdfText extends Zend_View_Helper_Abstract {

	/**
	 *
	 * taken from http://blog.alfbox.net/index.php/2008/06/04/wrapping-text-for-zend-pdf/
	 *
	 * @param unknown_type $string
	 * @param Zend_Pdf_Style $style
	 * @param unknown_type $max_width
	 */
	function wrappedPdfText($string, Zend_Pdf_Style $style,$max_width){
		$wrappedText = '' ;
		$lines = explode("\n",$string) ;
		foreach($lines as $line) {
			$words = explode(' ',$line) ;
			$word_count = count($words) ;
			$i = 0 ;
			$wrappedLine = '' ;
			while($i < $word_count)	{
				/* if adding a new word isn't wider than $max_width,
				 we add the word */
				if($this->widthForStringUsingFontSize($wrappedLine.' '.$words[$i]
				,$style->getFont()
				, $style->getFontSize()) < $max_width) {
					if(!empty($wrappedLine)) {
						$wrappedLine .= ' ' ;
					}
					$wrappedLine .= $words[$i] ;
				} else {
					$wrappedText .= $wrappedLine."\n" ;
					$wrappedLine = $words[$i] ;
				}
				$i++ ;
			}
			$wrappedText .= $wrappedLine."\n" ;
		}
		return $wrappedText ;
	}

	/**
	 * found here, not sure of the author :
	 * http://devzone.zend.com/article/2525-Zend_Pdf-tutorial#comments-2535
	 */
	function widthForStringUsingFontSize($string, $font, $fontSize){
		$drawingString = iconv('UTF-8', 'UTF-16BE//IGNORE', $string);
		$characters = array();
		for ($i = 0; $i < strlen($drawingString); $i++) {
			$characters[] = (ord($drawingString[$i++]) << 8 ) | ord($drawingString[$i]);
		}
		$glyphs = $font->glyphNumbersForCharacters($characters);
		$widths = $font->widthsForGlyphs($glyphs);
		$stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
		return $stringWidth;
	}
}