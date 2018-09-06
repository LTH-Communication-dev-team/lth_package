<?php
/***************
* Add Content Element
*/
if (!is_array($GLOBALS['TCA']['tt_content']['types']['masonry'])) {
   $GLOBALS['TCA']['tt_content']['types']['masonry'] = [];
}

/***************
* Add content element to seletor list
*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
   'tt_content',
   'CType',
   [
       'LLL:EXT:lth_package/Resources/Private/Language/Backend.xlf:content_element.masonry',
       'masonry',
       'content-lthpackage-masonry'
   ],
   '--div--',
   'after'
);

/***************
* Assign Icon
*/
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['masonry'] = 'content-lthpackage-masonry';

$GLOBALS['TCA']['tt_content']['types']['masonry'] = array_replace_recursive(
   $GLOBALS['TCA']['tt_content']['types']['masonry'],
   [
       'showitem' => '
                            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                                --palette--;;,
                            pi_flexform;,                                
     
                            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                                --palette--;;language,
                                
                            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                                --palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.visibility;visibility,
                                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
                                
                            --div--;Categories,
                                categories,
                                
                            --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:extended,
                        '
   ]
);

/***************
* Register fields
*/
/*$GLOBALS['TCA']['tt_content']['columns'] = array_replace_recursive(
   $GLOBALS['TCA']['tt_content']['columns'],
   [
       'external_media_source' => [
           'label' => 'LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:field.external_media_source',
           'config' => [
               'type' => 'input',
               'size' => 50,
               'eval' => 'trim',
               'max' => 1024,
           ]
       ],
       'external_media_ratio' => [
           'label' => 'LLL:EXT:bootstrap_package/Resources/Private/Language/Backend.xlf:field.external_media_ratio',
           'config' => [
               'type' => 'select',
               'renderType' => 'selectSingle',
               'items' => [
                   ['16:9', '16by9'],
                   ['4:3', '4by3'],
               ]
           ]
       ]
   ]
);
*/
/***************
* Register palettes

$GLOBALS['TCA']['tt_content']['palettes']['external_media'] = [
   'showitem' => '
       external_media_source, --linebreak--,
       external_media_ratio
   '
];*/

/***************
* Add flexForms for content element configuration
*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
   '*',
   'FILE:EXT:lth_package/Configuration/FlexForms/Masonry.xml',
   'masonry'
);