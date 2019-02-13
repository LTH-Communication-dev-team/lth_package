<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

/***************
 * Make the extension configuration accessible
 */
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}

/***************
 * Backend Styling
 */
if (TYPO3_MODE == 'BE') {
    include_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY).'Classes/Service/class.tx_lthpackage_addFieldsToFlexForm.php');

 /*   if (!isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['Logo'])
        || empty($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['Logo'])
    ) {
        $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['Logo'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Images/Backend/TopBarLogo@2x.png';
    }
    $GLOBALS['TBE_STYLES']['logo'] = $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]['Logo'];

    */
    /*$GLOBALS['TBE_MODULES']['_configuration'][$_EXTKEY] = array (
        'jsFiles' => array (
            'EXT:' . $_EXTKEY . '/Resources/Public/JavaScript/Dist/spectrum.js',
        ),
    );*/
}

/***************
 * Register Icons
 */
$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
$iconRegistry->registerIcon(
    'content-lthpackage-banner',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/banner.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-blockquote',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/blockquote.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-publications',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/Publications.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-infobox',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/infobox.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-staff',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/Staff.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-carousel',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/carousel.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-carousel-item',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/carousel-item.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-carousel-item-header',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/carousel-item-header.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-carousel-item-textandimage',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/carousel-item-textandimage.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-carousel-item-backgroundimage',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/carousel-item-backgroundimage.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-carousel-item-html',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/carousel-item-html.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-calendar',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/calendar.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-card',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/card.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-embed',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/embed.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-figure',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/figure.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-infographics',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/infographics.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-masonrytile',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/masonrytile.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-navigation',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/navigation.svg']
);
$iconRegistry->registerIcon('MasonryA',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, 
    ['source' => 'EXT:lth_package/Resources/Public/Icons/Backend/Grids/MasonryA.svg']
);
$iconRegistry->registerIcon('MasonryB',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, 
    ['source' => 'EXT:lth_package/Resources/Public/Icons/Backend/Grids/MasonryB.svg']
);
$iconRegistry->registerIcon('MasonryC',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, 
    ['source' => 'EXT:lth_package/Resources/Public/Icons/Backend/Grids/MasonryC.svg']
);
$iconRegistry->registerIcon('MasonryD',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, 
    ['source' => 'EXT:lth_package/Resources/Public/Icons/Backend/Grids/MasonryD.svg']
);
$iconRegistry->registerIcon('MasonryE',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, 
    ['source' => 'EXT:lth_package/Resources/Public/Icons/Backend/Grids/MasonryE.svg']
);
$iconRegistry->registerIcon('MasonryF',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, 
    ['source' => 'EXT:lth_package/Resources/Public/Icons/Backend/Grids/MasonryF.svg']
);
$iconRegistry->registerIcon('MasonryG',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class, 
    ['source' => 'EXT:lth_package/Resources/Public/Icons/Backend/Grids/MasonryG.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-progress',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/progress.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-teasercards',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/teasercards.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-title',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/title.svg']
);
$iconRegistry->registerIcon(
    'content-lthpackage-toggle',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:lth_package/Resources/Public/Icons/ContentElements/toggle.svg']
);

/***************
 * Allow Promos Item on Standard Pages
 */
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_lthpackage_infobox_item');

/***************
 * Remove new content element wizard registration for indexed_search
 * to override it and use the the extbase version instead
 */
/*if (TYPO3_MODE === 'BE' && \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('indexed_search')) {
    unset($GLOBALS['TBE_MODULES_EXT']['xMOD_db_new_content_el']['addElClasses']['tx_indexed_search_pi_wizicon']);
}*/

$GLOBALS["TYPO3_CONF_VARS"]["FE"]["addRootLineFields"] .= ",author,author_email,tx_lthpackage_headnav,tx_lthpackage_headnavdrop";


/***************
 * Reset extConf array to avoid errors
 */
if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY])) {
    $GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY] = serialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
}