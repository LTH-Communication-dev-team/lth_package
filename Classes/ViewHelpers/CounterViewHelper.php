<?php
namespace LTH\LthPackage\ViewHelpers;

class CounterViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Render
     *
     * @param string $genvar
     * @param string $gentype
     * @return string
     */
    public function render($b) 
    {
        /*$numberArray = array(1=>"One",2=>"Two",3=>"Three", 4=>"Four", 5=>"Five", 6=>"Six", 7=>"Seven", 8=>"Eight", 9=>"Nine",
                    10=>"Ten", 11=>"Eleven", 12=>"Twelve", 13=>"Thirteen", 14=>"Fourteen", 15=>"Fifteen", 16=>"Sixteen", 17=>"Seventeen", 18=>"Eighteen", 19=>"Nineteen");
        $content = $numberArray[intval($index)+1];
        if($cycle>1) {
            $content .= $numberArray[intval($cycle)];
        }*/
        return $b+1;
    }
}