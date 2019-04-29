<?php

namespace LTH\LthPackage\Controller;

/**
 * This file is part of the "lth_package" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Clipboard\Clipboard;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Utility\BackendUtility as BackendUtilityCore;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Core\Utility\MathUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Lang\LanguageService;

/**
 * Administration controller
 */
class ConvertdceController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    const SIGNAL_CONVERTDCE_INDEX_ACTION = 'indexAction';
    const SIGNAL_CONVERTDCE_ALL_ACTION = 'allAction';
    const SIGNAL_CONVERTDCE_UPDATE_ACTION = 'updateAction';
    const SIGNAL_CONVERTDCE_UPDATEALL_ACTION = 'updateAllAction';
    
    /**
     * Page uid
     *
     * @var int
     */
    protected $pageUid = 0;

    /**
     * TsConfig configuration
     *
     * @var array
     */
    //protected $tsConfiguration = [];

    /**
     * @var dceElements
     */
    protected $dceElements;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;
    /**
     * @var array
     */
    protected $pageInformation = [];

    /**
     * @var array
     */
    //protected $allowedNewTables = [];

    /**
     * @var array
     */
    //protected $deniedNewTables = [];

    /**
     * Function will be called before every other action
     *
     */
    public function initializeAction()
    {
        $this->pageUid = (int)\TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
        $this->pageInformation = BackendUtilityCore::readPageAccess($this->pageUid, '');
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lth_package']);
        //print_r($settings);
        $dceElements = $settings['dceElements'];
        if($dceElements) {
            $dceElements = explode(',', $dceElements);
            $dceElements = "'" . implode("','", $dceElements) . "'";
            $this->dceElements = $dceElements;
        }
        //$this->setTsConfig();
        parent::initializeAction();
    }

    /**
     * BackendTemplateContainer
     *
     * @var BackendTemplateView
     */
    protected $view;

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * Backend Template Container
     *
     * @var BackendTemplateView
     */
    protected $defaultViewObjectName = BackendTemplateView::class;
    
    
    /**
     * Set up the doc header properly here
     *
     * @param ViewInterface $view
     */
    protected function initializeView(ViewInterface $view)
    {
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
        /** @var BackendTemplateView $view */
        parent::initializeView($view);
        $view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation([]);
        $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
        $this->createMenu();
    }

    /**
    * Index action
    *
    * @return void
    */
    public function indexAction()
    {
        $pageUid = $this->pageUid;
        if($pageUid && $this->dceElements) {
            //$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('t.uid, p.title','tt_content t join pages p on t.pid = p.uid',
                    "t.CType IN(" . $this->dceElements . ") AND t.deleted = 0 AND t.pid = " . intval($pageUid),'','','');
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                $dceArray[] = array($row['uid'], $row['title']);
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res);
            
            if($dceArray) {
                $content = "<table class=\"table table-striped\">";
                $content .= "<thead class=\"thead-dark\">";
                $content .= "<tr><th scope=\"col\">DCE uid</th><th scope=\"col\">Page title</th></tr>";
                $content .= "</thead><tbody>";
                foreach ($dceArray as $key => $value) {
                    $content .= "<tr><td>$value[0]</td><td>$value[1]</td></tr>";
                }
                $content .= "</tbody></table>";
                $assignedValues = [
                    'moduleToken' => $this->getToken(true),
                    'page' => $this->pageUid,
                    'demand' => $demand,
                    'action' => 'update',
                    'btnText' => 'Update',
                    'dces' => $content,
                    'showSearchForm' => (!is_null($demand) || $dblist->counter > 0),
                    'requestUri' => GeneralUtility::quoteJSvalue(rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'))),
                    //'categories' => $this->categoryRepository->findTree($idList),
                    //'filters' => $this->tsConfiguration['filters.'],
                    //'enableFiltering' => $this->isFilteringEnabled(),
                    'dateformat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']
                ];
            } else {
                $assignedValues = [
                    'display' => 'none'
                    ];
                $this->showFlashMsg('', 'No records where found', 'WARNING');
            }
            
            
        } else {
            $assignedValues = [
                    'display' => 'none'
                    ];
            $this->showFlashMsg('', 'No records where found', 'WARNING');
        }
        $assignedValues = $this->emitActionSignal('ConvertdceController', self::SIGNAL_CONVERTDCE_INDEX_ACTION, $assignedValues);
            $this->view->assignMultiple($assignedValues);
    }
    
    
    /**
    * all action
    *
    * @return void
    */
    public function allAction()
    {        
        $pageUid = $this->pageUid;
        $pidList = $this->getTreePids(intval($pageUid), false);
        if($pidList) {
            $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('t.uid, t.pid,  p.title',
                    'tt_content t join pages p on t.pid = p.uid',
                    "t.CType IN(" . $this->dceElements . ") AND t.deleted = 0 AND p.uid IN(" . $pidList . ")",'','','');
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                $dceArray[] = array($row['uid'], $row['pid'], $row['title']);
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res);
            
            if($dceArray) {
                $content = "<table class=\"table table-striped\">";
                $content .= "<thead class=\"thead-dark\">";
                $content .= "<tr><th scope=\"col\">DCE uid</th><th scope=\"col\">Page uid</th><th scope=\"col\">Page title</th></tr>";
                $content .= "</thead><tbody>";
                foreach ($dceArray as $key => $value) {
                    $content .= "<tr><td>$value[0]</td><td>$value[1]</td><td>$value[2]</td></tr>";
                }
                $content .= "</tbody></table>";
                $assignedValues = [
                    'moduleToken' => $this->getToken(true),
                    'page' => $this->pageUid,
                    'demand' => $demand,
                    'action' => 'updateAll',
                    'btnText' => 'Update',
                    'dces' => $content, // . $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery,
                    'showSearchForm' => (!is_null($demand) || $dblist->counter > 0),
                    'requestUri' => GeneralUtility::quoteJSvalue(rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'))),
                    //'categories' => $this->categoryRepository->findTree($idList),
                    //'filters' => $this->tsConfiguration['filters.'],
                    //'enableFiltering' => $this->isFilteringEnabled(),
                    'dateformat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']
                ];
            } else {
                $assignedValues = [
                    'display' => 'none'
                    ];
                $this->showFlashMsg('', 'No records where found', 'WARNING');
            }
            
             
        } else {
            $assignedValues = [
                    'display' => 'none'
                    ];
            $this->showFlashMsg('', 'No records where found', 'WARNING');
        }
        $assignedValues = $this->emitActionSignal('ConvertdceController', self::SIGNAL_CONVERTDCE_ALL_ACTION, $assignedValues);
        $this->view->assignMultiple($assignedValues);
    }
    
    
    /**
    * Update action
    *
    * @return void
    */
    public function updateAction()
    {
        /* Initialize counter stored in session variable */
        $pageUid = $this->pageUid;
        if($pageUid) {
            //$GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('t.uid, p.title, t.bodytext, t.colpos, t.sorting','tt_content t join pages p on t.pid = p.uid',
                    "t.CType IN(" . $this->dceElements . ") AND t.deleted = 0 AND p.uid = " . intval($pageUid),'','','');
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                $bodytext = $row['bodytext'];
                $uid = $row['uid'];
                $colpos = $row['colpos'];
                $sorting = $row['sorting'];
                
                $header = "";
                $infoboxImage = "";
                $infoboxtype = "default";
                $lead = "";
                $backgroundcolor = "";
                $link = "";
                $alttext = "";
                
                if($bodytext) $bodytext = preg_replace('/(\>)\s*(\<)/m', '$1$2', $bodytext);
                
                $header = $this->get_string_between($bodytext, '<b>Header:</b><span class="simpleValue">', '</span>');
                $lead = $this->get_string_between($bodytext, '<b>Lead:</b><span class="simpleValue">', '</span>');
                $infoboxImage = $this->get_string_between($bodytext, '<b>Promoimage:</b><span class="simpleValue">', '</span>');
                $backgroundcolor = $this->get_string_between($bodytext, '<b>Backgroundcolor:</b><span class="simpleValue">', '</span>');
                $link = $this->get_string_between($bodytext, '<b>Link:</b><span class="simpleValue">', '</span>');
                $alttext = $this->get_string_between($bodytext, '<b>Alttext:</b><span class="simpleValue">', '</span>');
                
                if($infoboxImage) {
                    
                    $infoboxImage = str_replace('fileadmin', '', $infoboxImage);
                    $infoboxImage = str_replace('//', '/', $infoboxImage);
                    $res1 = $GLOBALS['TYPO3_DB']->exec_SELECTquery("uid AS imageUid","sys_file","identifier='$infoboxImage'","","","");
                    $row1 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res1);
                    $infoboxImage = $row1['imageUid'];
                    
                    if($infoboxImage) {
                        $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_file_reference', array('pid' => intval($pageUid), 'table_local' => 'sys_file',
                            'uid_local' => $infoboxImage, 'title' => $alttext, 'alternative' => $alttext, 'crdate' => time(), 'tstamp' => time()));
                        $infoboxImage = $GLOBALS['TYPO3_DB']->sql_insert_id();
                    } 
                }
                //<b>Header:</b> <span class="simpleValue">Dean's blog</span>
                if($backgroundcolor) {
                    $backgroundcolor = str_replace('bg_beige', 'plaster-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_pink', 'flower-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_green', 'copper-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_blue', 'sky-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_yellow', 'stone-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_white', 'none.gif', $backgroundcolor);
                }
                
                $convertArray = array(
                    'uid' => $uid,
                    'header' => $header,
                    'bodytext' => $lead,
                    'promoimage' => $promoimage,
                    'backgroundcolor' => $backgroundcolor,
                    'infoboxImage' => $infoboxImage,
                    'infoboxtype' => $infoboxtype,
                    'link' => $link,
                    'alttext' => $alttext,
                    'colpos' => $colpos,
                    'sorting' => $sorting
                );
                $dceArray[$uid] = $this->convertToContent($convertArray);
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res);
            $GLOBALS['TYPO3_DB']->sql_free_result($res1);
            
            if($dceArray) {
                
                foreach ($dceArray as $key => $value) {
                    //hide existing
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid='.intval($key), array('hidden' => 1, 'tstamp' => time()));
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_content', array('pid' => intval($pageUid), 'CType' => 'infobox',
                        'pi_flexform' => trim($value), 'crdate' => time(), 'tstamp' => time(), 'colpos' => $colpos, 'sorting' => $sorting));
                }
            }
            $assignedValues = [
                'moduleToken' => $this->getToken(true),
                'page' => $this->pageUid,
                'demand' => $demand,
                'content' => '',// . $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery,
                'action' => 'index',
                'btnText' => 'Back',
                'showSearchForm' => (!is_null($demand) || $dblist->counter > 0),
                'requestUri' => GeneralUtility::quoteJSvalue(rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'))),
                //'categories' => $this->categoryRepository->findTree($idList),
                //'filters' => $this->tsConfiguration['filters.'],
                //'enableFiltering' => $this->isFilteringEnabled(),
                'dateformat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']
            ];

            $this->showFlashMsg('OK', 'The records where updated', 'OK');
            $assignedValues = $this->emitActionSignal('ConvertdceController', self::SIGNAL_CONVERTDCE_UPDATE_ACTION, $assignedValues);
            $this->view->assignMultiple($assignedValues);
        } else {
            $this->showFlashMsg('', 'No records where updated', 'WARNING');
        }
    }
    
    
    /**
    * UpdateAll action
    *
    * @return void
    */
    public function updateAllAction()
    {
        $pageUid = $this->pageUid;
        $pidList = $this->getTreePids(intval($pageUid), false);
        if($pidList) {
            $GLOBALS['TYPO3_DB']->store_lastBuiltQuery = 1;
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('t.uid, t.pid, p.title, t.bodytext, t.colpos, t.sorting',
                    'tt_content t join pages p on t.pid = p.uid',
                    "t.CType IN(" . $this->dceElements . ") AND t.deleted = 0 AND p.uid IN(" . $pidList . ")",'','','');
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                $bodytext = $row['bodytext'];
                $uid = $row['uid'];
                $pid = $row['pid'];
                $colpos = $row['colpos'];
                $sorting = $row['sorting'];
                
                $alttext = "";
                $backgroundcolor = "";
                $infoboxImage = "";
                $infoboxtype = "default";
                $header = "";
                $lead = "";
                $link = "";
                
                
                if($bodytext) $bodytext = preg_replace('/(\>)\s*(\<)/m', '$1$2', $bodytext);
                
                $header = $this->get_string_between($bodytext, '<b>Header:</b><span class="simpleValue">', '</span>');
                $lead = $this->get_string_between($bodytext, '<b>Lead:</b><span class="simpleValue">', '</span>');
                $infoboxImage = $this->get_string_between($bodytext, '<b>Promoimage:</b><span class="simpleValue">', '</span>');
                $backgroundcolor = $this->get_string_between($bodytext, '<b>Backgroundcolor:</b><span class="simpleValue">', '</span>');
                $link = $this->get_string_between($bodytext, '<b>Link:</b><span class="simpleValue">', '</span>');
                $alttext = $this->get_string_between($bodytext, '<b>Alttext:</b><span class="simpleValue">', '</span>');
                
                if($infoboxImage) {
                    $infoboxImage = str_replace('fileadmin', '', $infoboxImage);
                    $infoboxImage = str_replace('//', '/', $infoboxImage);
                    $res1 = $GLOBALS['TYPO3_DB']->exec_SELECTquery("uid AS imageUid","sys_file","identifier='$infoboxImage'","","","");
                    $row1 = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res1);
                    $infoboxImage = $row1['imageUid'];
                    
                    if($infoboxImage) {
                        $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_file_reference', array('pid' => intval($pageUid), 'table_local' => 'sys_file',
                            'uid_local' => $infoboxImage, 'title' => $alttext, 'alternative' => $alttext, 'crdate' => time(), 'tstamp' => time()));
                        $infoboxImage = $GLOBALS['TYPO3_DB']->sql_insert_id();
                    }
                }
                
                //<b>Header:</b> <span class="simpleValue">Dean's blog</span>
                if($backgroundcolor) {
                    $backgroundcolor = str_replace('bg_beige', 'plaster-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_pink', 'flower-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_green', 'copper-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_blue', 'sky-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_yellow', 'stone-50.gif', $backgroundcolor);
                    $backgroundcolor = str_replace('bg_white', 'none.gif', $backgroundcolor);
                }
                
                $convertArray = array(
                    'uid' => $uid,
                    'pid' => $pid,
                    'header' => $header,
                    'bodytext' => $lead,
                    'infoboxImage' => $infoboxImage,
                    'infoboxtype' => $infoboxtype,
                    'backgroundcolor' => $backgroundcolor,
                    'link' => $link,
                    'alttext' => $alttext
                );
                $dceArray[$uid] = array('pi_flexform' => $this->convertToContent($convertArray), 'colpos' => $colpos, 'sorting' => $sorting);
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res);
            
            if($dceArray) {
                
                foreach ($dceArray as $key => $value) {
                    //hide existing
                    $GLOBALS['TYPO3_DB']->exec_UPDATEquery('tt_content', 'uid='.intval($key), array('hidden' => 1, 'tstamp' => time()));
                    $GLOBALS['TYPO3_DB']->exec_INSERTquery('tt_content', array('pid' => intval($pid), 'CType' => 'infobox',
                        'pi_flexform' => trim($value['pi_flexform']), 
                        'crdate' => time(), 
                        'tstamp' => time(), 
                        'colpos' => $value['colpos'], 
                        'sorting' => $value['sorting'])
                    );
                }
            }
            $assignedValues = [
                'moduleToken' => $this->getToken(true),
                'page' => $this->pageUid,
                'demand' => $demand,
                'content' => '',// . $GLOBALS['TYPO3_DB']->debug_lastBuiltQuery,
                'action' => 'all',
                'btnText' => 'Back',
                'showSearchForm' => (!is_null($demand) || $dblist->counter > 0),
                'requestUri' => GeneralUtility::quoteJSvalue(rawurlencode(GeneralUtility::getIndpEnv('REQUEST_URI'))),
                //'categories' => $this->categoryRepository->findTree($idList),
                //'filters' => $this->tsConfiguration['filters.'],
                //'enableFiltering' => $this->isFilteringEnabled(),
                'dateformat' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy']
            ];

            $this->showFlashMsg('OK', 'The records where updated', 'OK');
            $assignedValues = $this->emitActionSignal('ConvertdceController', self::SIGNAL_CONVERTDCE_UPDATEALL_ACTION, $assignedValues);
            $this->view->assignMultiple($assignedValues);
        } else {
            $this->showFlashMsg('', 'No records where updated', 'WARNING');
        }
    }
    
    
    protected function getTreePids($parent = 0, $as_array = true)
    {
        $depth = 999999;
        $queryGenerator = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance( 'TYPO3\\CMS\\Core\\Database\\QueryGenerator' );
        $childPids = $queryGenerator->getTreeList($parent, $depth, 0, 1); //Will be a string like 1,2,3
        if($as_array) {
            $childPids = explode(',',$childPids );
        }
        return $childPids;
    }
    
    
    protected function convertToContent($dceArray)
    {
        //print_r($dceArray);
        $xmlTemplate = '
                <?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="infoboxtype">
                    <value index="vDEF">###infoboxtype###</value>
                </field>
                <field index="header">
                    <value index="vDEF">###header###</value>
                </field>
                <field index="bodytext">
                    <value index="vDEF">###bodytext###</value>
                </field>
                <field index="email">
                    <value index="vDEF"></value>
                </field>
                <field index="backgroundcolor">
                    <value index="vDEF">###backgroundcolor###</value>
                </field>
                <field index="link">
                    <value index="vDEF">###link###</value>
                </field>
                <field index="readMoreLinkText">
                    <value index="vDEF"></value>
                </field>
                <field index="infoboxImage">
                    <value index="vDEF">###infoboxImage###</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>';
        if($dceArray) {
            foreach($dceArray as $key => $value) {
                $xmlTemplate = str_replace('###' . $key . '###', $value, $xmlTemplate);
            }
            return $xmlTemplate;
        }
    }
    
    
    protected function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
    
    
    /**
     * Create menu
     *
     */
    protected function createMenu()
    {
        //http://vkans-th0.kh.lth.lu.se/typo3/index.php?M=web_LthPackageMod1&moduleToken=c068f3984f15a804302522f73120bf95e862f1da&id=40518&tx_lthpackage_web_lthpackagemod1%5Baction%5D=indexAll&tx_lthpackage_web_lthpackagemod1%5Bcontroller%5D=Convertdce
        //http://vkans-th0.kh.lth.lu.se/typo3/index.php?M=web_LthPackageMod1&moduleToken=c068f3984f15a804302522f73120bf95e862f1da&id=40518&tx_lthpackage_web_lthpackagemod1%5Baction%5D=indexAll&tx_lthpackage_web_lthpackagemod1%5Bcontroller%5D=Convertdce
        
        //http://vkans-th0.kh.lth.lu.se/typo3/index.php?M=web_NewsTxNewsM2&moduleToken=75cd3fb1341ec5c3c9c8f094c0e136b179d1f300&id=40518&tx_news_web_newstxnewsm2%5Baction%5D=index&tx_news_web_newstxnewsm2%5Bcontroller%5D=Administration
        //http://vkans-th0.kh.lth.lu.se/typo3/index.php?M=web_NewsTxNewsM2&moduleToken=75cd3fb1341ec5c3c9c8f094c0e136b179d1f300&id=40518&tx_news_web_newstxnewsm2%5Baction%5D=newsPidListing&tx_news_web_newstxnewsm2%5Bcontroller%5D=Administration
        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        $menu = $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->makeMenu();
        $menu->setIdentifier('lthpackage');

        $actions = [
            ['action' => 'index', 'label' => 'Just this page'],
            ['action' => 'all', 'label' => 'This page and all sub pages']
        ];

        foreach ($actions as $action) {
            $item = $menu->makeMenuItem()
                ->setTitle($action['label'])
                ->setHref($uriBuilder->reset()->uriFor($action['action'], [], 'Convertdce'))
                ->setActive($this->request->getControllerActionName() === $action['action']);
            $menu->addMenuItem($item);
        }

        $this->view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry()->addMenu($menu);
        if (is_array($this->pageInformation)) {
            $this->view->getModuleTemplate()->getDocHeaderComponent()->setMetaInformation($this->pageInformation);
        }
    }
    
    
     /**
     * Create the panel of buttons
     *
     */
    protected function createButtons()
    {
        $buttonBar = $this->view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        $uriBuilder = $this->objectManager->get(UriBuilder::class);
        $uriBuilder->setRequest($this->request);

        if ($this->request->getControllerActionName() === 'index') {
            $toggleButton = $buttonBar->makeLinkButton()
                ->setHref('#')
                ->setDataAttributes([
                    'togglelink' => '1',
                    'toggle' => 'tooltip',
                    'placement' => 'bottom',
                ])
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:administration.toggleForm'))
                ->setIcon($this->iconFactory->getIcon('actions-filter', Icon::SIZE_SMALL));
            $buttonBar->addButton($toggleButton, ButtonBar::BUTTON_POSITION_LEFT, 1);
        }

        $buttons = [
            [
                'table' => 'tx_news_domain_model_news',
                'label' => 'module.createNewNewsRecord',
                'action' => 'newNews',
                'icon' => 'ext-news-type-default'
            ],
            [
                'table' => 'tx_news_domain_model_tag',
                'label' => 'module.createNewTag',
                'action' => 'newTag',
                'icon' => 'ext-news-tag'
            ],
            [
                'table' => 'sys_category',
                'label' => 'module.createNewCategory',
                'action' => 'newCategory',
                'icon' => 'mimetypes-x-sys_category'
            ]
        ];
        foreach ($buttons as $key => $tableConfiguration) {
            if ($this->showButton($tableConfiguration['table'])) {
                $title = $this->getLanguageService()->sL('LLL:EXT:news/Resources/Private/Language/locallang_be.xlf:' . $tableConfiguration['label']);
                $viewButton = $buttonBar->makeLinkButton()
                    ->setHref($uriBuilder->reset()->setRequest($this->request)->uriFor($tableConfiguration['action'],
                        [], 'Administration'))
                    ->setDataAttributes([
                        'toggle' => 'tooltip',
                        'placement' => 'bottom',
                        'title' => $title])
                    ->setTitle($title)
                    ->setIcon($this->iconFactory->getIcon($tableConfiguration['icon'], Icon::SIZE_SMALL, 'overlay-new'));
                $buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, 2);
            }
        }

        $clipBoard = GeneralUtility::makeInstance(Clipboard::class);
        $clipBoard->initializeClipboard();
        $elFromTable = $clipBoard->elFromTable('tx_news_domain_model_news');
        if (!empty($elFromTable)) {
            $viewButton = $buttonBar->makeLinkButton()
                ->setHref($clipBoard->pasteUrl('', $this->pageUid))
                ->setOnClick('return ' . $clipBoard->confirmMsg('pages',
                        BackendUtilityCore::getRecord('pages', $this->pageUid), 'into',
                        $elFromTable))
                ->setTitle($this->getLanguageService()->sL('LLL:EXT:lang/locallang_mod_web_list.xlf:clip_pasteInto'))
                ->setIcon($this->iconFactory->getIcon('actions-document-paste-into', Icon::SIZE_SMALL));
            $buttonBar->addButton($viewButton, ButtonBar::BUTTON_POSITION_LEFT, 4);
        }

        // Refresh
        $path = VersionNumberUtility::convertVersionNumberToInteger(TYPO3_branch) >= VersionNumberUtility::convertVersionNumberToInteger('8.6') ? 'Resources/Private/Language/' : '';
        $refreshButton = $buttonBar->makeLinkButton()
            ->setHref(GeneralUtility::getIndpEnv('REQUEST_URI'))
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:lang/' . $path . 'locallang_core.xlf:labels.reload'))
            ->setIcon($this->iconFactory->getIcon('actions-refresh', Icon::SIZE_SMALL));
        $buttonBar->addButton($refreshButton, ButtonBar::BUTTON_POSITION_RIGHT);
    }
    
    
    /**
     * @param string $table
     * @return bool
     */
    protected function showButton($table)
    {
        if (!$this->getBackendUser()->check('tables_modify', $table)) {
            return false;
        }

        // No deny/allow tables are set:
        if (empty($this->allowedNewTables) && empty($this->deniedNewTables)) {
            return true;
        }

        $showButton = !in_array($table, $this->deniedNewTables, true) &&
            (empty($this->allowedNewTables) || in_array($table, $this->allowedNewTables, true));

        return $showButton;
    }


    /**
     * If defined in TsConfig with tx_news.module.redirectToPageOnStart = 123
     * and the current page id is 0, a redirect to the given page will be done
     *
     */
    protected function redirectToPageOnStart()
    {
        if ((int)$this->tsConfiguration['allowedPage'] > 0 && $this->pageUid !== (int)$this->tsConfiguration['allowedPage']) {
            $url = 'index.php?M=web_NewsTxNewsM2&id=' . (int)$this->tsConfiguration['allowedPage'] . $this->getToken();
            HttpUtility::redirect($url);
        } elseif ($this->pageUid === 0 && (int)$this->tsConfiguration['redirectToPageOnStart'] > 0) {
            $url = 'index.php?M=web_NewsTxNewsM2&id=' . (int)$this->tsConfiguration['redirectToPageOnStart'] . $this->getToken();
            HttpUtility::redirect($url);
        }
    }

    /**
     * Get a CSRF token
     *
     * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
     * @return string
     */
    protected function getToken($tokenOnly = false)
    {
        $token = FormProtectionFactory::get()->generateToken('moduleCall', 'web_NewsTxNewsM2');
        if ($tokenOnly) {
            return $token;
        } else {
            return '&moduleToken=' . $token;
        }
    }

    /**
     * Returns the LanguageService
     *
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

    /**
     * Get backend user
     *
     * @return \TYPO3\CMS\Core\Authentication\BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
    
    
    /**
     * Emits signal for various actions
     *
     * @param string $classPart last part of the class name
     * @param string $signalName name of the signal slot
     * @param array $signalArguments arguments for the signal slot
     *
     * @return array
     */
    protected function emitActionSignal($classPart, $signalName, array $signalArguments)
    {
        $signalArguments['extendedVariables'] = [];
        return $this->signalSlotDispatcher->dispatch('LTH\\LthPackage\\Controller\\' . $classPart, $signalName, $signalArguments);
    }
    
    
    protected function showFlashMsg($msgHeader, $msgText, $type)
    {
        if($type==='OK') {
            $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessage::class,
                $msgText,
                $msgHeader,
                \TYPO3\CMS\Core\Messaging\FlashMessage::OK, // [optional] the severity defaults to \TYPO3\CMS\Core\Messaging\FlashMessage::OK
                true // [optional] whether the message should be stored in the session or only in the \TYPO3\CMS\Core\Messaging\FlashMessageQueue object (default is false)
            );
        }
        
        if($type==='WARNING') {
            $message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessage::class,
                $msgText,
                $msgHeader,
                \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING, // [optional] the severity defaults to \TYPO3\CMS\Core\Messaging\FlashMessage::OK
                true // [optional] whether the message should be stored in the session or only in the \TYPO3\CMS\Core\Messaging\FlashMessageQueue object (default is false)
            );
        }
        
                    $flashMessageService = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Messaging\FlashMessageService::class);
                    $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();
                    $defaultFlashMessageQueue->enqueue($message);

        /*
         * The severity is defined by using class constants provided by \TYPO3\CMS\Core\Messaging\FlashMessage:
\TYPO3\CMS\Core\Messaging\FlashMessage::WARNING for notifications
\TYPO3\CMS\Core\Messaging\FlashMessage::INFO for information messages
\TYPO3\CMS\Core\Messaging\FlashMessage::OK for success messages
\TYPO3\CMS\Core\Messaging\FlashMessage::WARNING for warnings
\TYPO3\CMS\Core\Messaging\FlashMessage::ERROR for errors
         */
    }
}
