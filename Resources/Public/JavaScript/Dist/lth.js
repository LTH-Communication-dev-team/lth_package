var localSearchUrl = "/sok/?query=";
var minlength = 3;
// initial interval value
var SearchTimeOut = 0;
var Interval = 777;
var sliderInterval = 10000;
var imagePath = "images/";
 
// stores the search keyword
var CurrentSearchKey = '';

var mouse_is_inside = false;

var url = window.location.pathname;

var ajaxLoginBox = '<form action="'+url+'" target="_top" method="post" onsubmit="return TYPO3FrontendLoginFormRsaEncryption.submitForm(this, TYPO3FrontendLoginFormRsaEncryptionPublicKeyUrl); return true;">\
  <fieldset>\
  <legend>Logga in</legend>\
  <div>\
    <label for="user">Användarnamn:</label>\
    <input type="text" id="user" name="user" value="">\
  </div>\
  <div>\
    <label for="pass">Lösenord:</label>\
    <input type="password" id="pass" name="pass" value="">\
  </div>\
  <div>\
    <input type="submit" name="submit" value="Logga in">\
  </div>\
  <div class="felogin-hidden">\
    <input type="hidden" name="logintype" value="login">\
    <input type="hidden" name="pid" value="42857,41797,41798,41799,41800,41801,41802,41803,41804,41805,41806,41807,41808,41809,41810,41811,41812,41813,41814,41815,41816,41817,41818,41819,41820,41821,41822,41823,41824,41825,41826,41827,41828,41829,41830,41831,41832,41833,41834,41835,41836,41837,41838,41839,41840,41841,41842,41843,41844,41845,41846,41847,41848,41849,41850,41851,41852,41853,41854,41855,41856,41857,41858,41859,41860,41861,41862,41863,41864,41865,41866,41867,41868,41869,41870,41871,41872,41873,41874,41875,41876,41877,41878,41879,41880,41881,41882,41883,41884,41885,41886,41887,41888,41889,41890,41891,41892,41893,41894,41895,41896,41897,41898,41899,41900,41901,41902,41903,41904,41905,41906,41907,41908,41909,41910,41911,41912,41913,41914,41915,41916,41917,41918,41919,41920,41921,41922,41923,41924,41925,41926,41927,41928,41929,41930,41931,41932,41933,41934,41935,41936,41937,41938,41939,41940,41941,41942,41943,41944,41945,41946,41947,41948,41949,41950,41951,41952,41953,41954,41955,41956,41957,41958,41959,41960,41961,41962,41963,41964,41965,41966,41967,41968,41969,41970,41971,41972,41973,41974,41975,41976,41977,41978,41979,41980,41981,41982,41983,41984,41985,41986,41987,41988,41989,41990,41991,41992,41993,41994,41995,41996,41997,41998,41999,42000,42001,42002,42003,42004,42005,42006,42007,42008,42009,42010,42011,42012,42013,42014,42015,42016,42017,42018,42019,42020,42021,42022,42023,42024,42025,42026,42027,42028,42029,42030,42031,42032,42033,42034,42035,42036,42037,42038,42039,42040,42041,42042,42043,42044,42045,42046,42047,42048,42049,42050,42051,42052,42053,42054,42055,42056,42057,42058,42059,42060,42061,42062,42063,42064,42065,42066,42067,42068,42069,42070,42071,42072,42073,42074,42075,42076,42077,42078,42079,42080,42081,42082,42083,42084,42085,42086,42087,42088,42089,42090,42091,42092,42093,42094,42095,42096,42097,42098,42099,42100,42101,42102,42103,42104,42105,42106,42107,42108,42109,42110,42111,42112,42113,42114,42115,42116,42117,42118,42119,42120,42121,42122,42123,42124,42125,42126,42127,42128,42129,42130,42131,42132,42133,42134,42135,42136,42137,42138,42139,42140,42141,42142,42143,42144,42145,42146,42147,42148,42149,42150,42151,42152,42153,42154,42155,42156,42157,42158,42159,42160,42161,42162,42163,42164,42165,42166,42167,42168,42169,42170,42171,42172,42173,42174,42175,42176,42177,42178,42179,42180,42181,42182,42183,42184,42185,42186,42187,42188,42189,42190,42191,42192,42193,42194,42195,42196,42197,42198,42199,42200,42201,42202,42203,42204,42205,42206,42207,42208,42209,42210,42211,42212,42213,42214,42215,42216,42217,42218,42219,42220,42221,42222,42223,42224,42225,42226,42227,42228,42229,42230,42231,42232,42233,42234,42235,42236,42237,42238,42239,42240,42241,42242,42243,42244,42245,42246,42247,42248,42249,42250,42251,42252,42253,42254,42255,42256,42257,42258,42259,42260,42261,42262,42263,42264,42265,42266,42267,42268,42269,42270,42271,42272,42273,42274,42275,42276,42277,42278,42279,42280,42281,42282,42283,42284,42285,42286,42287,42288,42289,42290,42291,42292,42293,42294,42295,42296,42297,42298,42299,42300,42301,42302,42303,42304,42305,42306,42307,42308,42309,42310,42311,42312,42313,42314,42315,42316,42317,42318,42319,42320,42321,42322,42323,42324,42325,42326,42327,42328,42329,42330,42331,42332,42333,42334,42335,42336,42337,42338,42339,42340,42341,42342,42343,42344,42345,42346,42347,42348,42349,42350,42351,42352,42353,42354,42355,42356,42357,42358,42359,42360,42361,42362,42363,42364,42365,42366,42367,42368,42369,42370,42371,42372,42373,42374,42375,42376,42377,42378,42379,42380,42381,42382,42383,42384,42385,42386,42387,42388,42389,42390,42391,42392,42393,42394,42395,42396,42397,42398,42399,42400,42401,42402,42403,42404,42405,42406,42407,42408,42409,42410,42411,42412,42413,42414,42415,42416,42417,42418,42419,42420,42421,42422,42423,42424,42425,42426,42427,42428,42429,42430,42431,42432,42433,42434,42435,42436,42437,42438,42439,42440,42441,42442,42443,42444,42445,42446,42447,42448,42449,42450,42451,42452,42453,42454,42455,42456,42457,42458,42459,42460,42461,42462,42463,42464,42465,42466,42467,42468,42469,42470,42471,42472,42473,42474,42475,42476,42477,42478,42479,42480,42481,42482,42483,42484,42485,42486,42487,42488,42489,42490,42491,42492,42493,42494,42495,42496,42497,42498,42499,42500,42501,42502,42503,42504,42505,42506,42507,42508,42509,42510,42511,42512,42513,42514,42515,42516,42517,42518,42519,42520,42521,42522,42523,42524,42525,42526,42527,42528,42529,42530,42531,42532,42533,42534,42535,42536,42537,42538,42539,42540,42541,42542,42543,42544,42545,42546,42547,42548,42549,42550,42551,42552,42553,42554,42555,42556,42557,42558,42559,42560,42561,42562,42563,42564,42565,42566,42567,42568,42569,42570,42571,42572,42573,42574,42575,42576,42577,42578,42579,42580,42581,42582,42583,42584,42585,42586,42587,42588,42589,42590,42591,42592,42593,42594,42595,42596,42597,42598,42599,42600,42601,42602,42603,42604,42605,42606,42607,42608,42609,42610,42611,42612,42613,42614,42615,42616,42617,42618,42619,42620,42621,42622,42623,42624,42625,42626,42627,42628,42629,42630,42631,42632,42633,42634,42635,42636,42637,42638,42639,42640,42641,42642,42643,42644,42645,42646,42647,42648,42649,42650,42651,42652,42653,42654,42655,42656,42657,42658,42659,42660,42661,42662,42663,42664,42665,42666,42667,42668,42669,42670,42671,42672,42673,42674,42675,42676,42677,42678,42679,42680,42681,42682,42683,42684,42685,42686,42687,42688,42689,42690,42691,42692,42693,42694,42695,42696,42697,42698,42699,42700,42701,42702,42703,42704,42705,42706,42707,42708,42709,42710,42711,42712,42713,42714,42715,42716,42717,42718,42719,42720,42721,42722,42723,42724,42725,42726,42727,42728,42729,42730,42731,42732,42733,42734,42735,42736,42737,42738,42739,42740,42741,42742,42743,42744,42745,42746,42747,42748,42749,42750,42751,42752,42753,42754,42755,42756,42757,42758,42759,42760,42761,42762,42763,42764,42765,42766,42767,42768,42769,42770,42771,42772,42773,42774,42775,42776,42777,42778,42779,42780,42781,42782,42783,42784,42785,42786,42787,42788,42789,42790,42791,42792,42793,42794,42795,42796,42797,42798,42799,42800,42801,42802,42803,42804,42805,42806,42807,42808,42809,42810,42811,42812,42813,42814,42815,42816,42817,42818,42819,42820,42821,42822,42823,42824,42825,42826,42827,42828,42829,42830,42831,42832,42833,42834,42835,42836,42837,42838,42839,42840,42841,42842,42843,42844,42845,42846,42847,42848,42849,42850,42851,42852,42853,42854,42855,42856,42857,41797,41798,41799,41800,41801,41802,41803,41804,41805,41806,41807,41808,41809,41810,41811,41812,41813,41814,41815,41816,41817,41818,41819,41820,41821,41822,41823,41824,41825,41826,41827,41828,41829,41830,41831,41832,41833,41834,41835,41836,41837,41838,41839,41840,41841,41842,41843,41844,41845,41846,41847,41848,41849,41850,41851,41852,41853,41854,41855,41856,41857,41858,41859,41860,41861,41862,41863,41864,41865,41866,41867,41868,41869,41870,41871,41872,41873,41874,41875,41876,41877,41878,41879,41880,41881,41882,41883,41884,41885,41886,41887,41888,41889,41890,41891,41892,41893,41894,41895,41896,41897,41898,41899,41900,41901,41902,41903,41904,41905,41906,41907,41908,41909,41910,41911,41912,41913,41914,41915,41916,41917,41918,41919,41920,41921,41922,41923,41924,41925,41926,41927,41928,41929,41930,41931,41932,41933,41934,41935,41936,41937,41938,41939,41940,41941,41942,41943,41944,41945,41946,41947,41948,41949,41950,41951,41952,41953,41954,41955,41956,41957,41958,41959,41960,41961,41962,41963,41964,41965,41966,41967,41968,41969,41970,41971,41972,41973,41974,41975,41976,41977,41978,41979,41980,41981,41982,41983,41984,41985,41986,41987,41988,41989,41990,41991,41992,41993,41994,41995,41996,41997,41998,41999,42000,42001,42002,42003,42004,42005,42006,42007,42008,42009,42010,42011,42012,42013,42014,42015,42016,42017,42018,42019,42020,42021,42022,42023,42024,42025,42026,42027,42028,42029,42030,42031,42032,42033,42034,42035,42036,42037,42038,42039,42040,42041,42042,42043,42044,42045,42046,42047,42048,42049,42050,42051,42052,42053,42054,42055,42056,42057,42058,42059,42060,42061,42062,42063,42064,42065,42066,42067,42068,42069,42070,42071,42072,42073,42074,42075,42076,42077,42078,42079,42080,42081,42082,42083,42084,42085,42086,42087,42088,42089,42090,42091,42092,42093,42094,42095,42096,42097,42098,42099,42100,42101,42102,42103,42104,42105,42106,42107,42108,42109,42110,42111,42112,42113,42114,42115,42116,42117,42118,42119,42120,42121,42122,42123,42124,42125,42126,42127,42128,42129,42130,42131,42132,42133,42134,42135,42136,42137,42138,42139,42140,42141,42142,42143,42144,42145,42146,42147,42148,42149,42150,42151,42152,42153,42154,42155,42156,42157,42158,42159,42160,42161,42162,42163,42164,42165,42166,42167,42168,42169,42170,42171,42172,42173,42174,42175,42176,42177,42178,42179,42180,42181,42182,42183,42184,42185,42186,42187,42188,42189,42190,42191,42192,42193,42194,42195,42196,42197,42198,42199,42200,42201,42202,42203,42204,42205,42206,42207,42208,42209,42210,42211,42212,42213,42214,42215,42216,42217,42218,42219,42220,42221,42222,42223,42224,42225,42226,42227,42228,42229,42230,42231,42232,42233,42234,42235,42236,42237,42238,42239,42240,42241,42242,42243,42244,42245,42246,42247,42248,42249,42250,42251,42252,42253,42254,42255,42256,42257,42258,42259,42260,42261,42262,42263,42264,42265,42266,42267,42268,42269,42270,42271,42272,42273,42274,42275,42276,42277,42278,42279,42280,42281,42282,42283,42284,42285,42286,42287,42288,42289,42290,42291,42292,42293,42294,42295,42296,42297,42298,42299,42300,42301,42302,42303,42304,42305,42306,42307,42308,42309,42310,42311,42312,42313,42314,42315,42316,42317,42318,42319,42320,42321,42322,42323,42324,42325,42326,42327,42328,42329,42330,42331,42332,42333,42334,42335,42336,42337,42338,42339,42340,42341,42342,42343,42344,42345,42346,42347,42348,42349,42350,42351,42352,42353,42354,42355,42356,42357,42358,42359,42360,42361,42362,42363,42364,42365,42366,42367,42368,42369,42370,42371,42372,42373,42374,42375,42376,42377,42378,42379,42380,42381,42382,42383,42384,42385,42386,42387,42388,42389,42390,42391,42392,42393,42394,42395,42396,42397,42398,42399,42400,42401,42402,42403,42404,42405,42406,42407,42408,42409,42410,42411,42412,42413,42414,42415,42416,42417,42418,42419,42420,42421,42422,42423,42424,42425,42426,42427,42428,42429,42430,42431,42432,42433,42434,42435,42436,42437,42438,42439,42440,42441,42442,42443,42444,42445,42446,42447,42448,42449,42450,42451,42452,42453,42454,42455,42456,42457,42458,42459,42460,42461,42462,42463,42464,42465,42466,42467,42468,42469,42470,42471,42472,42473,42474,42475,42476,42477,42478,42479,42480,42481,42482,42483,42484,42485,42486,42487,42488,42489,42490,42491,42492,42493,42494,42495,42496,42497,42498,42499,42500,42501,42502,42503,42504,42505,42506,42507,42508,42509,42510,42511,42512,42513,42514,42515,42516,42517,42518,42519,42520,42521,42522,42523,42524,42525,42526,42527,42528,42529,42530,42531,42532,42533,42534,42535,42536,42537,42538,42539,42540,42541,42542,42543,42544,42545,42546,42547,42548,42549,42550,42551,42552,42553,42554,42555,42556,42557,42558,42559,42560,42561,42562,42563,42564,42565,42566,42567,42568,42569,42570,42571,42572,42573,42574,42575,42576,42577,42578,42579,42580,42581,42582,42583,42584,42585,42586,42587,42588,42589,42590,42591,42592,42593,42594,42595,42596,42597,42598,42599,42600,42601,42602,42603,42604,42605,42606,42607,42608,42609,42610,42611,42612,42613,42614,42615,42616,42617,42618,42619,42620,42621,42622,42623,42624,42625,42626,42627,42628,42629,42630,42631,42632,42633,42634,42635,42636,42637,42638,42639,42640,42641,42642,42643,42644,42645,42646,42647,42648,42649,42650,42651,42652,42653,42654,42655,42656,42657,42658,42659,42660,42661,42662,42663,42664,42665,42666,42667,42668,42669,42670,42671,42672,42673,42674,42675,42676,42677,42678,42679,42680,42681,42682,42683,42684,42685,42686,42687,42688,42689,42690,42691,42692,42693,42694,42695,42696,42697,42698,42699,42700,42701,42702,42703,42704,42705,42706,42707,42708,42709,42710,42711,42712,42713,42714,42715,42716,42717,42718,42719,42720,42721,42722,42723,42724,42725,42726,42727,42728,42729,42730,42731,42732,42733,42734,42735,42736,42737,42738,42739,42740,42741,42742,42743,42744,42745,42746,42747,42748,42749,42750,42751,42752,42753,42754,42755,42756,42757,42758,42759,42760,42761,42762,42763,42764,42765,42766,42767,42768,42769,42770,42771,42772,42773,42774,42775,42776,42777,42778,42779,42780,42781,42782,42783,42784,42785,42786,42787,42788,42789,42790,42791,42792,42793,42794,42795,42796,42797,42798,42799,42800,42801,42802,42803,42804,42805,42806,42807,42808,42809,42810,42811,42812,42813,42814,42815,42816,42817,42818,42819,42820,42821,42822,42823,42824,42825,42826,42827,42828,42829,42830,42831,42832,42833,42834,42835,42836,42837,42838,42839,42840,42841,42842,42843,42844,42845,42846,42847,42848,42849,42850,42851,42852,42853,42854,42855,42856,1534">\
    <input type="hidden" name="redirect_url" value="">\
    <input type="hidden" name="tx_felogin_pi1[noredirect]" value="0">\
  </div>\
  </fieldset>\
</form>';

$(document).ready( function () {

    //Document ready BEGIN

    $('textarea,select').addClass('form-control');
    
  var phpsessid=getCookie("PHPSESSID");
      if($('.Tx-Formhandler').length > 0 && phpsessid=='') {
          $('.Tx-Formhandler').html('<h1>Cookies must be enabled!</h1>Formhandler needs cookies to work. If you think you have cookies enabled, try to reload the page.');
      }
  
  //Fancybox lightbox
    $('a.fancybox-enlarge').fancybox();
  
  $('a.fancybox-gallery').fancybox();
  
  $('.various').fancybox({
    maxWidth  : 800,
    maxHeight  : 600,
    fitToView  : false,
    width    : '70%',
    height    : '70%',
    autoSize  : false,
    closeClick  : false,
    openEffect  : 'none',
    closeEffect  : 'none'
  });
  
  $('a.promo_video, a.lightbox').click(function() {
    //alert(this.href);
    if(this.href.indexOf('www.youtube.com') > 0) {
    $.fancybox({
      'type' : 'iframe',
          // hide the related video suggestions and autoplay the video
          //'href' : this.href.replace(new RegExp('watch\\?v=', 'i'), 'embed/') + '?rel=0&autoplay=1',
      'href' : this.href + '?rel=0&autoplay=1',
          'overlayShow' : true,
          'centerOnScroll' : true,
          'speedIn' : 100,
          'speedOut' : 50,
          'width' : 640,
          'height' : 480
    });
    } else {
      $.fancybox({
          'padding' : 10,
          'autoScale' : false,
          'title' : this.title,
          'overlayOpacity' : '1.6',
          'overlayColor' : '#333',
          'transitionIn' : 'none',
          'transitionOut' : 'none',
          'centerOnScroll' : false,
          'showCloseButton' : true,
          'hideOnOverlayClick': false,
          'content': '<embed src="typo3/contrib/flashmedia/flvplayer.swf?file='+(this.href.replace(new RegExp("watch\\?v=", "i"), "v/"))+'&amp;autostart=true&amp;fs=1" type="application/x-shockwave-flash" width="640" height="376" wmode="opaque" allowfullscreen="true" allowscriptaccess="always"></embed>'
      });  
    }
    return false;
  });
  
  $(".fancybox").fancybox({
    openEffect  : 'none',
    closeEffect  : 'none'
  });
  
  //Main navigation with mega menu
  //$('.main-nav-inner-border').corner();
  $(function() {
    var $menu = $('#main-nav-menu');
    $menu.children('li').each(function(){
      var $this = $(this);
      //var $span = $this.children('span');
      //$span.data('width',$span.width());
      $this.bind('mouseenter',function(){
        if ( $this.find('span').length ) { 
          var pos = $this.find('span').position().left;
        }
        //var width = $this.find('span').width();
        $('.main-nav-arrow-up').css('backgroundPosition', pos + 10);
        $menu.find('.main-nav-submenu').stop(true,true).hide();
        //$span.stop().animate(300,function(){
        $this.find('.main-nav-submenu').slideDown(300);
        //});
      }).bind('mouseleave',function(){
        $this.find('.main-nav-submenu').stop(true,true).hide();
        //$span.stop().animate(300);
      });
    });
    //==##
    if($('.main-nav-inner-content-left').length){
      $('#newContactContent').siblings(".main-nav-inner-content-left").replaceWith($("#newContactContent").css('visibility','visible')); 
    }
    //WAS HERE
  });
  
   var url = $(location).attr('pathname').replace(/\/?$/, '/');
      var urlArray = url.split('/');
      var urlPart = urlArray[1];
    
     //Add selected parts of main navigation and hamburger menu. TH/150410
      $('#main-nav-menu .menuItem a[href="'+urlPart+'/"]').closest('li').addClass('selected');
      /*if($('#responsive_navigation a[href="'+url.substring(1)+'"]').length) {
          $('#responsive_navigation li:first').removeClass('selected');
          $('#responsive_navigation a[href="'+url.substring(1)+'"]').closest('li').addClass('selected');
      }*/
      
      //Add has sub
      //$('#responsive_navigation .has_sub a').after('<a class="responsive_link expand"></a>');
  
  //============================================================= 
       // "expand-div" function mainly for FAQ page 
       // MP 2013/12/20
       //=============================================================
      $(".expandHeader").click(function () {
          $expandHeader = $(this);
          //getting the next element
          $expandContent = $expandHeader.next();
          //open up the content needed - toggle the slide- if visible, slide up, if not slidedown.
          $expandContent.slideToggle(500, function () {
          //replace bg-img if content is visible  
            $expandContent.is(":visible") ? $expandHeader.css("background-image", "url(/fileadmin/templates/images/arrowSmallDown.png)") : $expandHeader.css("background-image", "url(/fileadmin/templates/images/arrowSmall15.png)");
        });
     });
     
  $(".content-wrapper .text-content .content-navigation .menu-level-1 li").hover(function(){
      $(this).css({backgroundColor: "#f1ede2"});
  }, function(){
      $(this).css({backgroundColor: ""});
  });
  
  if ( $('#getSinglePid')[0] ) {
    //http://www.lth.se/nyheter-och-press/nyheter/visa-nyhet/article/lastcykel-foer-flexibelt-behov-1/
    var singleNewsLink = decodeURIComponent($('#getSinglePid a').attr('href'));
    var singleNewsLinkArray = singleNewsLink.split('/');
    var arrayCount = singleNewsLinkArray.length;
    //console.log(arrayCount);
    if (arrayCount >= 4){
    singleNewsLinkArray.remove((arrayCount - 4),arrayCount);
    }
    //singleNewsLinkArray = singleNewsLinkArray[1].split('&');
    singleNewsLink = singleNewsLinkArray.join('/');
    if ( $('#getGoToArchive').length ) {
    singleNewsLink = decodeURIComponent($('#getGoToArchive').attr('href'));
    }
    if($('.archive_link').parents(".calendar-main-title").length == 0 && $('.archive_link').parents(".calendar-wrapper").length == 0){
    //if(!$('.archive_link a').hasClass('.calArchive')){
    $('.archive_link a').attr('href',singleNewsLink);
    //}
    }
  }
  /* FIX - On click outside lu-header tab the arrow down icon was not restored
    *  version 1.3 of templates should be fixed by next release, this can then be 
    *  deleted
    */     
    $('#lu_overlay').click(function() {
       $("#lu_header_button").css("background-image", "url(" + imagePath + "tab-down.png)");  
    });
    
  //footer_logo
  //$('#footer_logo').attr('href','onclitypo3/index.php?redirect_url='+window.location.pathname);
  /*
  * TYPO3-login removed from logo and put in separate link MP 2014/01/28
  */
  //Tror #footer_logo_en är gammalt, nu använder vi en_lu/en_lth 2015-01-29
  $("#footer_logo_en").click(function() {
    window.location = '#';
    //window.location = '/typo3/index.php?redirect_url='+window.location.pathname;
  });
  $("#footer_logo_en_lu").click(function() {
     window.location = 'http://www.lunduniversity.lu.se/';
  });
  $("#footer_logo").click(function() {
    window.location = 'http://www.lth.se';
  });
  $("#footer_logo_en_lth").click(function() {
    window.location = 'http://www.lth.se/english';
  });
  $("#footer_logo_lu").click(function() {
    window.location = 'http://www.lu.se/';
  });
  $(".header_logo_en_lth").attr("href","http://www.lth.se/english");
  $(".header_logo_lth").attr("href","http://www.lth.se");
  $(".header_logo").attr("href","http://www.lu.se");
  $(".header_logo_en").attr("href","http://www.lunduniversity.lu.se/");
  //lagt till +location.search för att få med query string, redirecten skickade inte med query string och fungerade därför inte alltid MP 2014-09-17
  $("#typo3Login").replaceWith('Typo3-login: <a href="/typo3/index.php?redirect_url='+encodeURIComponent(window.location.href)+'">Frontend</a>&nbsp;<a href="/typo3">Backend</a>');
  //$("#typo3Login").remove
    //$("#typo3Login").attr("href","/typo3/index.php?redirect_url="+encodeURIComponent(window.location.href));
  //$("#typo3Login").attr("href","/typo3");
  
  //Add shortqut to hambuerger menu BEGIN
     tmpContent = '';
     $('#header_nav li').each(function(){
           tmpContent += $(this).html();
     });
     if(tmpContent!='') {
           $('#responsive_navigation > ul').append('<li class="responsive-shortcuts has_sub"><a href="#">Genvägar</a><a class="responsive_link expand"></a><ul class="menu-level-2">'+tmpContent+'</ul></li>')
     }
     //Add shortqut to hambuerger menu END
     
     
     //Add google push to links BEGIN
      $.each([ 'pdf', 'mpeg', 'avi', 'doc', 'docx', 'xls', 'xlsx', 'mp4', 'mpeg4' ], function( index, value ) {
          $('a[href$=".'+value+'"]').click(function(e) {
                theLink = $(this).attr('href');
                ga('send', 'event', value, 'Download', theLink);
          });
     })
     //Add google push to links END
    $( "#searchsite, #edit-search" ).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "index.php",
                dataType: "json",
                data: {
                    query: request.term,
                    eID : 'lth_solr',
                    action: 'searchShort',
                    sid : Math.random()
                },
                /*success: function( data ) {
                    response( data );
                },*/
                type : "POST",
        //url : 'http://connector.search.lu.se:8181/solr/sr/130.235.208.15/sid-d86c248d60b4072f018c/ss/customsites/1/undefined?' + d.getTime() + '-sid-d86c248d60b4072f018c',
                //url: 'http://connector.search.lu.se:8181/solr/ac/130.235.208.15/sid-d86c248d60b4072f018c/' + request.term + '/customsites',
                dataType: "json",
                /*complete: function( data ) {
                    console.log(data);
                    response( data );
                },*/
                success: function( data ) {
                    //console.log(data);
                    jsonObj = [];
                    $.each(data, function(key, aData) {
                        if(aData.id==='lu' && aData.value) {
                            //console.log($(aData.value).filter('.hit'));
                            var i=0;
                            var obj = aData.value[1].search_result;
                            for (var key in obj) {
                                if (obj.hasOwnProperty(key)) {
                                    var val = obj[key];
                                    if(i<5) {
                                        item = {};
                                        item ["id"] = 'lu_'+i;
                                        item ["label"] = val.label;
                                        item ["value"] = val.label;
                                        item ["type"] = aData.type;
                                        jsonObj.push(item);
                                    }
                                }
                                i++;
                            }
                            /*$(aData.value).filter('.hit').each(function( index ) {
                                if(i<5) {
                                    item = {}
                                    item ["id"] = 'lu_'+i;
                                    item ["label"] = $(this).find('.title').text().trim();
                                    item ["value"] = 'lu_'+i;
                                    item ["type"] = aData.type;
                                    jsonObj.push(item);
                                    //console.log($(this).find('.title').text().trim());
                                }
                                i++;
                            });*/
                        } else if(aData.id==='lth' && aData.value) {
                            var i=0;
                            var i=0;
                            var obj = aData.value[0].search_result;
                            for (var key in obj) {
                                if (obj.hasOwnProperty(key)) {
                                    var val = obj[key];
                                    if(i<5) {
                                        item = {};
                                        item ["id"] = 'lth_'+i;
                                        item ["label"] = val.label;
                                        item ["value"] = val.label;
                                        item ["type"] = aData.type;
                                        jsonObj.push(item);
                                    }
                                }
                                i++;
                            }
                            /*$(aData.value).filter('.hit').each(function( index ) {
                                if(i<5) {
                                    item = {}
                                    item ["id"] = 'lth_'+i;
                                    item ["label"] = $(this).find('.title').text().trim();
                                    item ["value"] = 'lth_'+i;
                                    item ["type"] = aData.type;
                                    jsonObj.push(item);
                                    //console.log($(this).find('.title').text().trim());
                                }
                                i++;
                            });*/
                        } else if(aData.value) {
                            item = {}
                            item ["id"] = aData.id;
                            item ["label"] = aData.label;
                            item ["value"] = aData.value;
                            item ["type"] = aData.type;
                            jsonObj.push(item);
                        }
                    });
                    response( jsonObj );
                }
            });
            },
            minLength: 3,
            select: function( event, ui ) {
                //console.log( ui.item ? "Selected: " + ui.item.label :" Nothing selected, input was " + this.value);
                if(ui.item.label) {
                    //alert(ui.item.label);
                    if($('html').attr('lang') === 'sv') {
                        window.location.href = $('#searchsiteformlth').attr('action') + 'sok?term='+ui.item.id;
                    } else if($('html').attr('lang') === 'en') {
                        window.location.href = $('#searchsiteformlth').attr('action') + 'search?term='+ui.item.id;
                    }
                };
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( '<li style="min-height:20px;"></li>' ).data("item.autocomplete", item)
            .append( '<a title="' + item.value + '(' + item.type + ')">' + trimData(item.value,40)  + '</a>')
            .appendTo( ul );
    };
        //console.log($('#lthsolr_person').attr('data-homepage'));
        
    $('.lth-flyout a').mouseover(function() {
        
        if($(this).parent().hasClass('lth-logout')) {
            return false;
        }
        
        $('head').append('<script type="text/javascript">var TYPO3FrontendLoginFormRsaEncryptionPublicKeyUrl = \'index.php?eID=FrontendLoginRsaPublicKey\';</script>');
        
        if(typeof dbits=='undefined') $.getScript('http://vkans-th0.kansli.lth.se/typo3/sysext/rsaauth/resources/jsbn/jsbn.js');
        if(typeof dbits=='undefined') $.getScript('http://vkans-th0.kansli.lth.se/typo3/sysext/rsaauth/resources/jsbn/prng4.js');
        if(typeof dbits=='undefined') $.getScript('http://vkans-th0.kansli.lth.se/typo3/sysext/rsaauth/resources/jsbn/rng.js');
        if(typeof dbits=='undefined') $.getScript('http://vkans-th0.kansli.lth.se/typo3/sysext/rsaauth/resources/jsbn/rsa.js');
        if(typeof dbits=='undefined') $.getScript('http://vkans-th0.kansli.lth.se/typo3/sysext/rsaauth/resources/jsbn/base64.js');
        if(typeof dbits=='undefined') $.getScript('http://vkans-th0.kansli.lth.se/typo3/sysext/rsaauth/resources/FrontendLoginFormRsaEncryption.min.js');

        if($('#ajaxlogin').length === 0) {
            $('.lth-flyout-container').html(ajaxLoginBox);
            $('.lth-close').click(function(){
                $('.lth-flyout-container').hide();
                return false;
            });
            $('.lth-flyout-container').show();
        } else if($('.lth-flyout-container').is(":hidden")) {
            $('.lth-flyout-container').show();
        }
    });
    
    $('.lth-flyout a').click(function(){
        if($(this).parent().hasClass('lth-logout')) {
            logout(this);
            return false;
        } else {
            return false;
        }
    });
    

  /*   (function() {
        solr = {sid: 'sid-07856cbc0c3c046c4f20',q:'',p:1, url:'search'};
        d = new Date();function async_load(){var s=document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = 'http://solr.search.lu.se:8899/loader.js?'+ ('' + d.getTime()).slice(0,5);var x=document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(window.attachEvent){window.attachEvent('onload', async_load);}else{window.addEventListener('load', async_load, false);}})();
*/
     //Document ready END
});

/*Array.prototype.remove = function(from, to)
{
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};*/

function trimData(input, maxLength)
{
    if(input) {
        if(input.length > maxLength) {
            return input.substr(0,maxLength) + '...';
        }
    }
    return input;
}


function UrlExists(url, cb){
    $.ajax({
        url:      url,
        dataType: 'text',
        type:     'GET',
        complete:  function(xhr){
            if(typeof cb === 'function')
               cb.apply(this, [xhr.status]);
        }
    });
}


function toggleBarStart()
{
    document.write("<scr" + "ipt>var _baLocale = 'se', _baMode = ' ';</scr" + "ipt>" + "<scr" +"ipt src=\"//www.browsealoud.com/plus/scripts/ba.js\" async></scr" + "ipt>");
    /*var _baLocale = 'se', _baMode = ' ';
    $.getScript('//www.browsealoud.com/plus/scripts/ba.js', function() {
        toggleBar();
    });
    return false;*/
    toggleBar();
}

function toggleBarStart()
{
  //" + "
    //$('body').append("<script>var _baLocale = 'se', _baMode = ' ';</script><script src=\"//www.browsealoud.com/plus/scripts/ba.js\" async></script>");
    $('body').append("<script>var _baLocale = 'se', _baMode = ' ';</script>");
    var script = document.createElement( "script" )
    script.type = "text/javascript";
    if(script.readyState) {  //IE
        script.onreadystatechange = function() {
            if ( script.readyState === "loaded" || script.readyState === "complete" ) {
                script.onreadystatechange = null;
                toggleBar();
            }
        };
    } else {  //Others
        script.onload = function() {
            toggleBar();
        };
    }

    script.src = '//www.browsealoud.com/plus/scripts/ba.js';
    document.getElementsByTagName( "body" )[0].appendChild( script );
}

function validateLink(link)
{
  if(isNormalInteger(link)) {
    window.location = 'index.php?id='+link;
  } else {
    window.location.href = link;
    //console.log(link);
    
  }
}

function inlinePresentation(e) {
  var temp = e.target;
  var elementName= temp.innerHTML;
  alert(elementName);
   
    /*$("#elementName").click(function(){
    eventReg();
  });*/
  
}

function isNormalInteger(str) {
    return /^\+?(0|[1-9]\d*)$/.test(str);
}

function lth_calendar()
{
  $.ajax({
    type: "POST",
    url: "index.php",
    data: {
      eID : 'lth_calendar',
      sid : Math.random()
    },
    //contentType: "application/json; charset=utf-8",
    dataType: "json",
    beforeSend: function(){
      $('.calendar-horizontal').html('<img src="/typo3conf/ext/lth_calendar/res/ajax-loader.gif" />');
    },
    success: function(data) {
      //alert(data);
      //document.getElementById('calendar-horizontal').innerHTML = utf8_decode(data);
    },
    complete: function(data) {
      //alert(data.responseText);
      //document.write(data.responseText);
      //$('.calendar-horizontal').html(data.responseText);
      document.getElementById('calendar-horizontal').innerHTML = utf8_decode(data.responseText);
      //if(data[1]) $('#page_title').children().html(_decode(data[1]));
      //console.log(data);
    },
    failure: function(errMsg) {
      console.log(errMsg);
    }
  });
  
}

function eventReg()
{
  if (0 === $('.event-reg-modal-overlay').length) {
    var overlay = $('<div/>', {
      'class': 'event-reg-modal-overlay'
    }).appendTo('body');
  }
  
  if (0 === $('.event-reg-modal-window').length) {
    //var modal = $('body').append('<div class="feedit3-modal-window"></div>');
    var modal = $('<div/>', {
      'class': 'event-reg-modal-window'
    }).appendTo('body');
  }
  
  if (0 === $('.event-reg-container').length) {
    $('<div/>', {
      'class': 'event-reg-container'
    }).appendTo('.event-reg-modal-window');
  } else {
    $('.event-reg-container').html('');
  }
  
  $('<div/>', {
    'id': 'event-reg-content-wide',
    'class': 'event-reg-content-wide',
  }).appendTo('.event-reg-container');
  
  $('<div/>', {
    'class': 'event-reg-close',
    'html': '<button class="close">&times;</button>'
  }).appendTo('.event-reg-container');
  
  $('.close').click(function () {
    removeOverlay();
  });
  
  $.ajax({
    type: "POST",
    url: "index.php",
    data: {
      'eID' : 'tx_lthmailformadmin',
      'action' : 'formShow',
      'sid' : Math.random()
    },
    //contentType: "application/json; charset=utf-8",
    dataType: "json",
    beforeSend: function() {
      $('#event-reg-content-wide').html('<img src="/fileadmin/templates/images/ajax-loader.gif" />');
    },
    complete: function(data) {
      //console.log(data);
      $('#event-reg-content-wide').html(data.responseText);
      if(data) console.log('complete'+data.responseText);
    },
    /*success: function(data) {
      if(data) console.log('success'+data.responseText);
    },*/
    failure: function(errMsg) {
      console.log(errMsg);
    }
  });  
}

function ods_ajaxfelogin(obj)
{
  var prefix=obj.form.id.substr(0,obj.form.id.length-5);
  var content = '';
  //jQuery('#'+prefix+'_indication').css('display','block');
  //alert(obj.form.action.replace('http','https'));
  jQuery.ajax({  
    type: 'POST',  
    url: obj.form.action,  
    data: jQuery.param(jQuery(obj.form).serializeArray())+'&'+obj.name+'=1&tx_felogin_pi1[ajax]='+prefix,  
    success: function(data) {
      
      var response=jQuery.parseJSON(data);
      if(response.redirect) {
        //window.location.href=response.redirect;
      } else {
        //jQuery('#'+prefix).html(response.data);
        //jQuery('#'+prefix+'_indication').css('display','none');
        if(response.data.indexOf('success')>0) {
          content = 'Login succeded';
          $('#login-flyout').html('<i class=\"icon-user\"></i> Inloggad som&nbsp;'+getFeUser());
          $('#login-flyout').append('<li><a href="/logout?logintype=logout">Logga ut</a></li>');
        } else {
          content = 'Login failed';
        }
        $('#login_box_content').html(response.data);
      }
    },
    failure: function(errMsg) {
      console.log(errMsg);
    }
  });  
  return false;
}

function getFeUser()
{
  var content = '';
  
  $.ajax({
    type: "POST",
    url: "index.php",
    data: {
      'eID' : 'lth',
      'action' : 'getFeUser',
      'sid' : Math.random()
    },
    //contentType: "application/json; charset=utf-8",
    dataType: "json",
    complete: function(data) {
      //$('#login_box_content').html(data.responseText);
      content = data.responseText;
    },
    failure: function(errMsg) {
      console.log(errMsg);
    }
  });
  
  return content;
}

function autoGrowField(f, max)
{
   /* Default max height */
   var max = (typeof max == 'undefined')?1000:max;
   /* Don't let it grow over the max height */
   if (f.scrollHeight > max) {
      /* Add the scrollbar back and bail */
      if (f.style.overflowY != 'scroll') { f.style.overflowY = 'scroll'; }
      return;
   }
   /* Make sure element does not have scroll bar to prevent jumpy-ness */
   if (f.style.overflowY != 'hidden') { f.style.overflowY = 'hidden'; }
   /* Now adjust the height */
   var scrollH = f.scrollHeight;
   if( scrollH > f.style.height.replace(/[^0-9]/g,'') ){
      f.style.height = scrollH+'px';
   }
}

// cancels the previous search interval
var ResetSearch = function()
{
  // this is what cancels the setTimeout interval assigned
  // to SearchTimeOut
  clearTimeout(SearchTimeOut);
  SearchTimeOut = 0;
};


function createHamburgerMenu()
{
  var scContent = '';
    //console.log(nestedType);
    $('#responsive_menu').after('<ul id="hamburger_menu">' + String(recurse(hamburger_array))+'</ul>');
    $('.get_menu_empty').remove();
    if(nestedType === "one") {
      //console.log('????');
      $('#hamburger_menu > li > a').remove();
  $('#hamburger_menu > li > ul').unwrap();
      $('#hamburger_menu > ul').unwrap();
    }
    
    var startClass = ' selected';
    if($('#responsive_navigation .selected').length) {
        startClass = '';
    }
    if(nestedType === "one") { 
      $('#responsive_navigation > .menu-level-').prepend('<li class="'+startClass+'"><a href="#">Start</a></li>');
    }
    $('#responsive_navigation .menu-level-').each(function() {
        $(this).removeClass('menu-level-');
        $(this).addClass('menu-level-'+($(this).parents('.has_sub').length+1));
    });
    if(nestedType === "two") { 
  $('#hamburger_menu').addClass('menu-level-1');
    }
    $('#header_nav li').each(function() {
      scContent += '<li class="leaf">' + $(this).html() + '</li>';
      //console.log($(this));
    });
    
    if(scContent != '') {
      scContent = '<ul class="responsive-shortcuts"><li><a href="#">Genvägar</a><a class="responsive_link show-xs expand"></a><ul class="menu" style="display: none;">'+scContent+'</ul></li></ul>';
  $('#responsive_navigation').append(scContent);
  $('.responsive-shortcuts').css('background-color','#cccccc');
  $('.responsive-shortcuts a').css('color','#ffffff');
  $('.responsive-shortcuts').click(function() {
    $(this).find('.menu').show();
  });
     }
}


function createHamburgerNav()
{
    var pid;
    var ulCount=1;
    var liCount=1;
    var ii=0;
    var i=0;
    var selectedClass='';
    var selectedFlag=false;
    var content='';
    var scContent = '';
    var oldLevel=0;
    var syslang = $('#syslanguageforMenu').val();
    var title;
    
    if(syslang>0) {
        HA = HA.filter(function(menuitem) { return menuitem.level < 7 && menuitem.label_ol!=''});
    } else {
        HA = HA.filter(function(menuitem) { return menuitem.level < 7});
    }

    $.each(HA, function(key, value) {
        if(value.uid==$('#pageUidforMenu').val()) {
            selectedClass=' selected';
            selectedFlag=true;
        } else {
            selectedClass='';
        }
        //console.log(value.label);
        if(syslang>0) {
            title = value.label_ol;
        } else {
            title = value.label;
        }      
         if(value.level<oldLevel) {
            i=0;
            for(i==0;i<=(ulCount-value.level);i++) {
                content += '</ul></li>';
                ulCount--;
            }
        }
        if(value.hassub>0){
            content += '<li class="has_sub'+selectedClass+'">'+'<a href="index.php/?id='+value.uid+'&L='+syslang+'">'+title+'</a><a class="responsive_link expand"></a>';
            content += '<ul class="menu-level-'+(value.level+1)+'">';
            ulCount++;
        } else {
            content += '<li class="'+selectedClass+'">'+'<a href="index.php/?id='+value.uid+'&L='+syslang+'">'+title+'</a></li>';
        }
        
        oldLevel = value.level;
        ii++;
    });
    if(selectedFlag===false) {
        selectedClass = ' selected';
    }
    
    i=0;
    for(i==0;i<=(ulCount-oldLevel);i++) {
        content += '</ul></li>';
    }
    
    $('#header_nav li').each(function() {
      scContent += '<li class="leaf">' + $(this).html() + '</li>';
      console.log($(this));
    });
    
    $(scContent).wrap('<ul class="responsive-shortcuts"><li><a href="#">Genvägar</a><a class="responsive_link show-xs expand"></a><ul class="menu" style="display: none;"></ul></li></ul>');
                
    content = '<ul class="menu-level-1"><li class="'+selectedClass+'"><a href="/">Start</a></li>'+content+'</ul>'+scContent;

    $('#responsive_menu').after(content);
    
    //Expand - minimize the menu options
    $('#responsive_navigation .has_sub .responsive_link').click(function() {
      $(this).parent('.has_sub').toggleClass('expanded');
      $(this).siblings('ul').slideToggle(150);
      if ($(this).hasClass('expand')) {
        $(this).removeClass('expand').addClass('minimize');
      } else {
        $(this).removeClass('minimize').addClass('expand');
      }
    });

    //Expand menu options to the selected level on page load
      $('#responsive_navigation li.selected').each(function() {
      $(this).parents('#responsive_navigation ul').show();
      $(this).parents('.has_sub').addClass('expanded');
      $(this).children('ul').first().show();
      $(this).children('.responsive_link').removeClass('expand').addClass('minimize');
      $(this).parents('ul').siblings('.responsive_link').removeClass('expand').addClass('minimize');
    });
}


function recurse( data )
{
    var htmlRetStr = ""; //<ul class=\"menu-level-1\">";
    var link = "";
    var selectedClass = '';
    for (var key in data) {
        if (typeof(data[key])== 'object' && data[key] != null) {
            if(data[key].pagepath) {
               link = data[key].pagepath;
            } else {
                link = "index.php/?id=" + data[key].node_uid;
            }
            
            selectedClass = '';
            if($('body').attr('id') == data[key].node_uid) {
                selectedClass = ' selected';
            }
            
            if(data[key]._SUB_MENU && data[key].title) {
                htmlRetStr += "<li class=\"has_sub"+selectedClass+"\"><a href=\"" + link + "\">" + 
                data[key].title + 
                "</a><a class=\"responsive_link expand\"></a><ul class=\"menu-level-\">";
            } else if(data[key].title) {
                htmlRetStr += "<li class=\""+selectedClass+"\"><a href=\"" + link + "\">" + 
                data[key].title + 
                "</a><ul class=\"get_menu_empty\">";
            }
            htmlRetStr += recurse( data[key] );
            if(data[key].title) {
                htmlRetStr += '</ul></li>';
            }
        } else {

            //htmlRetStr += ("<li class=\"\">" + data[key] + '</li>' );
        }
            
    };
    //htmlRetStr += '</ul >'; 
    return(htmlRetStr);
}


function debug(input)
{
  $('#debug').append('- ' + input + '<br />');
}

function utf8_decode (str_data) {
    // http://kevin.vanzonneveld.net
    // +   original by: Webtoolkit.info (http://www.webtoolkit.info/)
    // +      input by: Aman Gupta
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Norman "zEh" Fuchs
    // +   bugfixed by: hitwork
    // +   bugfixed by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   bugfixed by: kirilloid
    // *     example 1: utf8_decode('Kevin van Zonneveld');
    // *     returns 1: 'Kevin van Zonneveld'

    var tmp_arr = [],
      i = 0,
      ac = 0,
      c1 = 0,
      c2 = 0,
      c3 = 0,
      c4 = 0;

    str_data += '';

    while (i < str_data.length) {
      c1 = str_data.charCodeAt(i);
      if (c1 <= 191) {
        tmp_arr[ac++] = String.fromCharCode(c1);
        i++;
      } else if (c1 <= 223) {
        c2 = str_data.charCodeAt(i + 1);
        tmp_arr[ac++] = String.fromCharCode(((c1 & 31) << 6) | (c2 & 63));
        i += 2;
      } else if (c1 <= 239) {
        // http://en.wikipedia.org/wiki/UTF-8#Codepage_layout
        c2 = str_data.charCodeAt(i + 1);
        c3 = str_data.charCodeAt(i + 2);
        tmp_arr[ac++] = String.fromCharCode(((c1 & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
        i += 3;
      } else {
        c2 = str_data.charCodeAt(i + 1);
        c3 = str_data.charCodeAt(i + 2);
        c4 = str_data.charCodeAt(i + 3);
        c1 = ((c1 & 7) << 18) | ((c2 & 63) << 12) | ((c3 & 63) << 6) | (c4 & 63);
        c1 -= 0x10000;
        tmp_arr[ac++] = String.fromCharCode(0xD800 | ((c1>>10) & 0x3FF));
        tmp_arr[ac++] = String.fromCharCode(0xDC00 | (c1 & 0x3FF));
        i += 4;
      }
    }

    return tmp_arr.join('');
}


function getCookie(cname)
{
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}


function timeConverter(UNIX_timestamp)
{
  var a = new Date(UNIX_timestamp * 1000);
  var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();
  var hour = a.getHours();
  var min = a.getMinutes();
  var sec = a.getSeconds();
  var time = date + ' ' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec ;
  return time;
}



function tx_rsaauth_feencrypt(obj)
{
    var user = $('#user', obj).val();
    var pass = $('#pass', obj).val();
    
    var url = window.location.pathname;

    $.ajax({
        type : "POST",
        url : url,
        data: {
            //eID : 'ajax_login',
            user : user,
            pass : pass,
            type : '270',
            logintype: 'login',
            sid : Math.random()
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function () {
            //$('#lthsolr_table tbody').html('<img src="/fileadmin/templates/images/ajax-loader.gif" />');
        },
        complete: function(data) {
            //console.log(data.responseText);
            if(data.responseText.indexOf('logintype') > 0) {
                showFlashMessage(3, 'Login failed', 'Login or password incorrect.', 5000)
            } else {
                $('.lth-flyout-container').hide();
                $('.lth-flyout a').html('<span class="icon-user"></span>Logga ut ' + user);
                $('.lth-flyout').addClass('lth-logout');

                url = window.location.pathname;
                getContent(url);
                showFlashMessage(2, 'Login success', 'You have been successfully logged in.', 5000)
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
            
}

function logout(obj)
{
    $.ajax({
        type : "POST",
        url : window.location.pathname,
        data: {
            type : '270',
            logintype: 'logout',
            sid : Math.random()
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function () {
            //$('#lthsolr_table tbody').html('<img src="/fileadmin/templates/images/ajax-loader.gif" />');
        },
        complete: function(data) {
            $('.lth-flyout a').text('Logga in');
            $('.lth-flyout').removeClass('lth-logout');
            url = window.location.pathname;
            getContent(url);
            showFlashMessage(2, 'Logout', 'You have been successfully logged out.', 5000)
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}


function showFlashMessage(severity, title, message, duration)
{
    var messageContainer;
    var severities = ['notice', 'information', 'ok', 'warning', 'error'];
    duration = duration || 5000;

    if ($('#msg-div').length == 0) {
            //messageContainer = Ext.DomHelper.insertFirst(document.body, {
        $('#page_wrapper').prepend('<div id="msg-div" style="position:absolute;margin-left:40%;z-index:10000;"></div>');
    }
    var msg = createBox(severities[severity], title, message);
    $('#msg-div').html(msg);
    $('#msg-div').fadeIn(duration);
    
    $('.t3-icon-actions-message-close').click(function (e, t, o) {
        $(this).parent().fadeOut(duration, function() {
            $(this).parent().parent().remove();
        });
    });
    $('#msg-div').fadeOut(duration);
    //$('#msg-div').remove();
}


function createBox(severity, title, message)
{
    return ['<div class="typo3-message message-', severity, '" style="width: 400px">',
        '<div class="t3-icon t3-icon-actions t3-icon-actions-message t3-icon-actions-message-close t3-icon-message-' + severity + '-close"></div>',
        '<div class="header-container">',
        '<div class="message-header">', title, '</div>',
        '</div>',
        '<div class="message-body">', message, '</div>',
        '</div>'].join('');
}


function getContent(url)
{
    $.ajax({
        //erweitere aufzurufenden Link
        url: url,
        dataType: "html",
        //wenn es geklappt hat
        success: function(html) {
            var currentContent = $("#content").html();
            var newContent = $('#content', html).html();
            if(currentContent != newContent) {
                $("#content")
                    .fadeOut()
                    .html(newContent)
                    .fadeIn();                
            }
            var currentMainNav = $("#main_navigation").html();
            var newMainNav = $('#main_navigation', html).html();
            if(currentMainNav != newMainNav) {
                $("#main_navigation")
                    //.css("display", "none")
                    .fadeOut()
                    .html(newMainNav)
                    .fadeIn();
            }
        }
    });
}