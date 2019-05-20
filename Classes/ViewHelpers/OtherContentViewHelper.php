<?php
namespace LTH\LthPackage\ViewHelpers;

class OtherContentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;
    
    /**
     * Render
     *
     * @param string $otherId
     * @return string
     */
    public function render($otherId) 
    {
        if($otherId) {
            $content = 'Error';
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery("pi_flexform,CType","tt_content","uid=".intval($otherId[0]),"","","");
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            $pi_flexform = $row['pi_flexform'];
            $CType = $row['CType'];
            if($pi_flexform && $CType) {
                $flexformService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Service\FlexFormService');
                $ffContent['data']['pi_flexform'] = $flexformService->convertFlexFormContentToArray($pi_flexform);
                $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\Extbase\\Object\\ObjectManager');
                $configurationManager = $objectManager->get('TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
                $feView = $objectManager->get('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
                $extbaseFrameworkConfiguration = $configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
                $templatePathAndFilename = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName(
                    'EXT:lth_package/Resources/Private/Templates/ContentElements/' . ucfirst($CType) . '.html'
                );
                $feView->setTemplatePathAndFilename($templatePathAndFilename);
                $feView->assignMultiple($ffContent);
                $content = $feView->render();
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res);
            return $content;
        } 
    }
    
    public function renderStandaloneView($language = '')
    {
        // Set the extensionKey
        $extensionKey = GeneralUtility::underscoredToUpperCamelCase('lth_package');

        if ($language !== '') {
            // Temporary set Language of current BE user to given language
            $GLOBALS['BE_USER']->uc['lang'] = $language;
            LocalizationUtility::resetLocalizationCache($extensionKey);
        }

        /** @var \TYPO3\CMS\Fluid\View\StandaloneView $view */
        $view = $this->objectManager->get(StandaloneView::class);
        $view->setFormat('html');
        $template = GeneralUtility::getFileAbsFileName(
            'EXT:lth_package/Resources/Private/Templates/Enbed.html'
        );
        $view->setTemplatePathAndFilename($template);

        // Set Extension name, so localizations for extension get respected
        $view->getRequest()->setControllerExtensionName($extensionKey);

        return $view->render();
    }
    
}