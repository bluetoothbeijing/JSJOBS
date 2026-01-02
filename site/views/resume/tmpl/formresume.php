<?php

/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     https://www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Nov 22, 2010
 ^
 + Project:     JS Jobs
 ^ 
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$document = Factory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/css/combobox/chosen.css');
if ($this->isadmin==0) { // no need to add these files in administrator case
    //HTMLHelper::_('behavior.calendar'); 
    HTMLHelper::_('behavior.formvalidator');
}
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');
$document->addScript('components/com_jsjobs/js/multi-files-selector.js');
HTMLHelper::_('jquery.framework');

Text::script('Select Files');
global $mainframe;
 ?>
<div id="js_jobs_main_wrapper">
<?php
if ($this->isadmin == 0) { // no need to add these files in administrator case ?>
        <div id="js_menu_wrapper">
            <?php
            if (isset($this->jobseekerlinks)) {
                if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
                    foreach ($this->jobseekerlinks as $lnk) {
                        ?>
                        <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                        <?php
                    }
                }
            }
            if (isset($this->employerlinks)) {
                if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
                    foreach ($this->employerlinks as $lnk) {
                        ?>
                        <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'coverletter') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                        <?php
                    }
                }
            }
            ?>
        </div>
<?php } ?>

<?php
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
    <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            var resume_image_url = jQuery('#resume_img').attr('src');
            // resumefilepopup
            var resumeid = jQuery('#resume_temp').val();
            getResumeFiles(resumeid, "existingFiles", "form");
            jQuery("div#black_wrapper_resumefiles").click(function () {
                jQuery("div#resumeFilesPopup").css("visibility", "hidden");
                jQuery("#black_wrapper_resumefiles").fadeOut();
            });
            jQuery("#closepopup").click(function () {
                jQuery("div#resumeFilesPopup").css("visibility", "hidden");
                jQuery("#black_wrapper_resumefiles").fadeOut();
            });

            // Tokeninput
            jQuery("input.jstokeninputcity").each(function(){
                var jsparent = jQuery(this).parent();
                var cityid = jQuery(jsparent).find('input.jscityid').val();
                var cityname = jQuery(jsparent).find('input.jscityname').val();
                var datafor = jQuery(this).attr('data-for');
                datafor = datafor.split('_');
                getTokenInput(datafor, cityid, cityname);
            });


            jQuery('#photo').change(function(){
                resumePhotoSelection(this , resume_image_url );
            });
            
            jQuery('input.cf_uploadfile').change(function(){
                resumeCustomUploadFileSelection(this);
            });

            executeIEspecificScript();
        });

        function fj_getsubcategories(src, val) {
            jQuery("#" + src).html("");
            jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=listsubcategoriesforresume", {categoryid: val}, function (data) {
                if (data) {
                    jQuery("#" + src).html(data);
                    jQuery("#" + src + " select.jsjobs-cbo").chosen();
                } else {
                    alert("<?php echo Text::_('Error while getting subcategories'); ?>");
                }
            });
        }

        function jsReadImageURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    jQuery('#resume_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function resumePhotoSelection(input , resume_image_url) {
            var photoValidated = 1;
            var photo = input.files[0];

            var maxPhotoSize = <?php echo $this->config['resume_photofilesize']; ?>;
            var photoTypes = "<?php echo strtolower($this->config['image_file_type']); ?>";
            var photoTypesArray = photoTypes.split(",");

            var photoExt = photo.name.split(".").pop();
            var photoSize = (photo.size / 1024);

            if (jQuery.inArray(photoExt, photoTypesArray) < 0) {
                alert("<?php echo Text::_('File extension mismatched'); ?>");
                jQuery("#photo").val("");
                jQuery('#resume_img').attr('src', resume_image_url);
                photoValidated = 0;
            }
            if (photoSize > maxPhotoSize) {
                alert("<?php echo Text::_('File size exceeded'); ?>");
                jQuery("#photo").val("");
                jQuery('#resume_img').attr('src', resume_image_url);
                photoValidated = 0;
            }
            if(photoValidated == 1){
                jsReadImageURL(input);
            }
        }
        
        function resumeCustomUploadFileSelection(input ) {
            var photoValidated = 1;
            var photo = input.files[0];

            var filesize = <?php echo $this->config['document_file_size']; ?>;

            var fileTypes_image = "<?php echo strtolower($this->config['image_file_type']); ?>";
            var fileTypes_file = "<?php echo strtolower($this->config['document_file_type']); ?>";
            var fileTypes = fileTypes_image + ','+fileTypes_file;
            var fileTypesArray = fileTypes.split(",");

            var photoExt = photo.name.split(".").pop();
            var photoSize = (photo.size / 1024);

            if (jQuery.inArray(photoExt, fileTypesArray) < 0) {
                alert("<?php echo Text::_('File extension mismatched'); ?>");
                jQuery(input).val("");
            }
            if (photoSize > filesize) {
                alert("<?php echo Text::_('File size exceeded'); ?>");
                jQuery(input).val("");
            }
        }

        function resumeFilesSelection() {
            jQuery("#black_wrapper_resumefiles").fadeIn(300, function () {
                jQuery("div#resumeFilesPopup").css("visibility", "visible");
                var resumeid = jQuery("#resume_temp").val();
                if (resumeid != -1) {
                    jQuery("#chosenFiles").remove();
                    jQuery("#filesInfo").prepend('<div id="chosenFiles" class="chosenFiles js-row no-margin"></div>');
                    getResumeFiles(resumeid, "chosenFiles", "popup");
                }
                var inputs = jQuery("#fileSelectionButton").children("input").length;
                if (inputs == 0) {
                    var postMaxSize = <?php echo (int) ini_get('post_max_size'); ?>;
                    var memoryLimit = <?php echo (int) ini_get('memory_limit'); ?>;
                    var maxResumeFiles = '<?php echo $this->config["document_max_files"]; ?>';
                    var maxDocumentSize = '<?php echo $this->config["document_file_size"]; ?>';
                    var fileTypes = '<?php echo strtolower($this->config["document_file_type"]); ?>';
                    var clearFilesLang = "<?php echo Text::_('Clear files'); ?>";
                    var juriPath = "<?php echo Uri::root(); ?>";
                    var fileRejLang = "<?php echo Text::_('This file will be rejected'); ?>";
                    var errorLang = "<?php echo Text::_('Error'); ?>";
                    var extMissLang = "<?php echo Text::_('File extension mismatched'); ?>";
                    var sizeExceedLang = "<?php echo Text::_('Maximum size limit exceeded'); ?>";
                    var andSizeExceedLang = "<?php echo Text::_('And file size exceeded'); ?>";
                    var filesLimitExceedLang = "<?php echo Text::_('Maximum resume files limit occurred'); ?>";
                    var noFileLang = "<?php echo Text::_('No File Selected'); ?>";
                    addNewResumeInput(postMaxSize, memoryLimit, maxResumeFiles, maxDocumentSize, fileTypes, fileRejLang, clearFilesLang, errorLang, extMissLang, sizeExceedLang, andSizeExceedLang, filesLimitExceedLang, noFileLang, juriPath);
                }
            });
        }
        function getResumeFiles(resumeid, src, filesfor) {
            jQuery.post('<?php echo Uri::root() . "index.php?option=com_jsjobs&c=resume&task=getresumefiles"; ?>', {resumeid: resumeid, filesfor: filesfor}, function (data) {
                if (data) {
                    if (jQuery.trim(data).length == 0) {
                        if (resumeid == -1) {
                            jQuery('#' + src).remove();
                        } else {
                            jQuery('#' + src).append("<?php echo Text::_('No uploaded file found'); ?>");
                        }
                    } else {
                        jQuery("#" + src).append(data);
                    }
                } else {
                    //alert("<?php echo Text::_('An error occurred while getting resume uploaded resume files'); ?>");
                }
            });
        }
        function deleteResumeFile(fileid, resumeid) {
            var confirmDelete = confirm("<?php echo Text::_('Confirm to delete resume file?'); ?>");
            if (confirmDelete == false) {
                return false;
            }
            jQuery("#ajax-loader").show();
            jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=deleteresumefiles', {fileid: fileid, resumeid: resumeid}, function (data) {
                if (data) {
                    jQuery("#existingFiles span#" + fileid).remove();
                    jQuery("#chosenFiles div#" + fileid).remove();
                } else {
                    alert("<?php echo Text::_("An error occurred while deleting the resume file"); ?>");
                }
            });
            jQuery("#ajax-loader").hide();
        }
        function getTokenInput(datafor, cityid, cityname) {
            var inputfor = datafor[0];
            var sectionid = datafor[1];
            var city = jQuery("#" + inputfor + "cityforedit_"+sectionid).val();
            if (city != "") {
                jQuery("#" + inputfor + "_city_"+sectionid).tokenInput('<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>', {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo Text::_('Type In A Search'); ?>",
                    noResultsText: "<?php echo Text::_('No Results'); ?>",
                    searchingText: "<?php echo Text::_('Searching...'); ?>",
                    tokenLimit: 1,
                    prePopulate: [{id: cityid, name: cityname}],
                    <?php if($this->config['newtyped_cities'] == 1){ ?>
                    onResult: function (item) {
                        if (jQuery.isEmptyObject(item)) {
                            return [{id: 0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id: 0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function (item) {
                        if (item.id > 0) {
                            return;
                        }
                        if (item.name.search(",") == -1) {
                            var input = jQuery("tester").text();
                            msg = "<?php echo Text::_('The location format is not correct please enter the city in this  format').' <br/>'.Text::_('City Name').', '.Text::_('Country Name').' <br/>'.Text::_('or').' <br/>'.Text::_('City Name').', '.Text::_('State Name').', '.Text::_('Country Name'); ?>";
                            jQuery("#" + inputfor + "_city_"+sectionid).tokenInput("remove", item);
                            jQuery("div#warn-message").find("span.text").html(msg).show();
                            jQuery("div#warn-message").show();
                            jQuery("div#black_wrapper_jobapply").show();
                            return false;
                        } else {
                            var location_data =  jQuery("tester").text();
                            var geocoder =  new google.maps.Geocoder();
                            geocoder.geocode( { 'address': location_data}, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    var n_latitude = results[0].geometry.location.lat();
                                    var n_longitude = results[0].geometry.location.lng();
                                    jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&task=cities.savecity&<?php echo Factory::getSession()->getFormToken(); ?>=1", {citydata: location_data,latitude: n_latitude,longitude: n_longitude}, function (data) {
                                        if (data) {
                                            try {
                                                var value = jQuery.parseJSON(data);
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude,longitude:value.longitude});
                                            } catch (e) { // string is not the json its the message come from server
                                                msg = data;
                                                jQuery("div#warn-message").find("span.text").html(msg).show();
                                                jQuery("div#warn-message").show();
                                                jQuery("div#black_wrapper_jobapply").show();
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                            }
                                        }
                                    });
                                } else {
                                    alert("Something got wrong"+status);
                                }
                            });
                        }
                    }
                    <?php } ?>
                });
            } else {
                jQuery("#" + inputfor + "_city_"+sectionid).tokenInput('<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>', {
                    theme: "jsjobs",
                    preventDuplicates: true,
                    hintText: "<?php echo Text::_('Type In A Search'); ?>",
                    noResultsText: "<?php echo Text::_('No Results'); ?>",
                    searchingText: "<?php Text::_('Searching...'); ?>",
                    tokenLimit: 1,
                    <?php if($this->config['newtyped_cities'] == 1){ ?>
                    onResult: function (item) {
                        if (jQuery.isEmptyObject(item)) {
                            return [{id: 0, name: jQuery("tester").text()}];
                        } else {
                            //add the item at the top of the dropdown
                            item.unshift({id: 0, name: jQuery("tester").text()});
                            return item;
                        }
                    },
                    onAdd: function (item) {
                        if (item.id > 0) {
                            return;
                        }
                        if (item.name.search(",") == -1) {
                            var input = jQuery("tester").text();
                            msg = "<?php echo Text::_('The location format is not correct please enter the city in this  format').' <br/>'.Text::_('City Name').', '.Text::_('Country Name').' <br/>'.Text::_('or').' <br/>'.Text::_('City Name').', '.Text::_('State Name').', '.Text::_('Country Name'); ?>";
                            jQuery("#" + inputfor + "_city_"+sectionid).tokenInput("remove", item);
                            jQuery("div#warn-message").find("span.text").html(msg).show();
                            jQuery("div#warn-message").show();
                            jQuery("div#black_wrapper_jobapply").show();
                            return false;
                        } else {
                            var location_data =  jQuery("tester").text();
                            var geocoder =  new google.maps.Geocoder();
                            geocoder.geocode( { 'address': location_data}, function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    var n_latitude = results[0].geometry.location.lat();
                                    var n_longitude = results[0].geometry.location.lng();
                                    jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&task=cities.savecity&<?php echo Factory::getSession()->getFormToken(); ?>=1", {citydata: location_data,latitude: n_latitude,longitude: n_longitude}, function (data) {
                                        if (data) {
                                            try {
                                                var value = jQuery.parseJSON(data);
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('add', {id: value.id, name: value.name,latitude:value.latitude,longitude:value.longitude});
                                            } catch (e) { // string is not the json its the message come from server
                                                msg = data;
                                                jQuery("div#warn-message").find("span.text").html(msg).show();
                                                jQuery("div#warn-message").show();
                                                jQuery("div#black_wrapper_jobapply").show();
                                                jQuery('#' + inputfor + '_city_'+sectionid).tokenInput('remove', item);
                                            }
                                        }
                                    });
                                } else {
                                    alert("Something got wrong"+status);
                                }
                            });
                        }
                    }
                    <?php } ?>
                });
            }
        }
        function loadMap( sectionid ) {
            var default_latitude = "<?php echo $this->config['default_latitude']; ?>";
            var default_longitude = "<?php echo $this->config['default_longitude']; ?>";

            var latitude = document.getElementById('latitude_'+sectionid).value;
            var longitude = document.getElementById('longitude_'+sectionid).value;
            var marker_flag = 0;
            if ((latitude != '') && (longitude != '')) {
                default_latitude = latitude;
                default_longitude = longitude;
                marker_flag = 1;
            }
            var latlng = new google.maps.LatLng(default_latitude, default_longitude);
            zoom = 10;
            var myOptions = {
                zoom: zoom,
                center: latlng,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map(document.getElementById("map_container_"+sectionid), myOptions);
            var lastmarker = new google.maps.Marker({
                postiion: latlng,
            });
            var marker = new google.maps.Marker({
                position: latlng,
            });
            if(marker_flag == 1){
                marker.setMap(map);
            }
            
            lastmarker = marker;
            google.maps.event.addListener(map, "click", function (e) {
                var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'latLng': latLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (lastmarker != '')
                            lastmarker.setMap(null);
                        var marker = new google.maps.Marker({
                            position: results[0].geometry.location,
                            map: map,
                        });
                        marker.setMap(map);
                        lastmarker = marker;
                        document.getElementById('latitude_'+sectionid).value = marker.position.lat();
                        document.getElementById('longitude_'+sectionid).value = marker.position.lng();

                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
            });
        }
        function showdiv(sectionid) {
            document.getElementById('map_'+sectionid).style.visibility = 'visible';
        }
        function hidediv(sectionid) {
            document.getElementById('map_'+sectionid).style.visibility = 'hidden';
        }
        function myValidate(f) {
            var msg = new Array();
            if (document.formvalidator.isValid(f)) {
                f.check.value = '<?php if (JVERSION < 3) echo JUtility::getToken(); else echo Factory::getSession()->getFormToken(); ?>';
            } else {
                msg.push("<?php echo Text::_('Some values are not acceptable, please retry'); ?>");
                alert(msg.join('\n'));
                return false;
            }
            return true;
        }
    </script>
   
<div id="black_wrapper_jobapply" style="display:none;"></div>
<div id="warn-message" style="display: none;">
    <span class="close-warnmessage"><img src="<?php echo Uri::root(); ?>components/com_jsjobs/images/close-icon.png" /></span>
    <img src="<?php echo Uri::root(); ?>components/com_jsjobs/images/warning-icon.png" />
    <span class="text"></span>
</div>
<script type="text/javascript">
    jQuery("div#black_wrapper_jobapply,div#warn-message span.close-warnmessage").click(function () {
        jQuery("div#warn-message").fadeOut();
        jQuery("div#black_wrapper_jobapply").fadeOut();
    });
</script>
<!-- Script -->
<script type="text/javascript">
    function showResumeSection( btn , sec_name){
        var path = 'div#jssection_'+sec_name;
        var obj = jQuery(path).find('.jssection_hide').first();
        var islast = jQuery(path).find('.jssection_hide').next().hasClass('jssection_hide');
        // now enable this section
        jQuery(obj).removeClass('jssection_hide');
        jQuery(obj).find('input.jsdeletethissection').val(0);
        if(!islast){
            jQuery(btn).remove();
        }
        // set required values
        jQuery(obj).find("[data-myrequired]").each(function(){
            var classname = jQuery(this).attr('data-myrequired');
            jQuery(this).addClass(classname);
        });
    }

    function deleteThisSection(obj){
        jQuery(obj).hide();
        var main = jQuery(obj).parent();
        main.find('input.jsdeletethissection').val(1);
        main.find('div.jsundo').addClass('jsundodiv');
        main.find('div.jsundo').show();
    }
    
    function undoThisSection(obj){
        var main = jQuery(obj).parent();
        main.hide();
        main.removeClass('jsundodiv');
        main.parent().find('input.jsdeletethissection').val(0);
        main.parent().find('img.jsdeleteimage').show();
    }
    
    function jsParseInt(string){
        string = string.replace( /[^\d.]/g, '' ); 
        var id = parseInt(string);
        if(isNaN(id)){
            id = 0;
        }
        return id;
    }

    function executeIEspecificScript(){
        if(!jQuery("#resumefileswrapper").length){
            return;
        }
        var isMSIE = false;
        if(navigator.appName === "Microsoft Internet Explorer") isMSIE = true;
        if(!isMSIE && /(MSIE|Trident\/|Edge\/)/i.test(navigator.userAgent || userAgent))    isMSIE = true;
        if(isMSIE){
            var input = '<input type="file" name="resumefiles[]" class="resumefiles">';
            jQuery("#resumefileswrapper_IE").prepend(input);
            jQuery("#resumefileswrapper").remove();
            jQuery("#resumefileswrapper_IE").show();
        }else{
            jQuery("#resumefileswrapper").show();
            jQuery("#resumefileswrapper_IE").remove();
        }
    }
</script>
<!-- END Script -->
<?php
    function getSectionTitle($sectionFor, $title , $sectionid) {
        if ($sectionFor == "education") {
            $sectionFor = "institute";
        }
        $html = '<div id="jsresume_sectionid'.$sectionid.'" class="js-resume-section-title">
                    <span>' . Text::_($title) . '</span>
                </div>';
        echo $html;
    }
?>
    <div id="js_main_wrapper">
        
                <span class="js_controlpanel_section_title"><?php echo Text::_('Resume Form'); ?></span>
                <?php if ($this->config['cur_location'] == '1') { ?>
                    <div class="jsjobs-breadcrunbs-wrp jsjobs-resume-breadcrunbs-wrp">
                        <div id="jsjobs-breadcrunbs">
                            <ul>
                                <li>
                                    <?php
                                        if (isset($this->jobseekerlinks) &&  is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
                                            $dlink='index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid='.$this->Itemid;
                                        }else{
                                            $dlink='index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid='.$this->Itemid;
                                        }
                                    ?>
                                    <a href="<?php echo $dlink; ?>" title="<?php echo Text::_('Dashboard'); ?>">
                                        <?php echo Text::_("Dashboard"); ?>
                                    </a>
                                </li>
                                <li>
                                    <?php echo Text::_('Resume Form'); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            <?php
            if (((isset($this->isadmin) AND $this->isadmin == 1)|| (isset($this->canaddnewresume) && $this->canaddnewresume == VALIDATE)) && $this->validresume == true) { ?>
            <div id="resumeform">
                <form action="index.php" method="post" name="resumeForm" id="resumeForm" class="resumeForm" enctype="multipart/form-data" onSubmit="return myValidate(this);">
                    <div id="black_wrapper_resumefiles" style="display:none;"></div>
                    <div id="resumeFilesPopup" class="resumeFilesPopup">
                        <div id="resumeFiles_headline"><?php echo Text::_("Resume Files"); ?></div>
                        <div id="fileSelectionButton" class="fileSelectionButton"></div>
                        <div class="chosenFiles_heading"><span><?php echo Text::_("Resume Files"); ?></span></div>
                        <div id="filesInfo" class="filesInfo js-row no-margin"></div>
                        <div class="resumeFiles_close"><span id="closepopup"><?php echo Text::_("Ok"); ?></span></div>
                    </div>
                    <input type="hidden" id="resume_temp" name="resume_temp" value="<?php echo $this->resumeid;?>">
                <?php
                require_once JPATH_COMPONENT_SITE . '/views/resume/resumeformlayout.php';
                $resumeformlayout = new JSJobsResumeformlayout();
                $resume_model = $this->resume_model;
                $cf_object = getCustomFieldClass();
                // On Edit form show percentage

                foreach ($this->fieldsordering AS $field) {
                    if($field->published == 1){
                        switch ($field->field){
                            case 'section_personal':
                                $title = 'Personal Information';
                                getSectionTitle('personal', $title , 1);
                                $personal_data = $resume_model->getResumeDataBySection($this->resumeid , 'personal');
                                $html = $resumeformlayout->makePersonalSectionFields($personal_data, $this->isadmin, $cf_object, $this->config);
                                echo $html;
                                break;
                            case 'section_address':
                                $title = 'Address';
                                getSectionTitle('address', $title, 2);

                                $result = $resume_model->getResumeDataBySection($this->resumeid , 'address');
                                $html = $resumeformlayout->makeAddressSectionFields($result, $cf_object, $this->config);
                                echo $html;
                                break;
                            case 'section_institute':
                                $title = 'Education';
                                getSectionTitle('education', $title, 3);

                                $result = $resume_model->getResumeDataBySection($this->resumeid , 'institute');
                                $html = $resumeformlayout->makeInstituteSectionFields($result, $cf_object, $this->config);
                                echo $html;
                                break;
                            case 'section_employer':
                                $title = 'Employer';
                                getSectionTitle('employer', $title, 4);
                                
                                $result = $resume_model->getResumeDataBySection($this->resumeid , 'employer');
                                $html = $resumeformlayout->makeEmployerSectionFields($result, $cf_object, $this->config);
                                echo $html;
                                break;
                            case 'section_skills':
                                $title = 'Skills';
                                getSectionTitle('skills', $title , 5);

                                $result = $resume_model->getResumeDataBySection($this->resumeid , 'skills');
                                $html = $resumeformlayout->makeSkillsSectionFields($result, $cf_object, $this->config);
                                echo $html;
                                break;
                            case 'section_resume':
                                $title = 'Resume editor';
                                getSectionTitle('editor', $title , 6);

                                $result = $resume_model->getResumeDataBySection($this->resumeid , 'editor');
                                $html = $resumeformlayout->makeResumeSectionFields($result, $cf_object, $this->config);
                                echo $html;
                                break;
                            case 'section_reference':
                                $title = 'Reference';
                                getSectionTitle('reference', $title , 7);
                                
                                $result = $resume_model->getResumeDataBySection($this->resumeid , 'reference');
                                $html = $resumeformlayout->makeReferenceSectionFields($result, $cf_object, $this->config);
                                echo $html;
                                break;
                            case 'section_language';
                                $title = 'Language';
                                getSectionTitle('language', $title , 8);
                                
                                $result = $resume_model->getResumeDataBySection($this->resumeid , 'language');
                                $html = $resumeformlayout->makeLanguageSectionFields($result, $cf_object, $this->config);
                                echo $html;
                                break;
                        }
                    }
                }
                ?>

                <input type="hidden" id="id" name="sec_1[id]" value="<?php echo isset($this->resumeid) ? $this->resumeid : ''; ?>" />
                <?php
                $issite = Factory::getApplication()->isClient('site');
                if($issite){ ?>
                    <input type="hidden" id="uid" name="sec_1[uid]" value="<?php echo isset($this->uid) ? $this->uid : ''; ?>" />
                    <input type="hidden" name="sec_1[packageid]" value="<?php echo isset($this->packagedetail[0]) ? $this->packagedetail[0] : '' ?>" />
                    <input type="hidden" name="sec_1[paymenthistoryid]" value="<?php echo isset($this->packagedetail[1]) ? $this->packagedetail[1] : '' ?>" />
                <?php
                }
                ?>
                <input type="hidden" id="isadmin" name="isadmin" value="<?php echo isset($this->isadmin) ? $this->isadmin : ''; ?>" />
                <input type="hidden" name="c" value="resume" />
                <input type="hidden" name="task" value="resume.saveresume" />
                <input type="hidden" name="check" value="" />
                <input type="hidden" id="validated" name="validated" value="" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <div class="resumesubmitbuttons"> 
                    <?php
                    if($issite){
                        $cancel_link = "index.php?option=com_jsjobs&c=resume&view=resume&layout=myresumes";
                    }else{
                        $cancel_link = "index.php?option=com_jsjobs&c=resume&view=resume&layout=empapps";
                    } ?>
                    <input class="resume_submits save" type="submit" name="save" value="<?php echo Text::_('Save'); ?>" />
                    <input class="resume_submits saveandclose" type="submit" name="saveandclose" value="<?php echo Text::_('Save and Close'); ?>" />

                    <a class="resume_submits cancel" href="<?php echo $cancel_link; ?>"><?php echo Text::_('Cancel'); ?></a>
                </div>
                <?php echo HTMLHelper::_( 'form.token' ); ?>
            </form>
        </div>
    <?php 
        } else { // can not add new resume 
            if($this->validresume != true){
                $this->jsjobsmessages->getAccessDeniedMsg('Ooops', 'Resume you are looking for is no more exists.');
            }else{
                $itemid = $this->Itemid;
                switch ($this->canaddnewresume) {
                    case NO_PACKAGE:
                        $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                        $vartext = Text::_('A package is required to perform this action').', '.Text::_('please get the new package');
                        $this->jsjobsmessages->getPackageExpireMsg('You do not have the package', $vartext, $link);
                    break;
                    case EXPIRED_PACKAGE:
                        $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                        $vartext = Text::_('A package is required to perform this action').', '.Text::_('please get A package');
                        $this->jsjobsmessages->getPackageExpireMsg('You do not have the package', $vartext, $link);
                    break;
                    case RESUME_LIMIT_EXCEEDS:
                        $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                        $vartext = Text::_('You can not add a new resume').', ' .Text::_('please get the new package to extend your resume limit');
                        $this->jsjobsmessages->getPackageExpireMsg('Resume limit exceeds', $vartext, $link);
                        break;
                    case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                        $this->jsjobsmessages->getAccessDeniedMsg('Employer not allowed', 'Employer is not allowed in jobseeker private area', 0);
                    break;
                    case USER_ROLE_NOT_SELECTED:
                        $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                        $vartext = Text::_('You do not select your role').', '.Text::_('Please select your role');
                        $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role', $vartext, $link);
                        break;
                    case VISITOR_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                        $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Please log in to access the private area', 1);
                    break;
                }
            }
        }
    ?>
    </div>
<?php } ?>
