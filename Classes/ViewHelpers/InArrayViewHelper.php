<?php
namespace LTH\LthPackage\ViewHelpers;

class InArrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Render
     *
     * @param string $haystack
     * @param string $needle
     * @return string
     */
    public function render($haystack, $needle) 
    {
        if(is_array($haystack) && $needle) {
            return $this->in_array_r($needle, $haystack);
        } else {
            return false;
        }
    }
    
    
    function in_array_r($needle, $haystack) {
        $found = false;
        foreach ($haystack as $key => $item) {
            if ($item && ($key === $needle)) { 
                $found = true; 
                break; 
            } elseif (is_array($item)) {
                $found = $this->in_array_r($needle, $item); 
                if($found) { 
                    break; 
                } 
            }    
        }
        return $found;
    }
}