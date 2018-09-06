<?php
defined('TYPO3_MODE') || die();

/***************
 * Default TypoScript
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'lth_package',
    'Configuration/TypoScript',
    'Lth Package'
);
