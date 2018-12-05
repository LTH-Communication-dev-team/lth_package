<?php
defined('TYPO3_MODE') || die();

                /***************
                 * Add Content Element
                 */
                if (!is_array($GLOBALS['TCA']['tt_content']['types']['blockquote'])) {
                    $GLOBALS['TCA']['tt_content']['types']['blockquote'] = [];
                }

                /***************
                 * Add content element to seletor list
                 */
                \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
                    'tt_content',
                    'CType',
                    [
                        'LLL:EXT:lth_package/Resources/Private/Language/backend.xlf:content_element.blockquote',
                        'blockquote',
                        'content-lthpackage-blockquote'
                    ],
                    '--div--',
                    'after'
                );

                /***************
                 * Assign Icon
                 */
                $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['blockquote'] = 'content-lthpackage-blockquote';

                /***************
                 * Configure element type
                 */
                $GLOBALS['TCA']['tt_content']['types']['blockquote'] = array_replace_recursive(
                    $GLOBALS['TCA']['tt_content']['types']['blockquote'],
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
        'tx_lthpackage_blockquote_item' => [
            'label' => 'LLL:EXT:lth_package/Resources/Private/Language/backend.xlf:blockquote_item',
            'config' => [
                'type' => 'inline',
                'foreign_table' => 'tx_lthpackage_blockquote_item',
                'foreign_field' => 'tt_content',
                'appearance' => [
                    'useSortable' => true,
                    'showSynchronizationLink' => true,
                    'showAllLocalizationLink' => true,
                    'showPossibleLocalizationRecords' => true,
                    'showRemovedLocalizationRecords' => false,
                    'expandSingle' => true,
                    'enabledControls' => [
                        'localize' => true,
                    ]
                ],
                'behaviour' => [
                    'localizationMode' => 'select',
                    'mode' => 'select',
                    'localizeChildrenAtParentLocalization' => true,
                ]
            ]
        ]
    ]
);*/

/***************
 * Add flexForms for content element configuration
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:lth_package/Configuration/FlexForms/Blockquote.xml',
    'blockquote'
);