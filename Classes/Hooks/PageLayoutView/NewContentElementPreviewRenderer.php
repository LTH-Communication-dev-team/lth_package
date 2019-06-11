<?php
namespace LTH\LthPackage\Hooks\PageLayoutView;

/*
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

use \TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use \TYPO3\CMS\Backend\View\PageLayoutView;

/**
 * Contains a preview rendering for the page module of CType="yourextensionkey_newcontentelement"
 */
class NewContentElementPreviewRenderer implements PageLayoutViewDrawItemHookInterface
{

   /**
    * Preprocesses the preview rendering of a content element of type "My new content element"
    *
    * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
    * @param bool $drawItem Whether to draw the item using the default functionality
    * @param string $headerContent Header content
    * @param string $itemContent Item content
    * @param array $row Record row of tt_content
    *
    * @return void
    */
    public function preProcess(PageLayoutView &$parentObject,&$drawItem,&$headerContent,&$itemContent,array &$row)
    {
        if ($row['CType'] === 'infobox') {
            $pi_flexform = $row['pi_flexform'];
            if($pi_flexform) {
                $xml = simplexml_load_string($pi_flexform);
                $test = $xml->data->sheet[0]->language;
                if($test) {
                    foreach ($test->field as $n) {
                        foreach($n->attributes() as $name => $val) {
                            if ($val == 'header') {
                                $header = (string)$n->value;
                            }
                            if ($val == 'bodytext') {
                                $bodytext = (string)$n->value;
                            }
                            if ($val == 'infoboxtype') {
                                $infoboxtype = (string)$n->value;
                            }
                        }
                    }
                }
            }
            if($header) {
                if(strlen($header) > 50) $header = substr ($header, 0, 49);
                $headerContent .= '<b>Infobox (' . $infoboxtype . ')<br />' . $header . '</b>';
            } else {
                $headerContent .= '<b>Infobox (' . $infoboxtype . ')</b>';
            }
            if($bodytext) {
                if(strlen($bodytext) > 100) $bodytext = substr ($bodytext, 0, 99);
                $itemContent .= '<p>' . strip_tags($bodytext) . '</p>';
            }
            $drawItem = false;
        } else if ($row['CType'] === 'masonrytile') {
            $pi_flexform = $row['pi_flexform'];
            if($pi_flexform) {
                $xml = simplexml_load_string($pi_flexform);
                $test = $xml->data->sheet[0]->language;
                if($test) {
                    foreach ($test->field as $n) {
                        foreach($n->attributes() as $name => $val) {
                            if ($val == 'header') {
                                $header = (string)$n->value;
                            }
                            if ($val == 'bodytext') {
                                $bodytext = (string)$n->value;
                            }
                        }
                    }
                }
            }
            if($header) {
                if(strlen($header) > 50) $header = substr ($header, 0, 49);
                $headerContent .= '<b>Masonry Tile<br />' . $header . '</b>';
            } else {
                $headerContent .= '<b>Masonry Tile</b>';
            }
            if($bodytext) {
                if(strlen($bodytext) > 100) $bodytext = substr ($bodytext, 0, 99);
                $itemContent .= '<p>' . strip_tags($bodytext) . '</p>';
            }
            $drawItem = false;
        }
    }
}