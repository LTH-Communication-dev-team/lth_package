<?php
namespace LTH\LthPackage\DataProcessing;

/*
 *  The MIT License (MIT)
 *
 *  Copyright (c) 2015 Benjamin Kott, http://www.bk2k.info
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in
 *  all copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\FlexFormService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Minimal TypoScript configuration
 * Process field pi_flexform and overrides the values stored in data
 *
 * 10 = LTH\LthPackage\DataProcessing\FlexFormProcessor
 *
 *
 * Advanced TypoScript configuration
 * Process field assigned in fieldName and stores processed data to new key
 *
 * 10 = LTH\LthPackage\DataProcessing\FlexFormProcessor
 * 10 {
 *   fieldName = pi_flexform
 *   as = flexform
 * }
 */
class FlexFormProcessor implements DataProcessorInterface
{
    /**
     * @var FlexFormService
     */
    protected $flexFormService;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
    }

    /**
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(ContentObjectRenderer $cObj, array $contentObjectConfiguration, array $processorConfiguration, array $processedData)
    {
        // The field name to process
        $fieldName = $cObj->stdWrapValue('fieldName', $processorConfiguration);
        if (empty($fieldName) && !$processedData['data']['pi_flexform']) {
            return $processedData;
        } else {
            $fieldName = 'pi_flexform';
        }

        // Process Flexform
        $originalValue = $processedData['data'][$fieldName];
        if (!is_string($originalValue)) {
            return $processedData;
        }
        $flexformData = $this->flexFormService->convertFlexFormContentToArray($originalValue);
        
        //check sys file reference
        if($flexformData['figureImage'] || $flexformData['carouselImages'] || $flexformData['bannerImage'] || $flexformData['titleImage'] || $flexformData['toggleImage']) {
            $i=0;
            $scope = $flexformData['figureImage'] . $flexformData['carouselImages'] . $flexformData['bannerImage'] . $flexformData['titleImage'] . $flexformData['toggleImage'];
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery("uid,title,description,alternative,link,crop","sys_file_reference",
                    "uid IN(" . addslashes($scope) .")","","","");
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
                $flexformData['imageItemsTemp'][$row['uid']] = array('uid' => $row['uid'], 'description' => $row['description'], 'title' => $row['title'], 
                    'alternative' => $row['alternative'], 'link' => $row['link'], 'crop' => $row['crop']);
            }
            $sortArray = explode(',',$scope);
            foreach($sortArray as $sortKey => $sortValue) {
                $flexformData['imageItems'][] = $flexformData['imageItemsTemp'][$sortValue];
            }
            $GLOBALS['TYPO3_DB']->sql_free_result($res);
        }
        //get sys color
        if($flexformData['backgroundcolor']) {
            $flexformData['backgroundcolor'] = str_replace('.gif','',$flexformData['backgroundcolor']);
        }
        
        if($flexformData['icons']) {
            $i = 0;
            if($flexformData['infoboxtype'] === 'icons') {
                
                $ii = 0;
                foreach($flexformData['icons'] as $key => $value) {
                    $i++;
                    $flexformData['iconItems'][$ii][$i]['title'] = $value['container']['title'];
                    $flexformData['iconItems'][$ii][$i]['bodytext'] = $value['container']['bodytext'];
                    $flexformData['iconItems'][$ii][$i]['icon'] = str_replace('.jpg','',$value['container']['icon']);
                    if ($i === 2) {
                        $ii++;
                        $i=0;
                    }

                }
            } else {
                foreach($flexformData['icons'] as $key => $value) {
                    $flexformData['iconItems'][$i]['title'] = $value['container']['title'];
                    $flexformData['iconItems'][$i]['bodytext'] = $value['container']['bodytext'];
                    $flexformData['iconItems'][$i]['icon'] = $value['container']['icon'];
                    $i++;
                }
            }
        }
        
        if($flexformData['links']) {
            $i = 0;
            foreach($flexformData['links'] as $key => $value) {
                $flexformData['linkItems'][$i]['linktext'] = $value['container']['linktext'];
                $flexformData['linkItems'][$i]['link'] = $value['container']['link'];
                $i++;
            }
        }
        
        if($flexformData['infographicItems']) {
            if($flexformData['infographicstype'] === 'animateonscroll') {
                $i = 0;
                $tmpTitle;
                foreach($flexformData['infographicItems'] as $key => $value) {

                    //$flexformData['infographicItem'][$i]['title'] = $value['container']['title'];
                    $tmpTitle = str_replace(' ','',$value['container']['title']);
                    if(strstr($tmpTitle,'%')) {
                        if($i > 0) {
                            $flexformData['infographicItem'][$i]['title'] = '<p class="h2 m-0"><span class="count-up" data-delay="' . (0.2 + ($i*0.1)) . '">' . str_replace('%','',$tmpTitle) . '</span>%</p>';
                        } else {
                            $flexformData['infographicItem'][$i]['title'] = '<p class="h2 m-0"><span class="count-up">' . str_replace('%','',$tmpTitle) . '</span>%</p>';
                        }
                    } else if(strstr($tmpTitle,'-')) {
                        if($i > 0) {
                            $flexformData['infographicItem'][$i]['title'] = '<p class="h2 m-0">' .
                                    array_shift(explode('-',$tmpTitle)) . '-<span class="count-up" data-start="' .
                                    array_shift(explode('-',$tmpTitle)) . '" data-delay="' . (0.2 + ($i*0.1)) . '">' .
                                    array_pop(explode('-',$tmpTitle)) . '</span></p>';
                        } else {
                            $flexformData['infographicItem'][$i]['title'] = '<p class="h2 m-0">' .
                                    array_shift(explode('-',$tmpTitle)) . '-<span class="count-up" data-start="' .
                                    array_shift(explode('-',$tmpTitle)) . '">' .
                                    array_pop(explode('-',$tmpTitle)) . '</span></p>';
                        }
                    } else {
                        if($i > 0) {
                            $flexformData['infographicItem'][$i]['title'] = '<p class="h2 m-0 count-up" data-delay="' . (0.2 + ($i*0.1)) . '">' . $tmpTitle . '</p>';
                        } else {
                            $flexformData['infographicItem'][$i]['title'] = '<p class="h2 m-0 count-up">' . $tmpTitle . '</p>';
                        }
                    }
                    $flexformData['infographicItem'][$i]['subtitle'] = $value['container']['subtitle'];
                    $flexformData['infographicItem'][$i]['icon'] = $value['container']['icon'];

                    if($i > 0) {
                        $flexformData['infographicItem'][$i]['dataaosdelay'] = ' data-aos-delay=' . ($i*100);
                        //$flexformData['infographicItem'][$i]['datadelay'] = ' data-delay=' . (0.2 + ($i*0.1));
                    }
                    $i++;
                }
            } else if($flexformData['infographicstype'] === 'circleprogress') {
                $i = 0;
                $tmpTitle;
                foreach($flexformData['infographicItems'] as $key => $value) {
                    
                    $tmpTitle = str_replace(' ','', str_replace('%','', trim($value['container']['title'])));
                    $flexformData['infographicItem'][$i]['title'] = $tmpTitle;
                    $flexformData['infographicItem'][$i]['dataValue'] = (intval($tmpTitle) / 100);
                    $flexformData['infographicItem'][$i]['subtitle'] = $value['container']['subtitle'];
                    $i++;
                }
            }
        }
        
        if($flexformData['navblockLinks']) {
            $i=0;
            foreach($flexformData['navblockLinks'] as $key => $value) {
                $flexformData['navblockLink'][$i]['linktext'] = $value['container']['linktext'];
                $flexformData['navblockLink'][$i]['link'] = $value['container']['link'];
                $i++;
            }
        }
        
        if($flexformData['toggleItems']) {
            $i=0;
            foreach($flexformData['toggleItems'] as $key => $value) {
                $flexformData['toggleItem'][$i]['toggleshowhide'] = $value['container']['toggleshowhide'];
                $flexformData['toggleItem'][$i]['bodytext'] = $value['container']['bodytext'];
                $i++;
            }
        }
        
        if($flexformData['gridbuilder']) {
            $content = '';
            $oldX = '';
            $oldY = '';
            $grid = $flexformData['gridbuilder'];
            $gridArray = json_decode($grid, true);
            //echo '1. Hur många rader finns det: ' . count($this->array_unique_deep($gridArray,'y'));
            //echo '1. Hur många kolumner finns det: ' . count($this->array_unique_deep($gridArray,'x'));
            
                   
            //$this->sortBy("x", $gridArray);
            /*array_multisort(array_column($gridArray, 'x'), SORT_ASC,
                array_column($gridArray, 'y'),      SORT_ASC,
                $gridArray);
            foreach($gridArray as $key => $val) {
                if($val['x'] != $oldX) {
                    $content .= '<div class="masonry-col masonry-col-50">';
                }
            }*/
            foreach($gridArray as $key => $value) {
                $gridContent = $value['c'];
                $gridContent = explode('||',$gridContent);
                $gridContentTitle = $gridContent[0];
                $gridContentText = $gridContent[1];
                $gridContentImage = $gridContent[2];
                $gridContentEmbed = $gridContent[3];
                $gridContentHtml = $gridContent[4];
            }
            $flexformData['gridbuilder'] = json_encode($gridArray);
            /*echo '<pre>';
            print_r($gridArray);
            echo '</pre>';*/
            
            /*echo '<pre>';
            print_r($this->array_unique_deep($gridArray,'x'));
            echo '</pre>';*/
            //$flexformData['gridbuilder'] = $this->createGrid($gridArray, $final = NULL);
            /*$i=0;
            foreach($flexformData['toggleItems'] as $key => $value) {
                $flexformData['toggleItem'][$i]['toggleshowhide'] = $value['container']['toggleshowhide'];
                $flexformData['toggleItem'][$i]['bodytext'] = $value['container']['bodytext'];
                $i++;
            }*/
            //return $gridArray;
        }
            
            
        // Set the target variable
        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration);
        if (!empty($targetVariableName)) {
            $processedData[$targetVariableName] = $flexformData;
        } else {
            $processedData['data'][$fieldName] = $flexformData;
        }
        
        return $processedData;
    }
    
    
    function sortBy($field, &$array, $direction = 'asc')
    {
        usort($array, create_function('$a, $b', '
            $a = $a["' . $field . '"];
            $b = $b["' . $field . '"];

            if ($a == $b)
            {
                return 0;
            }

            return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
        '));

        return true;
    }

    
    function array_unique_deep($array, $key)
    {
      $values = array();
      foreach ($array as $k1 => $row)
      {
        foreach ($row as $k2 => $v)
        {
          if ($k2 == $key)
          {
            $values[ $k1 ] = $v;
            continue;
          }
        }
      }
      return array_unique($values);
    }

    
    function createGrid($struct, $final = NULL) {
	if ( !is_array($struct) ) {
		return false;	
	}
	/**
	 *	If not first loop, and if not the final
	 * 	array elements, wrap with a row.
	 */
	$str = $final === false ? '<div class="row">' : '';
	foreach ( $struct as $k => $row ) {
		if ( is_array($row) ) {
			// Does $row contain arrays?
			$finalDepth = true;
			foreach ( $row as $v ) {
				if ( is_array($v) ) {
					$finalDepth = false;	
				}
			}
			// Sub-row, so get the grid size
			$dot = stristr($k, '.');
			if ( $dot !== false ) {
				$size = trim($dot, '.');
				$str .= '<div class="col-lg-'.$size.'">';
				$finalDepth = true;
			}
			$str .= $this->createGrid($row, $finalDepth);
			if ( stristr($k, '.') !== false ) {
				$str .= '</div>';	
			}
		} else {
			// Maximum depth, so wrap with a grid
			$str .= '<div class="col-lg-'.$struct['w'].'"><pre>Grid '.$struct['w'].'</pre></div>';
		}
	}
	$str .= $final === false ? '</div>' : '';
	return $str;
}
}