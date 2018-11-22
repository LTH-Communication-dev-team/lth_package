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
                'tx_lthpackage_subsitetitle' => [
			'exclude' => 1,
			'label'   => 'Show sub site title',
			'config' => [
				'type' => 'input'
			]
		]
	];

	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages',$tempPagesColumns);
        
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages','metatags','--linebreak--,tx_lthpackage_headnav,--linebreak--,tx_lthpackage_headnavdrop','after:abstract');
        
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addFieldsToPalette('pages','title','--linebreak--,tx_lthpackage_leftnav,tx_lthpackage_breadcrumb,--linebreak--,tx_lthpackage_subsitetitle','after:abstract');

        }, 'lth_package'
);

// New Content element wizards
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::registerPageTSConfigFile(
    $extensionKey,
    'Configuration/PageTS/Mod/Wizards/newContentElement.txt',
    'Lth Package: New Content Element Wizards'
);