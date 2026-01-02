var j = jQuery.noConflict();
j(document).ready(function ($) {
  
    function hidealldivremoveclass() {
        $("div.idTabs a").each(function () {
            var href = $(this).attr('href');
            $(href).css({'display': 'none'});
            $(this).removeClass('selected');
        });
    }
    $("div.idTabs a").each(function () {
        var href = $(this).attr('href');
        $(href).css({'display': 'none'});
        var selected = $(this).attr('class');
        if (selected == 'selected') {
            $($(this).attr('href')).css({'display': 'block'});
           
            
        }
        $(this).click(function (e) {
            e.preventDefault();
            hidealldivremoveclass();
            $(this).addClass('selected');
            $($(this).attr('href')).css({'display': 'block'});
        });
    });
    $("div#idTabs").css({'margin': '0px', 'padding': '13px 0px 9px 0px'});

});
/*
 Tabs Plugin
 Created By Shoaib Rehmat Ali
 Lisence GNU/GPL
 */
