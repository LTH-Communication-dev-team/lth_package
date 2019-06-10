<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class user_lthpackageflex_addFieldsToFlexForm
{
    function initVars($pid)
    {
        
        /*$backendUtility = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Utility\\BackendUtility');
        $rootLine = $backendUtility->BEgetRootline($pid);
        $TSObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\TypoScript\\TemplateService');
        $TSObj->tt_track = 0;
        $TSObj->init();
        $TSObj->runThroughTemplates($rootLine);
        $TSObj->generateConfig();
        $TS = $TSObj->setup;
        $syslang = $TS['config.']['language'];
        return $syslang;*/
    }
    
    
    function init($config)
    {
                       
        $content .= "            
            <script language=\"javascript\">
            TYPO3.jQuery(document).ready(function() {
            //alert('???');
            });
            </script>";
        return $content;
    }
    
    
    function gridBuilderA($config)
    {
        $content = '
<div class="masonry-container masonry-container-1by1">
    <div class="masonry masonry-cols">
        <div class="masonry-col masonry-col-60">
            <div class="masonry-row masonry-row-75">
                <div class="masonry-tile masonry-tile-lg">
                    
                </div>
            </div>
            <div class="masonry-row masonry-row-25">
                <div class="masonry-tile">
                    <a href="#" class="nav-block nav-block-flower nav-block-fade-flower">
                        <div class="p-3 p-lg-5 p-xl-7">
                            <h1><span class="a nav-block-link">Öppettider i sommar&nbsp;<i class="fal fa-chevron-circle-right fa-sm"></i></span>
                            </h1>
                            <p>Under sommaren har Ekonomihögskolans reception och bibliotek begränsade öppettider. Även huvudentrén har andra öppettider sommartid.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="masonry-col masonry-col-40">
            <div class="masonry-row masonry-row-55">
                <div class="masonry-tile">
                    <a href="#" class="nav-block nav-block-copper-50 nav-block-fade-copper-50">
                        <div class="p-3 p-xl-5">
                            <h1><span class="a nav-block-link">Kompletterande utbildning för dig med utländsk examen&nbsp;<i
                          class="fal fa-chevron-circle-right fa-sm"></i></span></h1>
                            <p>För dig som har en utländsk examen inom företagsekonomi eller systemvetenskap och som vill komma in på den svenska arbetsmarknaden.</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="masonry-row masonry-row-45">
                <div class="masonry-tile">
                    <a href="#" class="nav-block masonry-tile-img">
                        <div class="masonry-tile-img-bg">
                            <img src="../../images/placeholder_1@1x.jpg" srcset="../../images/placeholder_1@1x.jpg 1x, ../../images/placeholder_1@2x.jpg 2x" alt="Image description">

                        </div>
                        <div class="masonry-tile-img-content">
                            <div class="blockline">
                                <h1><span class="a nav-block-link">Nyhet! BSc International Business startar i höst&nbsp;<i
                            class="fal fa-chevron-circle-right fa-sm"></i></span></h1>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>';
        return $content;
    }
    
    
    function fixColorBoxes($config)
    {
        //data[tt_content][1276][pi_flexform][data][sDEF][lDEF][backgroundcolor][vDEF]
        $uid = $config['row']['uid'];  
        $content .= "            
            <script language=\"javascript\">
            TYPO3.jQuery(document).ready(function() {
                TYPO3.jQuery('[name=\"data[tt_content][$uid][pi_flexform][data][sDEF][lDEF][backgroundcolor][vDEF]\"]').parent().parent().next('div').find('img').css('width','50px');
            });
            </script>";
        return $content;
    }
    
    
    function fixFontawesomeClass($config)
    {
        $content = '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">';
        $uid = $config['row']['uid'];
        //$("[id^=jander]")
        $content .= "            
            <script language=\"javascript\">
            TYPO3.jQuery(document).ready(function() {
                TYPO3.jQuery('select[name^=\"data[tt_content][$uid][pi_flexform][data][sDEF][lDEF][icons]\"]').addClass('fa');
                TYPO3.jQuery('select[name^=\"data[tt_content][$uid][pi_flexform][data][sDEF][lDEF][infographicItems]\"]').addClass('fa');
            });
            </script>";
        return $content;
        return $content;
    }
    
    
    function getCalendarIds($config) 
    {
        $settings = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['lth_package']);
        $syslang = $data['syslang'];
        if(!$syslang) $syslang = "sv";

        $solrConfig = array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => $settings['solrHost'],
                    'port' => $settings['solrPort'],
                    'path' => "/solr/core_$syslang/",//$settings['solrPath'],
                    'timeout' => $settings['solrTimeout']
                )
            )
        );
        $fieldArray = array("calendar_ids");

        $client = new Solarium\Client($solrConfig);

        $query = $client->createSelect();
        
        $facetSet = $query->getFacetSet();
        
        $facetSet->createFacetField('ci')->setField('calendarIds');

        $queryToSet = 'docType:calendar';

        $query->setQuery($queryToSet);

        $query->setFields($fieldArray);

        $query->setStart(0)->setRows(5000);

        $response = $client->select($query);

        $numFound = $response->getNumFound();
        
        $facet = $response->getFacetSet()->getFacet('ci');
        
       //$i=0;
        /*echo '<pre>';
        print_r($config);
        echo '</pre>';
        die();*/
        foreach ($facet as $value => $count) {
            $optionList[] = array(0 => $value, 1 => $value);
            //$i++;
        }
        $config['items'] = array_merge($config['items'],$optionList);
        return $config;
    }
    
    
    function getColors($PA, $fobj)
    {
        
        $content = '<style type="text/css">
            .lth_package_banner_color_pick {font-size: 12px;padding:10px;}
            </style>
        <select class="lth_package_banner_color_pick">
        <option value="" selected>Background color</option>
        <option value="#22458a" style="background-color:#22458a;">blue</option>
        <option value="#92652a" style="background-color:#92652a;">bronze</option>
        <option value="#fcdee0" style="background-color:#fcdee0;">flower</option>
        <option value="#bad8e1" style="background-color:#bad8e1;">sky</option>
        <option value="#b4d7b8" style="background-color:#b4d7b8;">copper</option>
        <option value="#e2ddcb" style="background-color:#e2ddcb;">plaster</option>
        <option value="#beb7b3" style="background-color:#beb7b3;">stone</option>
        <option value="#4d4c44" style="background-color:#4d4c44;">dark</option>
        <option value="#fdeeef" style="background-color:#fdeeef;">flower-50</option>
        <option value="#dcebf0" style="background-color:#dcebf0;">sky-50</option>
        <option value="#d9ebdb" style="background-color:#d9ebdb;">copper-50</option>
        <option value="#f0eee5" style="background-color:#f0eee5;">plaster-50</option>
        <option value="#dedbd9" style="background-color:#dedbd9;">stone-50</option>
        <option value="#a6a5a1" style="background-color:#a6a5a1;">dark-50</option>
        <option value="#fef8f9" style="background-color:#fef8f9;">flower-25</option>
        <option value="#f1f7f9" style="background-color:#f1f7f9;">sky-25</option>
        <option value="#f0f7f1" style="background-color:#f0f7f1;">copper-25</option>
        <option value="#f9f8f5" style="background-color:#f9f8f5;">plaster-25</option>
        <option value="#f2f1f0" style="background-color:#f2f1f0;">stone-25</option>
        <option value="#dbdbda" style="background-color:#dbdbda;">dark-25</option>
        <option value="#aea688" style="background-color:#aea688;">bronze-medium</option>
        </select>';

	return $content;
    }
    
    
    public function gridbuilder($PA, $fObj)
    {
        $content = "<style type=\"text/css\">
                
#content {
    width: 100%;
}

.vue-grid-layout {
    background: #eee;
}

.layoutJSON {
    background: #ddd;
    border: 1px solid black;
    margin-top: 10px;
    padding: 10px;
}

.eventsJSON {
    background: #ddd;
    border: 1px solid black;
    margin-top: 10px;
    padding: 10px;
    height: 100px;
    overflow-y: scroll;
}

.columns {
    -moz-columns: 120px;
    -webkit-columns: 120px;
    columns: 120px;
}



.vue-resizable-handle {
    z-index: 5000;
    position: absolute;
    width: 20px;
    height: 20px;
    bottom: 0;
    right: 0;
    background: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pg08IS0tIEdlbmVyYXRvcjogQWRvYmUgRmlyZXdvcmtzIENTNiwgRXhwb3J0IFNWRyBFeHRlbnNpb24gYnkgQWFyb24gQmVhbGwgKGh0dHA6Ly9maXJld29ya3MuYWJlYWxsLmNvbSkgLiBWZXJzaW9uOiAwLjYuMSAgLS0+DTwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DTxzdmcgaWQ9IlVudGl0bGVkLVBhZ2UlMjAxIiB2aWV3Qm94PSIwIDAgNiA2IiBzdHlsZT0iYmFja2dyb3VuZC1jb2xvcjojZmZmZmZmMDAiIHZlcnNpb249IjEuMSINCXhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiDQl4PSIwcHgiIHk9IjBweCIgd2lkdGg9IjZweCIgaGVpZ2h0PSI2cHgiDT4NCTxnIG9wYWNpdHk9IjAuMzAyIj4NCQk8cGF0aCBkPSJNIDYgNiBMIDAgNiBMIDAgNC4yIEwgNCA0LjIgTCA0LjIgNC4yIEwgNC4yIDAgTCA2IDAgTCA2IDYgTCA2IDYgWiIgZmlsbD0iIzAwMDAwMCIvPg0JPC9nPg08L3N2Zz4=');
    background-position: bottom right;
    padding: 0 3px 3px 0;
    background-repeat: no-repeat;
    background-origin: content-box;
    box-sizing: border-box;
    cursor: se-resize;
}

.vue-grid-item:not(.vue-grid-placeholder) {
    background: #ccc;
    border: 1px solid black;
}

.vue-grid-item.resizing {
    opacity: 0.9;
}

.vue-grid-item.static {
    background: #cce;
    position:relative;
}

.vue-grid-item .text {
    font-size: 24px;
    text-align: center;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    height: 100%;
    width: 100%;
}

.vue-grid-item .no-drag {
    height: 100%;
    width: 100%;
}

.vue-grid-item .minMax {
    font-size: 12px;
}

.vue-grid-item .add {
    cursor: pointer;
}

.vue-draggable-handle {
    position: absolute;
    width: 20px;
    height: 20px;
    top: 0;
    left: 0;
    background: url(\"data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='10'><circle cx='5' cy='5' r='5' fill='#999999'/></svg>\") no-repeat;
    background-position: bottom right;
    padding: 0 8px 8px 0;
    background-repeat: no-repeat;
    background-origin: content-box;
    box-sizing: border-box;
    cursor: pointer;
}

.removeItem {
    cursor: pointer;
    width:10px;
    height:10px;
    position:absolute;
    top:2px;
    right:10px;
}

.hideItem {
    display:none;
}

#gridelementsModal {
    height:400px;
}
</style>";
        $content .= '
            <div id="app" style="width: 100%;">';
        
        $content .= '<textarea style="display:block" rows="10" cols="50" id="lthpackageGridDisplay" 
     name="'.$PA['itemFormElName'].'"
     onchange="'.htmlspecialchars(implode('',$PA['fieldChangeFunc'])).'"
     '.$PA['onFocus'].'
     >'.htmlspecialchars($PA['itemFormElValue']).'</textarea>';
        
            
        //<textarea id="lthpackageGridDisplay" name="' . $PA['itemFormElName'] . '" rows="10" cols="50">{{ $data | json }}</textarea>
        
        $content .= '<div id="content">
            <!--<button @click="decreaseWidth">Decrease Width</button>
            <button @click="increaseWidth">Increase Width</button>-->
            <div @click="addItem" class="btn btn-primary">Add an item</div>
            <br/>
            <grid-layout :layout="layout"
                         :col-num="12"
                         :row-height="30"
                         :is-draggable="true"
                         :is-resizable="true"
                         :vertical-compact="true"
                         :use-css-transforms="true"
            >
                <grid-item v-for="item in layout"
                           :x="item.x"
                           :y="item.y"
                           :w="item.w"
                           :h="item.h"
                           :i="item.i"
                           :c="item.c"
                           :id="item.i"
                           @resized="resized"
                           @moved="moved"
                        >
                    <span class="text"><div class="editItem" @click="editItem(item)" v-html="item.c"></div><div class="removeItem" @click="removeItem(item)">&times;</div></span>
                </grid-item>
            </grid-layout>
        </div>

    </div>';
        
        //Modal
        $content .= '

<!-- Modal -->
<div class="modal fade" id="gridelementsModal" tabindex="-1" role="dialog" aria-labelledby="gridelementsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gridelementsModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <select name="tileChoice" id="tileChoice">
                        <option value="" selected>Choose Tile</option>
                        <option value="tilea">Tile A</option>
                        <option value="tileb">Tile B</option>
                        <option value="tilec">Tile C</option>
                        <option value="tiled">Tile D</option>
                        <option value="tilee">Tile E</option>
                        <option value="tilef">Tile F</option>
                        <option value="tileg">Tile G</option>
                        <option value="tileh">Tile H</option>
                        <option value="html">Html</option>
                    </select>
                </div>
                <div class="hideItem">
                    <b>Title:</b><input type="text" id="lthPackageGridContentTitle" style="width:300px;" />
                </div>
                <div class="hideItem">
                    <b>Text:</b><br />
                    <textarea id="lthPackageGridContentText" style="width:200px;height:100px;"></textarea>
                </div>
                <div class="hideItem">
                    <b>Image:</b><input type="text" id="lthPackageGridContentImage" style="width:300px;" />
                </div>
                <div class="hideItem">
                    <b>Embed:</b><input type="text" id="lthPackageGridContentEmbed" style="width:300px;" />
                </div>
                <div class="hideItem">
                    <b>Html:</b><textarea id="lthPackageGridContentHtml" style="width:200px;height:100px;"></textarea>
                </div>
            </div>
            <input type="hidden" id="lthPackageGridId" />
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
    </div>
</div>';
        
        $testLayout = '{"x":0,"y":0,"w":6,"h":8,"i":"0","id":"0","c":"sucker1"},
        {"x":0,"y":1,"w":6,"h":3,"i":"1","id":"1","c":"sucker2"},
        {"x":6,"y":0,"w":4,"h":5,"i":"2","id":"2","c":"sucker3"},
        {"x":6,"y":1,"w":4,"h":6,"i":"3","id":"3","c":"sucker4"}';
        
        if($PA['itemFormElValue']) {
            $testLayout = '';
            $jsonArray = json_decode($PA['itemFormElValue'], true);
            
            foreach($jsonArray as $key => $value) {
                if($testLayout) {
                    $testLayout .= ',';
                }
                $testLayout .= (string)json_encode($value);
            }
        }
        $GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_devlog', array('msg' => $testLayout, 'crdate' => time()));
        $content .= '<script language="javascript">
            requirejs.config({
    paths: {  "vue": "../typo3conf/ext/lth_package/Resources/Public/JavaScript/Dist/vue.min",
        "vue-grid-layout": "../typo3conf/ext/lth_package/Resources/Public/JavaScript/Dist/vue-grid-layout.min"
    },
    shim: {
       "vue": {"exports": "Vue"},
    }
});

require([\'jquery\', \'vue\', \'vue-grid-layout\'], function($, Vue, VueGridLayout){
 
    var testLayout = [' .
        $testLayout .
    '];

    Vue.config.debug = false;
    Vue.config.devtools = false;

    var GridLayout = VueGridLayout.GridLayout;
    var GridItem = VueGridLayout.GridItem;
    
    new Vue({
        el: "#app",
        components: {
            "GridLayout": GridLayout,
            "GridItem": GridItem
        },
        data: {
            layout: testLayout,
            draggable: true,
            resizable: true,
            index: 0
        },
        mounted: function () {
            this.index = this.layout.length;
            if(!TYPO3.jQuery("#lthpackageGridDisplay").val()) TYPO3.jQuery("#lthpackageGridDisplay").val(JSON.stringify(testLayout));
        },
        methods: {
            removeItem: function(item) {
                //console.log("### REMOVE " + item.i);
                this.layout.splice(this.layout.indexOf(item), 1);
                TYPO3.jQuery("#lthpackageGridDisplay").val(JSON.stringify(this.layout));
            },
            addItem: function() {
                var self = this;
                //console.log("### LENGTH: " + this.layout.length);
                this.index++;
                var item = {"x":0,"y":0,"w":2,"h":2,"i":this.index+"","id":this.index+"","c":"Edit"};
                
                this.layout.push(item);
                TYPO3.jQuery("#lthpackageGridDisplay").val(JSON.stringify(this.layout));
            },
            editItem: function(item) {
                TYPO3.jQuery("#gridelementsModal").modal("toggle");
                var gridContent = TYPO3.jQuery("#"+item.i+" .editItem").html();
                gridContent = gridContent.split("||");
                TYPO3.jQuery("#tileChoice").val(gridContent[0]);
                TYPO3.jQuery("#lthPackageGridContentTitle").val(gridContent[1]);
                TYPO3.jQuery("#lthPackageGridContentText").val(gridContent[2]);
                TYPO3.jQuery("#lthPackageGridContentImage").val(gridContent[3]);
                TYPO3.jQuery("#lthPackageGridContentEmbed").val(gridContent[4]);
                TYPO3.jQuery("#lthPackageGridContentHtml").val(gridContent[5]);
                TYPO3.jQuery("#lthPackageGridId").val(item.i);
            },
            moved: function(i, newX, newY){
                console.log("### MOVED i=" + i + ", X=" + newX + ", Y=" + newY);
                var grids = TYPO3.jQuery.parseJSON(TYPO3.jQuery("#lthpackageGridDisplay").val());
                TYPO3.jQuery(grids).each( function() {
                    if (this.i == i) {
                        this.x = newX;
                        this.y = newY;
                    }
                });
                TYPO3.jQuery("#lthpackageGridDisplay").val(JSON.stringify(grids));
            },
            resized: function(i, newH, newW, newHPx, newWPx){
                //console.log("### RESIZED i=" + i + ", H=" + newH + ", W=" + newW + ", H(px)=" + newHPx + ", W(px)=" + newWPx);
                var grids = TYPO3.jQuery.parseJSON(TYPO3.jQuery("#lthpackageGridDisplay").val());
                TYPO3.jQuery(grids).each( function() {
                    if (this.i == i) {
                        this.h = newH;
                        this.w = newW;
                    }
                });
                TYPO3.jQuery("#lthpackageGridDisplay").val(JSON.stringify(grids));
            },
        }

    });
});

TYPO3.jQuery(document).ready(function() {
    
    TYPO3.jQuery("#gridelementsModal .btn-primary").click(function(){
        var gridId = TYPO3.jQuery("#lthPackageGridId").val();
        var gridContent = TYPO3.jQuery("#tileChoice").val() + "||";
        gridContent += TYPO3.jQuery("#lthPackageGridContentTitle").val() + "||";
        gridContent += TYPO3.jQuery("#lthPackageGridContentText").val() + "||";
        gridContent += TYPO3.jQuery("#lthPackageGridContentImage").val() + "||";
        gridContent += TYPO3.jQuery("#lthPackageGridContentEmbed").val() + "||";
        gridContent += TYPO3.jQuery("#lthPackageGridContentHtml").val();
        
        TYPO3.jQuery("#"+gridId+" .editItem").html(gridContent);
        var grids = TYPO3.jQuery("#lthpackageGridDisplay").val();
        //alert(grids);
        grids = TYPO3.jQuery.parseJSON(grids);
        TYPO3.jQuery(grids).each( function() {
            if (this.i == gridId) this.c = gridContent;
        });
        grids = JSON.stringify(grids);
        TYPO3.jQuery("#lthpackageGridDisplay").val(grids);
        TYPO3.jQuery("#gridelementsModal").modal("toggle");
    });
    TYPO3.jQuery("#tileChoice").change(function(){
        TYPO3.jQuery(".hideItem").hide();
        var tileChoice = TYPO3.jQuery(this).val();
        if(tileChoice === "tileb" || tileChoice === "tilec" || tileChoice === "tilee" || tileChoice === "tileg" || tileChoice === "tileh") {
            TYPO3.jQuery("#lthPackageGridContentTitle,#lthPackageGridContentText").parent().show();
        } else if(tileChoice === "tilea" || tileChoice === "tiled") {
            TYPO3.jQuery("#lthPackageGridContentTitle,#lthPackageGridContentImage").parent().show();
        } else if(tileChoice === "tilef") {
            TYPO3.jQuery("#lthPackageGridContentEmbed").parent().show();
        } else if(tileChoice === "html") {
            TYPO3.jQuery("#lthPackageGridContentHtml").parent().show();
        }
    });
});

</script>';
        
        
        
        return $content;
        
    }
}
/*
 * <input type="text" data-formengine-validation-rules="[{&quot;type&quot;:&quot;required&quot;,&quot;config&quot;:{&quot;type&quot;:&quot;input&quot;,&quot;size&quot;:&quot;8&quot;,&quot;default&quot;:&quot;&quot;}}]" data-formengine-input-params="{&quot;field&quot;:&quot;data[tt_content][1178][pi_flexform][data][sDEF][lDEF][header][vDEF]&quot;,&quot;evalList&quot;:&quot;required&quot;,&quot;is_in&quot;:&quot;&quot;}" data-formengine-input-name="data[tt_content][1178][pi_flexform][data][sDEF][lDEF][header][vDEF]" id="formengine-input-5ac623bdb4c19645851213" value="" class="form-control t3js-clearable hasDefaultValue">
 */