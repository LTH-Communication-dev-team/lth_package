$(document).ready( function () {
    var action = $('#lthPackageAction').val();
    var display = $('#lthPackageDisplay').val();
    
    if($('#lthPackagePublicationsUuid').val()) {
        showPublication();
    } else if($('#lthPackageStaffUuid').val()) {
        showStaff();
    } else {
        $("#refine").click(function(){
            $("#lthPackageFacetContainer").toggle(500);
            $('.close').toggle();
            $('#lthPackage'+action+'Container').toggleClass('expand');
        });
        if(action==='Publications') {
            if(display==='tagcloud') {
                listTagCloud();
            } else if(display==='studentpapers') {
                $('#lthPackagePublicationsFilter').keyup(function() {
                    listStudentPapers(0, getFacets(), $(this).val().trim(), '');
                });
                listStudentPapers(0, '', '', '');
            } else {
                $('#lthPackagePublicationsFilter').keyup(function() {
                    listPublications(0, getFacets(), $(this).val().trim(), '');
                });
                listPublications(0, '', '', '');
            }
        } else if(action==='Staff') {
            $('#lthPackageStaffFilter').keyup(function() {
                listStaff(0, getFacets(), $(this).val().trim(), '');
            });
            listStaff(0, '', '', '');
        }
        $('.close').toggle();
        $('#lthPackageSearchContainer').click(function(){
            $('#lthPackage'+action+'Filter').focus();
        });
    }
});

/*************************PUBLICATIONS**************************************************/
function listPublications(tableStart, facet, query, more)
{
    var categories = $('#lthPackageCategories').val();
    var display = $('#lthPackageDisplay').val();
    var feGroups = $('#lthPackageFeGroups').val();
    var feUsers = $('#lthPackageFeUsers').val();
    var noItemsToShow = $('#lthPackageNoItemsToShow').val();
    var projectDetailPage = $('#lthPackageProjectDetailPage').val();
    var staffDetailPage = $('#lthPackageStaffDetailPage').val();
    var syslang = $('html').attr('lang');
    var uuid = $('#lthPackagePublicationsUuid').val();
    var keyword = $('#lthPackageKeyword').val();
    var i = 0;
    var maxClass = '';
    var count = '';
    var content = '';
    var pages, title, publicationDate, journalTitle, facetHeader;
    var inputFacet = facet;

    $.ajax({
        type : 'POST',
        url : 'index.php',
        data: {
            eID : 'lth_package',
            action : 'publications',
            data: {
                categories: categories,
                display: display,
                facet: facet,
                feGroups: feGroups,
                feUsers: feUsers,
                keyword: keyword,
                noItemsToShow: noItemsToShow,
                pageId : $('body').attr('id').replace('p',''),
                projectDetailPage: projectDetailPage,
                query: query,
                staffDetailPage: staffDetailPage,
                syslang : syslang,
                tableStart: tableStart,
                uuid: uuid,
            },
            sid : Math.random(),
        },
        dataType: 'json',
        error : function(jq, st, err) {
            alert(st + " : " + err);
        },
        beforeSend: function () {
            if(!more) {
                $('#lthPackagePublicationsContainer div').not('#lthPackagePublicationsHeader').remove().append('<div class="lthPackageLoader"></div>');
            } else {
                $('.lthsolr_more').replaceWith('<div class="lthPackageLoader"></div>');
            }
        },
        success: function(d) {
            $('.lthPackageLoader').remove();
            if(d.data) {
                if(d.facet) {
                    $('#lthPackageFacetContainer').html('');
                    if($('.item-list').length == 0 || 1+1===2) {
                        $.each( d.facet, function( key, value ) {
                            
                            $.each( value, function( key1, value1 ) {
                                if(i > 4) {
                                    maxClass = ' class="maxlist-hidden"';
                                    more = '<p class="maxlist-more"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span><a href="#">' + lth_solr_messages.more + '</a></p>';
                                }

                                facet = value1[0].toString();
                                count = value1[1];
                                facetHeader = value1[2];
                                var facetCheck = '';
                                
                                if(inputFacet) {
                                    if(inArray(key + '###' + facet,JSON.parse(inputFacet))) {
                                        facetCheck = ' checked="checked"';
                                    }
                                }
                                if(parseInt(value1[1]) > 0 && value1[0]) {
                                    content += '<li' + maxClass + ' style="width:100%;">';
                                    content += facet.capitalize().replace(/_/g, ' ') + '&nbsp;[' + count + '] ';
                                    content += '<input type="checkbox" class="lthPackageFacet" name="lthPackageFacet" value="' + key + '###' + facet + '"' + facetCheck + '>';
                                    content += '</li>';
                                }
                                i++;
                            });

                            $('#lthPackageFacetContainer').append('<ul><li style="width:100%;"><b>'+facetHeader+'</b></li>' + content + '</ul>' + more);
                            i=0;
                            maxClass='';
                            more='';
                            content = '';
                        });
                        createFacetClick('listPublications');
                    }
                }

                var publicationDetailPage = 'visa';
                if(syslang=='en') {
                    publicationDetailPage = 'show';
                }
                //console.log($('#solrPublicationTemplate').html());
                $.each( d.data, function( key, aData ) {
                    var template = $('#solrPublicationTemplate').html();
                    pages = '';
                    publicationDate = '';
                    journalTitle = '';
                    var documentTitle = '';
                    if(aData.documentTitle) {
                        documentTitle = aData.documentTitle.charAt(0).toUpperCase() + aData.documentTitle.slice(1).toLowerCase();
                    } else {
                        documentTitle = 'untitled';
                    }
                    
                    var path = '';
                    if(window.location.href.indexOf('(') > 0) {
                        path = window.location.href.split('(').shift().split('/');
                        path.pop();
                        path = path.join('/');
                    } else if(window.location.href.indexOf('?') > 0) {
                        path = window.location.href.split('?').shift().split('/');
                        path.pop();
                        path = path.join('/');
                    } else {
                        path = window.location.href + publicationDetailPage;
                    }

                    documentTitle = '<a href="' + path + '/' + encodeURIComponent(documentTitle.replace(/[^\w\s-]/g,'').replace(/ /g,'-').toLowerCase()) + '('+aData.id+')">' + 
                            documentTitle + '</a>';

                    if(aData.publicationDateYear) publicationDate = aData.publicationDateYear;
                    if(aData.publicationDateMonth) publicationDate += '-'+aData.publicationDateMonth;
                    if(aData.publicationDateDay) publicationDate += '-'+aData.publicationDateDay;
                    if(aData.pages) {
                        if(syslang=='en') {
                            pages = 'p. ' + aData.pages;
                        } else {
                            pages = 's. ' + aData.pages;
                        }
                    }
                    if(aData.journalTitle) {
                        if(syslang=='en') {
                            journalTitle = 'In: ' + aData.journalTitle;
                        } else {
                            journalTitle = 'I: ' + aData.journalTitle;
                        }
                    }
                    if(aData.journalTitle && aData.journalNumber) journalTitle += ' ' + aData.journalNumber;

                    template = template.replace('###id###', aData.id);
                    template = template.replace('###title###', documentTitle);
                    template = template.replace('###authorName###', aData.authorName);
                    template = template.replace('###publicationType###', aData.publicationType);
                    template = template.replace('###publicationDate###', publicationDate);
                    template = template.replace('###pages###', pages);
                    template = template.replace('###journalTitle###', journalTitle);
                    //console.log(template);
                    $('#lthPackagePublicationsContainer').append(template);
                });
                
                $('#lthPackagePublicationsHeader').html('<div style="float:left;">1-' + maxLength(parseInt(tableStart),parseInt(noItemsToShow),parseInt(d.numFound)) + ' ' + lth_solr_messages.of + ' ' + d.numFound + '</div>');
                
                if((parseInt(tableStart) + parseInt(noItemsToShow)) < d.numFound) {
                    var tempMore = '<div style="margin-top:20px;" class="lthsolr_more"><a href="javascript:" onclick="listPublications(' + 
                            (parseInt(tableStart) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.next + ' ' + noItemsToShow + ' ' 
                            + lth_solr_messages.of + ' ' + d.numFound + '</a>';
                    if(d.numFound < 300) {
                        tempMore += ' | <a href="javascript:" onclick="$(\'#lth_solr_no_items\').val(' + d.numFound + '); listPublications(' +
                                (parseInt(tableStart) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.show_all + ' ' + d.numFound + '</a>';
                    }
                    tempMore += '</div>';
                    $('#lthPackagePublicationsContainer').append(tempMore);
                }
                if(!mobileCheck()) {
                    $('#lthPackagePublicationsContainer').parent().height($('#lthPackagePublicationsContainer').height());
                    $('#lthPackageFacetContainer').height($('#lthPackagePublicationsContainer').height());
                    $('#lthPackagePublicationsContainer, #lthPackageFacetContainer').css('float','left');
                }
            }
            
            $("#lthsolr_sort").change(function(){
                listPublications(0,facet,query,'','',$( this ).text());
            });
            
            toggleFacets();
        }
    });
}

function showPublication()
{
    var uuid = $('#lthPackagePublicationsUuid').val();
    var lthPackageStaffDetailPage = $('#lthPackageStaffDetailPage').val();
    var lthPackageProjectDetailPage = $('#lthPackageProjectDetailPage').val();
    var syslang = $('html').attr('lang');
    var lthPackageFeGroups = $('#lthPackageFeGroups').val();
    
    $.ajax({
        type : 'POST',
        url : 'index.php',
        data: {
            eID : 'lth_package',
            action : 'publications',
            data: {
                uuid : uuid,
                feGroups: lthPackageFeGroups,
                syslang : syslang,
            },
            sid : Math.random(),
        },
        //contentType: "application/json; charset=utf-8",
        dataType: 'json',
        beforeSend: function () {
            $('#lthPackagePublicationsContainer').append('<div class="lthPackageLoader"></div>');
        },
        success: function(d) {
            //$('.lthPackageLoader').remove();
            if(d.data) {
                var template = $('#solrTemplate').html();
                
                id = d.data.id;
                title = d.data.title;
                abstract = d.data.abstract;
                authorId = d.data.authorId;
                authorExternal = d.data.authorExternal;
                authorName = d.data.authorName;
                authorOrganisation = d.data.authorOrganisation;
                authorReverseName = d.data.authorReverseName;
                authorReverseNameShort = d.data.authorReverseNameShort;
                authorId = d.data.authorId;
                externalOrganisations = d.data.externalOrganisations;
                feGroups = d.data.feGroups;
                journalNumber = d.data.journalNumber;
                journalTitle = d.data.journalTitle;
                keywords_uka = d.data.keywords_uka;
                keywords_user = d.data.keywords_user;
                language = d.data.language.capitalize();
                numberOfPages = d.data.numberOfPages;
                organisationName = d.data.organisationName;
                organisationId = d.data.organisationId;
                organisationSourceId = d.data.organisationSourceId;
                pages = d.data.pages;
                peerReview = d.data.peerReview;
                publicationType = d.data.publicationType;
                publicationTypeUri = d.data.publicationTypeUri;
                publicationStatus = d.data.publicationStatus;
                publicationDateYear = d.data.publicationDateYear;
                publicationDateMonth = d.data.publicationDateMonth;
                publicationDateDay = d.data.publicationDateDay;
                publisher = d.data.publisher;
                standard_category_en = d.data.standard_category_en;
                volume = d.data.volume;
                doi = d.data.doi;
                issn = d.data.issn;
                isbn = d.data.isbn;
                cite = d.data.cite;
                bibtex = d.data.bibtex;
                
                var organisations = '';
                var path = window.location.href.split('(').shift().split('/');
                path.pop();
                path = path.join('/');

                var authors = '';
                var authorOrganisation;
                if(authorName) {
                    authorNameArray = authorName.split(',');
                    authorIdArray = authorId.split(',');
                    authorExternalArray = authorExternal.split(',');
                    for(var i = 0; i < authorNameArray.length; i++) {
                        if(authors) {
                            authors += ', ';
                        }
                        //check if autor belongs to selected organisation
                        var autorOrganisationCheck = false;
                        for(var ii = 0; ii < feGroups.fe_groups.length; ii++) {
                            if(inArray(feGroups.fe_groups[ii], authorOrganisation[i].split(','))) {
                                autorOrganisationCheck = true;
                            }
                        }
                        //console.log(authorOrganisation);
                        //if(authorIdArray[i] && authorExternalArray[i]==0) {
                        if(autorOrganisationCheck) {
                            authors += '<a href="' + lthPackageStaffDetailPage + 'visa/' + authorNameArray[i].replace(' ','-') + '(' + authorIdArray[i] + ')">' + authorNameArray[i] + '</a>';
                        } else {
                            authors += authorNameArray[i];
                        }
                    }
                }                
                
                if(organisationSourceId) {
                   organisations = '<a href="' + lthPackageProjectDetailPage + 'visa/' + organisationName + '('+ organisationSourceId + ')">' + organisationName + '</a>';
                } else {
                    organisations = organisationName;
                }
                
                if(keywords_user) {
                    for(var i = 0; i < keywords_user.length; i++) {
                        console.log(keywords_user[i]);
                    }
                }
                
                //overview
                template = template.replace(/###title###/g, title);
                template = template.replace('###abstract###', checkData(abstract, lth_solr_messages.abstract));
                template = template.replace(/###authors###/g, checkData(authors, lth_solr_messages.authors));
                template = template.replace('###organisations###', checkData(organisations, lth_solr_messages.organisations));
                template = template.replace('###externalOrganisations###', checkData(externalOrganisations, lth_solr_messages.externalOrganisations));
                template = template.replace('###keywords_uka###', checkData(keywords_uka, lth_solr_messages.keywords_uka));
                template = template.replace('###keywords_user###', checkData(keywords_user, lth_solr_messages.keywords_user));
                template = template.replace('###language###', checkData(language, lth_solr_messages.language));
                template = template.replace('###pages###', checkData(pages, lth_solr_messages.pages));
                template = template.replace('###numberOfPages###', checkData(numberOfPages, lth_solr_messages.numberOfPages));
                template = template.replace('###journalTitle###', checkData(journalTitle, lth_solr_messages.journalTitle));
                template = template.replace('###volume###', checkData(volume, lth_solr_messages.volume));
                template = template.replace('###journalNumber###', checkData(journalNumber, lth_solr_messages.journalNumber));
                template = template.replace('###publicationStatus###', checkData(publicationStatus, lth_solr_messages.publicationStatus));
                template = template.replace('###peerReview###', checkData(peerReview, lth_solr_messages.peerReview, syslang));

                //bibtex and cite
                template = template.replace('###bibtex###', bibtex);
                template = template.replace('###cite###', cite);
                
                $('#page_title h1').text(d.title);
                $('#lthPackagePublicationsContainer').html('').append(template);
                if(abstract==="") {
                    $("#lthsolrAbstract").remove();
                }
            }
        }
    });
}


function listStudentPapers(tableStart, facet, query, more)
{
    var categories = $('#lthPackageCategories').val();
    var display = $('#lthPackageDisplay').val();
    var feGroups = $('#lthPackageFeGroups').val();
    var noItemsToShow = $('#lthPackageNoItemsToShow').val();
    var syslang = $('html').attr('lang');
    var uuid = $('#lthPackagePublicationsUuid').val();
    var paperType = $('#lthPackagePublicationsPaperType').val();
    var i = 0;
    var maxClass = '';
    var count = '';
    var content = '';
    var inputFacet = facet;
    
    var i = 0;
    var maxClass, more, title, facetHeader;
    
    $.ajax({
        type : 'POST',
        url : 'index.php',
        data: {
            eID : 'lth_package',
            action : 'publications',
            data: {
                categories: categories,
                display: display,
                facet: facet,
                feGroups: feGroups,
                noItemsToShow: noItemsToShow,
                pageId : $('body').attr('id').replace('p',''),
                paperType: paperType,
                query: query,
                syslang : syslang,
                tableStart: tableStart,
                uuid: uuid,
            },
            sid : Math.random(),
        },
        dataType: 'json',
        error : function(jq, st, err) {
            alert(jq + ';' + st + " : " + err);
        },
        beforeSend: function () {
            if(!more) {
                $('#lthPackagePublicationsContainer div').not('#lthPackagePublicationsHeader').remove().append('<div class="lthPackageLoader"></div>');
            } else {
                $('.lthsolr_more').replaceWith('<div class="lthPackageLoader"></div>');
            }
        },
        success: function(d) {
            if(d.data) {
                $('.lthPackageLoader').remove();
                if(d.facet) {
                    $('#lthPackageFacetContainer').html('');
                    $.each( d.facet, function( key, value ) {

                        $.each( value, function( key1, value1 ) {
                            if(i > 4) {
                                maxClass = ' class="maxlist-hidden"';
                                more = '<p class="maxlist-more"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span><a href="#">' + 
                                        lth_solr_messages.more + '</a></p>';
                            }

                            facet = value1[0].toString();
                            count = value1[1];
                            facetHeader = value1[2];
                            var facetCheck = '';

                            if(inputFacet) {
                                if(inArray(key + '###' + facet,JSON.parse(inputFacet))) {
                                    facetCheck = ' checked="checked"';
                                }
                            }
                            if(parseInt(value1[1]) > 0 && value1[0]) {
                                content += '<li' + maxClass + ' style="width:100%;">';
                                content += facet.capitalize().replace(/_/g, ' ') + '&nbsp;[' + count + '] ';
                                content += '<input type="checkbox" class="lth_solr_facet" name="lth_solr_facet" value="' + key + '###' + facet + '"' + facetCheck + '>';
                                content += '</li>';
                            }
                            i++;
                        });

                        $('#lthPackageFacetContainer').append('<ul><li style="width:100%;"><b>'+facetHeader+'</b></li>' + content + '</ul>' + more);
                        i=0;
                        maxClass='';
                        more='';
                        content = '';
                    });
                    createFacetClick('listStudentPapers');
                }
                
                var publicationDetailPage = 'visa';
                if(syslang=='en') {
                    publicationDetailPage = 'show';
                }
                var path = window.location.href + publicationDetailPage;

                $.each( d.data, function( key, aData ) {
                    var template = $('#solrPublicationTemplate').html();
                    var documentTitle;
                    
                    if(aData.documentTitle) {
                        //title = '<a href="index.php?id=' + detailPage + '&uuid=' + aData[0] + '&no_cache=1">' + aData[1] + '</a>';
                        documentTitle = aData.documentTitle.charAt(0).toUpperCase() + aData.documentTitle.slice(1).toLowerCase();
                    } else {
                        documentTitle = 'untitled';
                    }
                    
                    documentTitle = '<a href="' + path + '/' + documentTitle.replace(/[^\w\s-]/g,'').replace(/ /g,'-').toLowerCase() + '('+aData.id+')">' + documentTitle + '</a>';

                    template = template.replace('###id###', aData.id);
                    template = template.replace('###documentTitle###', documentTitle);
                    template = template.replace('###authorName###', aData.authorName);
                    template = template.replace(/###publicationDateYear###/g, aData.publicationDateYear);
                    template = template.replace('###organisationName###', aData.organisationName);

                    $('#lthPackagePublicationsContainer').append(template);
                });
                
                $('.lthPackageLoader').remove();

                $('#lthPackagePublicationsHeader').html('<div style="float:left;">1-' + maxLength(parseInt(tableStart),parseInt(noItemsToShow),parseInt(d.numFound)) +
                        ' ' + lth_solr_messages.of + ' ' + d.numFound + '</div>');
                
                if((parseInt(tableStart) + parseInt(noItemsToShow)) < d.numFound) {
                    var tempMore = '<div style="margin-top:20px;" class="lthsolr_more"><a href="javascript:" onclick="listStudentPapers(' + 
                            (parseInt(tableStart) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.next + ' ' + noItemsToShow + ' ' 
                            + lth_solr_messages.of + ' ' + d.numFound + '</a>';
                    if(d.numFound < 300) {
                        tempMore += ' | <a href="javascript:" onclick="$(\'#lth_solr_no_items\').val(' + d.numFound + '); listStudentPapers(' +
                                (parseInt(tableStart) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.show_all + ' ' + d.numFound + '</a>';
                    }
                    tempMore += '</div>';
                    $('#lthPackagePublicationsContainer').append(tempMore);
                }
                if(!mobileCheck()) {
                    $('#lthPackagePublicationsContainer').parent().height($('#lthPackagePublicationsContainer').height());
                    $('#lthPackageFacetContainer').height($('#lthPackagePublicationsContainer').height());
                    $('#lthPackagePublicationsContainer, #lthPackageFacetContainer').css('float','left');
                }
            }
            toggleFacets();
        }
    });
}


function listTagCloud()
{
    var display = $('#lthPackageDisplay').val();
    var feGroups = $('#lthPackageFeGroups').val();
    var feUsers = $('#lthPackageFeUsers').val();
    var syslang = $('html').attr('lang');
    var lthPackagePublicationsListPage = $('#lthPackagePublicationsListPage').val();
    
    /*var publicationDetailPage = 'publikationer';
    if(syslang=='en') {
        publicationDetailPage = 'publications';
    }*/
    var path = lthPackagePublicationsListPage;
    
    $.ajax({
        type : "POST",
        url : 'index.php',
        data: {
            eID : 'lth_package',
            action : 'publications',
            data: {
                display: display,
                feGroups: feGroups,
                feUsers: feUsers,
                pageId : $('body').attr('id').replace('p',''),
                path: path,
                syslang : syslang,
            },
            sid : Math.random(),
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function () {
            $('#lthPackageTagcloudContainer').html('').append('<div class="lthPackageLoader"></div>');
        },
        success: function(d) {
            if(d) {
                $('#lthPackageTagcloudContainer').html('').jQCloud(d.data);
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}


/************************************************STAFF*********************************************************/
function listStaff(tableStart, facet, query, more)
{
    var categories = $('#lthPackageCategories').val();
    var feGroups = $('#lthPackageFeGroups').val();
    var feUsers = $('#lthPackageFeUsers').val();
    var noItemsToShow = $('#lthPackageNoItemsToShow').val();
    var publicationsDetailPage = $('#lthPackagePublicationDetailPage').val();
    var staffDetailPage = $('#lthPackageStaffDetailPage').val();
    var syslang = $('html').attr('lang');
    var uuid = $('#lthPackageStaffUuid').val();
    var curI;
    var inputFacet = facet;

    $.ajax({
        type : 'POST',
        url : 'index.php',
        data: {
            eID : 'lth_package',
            action : 'staff',
            data: {
                categories: categories,
                facet: facet,
                feGroups: feGroups,
                feUsers: feUsers,
                noItemsToShow: noItemsToShow,
                pageId : $('body').attr('id').replace('p',''),
                projectDetailPage: publicationsDetailPage,
                query: query,
                staffDetailPage: staffDetailPage,
                syslang : syslang,
                tableStart: tableStart,
                uuid: uuid,
            },
            sid : Math.random(),
        },
        dataType: 'json',
        error : function(jq, st, err) {
            alert(st + " :ss " + err);
        },
        beforeSend: function () {
            if(!more) {
                $('#lthPackageStaffContainer div').not('#lthPackageStaffHeader').remove().append('<div class="lthPackageLoader"></div>');
            } else {
                $('.lthsolr_more').replaceWith('<div class="lthPackageLoader"></div>');
            }
        },
        success: function(d) {
            var staffDetailPage = 'visa';
            if(syslang=='en') {
                staffDetailPage = 'show';
            }
                
            if(d.data) {
                var i = 0;
                var maxClass = '';
                var more = '';
                var count = '';
                var facet = '';
                var content = '';
                var more = '<p class="maxlist-more"></p>';
                
                if(d.facet) {
                    $('#lthPackageFacetContainer').html('');
                    if($('.item-list').length == 0 || 1+1===2) {
                        $.each( d.facet, function( key, value ) {
                            $.each( value, function( key1, value1 ) {
                                if(i > 4) {
                                    maxClass = ' class="maxlist-hidden"';
                                    more = '<p class="maxlist-more"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span><a href="#">' + lth_solr_messages.more + '</a></p>';
                                }

                                facet = value1[0].toString();
                                count = value1[1];
                                facetHeader = value1[2];
                                var facetCheck = '';
                                
                                if(inputFacet) {
                                    if(inArray(key + '###' + facet,JSON.parse(inputFacet))) {
                                        facetCheck = ' checked="checked"';
                                    }
                                }
                                if(parseInt(value1[1]) > 0 && value1[0]) {
                                    content += '<li' + maxClass + ' style="width:100%;">';
                                    content += facet.capitalize().replace(/_/g, ' ') + '&nbsp;[' + count + '] ';
                                    content += '<input type="checkbox" class="lth_solr_facet" name="lth_solr_facet" value="' + key + '###' + facet + '"' + facetCheck + '>';
                                    content += '</li>';
                                }
                                i++;
                            });

                            $('#lth_solr_facet_container').append('<ul><li style="width:100%;"><b>'+facetHeader+'</b></li>' + content + '</ul>' + more);
                            i=0;
                            maxClass='';
                            more='';
                            content = '';
                        });
                        createFacetClick('listStaff');
                    }
                }
            
                $.each( d.data, function( key, aData ) {
                    var template = $('#solrTemplate').html();

                    var id = aData.id;
                    template = template.replace('###id###', id);

                    var display_name = aData.firstName + ' ' + aData.lastName;
                    var uuid = aData.uuid;
                    if(!uuid) {
                        uuid = aData.guid;
                    }

                    //template = template.replace(/###display_name_t###/g, display_name_t);
                    var homepage = window.location.href + staffDetailPage + '/' + encodeURIComponent(display_name.replace(' ','-')) + '('+uuid+')';
                    if(aData.homePage) {
                        homepage = aData.homePage;
                    }
                    template = template.replace(/###display_name_t###/g, '<a href="'+homepage+'">' + display_name + '</a>');
                    var title = '', organisationName = '', autoHomepage = '', phone = '', roomNumber = '', homePage = '';

                    template = template.replace(/###email_t###/g, aData.email);

                    /*i=0;
                    curI = 0;
                    for (i = 0; i < aData.organisationId.length; i++) {
                        if(scope===aData.organisationId[i]) {
                            curI = i;
                        }
                    }*/
                    if(aData.title) title = aData.title[0];
                    if(aData.organisationName) organisationName = aData.organisationName[0];
                    if(aData.phone) phone = aData.phone[0];
                    /*if(aData[4]) {
                        if(aData[4][curI]) {
                            phone = aData[4][curI];
                        } else {
                            phone = aData[4][0];
                        }

                    }*/
                    if(phone) phone = phone.replace('+4646222', '+46 46 222 ').replace(/(.{2}$)/, ' $1');
                    if(aData.mobile) {
                        if(phone) phone += ', ';
                        phone += '+46 ' + aData.phone[0].replace(/ /g, '').replace('+46','').replace(/(\d{2})(\d{3})(\d{2})(\d{2})/, "$1 $2 $3 $4");;
                    }

                    template = template.replace('###title_t###', title.capitalize());
                    template = template.replace('###phone_t###', phone);

                    template = template.replace('###oname_t###', organisationName);

                    template = template.replace('###primary_affiliation_t###', aData[9]);

                    if(aData.homePage) {
                        homePage = lth_solr_messages.personal_homepage + ': <a data-homepage="' + aData.homePage + '" href="' + aData.homePage + '">' + aData.homePage + '</a>';
                    } else if(aData.autoHomepage) {
                        homePage = lth_solr_messages.personal_homepage + ': <a data-homepage="' + aData.autoHomepage + '" href="' + aData.autoHomepage + '">' + aData.autoHomepage + '</a>';
                    }
                    template = template.replace('###homepage_t###', '<p>' + homePage + '</p>');

                    var image = '';
                    if(aData.image) image = '<div class="align_left" style="height:100px;"><img style="max-height: 100%; max-width: 100%" src="' + aData.image + '" /></div>';
                    template = template.replace('###image_t###', image);
                    
                    template = template.replace('###lth_solr_intro###', aData.intro.replace('\n','<br />'));

                    roomNumber = aData.roomNumber;
                    if(roomNumber) {
                        roomNumber = '(' + lth_solr_messages.room + ' ' + aData.roomNumber + ')';
                    } else {
                        roomNumber = '';
                    }
                    template = template.replace('###room_number_s###', roomNumber);
                    $('#lthPackageStaffContainer').append(template);
                });
                $('.lthPackageLoader').remove();
                
                $('#lthPackageStaffHeader').html('<div style="float:left;">1-' + maxLength(parseInt(tableStart),parseInt(noItemsToShow),parseInt(d.numFound)) + ' ' + lth_solr_messages.of + ' ' + d.numFound + '</div>');
                if($('#lth_solr_lu').val() === "yes") {
                    $('#lthPackageStaffHeader').append('<div style="float:right;"><span class="glyphicon glyphicon-export"></span></div>');
                    $('.glyphicon-export').click(function() {
                        exportStaff('csv');
                    });
                }
                if((parseInt(tableStart) + parseInt(noItemsToShow)) < d.numFound) {
                    var tempMore = '<div style="margin-top:20px;" class="lthsolr_more"><a href="javascript:" onclick="listStaff(' + (parseInt(tableStart) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.next + ' ' + noItemsToShow + ' ' + lth_solr_messages.of + ' ' + d.numFound + '</a>';
                    if(d.numFound < 300) {
                        tempMore += ' | <a href="javascript:" onclick="$(\'#lth_solr_no_items\').val(' + d.numFound + '); listStaff(' + (parseInt(tableStart) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.show_all + ' ' + d.numFound + '</a>';
                    }
                    tempMore += '</div>';
                    $('#lthPackageStaffContainer').append(tempMore);
                }
                
                if(!mobileCheck()) {
                    $('#lthPackageStaffContainer').parent().height($('#lthPackageStaffContainer').height());
                    $('#lthPackageFacetContainer').height($('#lthPackageStaffContainer').height());
                    $('#lthPackageStaffContainer, #lthPackageFacetContainer').css('float','left');
                }
            }
           
            
        }
    });
}


function showStaff()
{
    var uuid = $('#lthPackageStaffUuid').val();
    var lthPackagePublicationDetailPage = $('#lthPackagePublicationDetailPage').val();
    var lthPackageProjectDetailPage = $('#lthPackageProjectDetailPage').val();
    var noItemsToShow = $('#lthPackageNoItemsToShow').val();
    var syslang = $('html').attr('lang');
    var tableStartPublications = 0;
    var tableStartProjects = 0;
    
    $.ajax({
        type : 'POST',
        url : 'index.php',
        data: {
            eID : 'lth_package',
            action : 'staff',
            data: {
                uuid : uuid,
                syslang : syslang,
            },
            sid : Math.random(),
        },
        //contentType: "application/json; charset=utf-8",

        dataType: "json",
        beforeSend: function () {
            /*if(lth_solr_staff_pos=='right') {
                $('.grid-23').after('<div id="content_sidebar_wrapper" class="grid-8 omega"><div id="content_sidebar"><h2>Contact</h2></div>');
                var staffContainer = $('#lthsolr_staff_container');
                $('#content_sidebar h2').after(staffContainer);
            }*/
            $('#lthPackageStaffContainer').append('<div class="lthPackageLoader lthPackageLoaderStaff"></div>');
            $('#lthPackagePublicationsContainer').append('<div class="lthPackageLoader lthPackageLoaderPublications"></div>');
            $('#lthPackageProjectsContainer').append('<div class="lthPackageLoader lthPackageLoaderProjects"></div>');
        },
        success: function(d) {
            //Staff
            if(d.personData) {                                 
                $.each( d.personData, function( key, aData ) {
                    var intro = '';
                    var template = $('#solrStaffTemplate').html();

                    var id = aData.id;
                    template = template.replace('###id###', id);

                    var display_name_t = aData.firstName + ' ' + aData.lastName;
                    
                    template = template.replace('###display_name_t###', display_name_t);
                    var title, organisationName = '', organisationPhone = '', organisationStreet = '', organisationCity = '', 
                    phone = '', roomNumber = '', homePage = '', organisationPostalAddress = '';

                    template = template.replace(/###email_t###/g, aData.email);

                    if(aData.title) title = aData.title[0].capitalize();
                    
                    if(aData.organisationName) organisationName = aData.organisationName[0];
                    if(aData.phone) {
                        phone = aData.phone[0];
                    }
                    if(phone) phone = phone.replace('+4646222', '+46 46 222 ').replace(/(.{2}$)/, ' $1');
                    if(aData.mobile) {
                        if(phone) phone += ', ';
                        phone += aData.mobile[0];
                    }

                    //Change page main header
                    $('#page_title h1').text(display_name_t).append('<h2>'+title+'</h2>');
                    
                    template = template.replace('###title_t###', title);
                    template = template.replace('###phone_t###', phone);

                    template = template.replace('###oname_t###', organisationName);

                    template = template.replace('###primary_affiliation_t###', aData.primaryAffiliation);

                    template = template.replace('###homepage_t###', '');

                    var image = '';
                    if(aData.image) image = '<div class="align_left" style="width:80px;"><img style="max-height: 100%; max-width: 100%" src="' + aData.image + '" /></div>';
                    template = template.replace('###image_t###', image);
                    
                    if(aData.intro) intro = aData.intro.replace('\n','<br />');
                    template = template.replace('###lth_solr_intro###', intro);

                    roomNumber = aData.roomNumber;
                    if(roomNumber) {
                        roomNumber = '(' + lth_solr_messages.room + ' ' + roomNumber + ')';
                    } else {
                        roomNumber = '';
                    }
                    template = template.replace('###room_number_s###', roomNumber);
                    if(aData.organisationPhone) organisationPhone = aData.organisationPhone[0];
                    if(aData.organisationStreet) organisationStreet = aData.organisationStreet[0];
                    if(aData.organisationCity) organisationCity = aData.organisationCity[0];
                    if(aData.organisationPostalAddress) organisationPostalAddress = aData.organisationPostalAddress[0].split('$').join(', ');
                    template = template.replace('###visiting_address###', organisationStreet + ' ' + organisationCity);
                    template = template.replace('###postal_address###', organisationPostalAddress);
                    template = template.replace('###organisation_phone###', organisationPhone);
                    
                    if(aData.profileInformation) {
                        $('#lthPackagePublicationsContainer').prepend('<h3>Forskning</h3>' + aData.profileInformation);
                    }
                    $('#lthPackageStaffContainer').append(template);
                });
            } 
            $('.lthPackageLoaderStaff').remove();

            //Publications
            var pages, publicationDate, journalTitle, title;
            
            if(syslang=='en') {
                lthPackagePublicationDetailPage += 'show';
            } else {
                lthPackagePublicationDetailPage += 'visa';
            }

            if(d.publicationData) {
                $('#lthPackagePublicationsHeader').append('<h3>' + lth_solr_messages.publications + '</h3>');
                $.each( d.publicationData, function( key, aData ) {
                    var template = $('#solrPublicationTemplate').html();
                    var documentTitle = '';
                    pages = '';
                    publicationDate = '';
                    journalTitle = '';
                    if(aData.documentTitle) {
                        documentTitle = aData.documentTitle;
                        if(lthPackagePublicationDetailPage) {
                            documentTitle = '<a href="' + lthPackagePublicationDetailPage + '/' + encodeURIComponent(documentTitle.replace(/ /g,'-')) + '(' + aData.id + ')">' + documentTitle + '</a>';
                        }
                        documentTitle = '<b>'+documentTitle+'</b>';
                    } else {
                        documentTitle = 'untitled';
                    }
                    if(aData.publicationDateYear) publicationDate = aData.publicationDateYear;
                    if(aData.publicationDateMonth) publicationDate += '-'+aData.publicationDateMonth;
                    if(aData.publicationDateDay) publicationDate += '-'+aData.publicationDateDay;
                    if(aData.pages) {
                        if(syslang=='en') {
                            pages = 'p. ' + aData.pages;
                        } else {
                            pages = 's. ' + aData.pages;
                        }
                    }
                    if(aData.journalTitle) {
                        if(syslang=='en') {
                            journalTitle = 'In: ' + aData.journalTitle;
                        } else {
                            journalTitle = 'I: ' + aData.journalTitle;
                        }
                    }
                    if(aData.journalTitle && aData.journalNumber) journalTitle += ' ' + aData.journalNumber;

                    template = template.replace('###id###', aData.id);
                    template = template.replace('###title###', documentTitle);
                    template = template.replace('###authorName###', aData.authorName);
                    template = template.replace('###publicationType###', aData.publicationType);
                    template = template.replace('###publicationDate###', publicationDate);
                    template = template.replace('###pages###', pages);
                    template = template.replace('###journalTitle###', journalTitle);
                    
                    $('#lthPackagePublicationsContainer').append(template);
                });
                
                $('#lthPackageLoaderPublications').remove();

                $('#lthPackagePublicationsHeader').append('1-' + maxLength(parseInt(tableStartPublications),parseInt(noItemsToShow),parseInt(d.publicationNumFound)) 
                    + ' ' + lth_solr_messages.of + ' ' + d.publicationNumFound);
                
                if((parseInt(tableStartPublications) + parseInt(noItemsToShow)) < d.publicationNumFound) {
                    var tempMore = '<div style="margin-top:20px;" class="lthsolr_more"><a href="javascript:" onclick="listPublications(' + 
                        (parseInt(tableStartPublications) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.next + 
                        ' ' + noItemsToShow + ' ' + lth_solr_messages.of + ' ' + d.publicationNumFound + '</a>';
                    if(d.publicationNumFound < 300) {
                        tempMore += ' | <a href="javascript:" onclick="$(\'#lth_solr_no_items\').val(' + d.numFound + '); listPublications(' + 
                        (parseInt(tableStartPublications) + parseInt(noItemsToShow)) + ',\'\',\'\',\'more\');">' + lth_solr_messages.show_all + ' ' + d.publicationNumFound + '</a>';
                    }
                    tempMore += '</div>';
                    $('#lthPackagePublicationsContainer').append(tempMore);
                }
                if(!mobileCheck()) {
                    $('#lthPackagePublicationsContainer').parent().height($('#lthPackagePublicationsContainer').height());
                    $('#lthPackageFacetContainer').height($('#lthPackagePublicationsContainer').height());
                    $('#lthPackagePublicationsContainer, #lthPackageFacetContainer').css('float','left');
                }
                
                /*if((parseInt(tableStartPublications) + parseInt(noItemsToShow)) < d.publicationNumFound) {
                    $('#lthPackagePublicationsContainer').append('<div style="margin-top:20px;" class="lthsolr_more">\
<a href="javascript:" onclick="listPublications(' + (parseInt(tableStartPublications) + parseInt(noItemsToShow)) + ');">' + lth_solr_messages.next + ' ' + noItemsToShow + ' ' + lth_solr_messages.of + ' ' + d.publicationNumFound + '</a> | <a href="javascript:" onclick="$(\'#lth_solr_no_items\').val(' + d.publicationNumFound + '); listPublications(' + (parseInt(tableStartPublications) + parseInt(noItemsToShow)) + ');">' + lth_solr_messages.show_all + ' ' + d.publicationNumFound + '</a></div>');
                }*/
            } 
            $('.lthPackageLoaderPublications').remove();
            
            //Projects
            //console.log(d.projectData.length);
            if(d.projectData) {
                $('#lthPackageProjectsHeader').append('<h3>Projects</h3>');
                $.each( d.projectData, function( key, aData ) {
                    var template = $('#solrProjectTemplate').html();

                    template = template.replace('###id###', aData[0]);
                    template = template.replace('###title###', aData[1]);
                    template = template.replace('###participants###', aData[2]);
                    template = template.replace('###projectStartDate###', aData[3]);
                    template = template.replace('###projectEndDate###', aData[4]);
                    template = template.replace('###projectStatus###', aData[5]);
                    
                    $('#lthPackageProjectsContainer').append(template);
                });
                
                $('#lthPackageProjectsHeader').append('1-' + maxLength(parseInt(tableStartProjects) + parseInt(noItemsToShow),parseInt(d.projectNumFound)) + ' of ' + d.projectNumFound);
                if((parseInt(tableStartProjects) + parseInt(noItemsToShow)) < d.projectNumFound) {
                    $('#lthPackageProjectsContainer').append('<div style="margin-top:20px;" class="lthsolr_more"><a href="javascript:" onclick="listProjects(' + (parseInt(tableStartProjects) + parseInt(noItemsToShow)) + ');">NEXT ' + noItemsToShow + ' of ' + d.projectNumFound + '</a> | <a href="javascript:" onclick="$(\'#lth_solr_no_items\').val(' + d.numFound + '); listProjects(' + (parseInt(tableStartProjects) + parseInt(noItemsToShow)) + ');">Show all ' + d.projectNumFound + '</a></div>');
                }
            }
            $('.lthPackageLoaderProjects').remove();
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}

/******************************************UTILITIES*********************************************/


function createFacetClick(listType)
{
    $('.lthPackageFacet').click(function() {
        if(listType==='listStaff') {
            listStaff(0, getFacets(), $('#lthPackageStaffFilter').val().trim(),false);
        } else if(listType==='listPublications') {
            listPublications(0, getFacets(), $('#lthPackagePublicationsFilter').val().trim(),false);
        } else if(listType==='listStudentPapers') {
            listStudentPapers(0, getFacets(), $('#lthPackagePublicationsFilter').val().trim(),false);
        }
    });
}


function getFacets()
{
    var facet = [];
    $("#lthPackageFacetContainer input[type=checkbox]").each(function() {
        if($(this).prop('checked')) {
            facet.push($(this).val());
        };
    });
    if(facet.length > 0) {
        return JSON.stringify(facet);
    } else {
        return null;
    }
}


function maxLength(tableStart, noItemsToShow, numFound)
{
    //console.log(tableStart + ';' + noItemsToShow + ';' + numFound);
    if(tableStart + noItemsToShow > numFound) {
        return numFound;
    } else {
        return parseInt(tableStart) + parseInt(noItemsToShow);
    }
}


function inArray(needle, haystack)
{
    if(haystack) {
        var length = haystack.length;
        for(var i = 0; i < length; i++) {
            if(haystack[i] == needle) return true;
        }
    }
    return false;
}


function mobileCheck() {
    var check = false;
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        check=true;
    }
    return check;
}


function checkData(data, label, syslang, isLink)
{
    var content = '';
    if(data) {
        if(data=='true' && syslang=='sv') data = 'Ja';
        if(data=='true' && syslang=='en') data = 'Yes'
        if(data=='false' && syslang=='sv') data = 'Nej'
        if(data=='false' && syslang=='en') data = 'No'
        content = '<p>';
        if(label) content += '<b>' + label + '</b><br/>';
        if(isLink) content += '<a href="'+data+'">';
        content += data;
        if(isLink) content += '</a>';
        content += '</p>';
        return content;
    } else {
        return '';
    }
}


function toggleFacets()
{
    $('.maxlist-more a').on( 'click', function () {
        //console.log($(this).parent().prev());
        $(this).parent().prev().find('.maxlist-hidden').toggle('slow');
        if($(this).text() == lth_solr_messages.more) {
            $(this).text(lth_solr_messages.close);
        } else {
            $(this).text(lth_solr_messages.more);
        }
        return false;
    });
}


String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}