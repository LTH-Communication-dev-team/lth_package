<?php
namespace LTH\LthPackage\ViewHelpers;

class GetHostViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Render
     *
     * @return string
     */
    public function render() 
    {
        return stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'];
    }
    
}