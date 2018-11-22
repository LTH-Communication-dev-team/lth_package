<?php
namespace LTH\LthPackage\ViewHelpers;

class OtherContentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Render
     *
     * @param string $otherId
     * @return string
     */
    public function render($otherId) 
    {
        if($otherId) {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery("pi_flexform","tt_content","uid=".intval($otherId),"","","");
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            $pi_flexform = $row['pi_flexform'];

            $GLOBALS['TYPO3_DB']->sql_free_result($res);
            return $pi_flexform;
        } 
    }
    
    
    
}