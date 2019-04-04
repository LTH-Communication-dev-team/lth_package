<?php
$content;
$action = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('action');
$data = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('data');
$syslang = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('syslang');
$dataSettings = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP('dataSettings');
$sid = \TYPO3\CMS\Core\Utility\GeneralUtility::_GP("sid");
date_default_timezone_set('Europe/Stockholm');
$settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lth_solr']);
        
$config = array(
    'endpoint' => array(
        'localhost' => array(
            'host' => $settings['solrHost'],
            'port' => $settings['solrPort'],
            'path' => "/solr/core_$syslang/",//$settings['solrPath'],
            'timeout' => $settings['solrTimeout']
        )
    )
);

if (!$settings['solrHost'] || !$settings['solrPort'] || !$settings['solrPath'] || !$settings['solrTimeout']) {
    return 'Please make all settings in extension manager';
}

switch($action) {
    case 'publications':
        if($data['uuid']) {
            $content = showPublication($data, $config);
        } else if($data['display'] === 'tagcloud') {
            $content = listTagCloud($data, $config);
        } else if($data['display'] === 'studentpapers') {
            $content = listStudentPapers($data, $config);
        } else {
            $content = listPublications($data, $config);
        }
        break;
    case 'staff':
        if($data['uuid']) {
            $content = showStaff($data, $config);
        } else {
            $content = listStaff($data, $config);
        }
        break;
    case 'listCalendar':
        $content = listCalendar($data, $config);
        //$content = sucker($data, $config);
        break;
    case 'showCalendar':
        $content = showCalendar($data, $config);
        break;
    case 'portalCalendar':
        $content = portalCalendar($data, $config);
        break;
    case 'getSingleContact':
        $content = getSingleContact($dataSettings, $config);
        break;
}

print $content;


function getSingleContact($dataSettings, $config)
{
    $fieldArray = array("firstName","lastName","primaryVroleTitle","phone","id","email","mailDelivery","organisationName","primaryAffiliation",
        "homepage","image","roomNumber","mobile","lucrisPhoto");
    
    $email = $dataSettings['email'];
    $sysLang = $dataSettings['sysLang'];
    $staffData = array();
       
    $client = new Solarium\Client($config);
    $query = $client->createSelect();
    $query->setStart(0)->setRows(100);
    
    //Staff
    if($email) {
        $term .= ' AND ('; 
        $emailArray = explode(',', $email);
        foreach($emailArray as $key => $value) {
            if($i>0) $term .= ' OR ';
            $term .= 'email:' . $value;
            $i++;
        }
        $term .= ')';
    }
    $queryToSet = 'docType:staff' . $term;
    $query->setQuery($queryToSet);
    $query->setFields($fieldArray);
    $response = $client->select($query);

    foreach ($response as $document) {    
        $id = $document->id;
        $docType = $document->docType;

        $image='';
        if($document->image) {
            $image = $document->image;
            if(!stristr($image, 'fileadmin')) $image = '/fileadmin' . $image;
            if(substr($image,0,1) !== '/') $image = '/' . $image;
        } else if($document->lucrisPhoto) {
            $image = $document->lucrisPhoto;
        }

        $data[] = array(
            "email" => $document->email[0],
            "name" => $document->firstName . ' ' . $document->lastName,
            "mailDelivery" => $document->mailDelivery,
            "title" => $document->primaryVroleTitle,
            "phone" => $document->phone,
            "organisationName" => $document->organisationName,
            "primaryAffiliation" => $document->primaryAffiliation,
            "homepage" => $document->homepage,
            "image" => $image,
            "roomNumber" => $document->roomNumber,
            "mobile" => $document->mobile,
        );
    }
    
    $resArray = array('data' => $data, 'query' => $queryToSet);
    
    return json_encode($resArray);
}


/*************************NEWS*********************************************/


function sucker()
{
    $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lth_package']);
    $syslang = addslashes($data['syslang']);
    if(!$syslang) $syslang = "sv";
    $currentDate = gmDate("Y-m-d\TH:i:s\Z");
    $setStart = intval($data['setStart']);

    $config = array(
        'endpoint' => array(
            'localhost' => array(
                'host' => $settings['solrHost'],
                'port' => $settings['solrPort'],
                'path' => "/solr/core_$syslang/",//$settings['solrPath'],
                'timeout' => $settings['solrTimeout']
            )
        )
    );
    $fieldArray = array("id","title","categoryId","categoryName","startTime","endTime","location","pathalias");
    
    $client = new Solarium\Client($config);
    // get a select query instance
    $query = $client->createSelect();
    $queryToSet = 'docType:calendar';
    $query->setQuery($queryToSet);
    $query->setFields(array('id'));
    // cursor functionality can be used for efficient deep paging (since Solr 4.7)
    $query->setCursormark('*');
    // cursor functionality requires a sort containing a uniqueKey field as tie breaker on top of your desired sorts for the query
    $query->addSorts(array("startTime" => "asc","id" => "asc"));
    // get a plugin instance and apply settings
    $prefetch = $client->getPlugin('prefetchiterator');
    $prefetch->setPrefetch(2); //fetch 2 rows per query (for real world use this can be way higher)
    $prefetch->setQuery($query);
    // display the total number of documents found by solr
    //echo 'NumFound: ' . count($prefetch);
    // show document IDs using the resultset iterator
    foreach ($prefetch as $document) {
        $data[] = array(
            "id" => $document->id
        );
    }
$resArray = array('data' => $data, 'facet' => $facetResult, 'query' => $queryToSet . count($prefetch));
    return json_encode($resArray);
}


function portalCalendar($data, $config)
{    
    $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lth_package']);
    $syslang = addslashes($data['syslang']);
    if(!$syslang) $syslang = "sv";
    $currentDate = gmDate("Y-m-d\TH:i:s\Z");
    $setStart = intval($data['setStart']);
    $more = (string)$data['more'];

    $config = array(
        'endpoint' => array(
            'localhost' => array(
                'host' => $settings['solrHost'],
                'port' => $settings['solrPort'],
                'path' => "/solr/core_$syslang/",//$settings['solrPath'],
                'timeout' => $settings['solrTimeout']
            )
        )
    );
    $fieldArray = array("id","title","categoryId","categoryName","startTime","endTime","location","lead","image");
    
    $client = new Solarium\Client($config);
    
    $query = $client->createSelect();

    $query->addSorts(array("dateOrder" => "asc"));
    if($more==='false') {
        $queryToSetTopList = 'docType:calendar AND (startTime:[' . substr($currentDate,0,10) . 'T00:00:00Z TO *])';
        $queryToSetCurrent = 'docType:calendar AND (startTime:[* TO ' . $currentDate . '] AND endTime:[' . $currentDate . ' TO *])';
        $query->setStart($setStart)->setRows(11);
    } else {
        $queryToSetTopList = 'docType:calendar AND (startTime:[' . substr($currentDate,0,10) . 'T00:00:00Z TO *])';
        //$queryToSet = 'docType:calendar AND startTime:[* TO ' . $currentDate . ']';
        //$query->addSorts(array("dateOrder" => "desc"));
        $query->setStart(abs($setStart * 8))->setRows(8);
    }
    
    if($data["calId"]) {
        $queryToSet = ' AND calender_ids:' . $calId;
    }
    
    $query->setFields($fieldArray);
    
    $query->setQuery($queryToSetTopList);
    
    $facetSet = $query->getFacetSet();
        
    $facetSet->createFacetField('category')->setField('categoryName');
    
    $responseTopList = $client->select($query);
    
    $numFound = $responseTopList->getNumFound();

    $facetCategory = $responseTopList->getFacetSet()->getFacet('category');
    
    foreach ($facetCategory as $value => $count) {
        if($count > 0) $facetResult["category"][] = array($value, $count, $facetHeader);
    }
    
    if($more==='false') {
        $query->setStart(0)->setRows(3);
        $query->setQuery($queryToSetCurrent);
        $responseCurrent = $client->select($query);
    }
    
    $dataTop = array();
    $dataList = array();
    $dataTemp = array();
    $i = 0;
    if($responseTopList) {
        foreach ($responseTopList as $document) {
            $dataTemp = array(
                "id" => $document->id,
                "title" => $document->title,
                "startTime" => $document->startTime,
                "endTime" => $document->endTime,
                "location" => $document->location,
                "categoryName" => $document->categoryName,
                "lead" => $document->lead,
                "image" => $document->image
            );
            if($i < 3 && $more==='false') {
                $dataTop[] = $dataTemp;
            } else {
                $dataList[] = $dataTemp;
            }
            $i++;
        }
    }
        
    if($responseCurrent) {
        foreach ($responseCurrent as $document) {
            $dataCurrent[] = array(
                "id" => $document->id,
                "title" => $document->title,
                "startTime" => $document->startTime,
                "endTime" => $document->endTime,
                "location" => $document->location,
                "categoryName" => $document->categoryName,
                "lead" => $document->lead,
                "image" => $document->image
            );
        }
    }
        
    
    $resArray = array('dataTop' => $dataTop, 'dataList' => $dataList, 'dataCurrent' => $dataCurrent, 'facet' => $facetResult, 'numFound' => $numFound, 'query' => $queryToSet);
    
    return json_encode($resArray);
}


function listCalendar($data, $config)
{    
    $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lth_package']);
    $syslang = addslashes($data['syslang']);
    if(!$syslang) $syslang = "sv";
    $currentDate = gmDate("Y-m-d\TH:i:s\Z");
    $setStart = intval($data['setStart']);

    $config = array(
        'endpoint' => array(
            'localhost' => array(
                'host' => $settings['solrHost'],
                'port' => $settings['solrPort'],
                'path' => "/solr/core_$syslang/",//$settings['solrPath'],
                'timeout' => $settings['solrTimeout']
            )
        )
    );
    $fieldArray = array("id","title","categoryId","categoryName","startTime","endTime","location","pathalias");
    
    $client = new Solarium\Client($config);
    
    $query = $client->createSelect();

    /*if(intval($setStart) >= 0) {
        $queryToSet = 'docType:calendar AND startTime:[' . $currentDate . ' TO *]';
        $query->addSorts(array("dateOrder" => "asc"));
        $query->setStart($setStart)->setRows(6);
    } else {
        $queryToSet = 'docType:calendar AND startTime:[* TO ' . $currentDate . ']';
        $query->addSorts(array("dateOrder" => "desc"));
        $query->setStart(abs($setStart + 6))->setRows(6);
    }*/
    $queryToSet = 'docType:calendar';
    
    if($data["calId"]) {
        $queryToSet .= ' AND calendarIds:' . $data["calId"];
    }
    
    if($data["catId"] && $data["catId"] !== 'events') {
        //$queryToSet .= ' AND categoryName:' . urldecode($data["catId"]);
        $query->addFilterQuery(array('key' => 'category', 'query' => 'categoryName:'. urldecode($data["catId"]), 'tag'=>'dt'));
    }
    
    if($data["query"]) {
        $query->addFilterQuery(array('key' => 'query', 'query' => 'title:*'. $data["query"] . '*', 'tag'=>'dt'));
    }
    
    if($data["startTime"]) {
        $queryToSet .= ' AND startTime:['.$data['startTime'].' TO ' . $data['endTime'] . ']';
    }
    
    $query->setQuery($queryToSet);
    
    $query->setStart(0)->setRows($data['numRows']);
    
    $query->setFields($fieldArray);
    
    $sortArray = array(
        'startTime' => 'asc'
    );
    
    $query->addSorts($sortArray);
    
    $facetSet = $query->getFacetSet();
        
    //$facetSet->createFacetField('category')->setField('categoryName');
    $facetSet->createFacetField('category')->setField('categoryName')->setExcludes(array("dt"));
    
    $response = $client->select($query);
    
    $numFound = $response->getNumFound();

    $facetCategory = $response->getFacetSet()->getFacet('category');
    
    foreach ($facetCategory as $value => $count) {
        if($count > 0) $facetResult["category"][] = array($value, $count, $facetHeader);
    }
    
    $data = array();
    
    foreach ($response as $document) {
        $data[] = array(
            "id" => $document->id,
            "title" => fixChar($document->title),
            "startTime" => $document->startTime,
            "endTime" => $document->endTime,
            "location" => $document->location,
            "categoryName" => $document->categoryName,
            "pathalias" => $document->pathalias,
        );
    }
    $resArray = array('data' => $data, 'facet' => $facetResult, 'query' => $queryToSet);
    return json_encode($resArray);
}


function fixChar($input)
{
    if($input) {
        $input = str_replace(':','', $input);
        $input = str_replace('#','', $input);
        $input = str_replace('?','', $input);
    }
    return $input;
}


function showCalendar($data, $config)
{
    $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lth_package']);
    $syslang = $data['syslang'];
    $eventId = $data['eventId'];
    $calId = $data['calId'];
    if(!$syslang) $syslang = "sv";

    $config = array(
        'endpoint' => array(
            'localhost' => array(
                'host' => $settings['solrHost'],
                'port' => $settings['solrPort'],
                'path' => "/solr/core_$syslang/",//$settings['solrPath'],
                'timeout' => $settings['solrTimeout']
            )
        )
    );
    $fieldArray = array("id","abstract","categoryId","categoryName","dateOrder","image","imageCaption","lead","startTime","endTime","location","title");
    
    $client = new Solarium\Client($config);
    
    $query = $client->createSelect();

    $queryToSet = 'docType:calendar AND id:' . addslashes($eventId);
    
    $query->setQuery($queryToSet);
    
    $query->setFields($fieldArray);
    
    $query->setStart(0)->setRows(1);
        
    $response = $client->select($query);
    
    foreach ($response as $document) {
        $data = array(
            "abstract" => $document->abstract,
            "categoryName" => $document->categoryName,
            "dateOrder" => $document->dateOrder,
            "endTime" => $document->endTime,
            "id" => $document->id,
            "image" => $document->image,
            "imageCaption" => $document->imageCaption,
            "lead" => $document->lead,
            "location" => $document->location,
            "startTime" => $document->startTime,
            "title" => $document->title,
        );
    }
    
    if($calId && $data['dateOrder']) {
        //get prev and next
        $queryToSet = 'docType:calendar AND calendar_ids:lth_se AND dateOrder:[' . (intval($data['dateOrder']) + 1) . ' TO *]';
        $query->setStart(0)->setRows(1);
        $query->setFields(array("id","title"));
        $query->addSorts(array("dateOrder" => "asc"));
        $query->setQuery($queryToSet);
        $response = $client->select($query);
        foreach ($response as $document) {
            $data["nextId"] = $document->id;
            $data["nextTitle"] = $document->title;
        };
        $queryToSet = 'docType:calendar AND calendar_ids:lth_se AND dateOrder:[* TO ' . (intval($data['dateOrder']) - 1) . ']';
        $query->setStart(0)->setRows(1);
        $query->setFields(array("id","title"));
        $query->addSorts(array("dateOrder" => "desc"));
        $query->setQuery($queryToSet);
        $response = $client->select($query);
        foreach ($response as $document) {
            $data["prevId"] = $document->id;
            $data["prevTitle"] = $document->title;
        };
    }
    
    $resArray = array('data' => $data, 'query' => $queryToSet);
    return json_encode($resArray);
}

/*************************PUBLICATIONS*************************************/

function listPublications($data, $config)
{
    $scope = array();
    $filterQuery;
    $keyword;
    $selection; //???
    $term;
    $tableStart = $data['tableStart'];
    $noItemsToShow = $data['noItemsToShow'];

    if($data['feGroups']) {
        $scope = getFegroups($scope, $data['feGroups']);
    }
    
    if($data['feUsers']) {
        $scope = getFeusers($scope, $data['feUsers']);
    }
    
    $currentDate = gmDate("Y-m-d\TH:i:s\Z");
    
    $client = new Solarium\Client($config);

    $query = $client->createSelect();
    
    $hideVal = 'lth_solr_hide_' . $data['pageId'] . '_i';
    
    if($data['query']) {
        $filterQuery = $data['query'];
        $filterQuery = str_replace(" ","\ ",$filterQuery);
        $filterQuery = " AND ((documentTitle:*$filterQuery*) OR authorName:*$filterQuery*)";
    }
    
    /*if($selection == 'coming_dissertations') {
        $selection = ' AND publicationType:Doctoral Thesis*';
    }*/
    
    if($data['keyword']) {
        $keyword = $data['keyword'];
        $keyword = ' AND (keywordsUser:' . str_replace(' ', '\\ ', urldecode($keyword)) . ' OR keywordsUka:' . str_replace(' ', '\\ ', urldecode($keyword)) . ')';
    }
    
    if($scope) {
        //$debugQuery = urldecode($scope);
        //$scope = json_decode(urldecode($scope),true);
        //var_dump($scope);
        foreach($scope as $key => $value) {
            if($term) {
                $term .= " OR ";
            }
            if($key === "fe_groups") {
                $term .= "organisationSourceId:" . implode(' OR organisationSourceId:', $value);
            } else {
                $term .= "authorId:" . implode(' OR authorId:', $value);
            }
        }
    }

    $query->setQuery('docType:publication AND -' . $hideVal . ':1 AND publicationDateYear:[* TO ' . date("Y") . '] AND (' . $term . ')' . $keyword . $selection . $filterQuery);
    $debugQuery = 'docType:publication AND -' . $hideVal . ':1 AND publicationDateYear:[* TO ' . date("Y") . '] AND ('.$term.')' . $keyword . $selection . $filterQuery;
    //$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => 'docType:publication AND -' . $hideVal . ':1 AND publicationDateYear:[* TO ' . date("Y") . '] AND ('.$term.')' . $keyword . $selection . $filterQuery, 'crdate' => time()));
    //$query->addParam('rows', 1500);
    $query->setStart($tableStart)->setRows($noItemsToShow);
    
    // get the facetset component
    $facetSet = $query->getFacetSet();
    
    // create a facet field instance and set options
    $facetSet->createFacetField('standard')->setField('standardCategory');
    $facetSet->createFacetField('language')->setField('language');
    $facetSet->createFacetField('year')->setField('publicationDateYear');

    if($data['facet']) {
        $facetArray = json_decode($data['facet'], true);

        $facetQuery = '';
        foreach($facetArray as $key => $value) {
            $facetTempArray = explode('###', $value);
            if($facetQuery) {
                $facetQuery .= ' AND ';
            }
            $facetQuery .= $facetTempArray[0] . ':"' . $facetTempArray[1] . '"';
        }

        $query->addFilterQuery(array('key' => 0, 'query' => $facetQuery, 'tag'=>'inner'));
    }

    $sortArray = array(
        'lth_solr_sort_' . $data['pageId'] . '_i' => 'asc',
        'publicationDateYear' => 'desc',
        'publicationDateMonth' => 'desc',
        'publicationDateDay' => 'desc',
        'documentTitle' => 'asc'
    );
    $query->addSorts($sortArray);

    $response = $client->select($query);
    
    $numFound = $response->getNumFound();
    
    // display facet query count
    $facetResult = array();
    $facetHeader;
    //if(!$facet) {
        $facetStandard = $response->getFacetSet()->getFacet('standard');
        if($syslang==="en") {
            $facetHeader = "Publication Type";
        } else {
            $facetHeader = "Publikationstyp";
        }
        foreach ($facetStandard as $value => $count) {
            //if($count > 0) {
                $facetResult["standardCategory"][] = array($value, $count, $facetHeader);
            //}
        }
        
        $facetLanguage = $response->getFacetSet()->getFacet('language');
        if($syslang==="en") {
            $facetHeader = "Language";
        } else {
            $facetHeader = "Språk";
        }
        foreach ($facetLanguage as $value => $count) {
            //if($count > 0) {
                $facetResult["language"][] = array($value, $count, $facetHeader);
            //}
        }
        
        $facetYear = $response->getFacetSet()->getFacet('year');
        if($syslang==="en") {
            $facetHeader = "Publication Year";
        } else {
            $facetHeader = "Publikationsår";
        }
        foreach ($facetYear as $value => $count) {
            //if($count > 0) {
                $facetResult['publicationDateYear'][] = array($value, $count, $facetHeader);
            //}
        }
    //}
    $data = array();
    foreach ($response as $document) {     
        $data[] = array(
            'id' => $document->id,
            'documentTitle' => $document->documentTitle,
            'authorName' => ucwords(strtolower(fixArray($document->authorName))),
            'publicationType' => fixArray($document->publicationType),
            'publicationDateYear' => $document->publicationDateYear,
            'publicationDateMonth' => $document->publicationDateMonth,
            'publicationDateDay' => $document->publicationDateDay,
            'pages' => $document->pages,
            'journalTitle' => $document->journalTitle,
            'journalNumber' => $document->journalNumber
        );
    }
    $resArray = array('data' => $data, 'numFound' => $numFound, 'facet' => $facetResult, 'query' => $debugQuery);
    return json_encode($resArray);
}


function showPublication($data, $config)
{
    $syslang = $data['syslang'];
    $uuid = $data['uuid'];
    
    $client = new Solarium\Client($config);

    $query = $client->createSelect();

    $query->setQuery('id:'.$uuid);

    $response = $client->select($query);
    
    $content = '';
    
    $organisationNameHolder = 'organisationName_' . $syslang;
    $publicationTypeHolder = 'publicationType_' . $syslang;
    $languageHolder = 'language_' . $syslang;
    
    if($data['feGroups']) {
        $feGroups = getFegroups($feGroups, $data['feGroups']);
    }
    
    /*$detailPageArray = explode(',', $detailPage);
    $staffDetailPage = $detailPageArray[0];
    $projectDetailPage = $detailPageArray[1];*/
        
    foreach ($response as $document) {
        $id = $document->id;
        $title = fixArray($document->documentTitle);
        $authorNameArray = $document->authorName;
        $authorFirstNameArray = $document->authorFirstName;
        $authorLastNameArray = $document->authorLastName;
        $authorExternalArray = $document->authorExternal;
        $authorOrganisation = $document->authorOrganisation;
        $authorIdArray = $document->authorId;
        $i=0;
        foreach ($authorNameArray as $key => $name) {
            if($authorName) $authorName .= ',';
            if($authorId) $authorId .= ',';
            if($authorExternal || $authorExternal=='0') $authorExternal .= ',';
            if($authorReverseName) $authorReverseName .= '; ';
            if($authorReverseNameShort) $authorReverseNameShort .= '$';
            $authorName .= mb_convert_case(strtolower($name), MB_CASE_TITLE, "UTF-8");
            $authorReverseName .= mb_convert_case(strtolower($authorLastNameArray[$i]), MB_CASE_TITLE, "UTF-8") . ', ' . mb_convert_case(strtolower($authorFirstNameArray[$i]), MB_CASE_TITLE, "UTF-8");
            $authorReverseNameShort .= mb_convert_case(strtolower($authorLastNameArray[$i]), MB_CASE_TITLE, "UTF-8") . ', ' . substr($authorFirstNameArray[$i], 0, 1) . '.';
            $authorId .= $authorIdArray[$i];
            $authorExternal .= $authorExternalArray[$i];
            $authorExternal = (string)$authorExternal;
            $i++;
        }
        if($document->organisationName) {
            $organisationName = $document->organisationName[0];
            $organisationId = $document->organisationId[0];
            /*$i=0;
            foreach($organisationNameArray as $key => $organisationName) {
                if($organisations) $organisations .= ', ';
                $organisations .= '<a href="' . $organisationIdArray[$i] . '">' . $organisationName . '</a>';
                $i++;
            }*/
        }
        if($document->organisationSourceId) {
            $organisationSourceId = $document->organisationSourceId[0];
        }
        if($document->externalOrganisationsName) {
            $externalOrganisationsNameArray = $document->externalOrganisationsName;
            $externalOrganisationsIdArray = $document->externalOrganisationsId;
            $i=0;
            foreach($externalOrganisationsNameArray as $key => $externalOrganisationsName) {
                if($externalOrganisations) $externalOrganisations .= ', ';
                //$externalOrganisations .= '<a href="' . $externalOrganisationsIdArray[$i] . '">' . $externalOrganisationsName . '</a>';
                $externalOrganisations .= $externalOrganisationsName;
                $i++;
            }
        }

        $publicationType = fixArray($document->publicationType);
        $publicationTypeUri = $document->publicationTypeUri;
        $language = fixArray($document->language);
        $publicationDateYear = $document->publicationDateYear;
        $publicationDateMonth = $document->publicationDateMonth;
        $publicationDateDay = $document->publicationDateDay;
        $abstract = fixArray($document->abstract);
        //$abstract_sv = fixArray($document->abstract_sv);
        /*if($syslang == 'sv' && $abstract_sv && $abstract_sv != '<br/>') {
            $abstract = $abstract_sv;
        } else {
            $abstract = $abstract_en;
        }*/
        $pages = $document->pages;
        $journalTitle = $document->journalTitle;
        $numberOfPages = $document->numberOfPages;
        $volume = $document->volume;
        $journalNumber = $document->journalNumber;
        //if($syslang == 'sv') {
            $publicationStatus = $document->publicationStatus;
            $keywordsUka = $document->keywordsUka;
            $keywordsUser = $document->keywordsUser;
       /* } else {
            $publicationStatus = $document->publicationStatus_en;
            $keywords_uka = $document->keywords_uka_en;
            $keywords_user = $document->keywords_user_en;
        }*/
        $peerReview = $document->peerReview;
        $doi = $document->doi;
        $issn = $document->issn;
        $isbn = $document->isbn;
        $publisher = $document->publisher;
        
        $standardCategory = $document->standardCategory;
        
        $type = $document->type;
        
        $bibtex = $document->bibtex;
        $cite = $document->cite;
        
        $data = array(
            'authorId' => $authorId,
            'authorExternal' => $authorExternal,
            'id' => $id,
            'title' => $title,
            'abstract' => $abstract,
            'authorName' => $authorName,
            'authorOrganisation' => $authorOrganisation,
            'authorReverseName' => rawurlencode($authorReverseName),
            'authorReverseNameShort' => rawurlencode(str_replace("$", ", ", str_lreplace("$", " and ", $authorReverseNameShort))),
            'organisationName' => $organisationName,
            'organisationId' => $organisationId,
            'organisationSourceId' => $organisationSourceId,
            'externalOrganisations' => $externalOrganisations,
            'keywords_uka' => $keywordsUka,
            'keywords_user' => $keywordsUser,
            'language' => $language,
            'pages' => $pages,
            'numberOfPages' => $numberOfPages,
            'journalTitle' => $journalTitle,
            'volume' => $volume,
            'journalNumber' => $journalNumber,
            'publicationStatus' => $publicationStatus,
            'peerReview' => $peerReview,
            'publicationDateYear' => $publicationDateYear,
            'publicationDateMonth' => $publicationDateMonth,
            'publicationDateDay' => $publicationDateDay,
            'publicationType' => $publicationType,
            'publicationTypeUri' => $publicationTypeUri,
            'feGroups' => $feGroups,
            'doi' => $doi,
            'issn' => $issn,
            'isbn' => $isbn,
            'standard_category_en' => $standardCategory,
            'publisher' => $publisher,
            'bibtex' => $bibtex,
            'cite' => $cite
        );

    }
    
    $resArray = array('data' => $data, 'title' => $title);
    
    return json_encode($resArray);
}


/*******************************************STAFF**************************************************************/
function listStaff($data, $config)
{
    $content;
    $term;
    $facetResult = array();
    $categories = $data['categories'];
    $pageId = $data['pageId'];
    $filterQuery = $data['query'];
    $tableStart = $data['tableStart'];
    $noItemsToShow = $data['noItemsToShow'];
    
    if($data['feGroups']) {
        $scope = getFegroups($scope, $data['feGroups']);
    }
    
    if($data['feUsers']) {
        $scope = getFeusers($scope, $data['feUsers']);
    }
    
    if($categories === 'standard_category') {
        //$catVal = 'standard_category_sv_txt';
        $catVal = 'standardCategory';
    } elseif($categories === 'custom_category') {
        $catVal = 'lth_solr_cat_' . $pageId . '_ss';
    }

    $introVar = 'staff_custom_text_' . $pageId . '_s';
    
    $hideVal = 'lth_solr_hide_' . $pageId . '_i';
    
    $autoVal = 'lth_solr_autohomepage_' . $pageId . '_s';

    $client = new Solarium\Client($config);

    $query = $client->createSelect();
    
    if($scope) {
        foreach($scope as $key => $value) {
            if($term) {
                $term .= " OR ";
            }
            if($key === "fe_groups") {
                $term .= "heritage:" . implode(' OR heritage:', $value);
            } else {
                $term .= "primaryUid:" . implode(' OR primaryUid:', $value);
            }
        }
    }

    if($filterQuery) {
        $filterQuery = ' AND (name:*' . $filterQuery . '* OR phone:*' . $filterQuery . '*)';
    }

    $queryToSet = '(docType:staff AND (' . $term . ')'. ' AND hideOnWeb:0 AND disable_i:0 AND -' . $hideVal . ':[* TO *])' . $filterQuery;
    //$debug = '(docType:staff AND (' . $term . ')'. ' AND hideOnWeb:0 AND disable_i:0 AND -' . $hideVal . ':[* TO *])' . $filterQuery;
    $query->setQuery($queryToSet);
    
    $query->setStart($tableStart)->setRows($noItemsToShow);
    
    // get the facetset component
    $facetSet = $query->getFacetSet();
    if($facet) {
        $facetArray = json_decode($facet, true);
        $facetQuery = '';
        foreach($facetArray as $key => $value) {
            $facetTempArray = explode('###', $value);
            if($facetQuery) {
                $facetQuery .= ' OR ';
            }
            $facetQuery .= $facetTempArray[0] . ':' . $facetTempArray[1] . '';
        }
        //$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => $facetQuery, 'crdate' => time()));
        $query->addFilterQuery(array('key' => 0, 'query' => $facetQuery, 'tag'=>'inner'));
    } /*else {
        $facetSet->createFacetField('title')->setField('title_sort');
    }*/
    if($categories === 'standard_category') {
        $facetSet->createFacetField('standard')->setField($catVal);
    } else if($categories === 'custom_category') {
        $facetSet->createFacetField('custom')->setField($catVal);
    }
        
    $sortArray = array(
        'lth_solr_sort_' . $pageId . '_i' => 'asc',
        'lastNameExact' => 'asc',
        'firstNameExact' => 'asc'
    );
    
    //$query->addSort('last_name_s', $query::SORT_ASC);
    //$query->addSort('first_name_s', $query::SORT_ASC);
    $query->addSorts($sortArray);
    
    //$query->addParam('rows', 15000);
    
    // this executes the query and returns the result
    $response = $client->select($query);

    // display the total number of documents found by solr
    $numFound = $response->getNumFound();

    // display facet query count
    $facetHeader = "";
        if($syslang==="en") {
            $facetHeader = "Staff category";
        } else {
            $facetHeader = "Personalkategori";
        }
    //if(!$facet) {
        if($categories === 'standard_category') {
            $facet_standard = $response->getFacetSet()->getFacet('standard');
            foreach ($facet_standard as $value => $count) {
                if($count > 0) $facetResult[$catVal][] = array($value, $count, $facetHeader);
            }
        } else if($categories === 'custom_category') {
            $facet_custom = $response->getFacetSet()->getFacet('custom');
            foreach ($facet_custom as $value => $count) {
                if($count > 0) $facetResult[$catVal][] = array($value, $count, $facetHeader);
            }
        } 
    //}
    
    $data = array();
    foreach ($response as $document) {
        $image = '';
        $intro = '';
        if($document->$introVar) {
            $intro = '<p class="lthsolr_intro">' . $document->$introVar . '</p>';
        }

        if($document->image) {
            $image = '/fileadmin' . $document->image;
        } else if($document->lucrisPhoto) {
            $image = $document->lucrisPhoto;
        } else {
            $image = '';
        }
        
        $data[] = array(           
            'firstName' => mb_convert_case(strtolower($document->firstName), MB_CASE_TITLE, "UTF-8"),
            'lastName' => mb_convert_case(strtolower($document->lastName), MB_CASE_TITLE, "UTF-8"),
            'title' => $document->title,
            'phone' => $document->phone,
            'id' => $document->id,
            'email' => $document->email,
            'organisationName' => $document->organisationName,
            'primaryAffiliation' => $document->primaryAffiliation,
            'homepage' => $document->homepage,
            'image' => $image,
            'intro' => $intro,
            'roomNumber' => fixRoomNumber($document->roomNumber),
            'mobile' => $document->mobile,
            'autoHomepage' => $document->$autoVal,
            'organisationId' => $document->organisationId,
            'guid' => $document->guid,
            'uuid' => $document->uuid
        );
    }
    $resArray = array('data' => $data, 'numFound' => $numFound,'facet' => $facetResult, 'draw' => 1, 'debug' => $debug);
    
    return json_encode($resArray);
}


function showStaff($data, $config)
{
    $content = '';
    $pageId = $data['pageId'];
    $filterQuery = $data['$filterQuery'];
    $tableStart = $data['tableStart'];
    $noItemsToShow = $data['noItemsToShow'];
    
    if(!$noItemsToShow) $noItemsToShow = 10;
    
    $publicationType = "publicationType_$syslang";

    $client = new Solarium\Client($config);
    $query = $client->createSelect();
    $groupComponent = $query->getGrouping();

    $groupComponent->addQuery('guid:' . $data['uuid'] . ' OR uuid:' . $data['uuid']);
    $groupComponent->addQuery('authorId:' . $data['uuid']);
    $groupComponent->addQuery('participantId:' . $data['uuid']);
    $groupComponent->setSort('publicationDateYear desc');
    $groupComponent->setLimit($noItemsToShow);

    $resultset = $client->select($query);
    $groups = $resultset->getGrouping();
    foreach ($groups as $groupKey => $group) {
        //var_dump($group);
        $numRow[] = $group->getNumFound();

        foreach ($group as $document) {        
            $id = $document->id;
            $docType = $document->docType;
            
            $intro = '';
            if($document->$introVar) {
                $intro = '<p class="lthsolr_intro">' . $document->staff_custom_text_s . '</p>';
            }

            if($docType === 'staff') {
                if($document->image) {
                    $image = '/fileadmin' . $document->image;
                } else if($document->lucrisphoto) {
                    $image = $document->lucrisphoto;
                } else {
                    $image = '/typo3conf/ext/lth_solr/res/placeholder_noframe.gif';
                }
                
                $personData[] = array(
                    'firstName' => ucwords(strtolower($document->firstName)),
                    'lastName' => ucwords(strtolower($document->lastName)),
                    'title' => $document->title,
                    'phone' => $document->phone,
                    'id' => $document->id,
                    'email' => $document->email,
                    'organisationName' => $document->organisationName,
                    'primaryAffiliation' => $document->primaryAffiliation,
                    'homepage' => $document->homepage,
                    'image' => $image,
                    'intro' => $intro,
                    'roomNumber' => $document->roomNumber,
                    'mobile' => $document->mobile,
                    'uuid' => $document->uuid,
                    'organisationId' => $document->organisationId,
                    'organisationPhone' => $document->organisationPhone,
                    'organisationStreet' => $document->organisationStreet,
                    'organisationCity' => $document->organisationCity,
                    'organisationPostalAddress' => $document->organisationPostalAddress,
                    'profileInformation' => $document->profileInformation
                );
            } else if($docType === 'publication') {
                $publicationData[] = array(
                    'id' => $document->id,
                    'documentTitle' => fixArray($document->documentTitle),
                    'authorName' => ucwords(strtolower(fixArray($document->authorName))),
                    'publicationType' => fixArray($document->publicationType),
                    'publicationDateYear' => $document->publicationDateYear,
                    'publicationDateMonth' => $document->publicationDateMonth,
                    'publicationDateDay' => $document->publicationDateDay,
                    'pages' => $document->pages,
                    'journalTitle' => $document->journalTitle,
                    'journalNumber' => $document->journalNumber
                );
                
            } else if($docType === 'upmproject') {
                $projectData[] = array(
                    'id' => $document->id,
                    'title' => fixArray($document->title),
                    'participants' => ucwords(strtolower(fixArray($document->participants))),
                    'projectStartDate' => substr($document->projectStartDate,0,10).'',
                    'projectEndDate' => substr($document->projectEndDate,0,10).'',
                    'projectStatus' => ucwords(strtolower(str_replace('_',' ',$document->projectStatus)))
                );
                
            }
        }
    }
    
    $resArray = array('personData' => $personData, 'publicationData' => $publicationData, 'publicationNumFound' => $numRow[1], 'projectData' => $projectData, 'projectNumFound' => $numRow[2]);
    
    return json_encode($resArray);
}


/******************************************STUDENT PAPERS******************************************************/
function listStudentPapers($data, $config)
{
    $tableStart = $data['tableStart'];
    $noItemsToShow = $data['noItemsToShow'];
    $filterQuery = $data['query'];
    $syslang = $data['syslang'];
    
    $client = new Solarium\Client($config);

    $query = $client->createSelect();
    
    if($data['feGroups']) {
        $scope = getFegroups($scope, $data['feGroups']);
    }
    
    if($scope) {
        //$debugQuery = urldecode($scope);
        //$scope = json_decode(urldecode($scope),true);
        //var_dump($scope);
        foreach($scope as $key => $value) {
            if($term) {
                $term .= " OR ";
            }
            $term .= "organisationSourceId:" . implode(' OR organisationSourceId:', $value);
        }
    }
    
    if($filterQuery) {
        $filterQuery = str_replace(" ","\ ",$filterQuery);
        $filterQuery = " AND ((documentTitle:*$filterQuery*) OR authorName:*$filterQuery*)";
    }
    
    if($data['paperType']) {
        $paperTypeArray = explode(',', $data['paperType']);
        foreach($paperTypeArray as $key => $value) {
            if($paperType) $paperType .= ' OR ';
            $paperType .= 'genre:studentPublications' . $value;
        }
        $paperType = ' AND (' . $paperType . ')';
    }

    $query->setQuery('docType:studentPaper AND (' . $term . ')' . $paperType . $filterQuery);
    //$debug = 'docType:studentPaper AND (' . $term . ')' . $paperType . $filterQuery;
    //$query->addParam('rows', 1500);
    $query->setStart($tableStart)->setRows($noItemsToShow);
    
    // get the facetset component
    $facetSet = $query->getFacetSet();
    // create a facet field instance and set options
    $facetSet->createFacetField('standard')->setField('standardCategory');
    $facetSet->createFacetField('language')->setField('language');
    $facetSet->createFacetField('year')->setField('publicationDateYear');

    if($facet) {
        $facetArray = json_decode($facet, true);

        $facetQuery = '';
        foreach($facetArray as $key => $value) {
            $facetTempArray = explode('###', $value);
            if($facetQuery) {
                $facetQuery .= ' AND ';
            }
            $facetQuery .= $facetTempArray[0] . ':"' . $facetTempArray[1] . '"';
        }

        $query->addFilterQuery(array('key' => 0, 'query' => $facetQuery, 'tag'=>'inner'));
    }

    $sortArray = array(
        'publicationDateYear' => 'desc',
        'documentTitle' => 'asc'
    );
    $query->addSorts($sortArray);

    $response = $client->select($query);
    
    $numFound = $response->getNumFound();
    
    $facet_standard = $response->getFacetSet()->getFacet('standard');
    if($syslang==="en") {
        $facetHeader = "Publication Type";
    } else {
        $facetHeader = "Publikationstyp";
    }
    foreach ($facet_standard as $value => $count) {
        //if($count > 0) {
            $facetResult["standardCategory"][] = array($value, $count, $facetHeader);
        //}
    }

    $facet_language = $response->getFacetSet()->getFacet('language');
    if($syslang==="en") {
        $facetHeader = "Language";
    } else {
        $facetHeader = "Språk";
    }
    foreach ($facet_language as $value => $count) {
        //if($count > 0) {
            $facetResult["language"][] = array($value, $count, $facetHeader);
        //}
    }

    $facet_year = $response->getFacetSet()->getFacet('year');
    if($syslang==="en") {
        $facetHeader = "Publication Year";
    } else {
        $facetHeader = "Publikationsår";
    }
    foreach ($facet_year as $value => $count) {
        //if($count > 0) {
            $facetResult['publicationDateYear'][] = array($value, $count, $facetHeader);
        //}
    }
    $data = array();
    foreach ($response as $document) {     
        $data[] = array(
            'id' => $document->id,
            'documentTitle' => fixArray($document->documentTitle),
            'authorName' => ucwords(strtolower(fixArray($document->authorName))),
            'publicationDateYear' => $document->publicationDateYear,
            'organisationName' => $document->organisationName
        );
    }
    $resArray = array('data' => $data, 'numFound' => $numFound, 'facet' => $facetResult, 'debug' => $debug);
    return json_encode($resArray);
}


/******************************************TAG CLOUD***********************************************************/
function listTagCloud($data, $config)
{
    $pageId = $data['pageId'];
    $path = $data['path'];
    
    $client = new Solarium\Client($config);

    $query = $client->createSelect();
    
    $hideVal = 'lth_solr_hide_' . $pageId . '_i';
    
    if($data['feGroups']) {
        $scope = getFegroups($scope, $data['feGroups']);
    }
    
    if($scope) {       
        foreach($scope as $key => $value) {
            if($term) {
                $term .= " OR ";
            }
            if($key === "fe_groups") {
                $term .= "organisationSourceId:$value[0]";
            } else {
                $term .= "authorId:$value[0]";
            }
        }
    }

    $query->setQuery('docType:publication AND -' . $hideVal . ':1 AND publicationDateYear:[* TO ' . date("Y") . '] AND ('.$term.')');
    //$debug = 'docType:publication AND -' . $hideVal . ':1 AND publicationDateYear:[* TO ' . date("Y") . '] AND ('.$term.')';
    
    $query->setStart(0)->setRows(10000);
    $sortArray = array(
        'documentTitle' => 'asc'
    );
    $query->addSorts($sortArray);

    $response = $client->select($query);
    
    $numFound = $response->getNumFound();
    $tagArray = array();
    $i=1;
    
    foreach ($response as $document) {
        /*if(is_array($document->keywordsUser)) {
            foreach($document->keywordsUser as $key => $value) {
                $keywordsArray[] = $value;
            }
        }
        if(is_array($document->keywordsUka)) {
            foreach($document->keywordsUka as $key => $value) {
                $keywordsArray[] = $value;
            }
        }*/
        if($document->keywordsUser) $keywordsArray[] = $document->keywordsUser;
        if($document->keywordsUka) $keywordsArray[] = $document->keywordsUka;
    }
    sort($keywordsArray);
    
    $data = array();
    $tempArray = array();
    $count = array();
    $steps = 13;
    $min = 1e13;
    $max = -1e13;
    foreach($keywordsArray as $key => $value) {
        if($oldValue != $value[0] && $ii > 0) {
            $tempArray[] = $oldValue;
            $count[] = $i;
            $i=0;
        }
        $oldValue = $value[0];
        $i++;
        $ii++;
    }
    $highest = max($count);
    //$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => print_r($count,true), 'crdate' => time()));
    $x=0;
    $range = max(.01, $max - $min) * 1.0001;
    foreach($tempArray as $key => $value) {
        //$normalized = $count[$x] / $highest;
        $weight = 1 + floor($steps * ($count[$x] - $min) / $range);//ceil($normalized * 13);
        $data[] = array(
            'text' => $value,
            'link' => urldecode($path) . '?keyword=' . $value,
            'weight' => $weight
        );
        $x++;
    }
    
    $resArray = array('data' => $data, 'numFound' => $numFound, 'debug' => $debug);
    return json_encode($resArray);
}


/*******************************************UTILITES***********************************************************/


function getFeGroups($scope, $feGroups)
{
    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('title','fe_groups',"uid in(" . explode('|',$feGroups)[0].")");
    while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
        $scope['fe_groups'][] = explode('__', $row['title'])[0];
    }
    $GLOBALS['TYPO3_DB']->sql_free_result($res);
    return $scope;
}


function getFeUsers($scope, $feUsers)
{                 
    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery('lth_solr_uuid','fe_users',"uid in(" . explode('|',$feUsers)[0].") AND lth_solr_uuid!=''");
    while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($res)) {
        $scope['fe_users'][] = $row['lth_solr_uuid'];
    }
    $GLOBALS['TYPO3_DB']->sql_free_result($res);
    return $scope;
}


function fixArray($inputArray)
{
    if($doctype==='lucat') {
        return false;
    }
    if($inputArray) {
        if(is_array($inputArray)) {
            $inputArray = array_unique($inputArray);
            $inputArray = array_filter($inputArray);
            $inputArray = implode(', ', $inputArray);
        }
    }
    return $inputArray;
}


function fixRoomNumber($input)
{
    if(is_array($input)) {
        $input = array_unique($input);
    }
    return $input;
}


function str_lreplace($search, $replace, $subject)
{
    $pos = strrpos($subject, $search);

    if($pos !== false)
    {
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
    }

    return $subject;
}