<?php
namespace LTH\LthPackage\ViewHelpers;

class GenericViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Render
     *
     * @param string $genvar
     * @param string $gentype
     * @return string
     */
    public function render($gentype, $genvar) 
    {
        switch($gentype) {
            case 'gp':
                $content = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP($genvar);
                
                if($content) {
                    if(strstr($content,")")) {
                        $content = rtrim(array_pop(explode('(',$content)),")");
                    }
                    return $content;
                } else {
                    return '';
                }
                break;
            case 'detailPage':
                $content = $GLOBALS['TSFE']->cObj->typoLink_URL(
                    array(
                        'parameter' => $genvar,
                        'forceAbsoluteUrl' => true,
                    )
                );
                return $content;
                break;
            case 'inArray':
                $content = $GLOBALS['TSFE']->cObj->typoLink_URL(
                    array(
                        'parameter' => $genvar,
                        'forceAbsoluteUrl' => true,
                    )
                );
                return $content;
                break;
        }        
    }
}