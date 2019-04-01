<?php
namespace Lth\Lthpackage\Slots;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QueryResult;
use GeorgRinger\News\Domain\Model\News;
use GeorgRinger\News\Domain\Model\Dto\NewsDemand;
use GeorgRinger\News\Domain\Repository\NewsRepository;

/**
 * News Controller Slot
 */
class NewsControllerSlot
{
    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \GeorgRinger\News\Domain\Repository\NewsRepository
     */
    protected $newsRepository;

    /**
     * @return void
     */
    public function __construct()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->newsRepository = $this->objectManager->get(NewsRepository::class);
    }

    /**
     * @param QueryResult $news
     * @param array $overrideDemand
     * @param array $demand
     * @return array
     */
    public function listActionSlot(QueryResult $news = null, $overrideDemand, NewsDemand $demand)
    {
        // Get tags from resultset
        $currentTags = $demand->getTags();
        $demand->setTags('');
        $filteredNewsRecords = $this->newsRepository->findDemanded($demand);
        $demand->setTags($currentTags);
        $tags = array();
        foreach ($filteredNewsRecords as $record) {
            foreach ($record->getTags() as $tag) {
                $tags[] = $tag;
            }
        }
        $uniqueTags = array_unique($tags);

        // Get headquarter
        $headquarter = $this->officeRepository->findByType(0)->getFirst();

        // Assign values
        $assignedValues = [
            'news' => $news,
            'overwriteDemand' => $overwriteDemand,
            'demand' => $demand,
            'extendedVariables' => [
                'tags' => $uniqueTags,
                'headquarter' => $headquarter
            ]
        ];
        return $assignedValues;
    }

    /**
     * @param News $newsItem
     * @param int $currentPage
     * @param array $demand
     * @return array
     */
    public function detailActionSlot(News $newsItem = null, $currentPage, NewsDemand $demand)
    {
        // Get headquarter
        //$headquarter = $this->officeRepository->findByType(0)->getFirst();
//$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => $newsItem.uid, 'crdate' => time()));
        $newsUid = $newsItem->getUid();
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery("pi_flexform, cType","tt_content","tx_news_related_news = " . intval($newsUid) . " AND deleted=0 AND CType != 'textmedia'","","","");
        while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
            $pi_flexform = $row['pi_flexform'];
            $cType = $row['cType'];
            if($pi_flexform) {
                $xml = simplexml_load_string($pi_flexform);
                $test = $xml->data->sheet[0]->language;
                if($test) {
                    foreach ($test->field as $n) {
                        foreach($n->attributes() as $name => $val) {
                            $resArray[(string)$val] = (string)$n->value;                           
                        }
                    }
                }
            }
        }
        $GLOBALS['TYPO3_DB']->sql_free_result($res);

        $assignedValues = [
            'newsItem' => $newsItem,
            'currentPage' => $currentPage,
            'demand' => $demand,
            'extendedVariables' => [
                'data' => [
                    'pi_flexform' => $resArray,
                    'cType' => $cType,
                ]
            ]
        ];
        return $assignedValues;
    }
}