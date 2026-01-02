function getDataForDepandantField(parentf, childf, type) {
    var section = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
    var sectionid = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : null;

    if (type == 1) {
        if (1 != section) {
            var val = jQuery("select#" + parentf + sectionid).val();
        } else if (1 == section) {
            var val = jQuery("select#" + parentf).val();
        }
    } else if (type == 2) {
		if (1 < section) {
			var val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]\\[" + sectionid + "\\]]:checked").val();
		} else if (1 == section) {
			var val = jQuery("input[name=sec_" + section + "\\[" + parentf + "\\]]:checked").val();
		} else {
			var val = jQuery("input[name=" + parentf + "]:checked").val();
		}		
    }
    var link = "index.php?option=com_jsjobs&c=fieldordering&task=datafordepandantfield";
    jQuery.post(link, {fvalue: val, child: childf, section: section, sectionid: sectionid, type: type}, function (data) {
        if (data) {
            console.log(data);
            var d = jQuery.parseJSON(data);
            if (1 != section) {
                jQuery("select#" + childf + sectionid).replaceWith(d);
            } else {
                jQuery("select#" + childf).replaceWith(d);
            }
        }
    });
}

function deleteCutomUploadedFile(field , isrequired) {
    var message = Joomla.Text._('Are you sure ?');
    var field_1 = field+"_1";
    var result = confirm(message);
    if(result){
        jQuery("input#"+field_1).val(1);
        jQuery("span."+field_1).hide();
        if(isrequired == 1){
            jQuery("input#"+field).addClass('required');
        }        
    }
}
