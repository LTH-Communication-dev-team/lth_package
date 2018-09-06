<?php
namespace LTH\LthPackage\ViewHelpers;

class AddjsViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * Render
     *
     * @param string $additionaljs
     * @return string
     */
    public function render($additionaljs) 
    {
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => $additionaljs, 'crdate' => time()));
        $syslang = $GLOBALS['TSFE']->config['config']['language'];
        if(!$syslang) {
            $syslang = 'en';
        }
        if($syslang=='se') {
            $syslang='sv';
        }
        $GLOBALS['TSFE']->getPageRenderer()->addCssFile("typo3conf/ext/lth_package/Resources/Public/Css/lth_package.css");
        $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile("typo3conf/ext/lth_solr/res/lth_solr_lang_$syslang.js", NULL, FALSE, FALSE, '', TRUE);
        $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile('typo3conf/ext/lth_package/Resources/Public/JavaScript/Dist/Publications.js', NULL, FALSE, FALSE, '', TRUE);
        if($additionaljs==='tagcloud') {
            $GLOBALS['TSFE']->getPageRenderer()->addCssFile("typo3conf/ext/lth_package/Resources/Public/Css/jqcloud.min.css");
            $GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile("typo3conf/ext/lth_package/Resources/Public/JavaScript/Dist/jqcloud.min.js", NULL, FALSE, FALSE, '', TRUE);
        }
    }
}