<?php
defined('TYPO3_MODE') || die();

/***************
 * Add Content Element
 */
if (!is_array($GLOBALS['TCA']['tt_content']['types']['publications'])) {
    $GLOBALS['TCA']['tt_content']['types']['publications'] = [];
}

/***************
 * Add content element to seletor list
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:lth_package/Resources/Private/Language/Backend.xlf:content_element.publications',
        'publications',
        'content-lthpackage-publications'
    ],
    '--div--',
    'after'
);

/***************
 * Assign Icon
 */
$GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['publications'] = 'content-lthpackage-publications';

/***************
 * Configure element type
 */
$GLOBALS['TCA']['tt_content']['types']['publications'] = array_replace_recursive(
    $GLOBALS['TCA']['tt_content']['types']['publications'],
    [
        'showitem' => '
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
            --div--;LLL:EXT:lth_package/Resources/Private/Language/Backend.xlf:publications.options,
                pi_flexform;LLL:EXT:lth_package/Resources/Private/Language/Backend.xlf:publications.options,
            --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,
                --palette--;;hidden,
                --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
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
    'FILE:EXT:lth_package/Configuration/FlexForms/Publications.xml',
    'publications'
);