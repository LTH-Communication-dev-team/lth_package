<?php
defined('TYPO3_MODE') || die();

/***************
 * Add content element group to selector list
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTcaSelectItem(
    'tt_content',
    'CType',
    [
        'LLL:EXT:lth_package/Resources/Private/Language/backend.xlf:theme_name',
        '--div--'
    ],
    '--div--',
    'before'
);