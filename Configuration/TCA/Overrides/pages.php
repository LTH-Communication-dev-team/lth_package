<?php
defined('TYPO3_MODE') || die();

/***************
 * Temporary variables
 */
$extensionKey = 'lth_package';

/***************
 * Register PageTS
 */

// Ionicons
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/Feature/Ionicons.txt',
    'Lth Package: Use Ionicons as Iconset'
);

// AdminPanel
/*\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/admPanel.txt',
    'Bootstrap Package: Admin Panel'
);*/

// BackendLayouts
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/Mod/WebLayout/BackendLayouts.txt',
    'LTH Package: Backend Layouts'
);

// TCEMAIN
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/TCEMAIN.txt',
    'Lth Package: TCEMAIN'
);

// TCEFORM
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/TCEFORM.txt',
    'Lth Package: TCEFORM'
);

// RTE
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/RTE.txt',
    'LTH Package: RTE'
);

// TtContent Previews
/*\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/Mod/WebLayout/TtContent/preview.txt',
    'Bootstrap Package: Content Previews'
);*/

/***************
 * New field in table:pages
 */
call_user_func(
    function ($extKey) {
	$tempPagesColumns = [
            'tx_lthpackage_headnav' => [
                'exclude' => 1,
                'label'   => 'Head navigation menu items',
                'config' => [
                    'type' => 'text',
                    'cols' => '20',
                    'rows' => '3'
                ]
            ],
            'tx_lthpackage_headnavdrop' => [
                'exclude' => 1,
                'label'   => 'Head navigation dropdown items',
                'config' => [
                    'type' => 'text',
                    'cols' => '20',
                    'rows' => '3'
                ]
            ],
            'tx_lthpackage_breadcrumb' => [
                'exclude' => 1,
                'label'   => 'Show breadcrumb navigation (for landingpage only)',
                'config' => [
                    'type' => 'check',
                    'items' => [
                       ['Yes', ''],
                    ],
                ]
            ],
            'tx_lthpackage_mainclass' => [
                'exclude' => 1,
                'label'   => 'Extra class for main-tag',
                'config' => [
                    'type' => 'input',
                    'cols' => '20',
                ]
            ],
            'tx_lthpackage_otherlanguageversion' => [
                'exclude' => 1,
                'label'   => 'Other language version',
                'config' => [
                    'type' => 'input',
                    'size' => 30,
                    'eval' => 'trim',
                    'wizards' => [
                        '_PADDING' => 2,
                        'link' => [
                            'type' => 'popup',
                            'title' => 'LLL:EXT:cms/locallang_ttc.xml:header_link_formlabel',
                            'icon' => 'link_popup.gif',
                            'module' => [
                                'name' => 'wizard_element_browser',
                                'urlParameters' => [
                                    'mode' => 'wizard',
                                    'act' => 'page'
                                ]
                            ],
                            'JSopenParams' => 'height=300,width=500,status=0,menubar=0,scrollbars=1',
                        ],
                    ],
                    'softref' => 'typolink',
                ],
            ],
        ];

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages',$tempPagesColumns);
                
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages','layout','--linebreak--,tx_lthpackage_headnav,--linebreak--,tx_lthpackage_headnavdrop,--linebreak--,tx_lthpackage_breadcrumb,--linebreak--,tx_lthpackage_mainclass,--linebreak--,tx_lthpackage_otherlanguageversion','after:content_from_pid');

        }, 'lth_package'
);

// New Content element wizards
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/Mod/Wizards/newContentElement.txt',
    'Lth Package: New Content Element Wizards'
);