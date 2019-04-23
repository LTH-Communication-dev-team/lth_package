if (typeof TYPO3RsaEncryptionPublicKeyUrl === 'undefined') {
    var protocol = location.protocol;
    var slashes = protocol.concat("//");
    var host = slashes.concat(window.location.hostname);
    var TYPO3RsaEncryptionPublicKeyUrl = host + '/index.php?eID=RsaPublicKeyGenerationController';
}

$(document).ready(function() {
    if($('.carousel-controls-play').length > 0) {
        $('.carousel-controls-play').toggle();
        $('.carousel-controls-play').click(function () {
            $('.carousel').carousel('cycle');
            $('.carousel-controls-play').toggle();
            $('.carousel-controls-pause').toggle();
        });
        $('.carousel-controls-pause').click(function () {
            $('.carousel').carousel('pause');
            $('.carousel-controls-play').toggle();
            $('.carousel-controls-pause').toggle();
        });
    }
    if($('.lthPackageContactCard, .lthPackageContactInfobox').length > 0) {
        var email = $('.lthPackageContactCard, .lthPackageContactInfobox').attr('data-email');
        if(email) {
            $.ajax({
                type : "POST",
                url : 'index.php',
                data: {
                    eID: 'lth_package',
                    action: 'getSingleContact',
                    dataSettings: {
                        email: email,
                    },
                    syslang: $('html').attr('lang'),
                    sid: Math.random(),
                },
                //contentType: "application/json; charset=utf-8",
                dataType: "json",
                beforeSend: function () {

                },
                success: function(d) {
                    var i = 0;
                    $.each( d.data, function( key, aData ) {
                        if(aData.email) {
                            if($(".lthPackageContactCard[data-email='"+aData.email+"'] h5, .lthPackageContactInfobox[data-email='"+aData.email+"'] h3").text() === '' && aData.name) {
                                $(".lthPackageContactCard[data-email='"+aData.email+"'] h5, .lthPackageContactInfobox[data-email='"+aData.email+"'] h3").text(aData.name);
                            }
                            //if(aData.title) $(".lthPackageContactCard[data-email='"+aData.email+"'] p").text(aData.title);
                            //$(".lthPackageContactCard[data-email='"+aData.email+"'] .card-text:eq(0) a").text(aData.email).attr('href','mailto:'+aData.email);
                            if($(".lthPackageContactCard[data-email='"+aData.email+"'] img, .lthPackageContactInfobox[data-email='"+aData.email+"'] img").attr('src') === '' && aData.image) {
                                $(".lthPackageContactCard[data-email='"+aData.email+"'] img, .lthPackageContactInfobox[data-email='"+aData.email+"'] img").attr('src', aData.image);
                                if(aData.name) { 
                                    $(".lthPackageContactCard[data-email='"+aData.email+"'] img, .lthPackageContactInfobox[data-email='"+aData.email+"'] img").attr('alt', aData.name);
                                }
                            }
                            if($('#lthPackageContactCardPhone').val()) {
                                var pData = $('#lthPackageContactCardPhone').val();
                                $(".lthPackageContactCard[data-email='"+aData.email+"'] .card-text:eq(1)").append('<a href="tel:'+pData + '">' + pData + '</a>');
                            } else if(aData.phone) {
                                $.each(aData.phone, function( pKey, pData ) {
                                    if(pData && pData!=='NULL') $(".lthPackageContactCard[data-email='"+aData.email+"'] .card-text:eq(1)").append('<a href="tel:'+pData + '">' + pData + '</a>');
                                });
                            }
                            if($('#lthPackageContactCardMobile').val()) {
                                var mData = $('#lthPackageContactCardPhone').val();
                                if($(".lthPackageContactCard[data-email='"+aData.email+"'] .card-text:eq(1)").text() !== '') {
                                    $(".lthPackageContactCard[data-email='"+aData.email+"']  .card-text:eq(1)").append(' och ');
                                }
                                $(".lthPackageContactCard[data-email='"+aData.email+"']  .card-text:eq(1)").append('<a href="tel:'+mData + '">' + mData + '</a>');
                            } else if(aData.mobile) {
                                $.each(aData.mobile, function( mKey, mData ) {
                                    if(mData && mData!=='NULL') {
                                        if($(".lthPackageContactCard[data-email='"+aData.email+"'] .card-text:eq(1)").text() !== '') {
                                            $(".lthPackageContactCard[data-email='"+aData.email+"']  .card-text:eq(1)").append(' och ');
                                        }
                                        $(".lthPackageContactCard[data-email='"+aData.email+"']  .card-text:eq(1)").append('<a href="tel:'+mData + '">' + mData + '</a>');
                                    }
                                });
                            }
                        }     
                        i++;
                    });

                }
            });
        }
    }
    if(!getCookie('cookieConsent')) {
        $('.alert').removeClass('hide').addClass('show');
    }
    $('#cookieConsent').click(function(){
        $('.alert').removeClass('show').addClass('hide');
        setCookie('cookieConsent');
    });
    if($('.full-width-dropdown__login').length > 0 && $('.lthPackageLogin').length > 0) {
        $('.lthPackageLogin').click(function() {
            $('.full-width-dropdown__login').toggle(500);
        });
        $(document).mouseup(function(e) {
            var container = $(".full-width-dropdown__login");

            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && container.has(e.target).length === 0) 
            {
                container.hide();
            }
        });
    }

    if($('#lthPackageCalendarPortal').length > 0) {
        portalCalendar(0,false);
    }
    if($('#lthPackageCalendarCards').length > 0) {
        listCalendar(0,'cards', 6);
    }
    if($('#lthPackageCalendarList').length > 0) {
        listCalendar(0,'list', 10000);
    }
    
    if($('#lthPackageCalendarShow').length > 0) {
        showCalendar();
    }
    
    if($("#header-search-field").length > 0) {
        $('#header-search-field').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        },
        {
            source: function (query, processSync, processAsync) {
              //processSync(['This suggestion appears immediately', 'This one too']);
                return $.ajax({
                    url: "index.php", 
                    type: 'GET',
                    //data: {query: query},
                    data: {
                        query: query,
                        eID : 'lth_solr',
                        action: 'searchShort',
                        sid : Math.random()
                    },
                    dataType: 'json',
                    success: function (data) {
                      // in this example, json is simply an array of strings
                        //return processAsync(json);
                        jsonObj = [];
                        $.each(data, function(key, aData) {
                            //console.log(aData);
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
                            } else if(aData.value) {
                                item = {}
                                item ["id"] = aData.id;
                                item ["label"] = aData.label;
                                item ["value"] = aData.value;
                                item ["type"] = aData.type;
                                jsonObj.push(item);
                            }
                        });
                        //response( jsonObj );
                        //console.log(jsonObj);
                        processAsync(jsonObj);
                    }
                });
            },
            limit: 12,
            display: function (suggestion) {
                return suggestion.label;
            },
            async: true,
        }).on('typeahead:select', function(event, select) {
            window.location.href = $('#header-search-form').find('form').attr('action') + '?term='+select.id;
        })/*.on('typeahead:render', function(event, items) {
            console.log(event);
            var result = items.map(function(a) {return a.label;});
            processAsync(result).bind();
            
        })*/;
    }

    if($('.newsCategoryList').length > 0 && $('.newsCategoryContainer').length > 0) {
        var catList = {};
        $('.newsCategoryList').each(function() {
            var txt = $(this).text();
            if (catList[txt]) {
                $(this).remove();
            } else {
                catList[txt] = true;
                $('.newsCategoryContainer').append('<li class="nav-item">' + $(this).html() + '</li>');
                $(this).remove();
            }
        });
    }
    
    if (typeof b64map === 'undefined') {
        $.ajax({
            url: '/typo3/sysext/rsaauth/Resources/Public/JavaScript/RsaLibrary.js',
            dataType: "script",
            async: false,
            success: function(data){
                
            }
        });
        $.ajax({
            url: '/typo3/sysext/rsaauth/Resources/Public/JavaScript/RsaEncryption.js',
            dataType: "script",
            async: false,
            success: function(data){
                
            }
        });
    }

});

function portalCalendar(setStart, more)
{

    var template = '';

    $.ajax({
        type : "POST",
        url : 'index.php',
        data: {
            eID: 'lth_package',
            action: 'portalCalendar',
            data: {
                setStart: setStart,
                more: more
            },
            syslang: $('html').attr('lang'),
            sid: Math.random(),
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function () {
            if(setStart === 0 && !more) {
                $('#lthPackageCalendarPortalTop > div:eq(0), #lthPackageCalendarPortalTop > div:eq(1), #lthPackageCalendarPortalTop > div:eq(2),#lthPackageCalendarPortalList,#lthPackageCalendarPortalCurrent').html('<div class="lthPackageLoader"></div>');
            } else {
                //console.log(setStart);
                $('#lthPackageCalendarPortalList').html('<div class="lthPackageLoader"></div>');
                $('.pagination li').remove();
            }
        },
        success: function(d) {
            
            $('.lthPackageLoader').remove();
            if(d.dataTop) {
                var i = 0;
                $.each( d.dataTop, function( key, aData ) {
                    template = $('#lthPackageCalendarCardTemplate').html();
                    var title = '';
                    var categoryName = '';
                    var location = '';
                    var id = '';
                    var image = '/typo3conf/ext/lth_package/Resources/Public/Images/seal-300x200@1x.jpg';
                    if(aData.startTime) {
                        var objDate = new Date(aData.startTime);
                        var locale = "sv-se";
                        //var calMonth = objDate.toLocaleString(locale, { month: "short" });
                        var calLongMonth = objDate.toLocaleString(locale, { month: "long" });
                        var calDate = objDate.getDate();
                        var calStartTime = objDate.getUTCHours() + ':' + (objDate.getMinutes()<10?'0':'') + objDate.getMinutes();
                        //var calYear = objDate.getFullYear();
                        template = template.replace('###dateTime###', calDate + ' ' + calLongMonth + ', kl ' + calStartTime);
                    }
                    if(aData.id) id = aData.id;
                    if(aData.title) title = aData.title;
                    if(aData.categoryName) categoryName = aData.categoryName;
                    if(aData.location) location = aData.location;
                    if(aData.image) image = aData.image;
                    template = template.replace('###id###', id);
                    template = template.replace(/###title###/g, title);
                    template = template.replace('###categoryName###', categoryName);
                    template = template.replace('###location###', location);
                    template = template.replace('###image###', image);
                    template = template.replace('###link###', 'event/' + encodeURIComponent(title.replace('/','')) + '(' + id + ')');
                    //console.log(i);
                    $('#lthPackageCalendarPortalTop > div:eq('+i+')').html(template);
                    i++;                    
                });
            }
            if(d.dataList) {
                $.each( d.dataList, function( key, aData ) {
                    template = $('#lthPackageCalendarListTemplate').html();
                    var title = '';
                    var categoryName = '';
                    var location = '';
                    var id = '';
                    var lead = '';
                    var image = '/typo3conf/ext/lth_package/Resources/Public/Images/seal-300x200@1x.jpg';
                    if(aData.startTime) {
                        var objDate = new Date(aData.startTime);
                        var locale = "sv-se";
                        //var calMonth = objDate.toLocaleString(locale, { month: "short" });
                        var calLongMonth = objDate.toLocaleString(locale, { month: "long" });
                        var calDate = objDate.getDate();
                        var calStartTime = objDate.getUTCHours() + ':' + (objDate.getMinutes()<10?'0':'') + objDate.getMinutes();
                        //var calYear = objDate.getFullYear();
                        template = template.replace('###dateTime###', calDate + ' ' + calLongMonth + ', kl ' + calStartTime);
                    }
                    if(aData.id) id = aData.id;
                    if(aData.title) title = aData.title;
                    if(aData.categoryName) categoryName = aData.categoryName;
                    if(aData.lead) lead = aData.lead;
                    if(aData.location) location = aData.location;
                    if(aData.image) image = aData.image;
                    template = template.replace('###id###', id);
                    template = template.replace(/###title###/g, title);
                    template = template.replace('###categoryName###', categoryName);
                    template = template.replace('###lead###', lead);
                    template = template.replace('###location###', location);
                    template = template.replace('###image###', image);
                    template = template.replace('###link###', 'event/' + encodeURIComponent(title) + '(' + id + ')');
                    //console.log(i);
                    $('#lthPackageCalendarPortalList').append(template);
                });
            }
            if(d.dataCurrent) {
                var i = 0;
                $.each( d.dataCurrent, function( key, aData ) {
                    template = $('#lthPackageCalendarCardTemplate').html();
                    var title = '';
                    var categoryName = '';
                    var location = '';
                    var id = '';
                    var image = '/typo3conf/ext/lth_package/Resources/Public/Images/seal-300x200@1x.jpg';
                    if(aData.startTime) {
                        var objDate = new Date(aData.startTime);
                        var locale = "sv-se";
                        //var calMonth = objDate.toLocaleString(locale, { month: "short" });
                        var calLongMonth = objDate.toLocaleString(locale, { month: "long" });
                        var calDate = objDate.getDate();
                        var calStartTime = objDate.getUTCHours() + ':' + (objDate.getMinutes()<10?'0':'') + objDate.getMinutes();
                        //var calYear = objDate.getFullYear();
                        template = template.replace('###dateTime###', calDate + ' ' + calLongMonth + ', kl ' + calStartTime);
                    }
                    if(aData.id) id = aData.id;
                    if(aData.title) title = aData.title;
                    if(aData.categoryName) categoryName = aData.categoryName;
                    if(aData.location) location = aData.location;
                    if(aData.image) image = aData.image;
                    template = template.replace('###id###', id);
                    template = template.replace(/###title###/g, title);
                    template = template.replace('###categoryName###', categoryName);
                    template = template.replace('###location###', location);
                    template = template.replace('###image###', image);
                    template = template.replace('###link###', 'event/' + encodeURIComponent(title) + '(' + id + ')');
                    //console.log(i);
                    $('#lthPackageCalendarPortalCurrent').prepend(template);
                    i++;                    
                });
            }
            if(d.numFound) {
                var pageRows = 8;
                var totalPages = Math.ceil(d.numFound / pageRows);
                var activeClass = '';
                var addOn = setStart / 8;
                var ii = 1;
                
                $('.pagination').append('<li class="page-item"><a class="page-link" href="javascript:" onclick="portalCalendar(0,true);return false;"><span class="page-icon"><i class="fal fa-chevron-left fa-sm"></i></span><span class="page-label"> Föregående</span></a></li>');
                for (var i = setStart; i <= (setStart+3); i++) {
                        if(setStart === i) {
                            activeClass = ' active';
                        } else {
                            activeClass = '';
                        }
                        $('.pagination').append('<li class="page-item'+activeClass+'"><a class="page-link" href="javascript:" onclick="portalCalendar('+i+',true);return false;">'+(i+1)+'</a></li>');
                }
                $('.pagination').append('<li class="page-item"><a class="page-link" href="javascript:" onclick="portalCalendar('+totalPages+',true);return false;"><span class="page-label">Nästa </span><span class="page-icon"><i class="fal fa-chevron-right fa-sm"></i></span></a></li>');
                if(setStart === 0) {
                    $('.pagination li:first').addClass('disabled');
                }
                if(setStart === totalPages) {
                    $('.pagination li:last').addClass('disabled');
                }
                /*if(totalPages > 4) {
                    noLoops = 3;
                } else {
                    noLoops = totalPages;
                }
                for (var i = (1+addOn); i <= (noLoops+addOn); i++) {
                    if((setStart+1) === ii) {
                        activeClass = ' active';
                    } else {
                        activeClass = '';
                    }
                    $('.pagination li:nth-child('+ii+')').after('<li class="page-item'+activeClass+'"><a class="page-link" href="javascript:" onclick="portalCalendar('+(setStart+(8*i))+');return false;">'+i+'</a></li>');
                    ii++;
                }
                if(totalPages > 4) {
                    
                    $('.pagination li:nth-child('+ii+')').after('<li class="page-item"><span class="page-text"><i class="fal fa-ellipsis-h"></i></span></li>');
                    i++;
                    $('.pagination li:nth-child('+ii+')').after('<li class="page-item"><a class="page-link" href="#">'+totalPages+'</a></li>');
                    $('.pagination li:last').click(function(){portalCalendar((setStart+8));return false;});
                }
                if(setStart > 0) {
                    $('.pagination li:first').click(function(){portalCalendar((0));return false;});
                }*/
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}


function listCalendar(setStart, type, numRows)
{
    var template = '';
    var sysLang = $('html').attr('lang');
    var calId = $('#lthPackageCalId').val();
    var catId = '';
    
    var startTime = '';
    var endTime = '*';
    
    if($('.culdesac').length === 0) {
        window.location.href;
        catId = catId.substring(0, catId.length-1).split('/').pop();
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();
        var hh = today.getHours();
        var ss = today.getSeconds();

        //$('#datetimepicker1').attr('data-val', yyyy+'-'+mm+'-'+dd + ' ' + hh + ':' + ss);
        //$('#datetimepicker2').attr('data-val', yyyy+'-'+mm+'-'+dd + ' ' + hh + ':' + ss);
        $('#datetimepicker1').val(yyyy+'-'+mm+'-'+dd);
        $('#datetimepicker2').val(yyyy+'-'+mm+'-'+dd);
        $('#datetimepicker1, #datetimepicker2').datetimepicker();
        
        $('#lthPackageCategorySearchButton').click(function() {
            listCalendar(setStart, type, numRows);
        }).addClass('culdesac');
        startTime = yyyy+'-'+mm+'-'+dd+'T'+hh+':'+ss+':00Z';
    } else {
        startTime = $('#datetimepicker1').val();
        if(startTime.indexOf(':') > 0) {
            startTime = startTime.replace(/\//g, '-').replace(' ', 'T') + ':00Z';
        } else {
            startTime =$('#datetimepicker1').val() +'T00:00:00Z';
        }
        endTime = $('#datetimepicker2').val();
        if(endTime.indexOf(':') > 0) {
            endTime = endTime.replace(/\//g, '-').replace(' ', 'T') + ':00Z';
        } else {
            endTime =$('#datetimepicker2').val() +'T00:00:00Z';
        }
        catId = $('#lthPackageCalendarSelectCategories').val();
        if(endTime < startTime) {
            alert('Sluttiden får inte vara större än starttiden.');
            return false;
        }
    }

    $.ajax({
        type : "POST",
        url : 'index.php',
        data: {
            eID: 'lth_package',
            action: 'listCalendar',
            data: {
                setStart: setStart,
                calId: calId,
                catId: catId,
                startTime: startTime,
                endTime: endTime,
                numRows: numRows,
                query: $('#lthPackageCategorySearchField').val(),
            },
            syslang: sysLang,
            sid: Math.random(),
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function () {
            if(type==='cards') {
                $('#lthPackageCalendarCards').empty().append(getSpinner(sysLang));
            } else if(type==='list') {
                $('#lthPackageCalendarList').empty().append(getSpinner(sysLang));
            }
        },
        success: function(d) {
            //console.log(d.data);
            if(d.facet && setStart >= 0) {
                if($('#lthPackageCalendarCategories').length === 1 && $('#lthPackageCalendarCategories option').length === 1) {
                    $.each( d.facet, function( key, value ) {
                        $.each( value, function( key1, value1 ) {
                            $('#lthPackageCalendarCategories').append('<a href="/events/' + encodeURIComponent(value1[0]) + '/" class="btn btn-primary mx-1 mb-3">' + value1[0].toString() + ' (' + value1[1] + ')</a>');
                        });
                    });
                }

                if($('#lthPackageCalendarSelectCategories').length === 1 && $('#lthPackageCalendarSelectCategories option').length === 1) {
                    $.each( d.facet, function( key, value ) {
                        $.each( value, function( key1, value1 ) {
                            $('#lthPackageCalendarSelectCategories').append('<option value="' + encodeURIComponent(value1[0]) + '">' + value1[0] + '</option>');
                        });
                    });
                }
            }

            if(d.data) {
                $('.spinner').remove();
                $.each( d.data, function( key, aData ) {
                    if(type==='cards') {
                        template = $('#lthPackageCalendarCardsTemplate').html();
                    } else if(type==='list') {
                        template = $('#lthPackageCalendarListTemplate').html();
                    }
                    var title = '';
                    var categoryName = '';
                    var pathalias = '';
                    var id = '';
                    var link = '';
                    if(aData.startTime) {
                        var objDate = new Date(aData.startTime);
                        var locale = "sv-se";
                        var calMonth = objDate.toLocaleString(locale, { month: "short" });
                        var calLongMonth = objDate.toLocaleString(locale, { month: "long" });
                        var calDate = objDate.getDate();
                        var calStartTime = objDate.getUTCHours() + ':' + (objDate.getMinutes()<10?'0':'') + objDate.getMinutes();
                        var calYear = objDate.getFullYear();
                        template = template.replace('###date###', calDate);
                        template = template.replace('###month###', calMonth);
                    }
                    if(aData.endTime) {
                        objDate = new Date(aData.endTime);
                        var calEndTime = objDate.getUTCHours() + ':' + (objDate.getMinutes()<10?'0':'') + objDate.getMinutes();
                    }
                    //console.log(calDate + ' ' + calLongMonth + ' ' + calYear + ' kl. ' + calStartTime + ' ' + calEndTime);
                    if(calDate && calStartTime && calEndTime && calYear && calLongMonth) {
                        template = template.replace(/###dateTime###/g, calDate + ' ' + calLongMonth + ' ' + calYear + ' kl. ' + calStartTime + ' ' + calEndTime);
                    }
                    //26 april 2018 kl. 13:15–17:00
                    if(aData.id) id = aData.id;

                    if(aData.title) title = aData.title.toString();
                    if(aData.categoryName) categoryName = aData.categoryName;
                    if(aData.pathalias) pathalias = aData.pathalias;
                    template = template.replace('###id###', id);
                    template = template.replace(/###title###/g, title);
                    template = template.replace('###categoryName###', categoryName);
                    template = template.replace('###link###', '/event/' + encodeURIComponent(title) + '(' + id + ')/');
                    //console.log(template);
                    if(type==='cards') {
                        $('#lthPackageCalendarCards').append(template);
                    } else if(type==='list') {
                        $('#lthPackageCalendarList').append(template);
                    }

                    /*$('#' + id).click(function(){
                        showCalendar(id)
                    });*/
                });
                /*$('#lthPackageCalendarCards .col .lined-2col, #lthPackageCalendarList').append(setStart - 6);*/
                /*$('#prev-btn').off('click');
                $('#next-btn').off('click');
                $('#prev-btn').on('click', function(e){
                    e.preventDefault();
                    listCalendar(setStart - 6);
                });
                $('#next-btn').on('click', function(e){
                    e.preventDefault();
                    listCalendar(setStart + 6);
                });*/
            } 
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });

}


function showCalendar()
{
    //alert(window.location.href);
    //var eventId = $('#lthPackageEventId').val();
    var eventId = window.location.href.split('(').pop().split(')').shift();
    var calId = $('#lthPackageCalId').val();
    var sysLang = $('html').attr('lang');
    
    if(eventId) {
        $.ajax({
            type : "POST",
            url : 'index.php',
            data: {
                eID: 'lth_package',
                action: 'showCalendar',
                data: {
                    calId: calId,
                    eventId: eventId
                },
                syslang: sysLang,
                sid: Math.random(),
            },
            //contentType: "application/json; charset=utf-8",
            dataType: "json",
            beforeSend: function () {
                $('#lthPackageCalendarShow article').append(getSpinner(sysLang));
            },
            success: function(d) {
                $('.spinner').remove();
                
                $('#lthPackageCalendarShow article').empty();
                
                if(d.data) {
                    if(d.data.startTime) {
                        var objDate = new Date(d.data.startTime);
                        var locale = "sv-se";
                        var calMonth = objDate.toLocaleString(locale, { month: "short" });
                        var calLongMonth = objDate.toLocaleString(locale, { month: "long" });
                        var calDate = objDate.getDate();
                        var calStartTime = objDate.getUTCHours() + ':' + (objDate.getMinutes()<10?'0':'') + objDate.getMinutes();
                        var calYear = objDate.getFullYear();
                        $('#lthPackageCalendarShow .calendar-date-box h1').text(calDate);
                        $('#lthPackageCalendarShow .calendar-date-box p').text(calLongMonth);
                        $('.meta-date').text(calDate + ' ' + calLongMonth + ', kl ' + calStartTime);
                        
                    }
                    if(d.data.endTime) {
                        objDate = new Date(d.data.endTime);
                        var calEndTime = objDate.getUTCHours() + ':' + (objDate.getMinutes()<10?'0':'') + objDate.getMinutes();
                    }
                    
                    if(d.data.categoryName) {
                        $('.meta-category').text(d.data.categoryName);
                    }
                    
                    if(d.data.image) {
                        $('#lthPackageCalendarShow .event-title').after('<figure class="figure"><img src="'+d.data.image + '" alt="' + d.data.title + '" class=" figure-img img-fluid m-0"></figure>');                    }
                    if(d.data.imageCaption) {
                        $('#lthPackageCalendarShow figure').append('<figcaption class="figure-caption bg-dark text-white p-2">' + d.data.imageCaption + '</figcaption>');
                    }

                    if(d.data.title) {
                        $('#lthPackageCalendarShow .event-title__heading h1').text(d.data.title);
                        //$('#page_title h1, article h1').text(d.data.title).css('max-width','650px').css('margin-bottom','18px');
                    }
                    if(d.data.lead) {
                        //$('#lthPackageCalendarShow article').append('<p>' + d.data.lead + '</p>');
                        $('#lthPackageCalendarShow .event-text').append('<p>' + d.data.lead + '</p>');
                    }
                    if(d.data.abstract) {
                        //$('#lthPackageCalendarShow article').append('<p>' + d.data.abstract + '</p>');
                        $('#lthPackageCalendarShow .event-text').append('<p>' + d.data.abstract + '</p>');
                    }
                    //$('#lthPackageCalendarShow article').append('<p><b>Plats: </b>' + d.data.location + '</p>');
                    /*if(d.data.location) {
                        $('#lthPackageCalendarShow article').append('<p><b>Plats: </b>' + calYear + calMonth + calDate + calStartTime + '</p>');
                    }*/
                    if(d.data.prevId) {
                        $('#lthPackagePrevBtn').attr('href','/event/' + d.data.prevTitle + '(' + d.data.prevId + ')');
                    } else {
                        $('#lthPackagePrevBtn').hide();
                    }
                    if(d.data.nextId) {
                        $('#lthPackageNextBtn').attr('href','/event/' + d.data.nextTitle + '(' + d.data.nextId + ')');
                    } else {
                        $('#lthPackageNextBtn').hide();
                    }
                    //Right column
                    $('.col-lg-3').html('<div class="infobox bg-sky-50"><p><strong>Om händelsen</strong><br />' + calDate + ' ' + calLongMonth + ' kl ' + calStartTime + ' till' + calEndTime + '</p>' +
                            '<p><strong>Plats</strong><br />' + d.data.location + '</p></div>');
                } 
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }
}


function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}


function getSpinner(sysLang)
{
    var loadText;
    if(sysLang==='sv') {
        loadText = 'Laddar...';
    } else {
        loadText = 'Loading...';
    }
    
    var content = '<div class="spinner text-center">';
    content += '<p class="text-primary"><i class="fal fa-circle-notch fa-3x fa-spin"></i></p>';
    content += '<p class="font-weight-bold">' + loadText + '</p>';
    content += '</div>';
    
    return content;
}

/*
 * 
 <div class="masonry-container masonry-container-2by1">
	<div class="masonry masonry-wide masonry-cols">
		<div class="masonry-col masonry-col-50">
			<div class="masonry-row masonry-row-100">
				<div class="masonry-tile">
					<a href="#" class="nav-block bg-copper-50">
						<div class="p-3 p-xl-5">
							<h1><span class="a nav-block-link">Kompletterande utbildning för dig med utländsk examen&nbsp;<span class="ml-1 text-nowrap"><i class="fal fa-chevron-circle-right fa-sm"></i></span></span>
							</h1>
							<p>För dig som har en utländsk examen inom företagsekonomi eller systemvetenskap och som vill komma in på den svenska arbetsmarknaden.</p>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="masonry-col masonry-col-50">
			<div class="masonry-row masonry-row-50">
				<div class="masonry-cols">
					<div class="masonry-col masonry-col-50">
						<div class="masonry-tile masonry-tile-sm">
							<a href="#" class="nav-block masonry-tile-img">
								<div class="masonry-tile-img-bg">
									<img src="/assets/toolkit/images/placeholder_1.jpg" alt="" class="">
								</div>
								<div class="masonry-tile-img-content">
									<div class="blockline">
										<h1><span class="a nav-block-link">Ekonomihögskolans finansprogram bland de 50 bästa i världen&nbsp;<span class="ml-1 text-nowrap"><i class="fal fa-chevron-circle-right fa-sm"></i></span></span>
										</h1>
									</div>
								</div>
							</a>
						</div>
					</div>
					<div class="masonry-col masonry-col-50">
						<div class="masonry-tile masonry-tile-sm">
							<a href="#" class="nav-block masonry-tile-img">
								<div class="masonry-tile-img-bg">
									<img src="/assets/toolkit/images/placeholder_2.jpg" alt="" class="">
								</div>
								<div class="masonry-tile-img-content">
									<div class="blockline">
										<h1><span class="a nav-block-link">Nyhet! BSc International Business startar i höst&nbsp;<span class="ml-1 text-nowrap"><i class="fal fa-chevron-circle-right fa-sm"></i></span></span>
										</h1>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="masonry-row masonry-row-50">
				<div class="masonry-tile">
					<a href="#" class="nav-block bg-flower">
						<div class="p-3 p-lg-5 p-xl-7">
							<h1><span class="a nav-block-link">Öppettider i sommar&nbsp;<span class="ml-1 text-nowrap"><i class="fal fa-chevron-circle-right fa-sm"></i></span></span>
							</h1>
							<p>Under sommaren har Ekonomihögskolans reception och bibliotek begränsade öppettider. Även huvudentrén har andra öppettider sommartid.</p>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
 */