<?php
namespace LTH\LthPackage\ViewHelpers;

class ColPaddingViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Render
     *
     * @param string $padding
     * @return string
     */
    public function render($padding) 
    {
        if($padding) {
            $paddingArray = explode(',', $padding);
            if(is_array($paddingArray)) {
                $padding = '';
                if($paddingArray[0] && intval($paddingArray[0])) {
                    $padding .= 'padding-top:' . $paddingArray[0] . 'px;';
                }
                if($paddingArray[1] && intval($paddingArray[1])) {
                    $padding .= 'padding-right:' . $paddingArray[1] . 'px;';
                }
                if($paddingArray[2] && intval($paddingArray[2])) {
                    $padding .= 'padding-bottom:' . $paddingArray[2] . 'px;';
                }
                if($paddingArray[3] && intval($paddingArray[3])) {
                    $padding .= 'padding-left:' . $paddingArray[3] . 'px;';
                }
            }
            return $padding;
        }
    }   
}