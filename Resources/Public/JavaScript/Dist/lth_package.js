$(document).ready( function () {
    if(document.getElementById('lthPackageContent')) {
        if($('#lthPackageAction').val()==='news'){
            var switchableControllerActions = $('#switchableControllerActions').val();
            var settingsCategories = $('#settingsCategories').val();
            var settingsStartingpoint = $('#settingsStartingpoint').val();
            var settingsTemplateLayout = $('#settingsTemplateLayout').val();
            showNews(switchableControllerActions, settingsCategories, settingsStartingpoint, settingsTemplateLayout);
        }
    }
    
});


function showNews(switchableControllerActions, settingsCategories, settingsStartingpoint, settingsTemplateLayout)
{
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {
          'eID' : 'lth_package',
          'action' : 'news',
          'data' : {
              'switchableControllerActions' : switchableControllerActions,
              'settingsCategories' : settingsCategories,
              'settingsStartingpoint' : settingsStartingpoint,
              'settingsTemplateLayout' : settingsTemplateLayout
          },
          'sid' : Math.random()
        },
        //contentType: "application/json; charset=utf-8",
        dataType: "json",
        beforeSend: function(){
            $('#lthPackageContent').append('<div class="lthPackageLoader"></div>');
        },
        success: function(data) {
            var title, teaser, datetime,replace,re;
            var i = 1;
            var template = $('#newsTemplate').html();
            $('.lthPackageLoader').remove();
            if(data) {
                
                $.each(data.data, function( key, item ) {
                    datetime = item.datetime;
                    title = item.title;
                    teaser='';
                    if(item.teaser) teaser = item.teaser;
                    replace = '###title_'+i+'###';
                    re = new RegExp(replace,"g");
                    template = template.replace('###datetime_'+i+'###', datetime);
                    template = template.replace('###teaser_'+i+'###', teaser);
                    template = template.replace(re, title);
                    i++;
                });
                $('#lthPackageContent').append(template);
            }
            
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}