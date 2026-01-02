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
if (JVERSION < 3) {
    HTMLHelper::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    HTMLHelper::_('bootstrap.framework');
    HTMLHelper::_('jquery.framework');
}

if (!empty($this->cvids)) {
    $jobaliasid = $this->jobaliasid;
    $rd = $this->resume->id;
    $nav = $this->nav; // hardcode for the employer resumes navigations.
    $sort = $this->sortby;
    $index_cvid = -1;
    foreach ($this->cvids AS $key => $cvid) {
        if ($rd == $cvid->cvid) {
            $index_cvid = $key;
            break;
        }
    }
    if ($index_cvid != -1) {
        $rd = $this->cvids[$index_cvid]->cvid;
        if (isset($this->cvids[($index_cvid - 1)])){
            $resumealiasid = JSModel::getJSModel('common')->removeSpecialCharacter($this->cvids[($index_cvid - 1)]->cvid);
            $pre_rd_link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=2&rd=' . $resumealiasid . '&bd=' . $jobaliasid . '&sortby=' . $sort . '&ta=' . $this->tabaction . '&Itemid=' . $this->Itemid;
        }

        if (isset($this->cvids[($index_cvid + 1)])){
            $resumealiasid = JSModel::getJSModel('common')->removeSpecialCharacter($this->cvids[($index_cvid + 1)]->cvid);
            $nex_rd_link = 'index.php?option=com_jsjobs&c=resume&view=resume&layout=view_resume&nav=2&rd=' . $resumealiasid . '&bd=' . $jobaliasid . '&sortby=' . $sort . '&ta=' . $this->tabaction . '&Itemid=' . $this->Itemid;
        }
    }
}

if (isset($this->isadmin) AND $this->isadmin == 1) {
    $validatePermissions = 0;
} else {
    $validatePermissions = 1;
}
?>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<script type="text/javascript">
    function toggleMap(id) {
        var containerWidth = jQuery("#address_" + id).width();
        var printResume = <?php
                            if (isset($this->printresume) && !empty($this->printresume)) {
                                echo $this->printresume;
                            } else {
                                echo 0;
                            }
                            ?>;
        if (printResume == 1) {
            jQuery("div.map_view").css('width', (containerWidth + 230) + 'px');
            jQuery("div.map_view").css('overflow', 'hidden');
        } else {
            jQuery("div.map_view").css('width', containerWidth);
        }
        if (jQuery("#map_container_" + id).is(':visible')) {
            jQuery("#map_container_" + id).hide('slow');
            jQuery("#address_" + id).find("div.map-toggler").find("span img").attr("src", "<?php echo Uri::root(); ?>components/com_jsjobs/images/show-map.png");
            jQuery("#address_" + id).find("div.map-toggler").find("span.text").html("<?php echo Text::_('Show Map'); ?>");
        } else {
            jQuery("#map_container_" + id).show('slow');
            jQuery("#address_" + id).find("div.map-toggler").find("span img").attr("src", "<?php echo Uri::root(); ?>components/com_jsjobs/images/hide-map.png");
            jQuery("#address_" + id).find("div.map-toggler").find("span.text").html("<?php echo Text::_('Hide Map'); ?>");
            loadMap(id);
        }
    }
    function loadMap(addressid) {
        var default_latitude = document.getElementById('latitude_' + addressid).value;
        var default_longitude = document.getElementById('longitude_' + addressid).value;

        var latlng = new google.maps.LatLng(default_latitude, default_longitude);
        zoom = 10;
        var myOptions = {
            zoom: zoom,
            center: latlng,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_view_" + addressid), myOptions);
        var lastmarker = new google.maps.Marker({
            postiion: latlng,
            map: map,
        });
        var marker = new google.maps.Marker({
            position: latlng,
            map: map,
        });
        marker.setMap(map);
        lastmarker = marker;

        google.maps.event.addListener(map, "click", function (e) {
            return false;
        });
    }
</script>
<div id="js_jobs_main_wrapper">
<?php
if (isset($this->printresume) AND $this->printresume != 1) {
    ?>
    <div id="js_menu_wrapper">
        <?php
        if (isset($this->jobseekerlinks)) {
            if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
                foreach ($this->jobseekerlinks as $lnk) { ?>                     
                    <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                    <?php
                }
            }
        }
        if (isset($this->employerlinks)) {
            if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
                foreach ($this->employerlinks as $lnk) {
                    ?>
                    <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
                    <?php
                }
            }
        }
        ?>
    </div>
    <?php
}
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    if($this->validresume != true){
        $this->jsjobsmessages->getAccessDeniedMsg('Ooops', 'Resume you are looking for is no more exists.');
        return '';
    }

    $printform = 1;
    $printform = 1;
    $canprint = 1; //canprint if the message is already print or not
    if ((isset($this->resume)) && ($this->resume->id != 0)) { // not new form
        if ($this->resume->status == 1) { // Employment Application is actve
            $printform = 1;
        } elseif ($this->resume->status == 0) { // not allowed job posting
            $printform = 0;
            $canprint = 0;
            $this->jsjobsmessages->getAccessDeniedMsg('Resume Waiting For Approval', 'Resume Waiting For Approval', 0);
        } else { // not allowed job posting
            $printform = 0;
            $canprint = 0;
            $this->jsjobsmessages->getAccessDeniedMsg('Resume Rejected', 'Resume Rejected', 0);
        }
        if ($this->nav == 1) {
            if ($this->resume->uid != $this->uid) { // job seeker can't see other job seeker resume
                $printform = 0;
                $canprint = 0;
                $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
            }
        }

    } elseif ($this->canview == 0) {
        $printform = 0;
        $canprint = 0;
        if($this->uid > 0 ){ //employer
            $this->jsjobsmessages->getAccessDeniedMsg('You can not view resume in detail', 'View resume in detail is not allowed in your package.', 0);
        }else{
            $this->jsjobsmessages->getAccessDeniedMsg('You can not view resume in detail', 'You can not view resume in detail', 0);
        }
    }

    if ($canprint == 1)
        if ($printform == 1) {
            if (isset($this->resume)) {
                ?>
                <div id="js_main_wrapper">
                    <?php if ($validatePermissions == 1) { ?>
                    <span class="js_controlpanel_section_title">
                        <?php echo Text::_('View Resume'); ?>
                    </span>
                    <?php if ($this->config['cur_location'] == '1') { ?>
                        <div class="jsjobs-breadcrunbs-wrp">
                            <div id="jsjobs-breadcrunbs">
                                <ul>
                                    <li>
                                        <?php
                                            if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
                                                $slink='index.php?option=com_jsjobs&c=jobseeker&view=jobseeker&layout=controlpanel&Itemid='.$this->Itemid;
                                            }else{
                                                $slink='index.php?option=com_jsjobs&c=employer&view=employer&layout=controlpanel&Itemid='.$this->Itemid;
                                            }
                                        ?>
                                        <a href="<?php echo $slink; ?>" title="<?php echo Text::_('Dashboard'); ?>">
                                            <?php echo Text::_("Dashboard"); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_jsjobs&c=jobapply&view=resume&layout=resumesearch" title="<?php echo Text::_("Resume Search"); ?>">
                                            <?php echo Text::_("Resume Search"); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="index.php?option=com_jsjobs&c=resume&view=resume&layout=resume_searchresults" title="<?php echo Text::_(" Resume Search Result"); ?>">
                                            <?php echo Text::_(" Resume Search Result"); ?>
                                        </a>
                                    </li>
                                    <li>
                                        <?php echo Text::_("View Resume"); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <?php
                    }

                    function getResumeUserField( $customfieldobj , $object, $field,$subreferenceid = null) {
                        $data = '';
                        $isodd = 0;
                        if (isset($object->isjobsharing) AND $object->isjobsharing != "") {

                        } else {
                            $field = $field->field;
                            $arr = $customfieldobj->showCustomFields($field, 11 ,$object);
                            if(!$arr)
                                return '';
                            $title = $arr['title'];
                            $value = $arr['value'];
                            $data .= ' <div class="js-row no-margin">
                                                <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding">
                                                    <span>' . Text::_($title) . ':&nbsp;</span>
                                                </div>';
                            $data .= ' <div class="js-resume-data-value js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">
                                            ' . $value . '
                                        </div>
                            </div>';
                        }
                        return $data;
                    }

                    function getResumeUserFieldForAddress( $customfieldobj , $object, $field) {
                        $data = '';
                        $isodd = 0;
                        if (isset($object->isjobsharing) && $object->isjobsharing != "") {

                        } else {
                            
                            $field = $field->field;
                            $arr = $customfieldobj->showCustomFields($field, 11 ,$object);
                            if(!$arr)
                                return '';
                            $title = $arr['title'];
                            $value = $arr['value'];
                            $data .= '  <div class="js-row no-margin">
                                            <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding js-resume-address-field">
                                                <span>' . Text::_($title) . ':</span>
                                            </div>
                                            <div class="js-resume-data-value js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">
                                                <span>' . $value . '</span>
                                            </div>
                                        </div>';
                        }
                        return $data;
                    }

                    function getResumeField($fieldtitle, $fieldvalue, $published, $fullwidth = 0) {
                        $data = '';
                        $class = '';
                        if ($fullwidth == 1) {
                            $class = 'js-full-width-row';
                        }
                        if ($published == 1) {
                            $data .= '
                    <div class="js-row no-margin '.$class.'">
                        <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding">
                            <span>' . Text::_($fieldtitle) . ':</span>
                        </div>
                        <div class="js-resume-data-value js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">
                            <span>' . $fieldvalue . '</span>
                        </div>
                    </div>';
                        }
                        return $data;
                    }

                    if (isset($this->printresume) AND $this->printresume != 1) {
                        ?>
                    <?php if ((($this->nav == 2) || ($this->nav == 3) || ($this->nav == 5)) && (isset($pre_rd_link) || isset($nex_rd_link))) { ?>
                            <span class="js_controlpanel_section_title" style="margin-top:-5px;">
                                <?php if(isset($pre_rd_link)){ ?>
                                <span class="js_resume_prev"><a href="<?php echo $pre_rd_link; ?>" ><?php echo "<<&nbsp;&nbsp;" . Text::_('Previous Resume'); ?></a></span>
                                <?php } ?>
                                <?php if(isset($nex_rd_link)){ ?>
                                <span class="js_resume_next"><a href="<?php echo $nex_rd_link; ?>" ><?php echo Text::_('Next Resume') . "&nbsp;&nbsp;>>"; ?></a></span>
                                <?php } ?>
                            </span>
                            <?php
                        }
                    }
                    ?>
                    <div class="js-row no-margin">
                        <?php
                        $section_personal = 0;
                        $first_name = 0;
                        $middle_name = 0;
                        $last_name = 0;
                        $email_address = 0;
                        $cell = 0;
                        $photo = 0;
                        $section_moreoptions = 0;
                        $customfieldobj = getCustomFieldClass();
                        for ($i = 0; $i < COUNT($this->fieldsordering['personal']); $i++) {
                            $field = $this->fieldsordering['personal'][$i];
                            switch ($field->field) {
                                case "section_personal":
                                    $section_personal = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "first_name":
                                    $first_name = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "middle_name":
                                    $middle_name = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "last_name":
                                    $last_name = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "email_address":
                                    $email_address = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "cell":
                                    $cell = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "photo":
                                    $photo = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                                case "section_moreoptions":
                                    $section_moreoptions = ($field->published == 1) ? 1 : 0;
                                    unset($this->fieldsordering['personal'][$i]);
                                    break;
                            }
                        }
                        $data = '';
                        $data .= '
                            <div id="resumeFormContainer" class="js-resume-section-body personal-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                <div id="js-resume-section-view js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                    <div class="js-resume-section-view js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div class="js-resume-profile js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div class="js-col-lg-3 js-col-md-3 js-col-xs-12 no-padding js-resume-logo">';
                        if ($photo == 1) {
                            if (!empty($this->resume->photo)) {
                                if(isset($this->resume->image_path)){
                                    $data .= '<img class="avatar" height="150" width="150" src="' . $this->resume->image_path . '"  />';
                                }else{
                                    $data .= '<img class="avatar" height="150" width="150" src="' . Uri::root() . $this->config['data_directory'] . '/data/jobseeker/resume_' . $this->resume->id . '/photo/' . $this->resume->photo . '"  />';
                                }
                                
                            } else {
                                $data .= '<img class="avatar" src="' . Uri::root() . '/components/com_jsjobs/images/user_b.png"  />';
                            }
                        }
                        $data .= '
                                            </div>
                                            <div class="js-resume-profile-info js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">';
                                            if ( ($this->nav == 2) || ($this->nav == 3) || ($this->nav == 5)) {
                                                $jobid = $this->jobaliasid;
                                                $data .= '
                                                <div class="js-resume-print-wrap">
                                                <a class="" target="_blank" href="'.Uri::root().'index.php?option=com_jsjobs&c=resume&view=output&layout=resumepdf&format=pdf&rd='.$this->resume->resumealiasid.'&bd='.$this->jobaliasid.'&ms='.$this->ms.'&Itemid='.$this->Itemid.'">
                                                    '.Text::_("Pdf").'
                                                </a>
                                                </div>
                                                ';
                                            }

                        $data .= '          <div class="profile-name-outer js-row no-margin">
                                                    <div class="js-resume-profile-name js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                                        <span>';
                        if ($first_name == 1) {
                            $data .= $this->resume->first_name . ' ';
                        }
                        if ($middle_name == 1) {
                            $data .= $this->resume->middle_name . ' ';
                        }
                        if ($last_name == 1) {
                            $data .= $this->resume->last_name;
                        }
                        $data .= '
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="js-resume-profile-email js-row no-margin">
                                                    <span>';
                        if ($email_address == 1) {
                            $data .= $this->resume->email_address;
                        }
                        $data .= '
                                                    </span>
                                                </div>
                                                <div class="js-resume-profile-cell js-row no-margin">
                                                    <span>';
                        if ($cell == 1) {
                            $data .= $this->resume->cell;
                        }
                        $data .= '
                                                    </span>
                                                </div>';
                        
                            $data.='</div>
                        </div>';
                        if (isset($this->printresume) AND $this->printresume == 1) {
                            $data .= '<div class="js-row no-margin"></div>';
                        }
                        if ($section_personal == 1) {
                            $data .= '
                                    <a id="personalSectionAnchor">
                                        <div class="js-row no-margin">
                                            <div class="js-resume-section-title">
                                                <span>' . Text::_("Personal Information") . '</span>
                                            </div>
                                        </div>
                                    </a>
                            ';
                        }
                        $data .= '
                                        <div class="js-resume-data js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">';
                        foreach ($this->fieldsordering['personal'] as $field) {
                            switch ($field->field) {
                                case "application_title":
                                    $data .= getResumeField( $field->fieldtitle , $this->resume->application_title, $field->published);
                                    break;
                                case "nationality":
                                    $data .= getResumeField($field->fieldtitle, $this->resume->nationalitycountry, $field->published);
                                    break;
                                case "license_country":
									if($this->resume->driving_license != 1){
										$this->resume->licensecountryname = Text::_('N/a');
									}
                                    $data .= getResumeField($field->fieldtitle, $this->resume->licensecountryname, $field->published);
                                    break;
                                case "license_no":
									if($this->resume->driving_license != 1){
										$this->resume->license_no = Text::_('N/a');
									}
                                    $data .= getResumeField($field->fieldtitle, $this->resume->license_no, $field->published);
                                    break;
                                case "driving_license":
									switch($this->resume->driving_license){
										case 1:
											$driving_license = Text::_('JYES');
										break;
										case 2:
											$driving_license = Text::_('N/a');
										break;
										default:
											$driving_license = Text::_('N/a');
										break;
									}
                                    $data .= getResumeField($field->fieldtitle, $driving_license, $field->published);
                                    break;
                                case "resumefiles":
                                    $data .= '
                                                        <div class="js-row no-margin">
                                                            <div class="js-resume-data-title js-col-lg-3 js-col-md-3 js-col-xs-3 no-padding">
                                                                <span>' . Text::_($field->fieldtitle) . ':</span>
                                                            </div>
                                                            <div id="resumeFilesList" class="js-resume-data-value filesList js-col-lg-8 js-col-md-8 js-col-xs-8 no-padding">';
                                    if(isset($this->resumefiles)){
                                        if (isset($this->printresume) && $this->printresume == 1) {
                                            $data .= '<ul>';
                                        } else {
                                            $data .= '<ul><a title="'.Text::_('Download All').'" class="zip-downloader" href="' . Uri::root() . 'index.php?option=com_jsjobs&c=resume&task=getallresumefiles&resumeid=' . $this->resume->id . '&sr=1&Itemid='.$this->Itemid.'" onclick="" target="_blank"><img src="' . Uri::root() . 'components/com_jsjobs/images/download-all.png"></a>';
                                        }
                                        foreach($this->resumefiles AS $file){
                                            if(!($file instanceof Object)){
                                                $file = (Object) $file;
                                            }
                                            $selectedFilename = substr($file->filename, 0, 4);
                                            $fileExt = substr($file->filename, strrpos($file->filename, '.') + 1);
                                            $data .= '<li id="' . $file->id . '" class="selectedFile" title="' . $file->filename . '">' . $selectedFilename . '.. .' . $fileExt;
                                            if ($this->printresume != 1) {
                                                $data .= '<a target="_blank" href="' . $file->filepath . '" alt=""><img src="' . Uri::root() . 'components/com_jsjobs/images/download.png" height="15px" width="15px" /></a></li>';
                                            }
                                        }
                                        $data .= '</ul>';
                                    }
                                    $data .= '
                                                            </div>
                                                        </div>';
                                    break;
                                case "date_of_birth":
                                    if($this->resume->date_of_birth == '0000-00-00 00:00:00' || $this->resume->date_of_birth == NULL)
                                        $date = '';
                                    else
                                        $date = HTMLHelper::_('date', $this->resume->date_of_birth, $this->config['date_format']);
                                    $data .= getResumeField($field->fieldtitle, $date, $field->published);
                                    break;
                                case "gender":
                                    $genderText = '';
                                    if($this->resume->gender == 1){
                                        $genderText = Text::_('Male');
                                    }elseif ($this->resume->gender == 1) {
                                        $genderText = Text::_('Female');
                                    }elseif ($this->resume->gender == 3) {
                                        $genderText = Text::_('Does not matter');
                                    }

                                    $gender = $genderText;
                                    $data .= getResumeField($field->fieldtitle, $gender, $field->published);
                                    break;
                                case "iamavailable":
                                    $available = ($this->resume->iamavailable == 1) ? Text::_('JYES') : Text::_('JNO');
                                    $data .= getResumeField($field->fieldtitle, $available, $field->published);
                                    break;
                                case "searchable":
                                    $searchable = ($this->resume->searchable == 1) ? Text::_('JYES') : Text::_('JNO');
                                    $data .= getResumeField($field->fieldtitle, $searchable, $field->published);
                                    break;
                                case "home_phone":
                                    $data .= getResumeField($field->fieldtitle, $this->resume->home_phone, $field->published);
                                    break;
                                case "work_phone":
                                    $data .= getResumeField($field->fieldtitle, $this->resume->work_phone, $field->published);
                                    break;
                                case "job_category":
                                    $data .= getResumeField($field->fieldtitle, Text::_($this->resume->categorytitle), $field->published);
                                    break;
                                case "job_subcategory":
                                    $data .= getResumeField($field->fieldtitle, Text::_($this->resume->subcategorytitle), $field->published);
                                    break;
                                case "salary":
                                    $salary = $this->getJSModel('common')->getSalaryRangeView($this->resume->symbol,$this->resume->rangestart,$this->resume->rangeend,$this->resume->salarytype,$this->config['currency_align']);
                                    $data .= getResumeField($field->fieldtitle, $salary, $field->published);
                                    break;
                                case "desired_salary":
                                    $salary = $this->getJSModel('common')->getSalaryRangeView($this->resume->dsymbol,$this->resume->drangestart,$this->resume->drangeend,$this->resume->dsalarytype,$this->config['currency_align']);
                                    $data .= getResumeField($field->fieldtitle, $salary, $field->published);
                                    break;
                                case "keywords":
                                    $data .= getResumeField($field->fieldtitle, $this->resume->keywords, $field->published);
                                    break;
                                case "jobtype":
                                    $data .= getResumeField($field->fieldtitle, Text::_($this->resume->jobtypetitle) , $field->published);
                                    break;
                                case "heighestfinisheducation":
                                    $data .= getResumeField($field->fieldtitle, Text::_($this->resume->heighesteducationtitle), $field->published);
                                    break;
                                case "date_start":
                                    if($this->resume->date_start == '0000-00-00 00:00:00' || $this->resume->date_start == NULL)
                                        $datestart = '';
                                    else
                                        $datestart = HTMLHelper::_('date', $this->resume->date_start, $this->config['date_format']);
                                    $data .= getResumeField($field->fieldtitle, $datestart, $field->published);
                                    break;
                                case "total_experience":
									if(!empty($this->resume->experienceid)){
										$this->resume->total_experience = Text::_($this->resume->experiencetitle);
									}
                                    $data .= getResumeField($field->fieldtitle, $this->resume->total_experience, $field->published);
                                    break;
                                case "video":
                                    if($this->resume->video == ''){
                                        $html = '';
                                    }else{
                                        $videoid = $this->resume->video;
                                        if( filter_var($this->resume->video,FILTER_VALIDATE_URL) ){
                                            $videoid = preg_replace('/.+?v=/','',$this->resume->video);
                                        }
                                        $html = '
                                        <iframe title="YouTube video player" width="380" height="290" 
                                                src="https://www.youtube.com/embed/' . $videoid . '" frameborder="0" allowfullscreen>
                                        </iframe>
                                        ';
                                    }
                                    $data .= getResumeField($field->fieldtitle, $html, $field->published);
                                    break;
                                default:
                                    $data .= getResumeUserField( $customfieldobj , $this->resume, $field);
                                    break;
                            }
                        }
                        $data .= '
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        $section_address = 0;
                        if (isset($this->fieldsordering['address'][0]) && $this->fieldsordering['address'][0]->field == 'section_address') {
                            $section_address = $this->fieldsordering['address'][0]->published;
                            unset($this->fieldsordering['address'][0]);
                        }
                        if ($section_address == 1) {
                            $data .= '
                                        <a id="addressSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title">
                                                    <span>' . Text::_("Address") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->addresses)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                                <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                                    <div id="js-resume-address-section-view" class="js-row no-margin">
                                                        <div class="section-error js-col-lg-12 js-col-md-12">' . Text::_("No record found") . '</div>
                                                    </div>
                                                </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-address-section-view" class="js-row no-margin">';
                                foreach ($this->addresses as $address) {
                                    if(!($address instanceof Object)){
                                        $address = (object) $address;
                                    }
                                    $add_loccount = 0;
                                    $data .= '
                                            <div id="address_' . $address->id . '" class="js-resume-address-section-view js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['address'] as $field) {
                                        switch ($field->field) {
                                            case "address_city":
                                                if ($field->published == 1) {
                                                    $data .= '
                                                                            <div class="js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                                                                <span class="addressDetails">';
                                                    $comma = '';
                                                    $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                    if ($address->address_cityname != '') {
                                                        $data .= Text::_($address->address_cityname);
                                                        $comma = ', ';
                                                    }
                                                    switch ($addresslayouttype) {
                                                        case 'csc':
                                                            if ($address->address_statename != '') {
                                                                $data .= $comma . Text::_($address->address_statename);
                                                            }
                                                            if ($address->address_countryname != '') {
                                                                $data .= $comma . Text::_($address->address_countryname);
                                                            }
                                                            break;
                                                        case 'cs':
                                                            if ($address->address_statename != '') {
                                                                $data .= $comma . Text::_($address->address_statename);
                                                            }
                                                            break;
                                                        case 'cc':
                                                            if ($address->address_countryname != '') {
                                                                $data .= $comma . Text::_($address->address_countryname);
                                                            }
                                                            break;
                                                    }
                                                    $data .= '      </span>
                                                                            </div>';
                                                }
                                                break;
                                            case "address_zipcode":
                                                if ($field->published == 1) {
                                                    $data .= '  <div class="js-col-lg-11 js-col-md-11 js-col-xs-11 no-padding">
                                                                                <span class="addressDetails">' . $field->fieldtitle . ' ' . $address->address_zipcode . '</span>
                                                                            </div>';
                                                }
                                                break;
                                            case "address":
                                                if ($field->published == 1) {
                                                    $address_address = ($address->address == '') ? 'N/A' : $address->address;
                                                    $data .= '
                                                                        <div class="sectionTextWrp no-padding">
                                                                            <span class="sectionText">' . $address_address . '</span>
                                                                        </div>';
                                                }
                                                break;
                                            case "latitude":
                                            case "longitude":
                                            case "address_location":
                                                if ($field->published == 1 && $add_loccount == 0) {
                                                    $data .= '<input id="longitude_' . $address->id . '" type="hidden" name="longitude" value="' . $address->longitude . '" />
                                                                        <input id="latitude_' . $address->id . '" type="hidden" name="latitude" value="' . $address->latitude . '" />';
                                                    if (isset($this->printresume) AND $this->printresume == 1) {
                                                        $data .= '
                                                                    <div id="map_container_' . $address->id . '" class="map_container js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding" style="display:none;">
                                                                        <div id="map_view_' . $address->id . '" class="map_view"></div>
                                                                    </div><script type="text/javascript">toggleMap(' . $address->id . ');</script>';
                                                    } else {
                                                        $data .= '
                                                                    <div class="map-toggler js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding" onclick="return toggleMap(' . $address->id . ');">
                                                                        <span><span class="text">' . Text::_('Show Map') . '</span></span>
                                                                    </div>
                                                                    <div id="map_container_' . $address->id . '" class="map_container js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding" style="display:none;">
                                                                        <div id="map_view_' . $address->id . '" class="map_view"></div>
                                                                    </div>';
                                                    }
                                                    $add_loccount++;
                                                }
                                                break;
                                            default:
                                                $data .= getResumeUserFieldForAddress( $customfieldobj , $address, $field);
                                                break;
                                        }
                                    }
                                    $data .= '
                                                    </div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_institute = 0;
                        if (isset($this->fieldsordering['institute'][0]) && $this->fieldsordering['institute'][0]->field == 'section_institute') {
                            $section_institute = $this->fieldsordering['institute'][0]->published;
                            unset($this->fieldsordering['institute'][0]);
                        }
                        if ($section_institute == 1) {
                            $data .= '
                                        <a id="instituteSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title">
                                                    <span>' . Text::_("Institutes") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->institutes)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . Text::_("No Record Found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="instituteFormContainer" class="js-resume-section-body institute-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-data-section-view" class="js-row no-margin">';
                                // resume institutes starts here
                                foreach ($this->institutes as $institute) {
                                    if(!($institute instanceof Object)){
                                        $institute = (Object) $institute;
                                    }
                                    $data .= '
                                            <div id="institute_' . $institute->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['institute'] as $field) {
                                        switch ($field->field) {
                                            case "institute":
                                                if ($field->published == 1) {
                                                    $institute_institute = ($institute->institute != '') ? $institute->institute : 'N/A';
                                                    $data .= '<div class="js-resume-data-head js-row no-margin">
                                                                            <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                                <span class="data-head-name">' . $institute_institute . '</span>
                                                                            </div>
                                                                        </div>';
                                                }
                                                break;
                                            case "institute_city":
                                                $comma = '';
                                                $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                $cityname = ($institute->institute_cityname != '') ? Text::_($institute->institute_cityname) : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $cityname, $field->published);
                                                switch ($addresslayouttype) {
                                                    case 'csc':
                                                        $statename = ($institute->institute_statename != '') ? Text::_($institute->institute_statename) : 'N/A';
                                                        $data .= getResumeField(Text::_('State'), $statename, $field->published);
                                                        $countryname = ($institute->institute_countryname != '') ? Text::_($institute->institute_countryname) : 'N/A';
                                                        $data .= getResumeField(Text::_('Country'), $countryname, $field->published);
                                                        break;
                                                    case 'cs':
                                                        $statename = ($institute->institute_statename != '') ? Text::_($institute->institute_statename) : 'N/A';
                                                        $data .= getResumeField(Text::_('State'), $statename, $field->published);
                                                        break;
                                                    case 'cc':
                                                        $countryname = ($institute->institute_countryname != '') ? Text::_($institute->institute_countryname) : 'N/A';
                                                        $data .= getResumeField(Text::_('Country'), $countryname, $field->published);
                                                        break;
                                                }
                                                break;
                                            case "institute_address":
                                                $value = ($institute->institute_address != '') ? $institute->institute_address : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $value, $field->published);
                                                break;
                                            case "institute_certificate_name":
                                                $value = ($institute->institute_certificate_name != '') ? $institute->institute_certificate_name : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $value, $field->published);
                                                break;
                                            case "institute_study_area":
                                                $value = ($institute->institute_study_area != '') ? $institute->institute_study_area : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $value, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField( $customfieldobj , $institute, $field,$institute->id);
                                                break;
                                        }
                                    }
                                    $data .= '</div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_employer = 0;
                        if (isset($this->fieldsordering['employer'][0]) && $this->fieldsordering['employer'][0]->field == 'section_employer') {
                            $section_employer = $this->fieldsordering['employer'][0]->published;
                            unset($this->fieldsordering['employer'][0]);
                        }
                        if ($section_employer == 1) {
                            $data .= '
                                        <a id="employerSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title">
                                                    <span>' . Text::_("Employers") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->employers)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="addressFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . Text::_("No record found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="employerFormContainer" class="js-resume-section-body employer-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-data-section-view" class="js-row no-margin">';
                                // resume employers starts here
                                foreach ($this->employers as $employer) {
                                    if(!($employer instanceof Object)){
                                        $employer = (Object) $employer;
                                    }
                                    $data .= '<div id="employer_' . $employer->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['employer'] as $field) {
                                        switch ($field->field) {
                                            case "employer":
                                                if ($field->published == 1) {
                                                    $employer_employer = ($employer->employer != '') ? $employer->employer : 'N/A';
                                                    $data .= '
                                                                        <div class="js-resume-data-head js-row no-margin">
                                                                            <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                                <span class="data-head-name">' . $employer_employer . '</span>
                                                                            </div>
                                                                        </div>';
                                                }
                                                break;
                                            case "employer_city":
                                                $comma = '';
                                                $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                $cityname = ($employer->employer_cityname != '') ? $employer->employer_cityname : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $cityname, $field->published);
                                                switch ($addresslayouttype) {
                                                    case 'csc':
                                                        $statename = ($employer->employer_statename != '') ? $employer->employer_statename : 'N/A';
                                                        $data .= getResumeField(Text::_('State'), $statename, $field->published);
                                                        $countryname = ($employer->employer_countryname != '') ? $employer->employer_countryname : 'N/A';
                                                        $data .= getResumeField(Text::_('Country'), $countryname, $field->published);
                                                        break;
                                                    case 'cs':
                                                        $statename = ($employer->employer_statename != '') ? $employer->employer_statename : 'N/A';
                                                        $data .= getResumeField(Text::_('State'), $statename, $field->published);
                                                        break;
                                                    case 'cc':
                                                        $countryname = ($employer->employer_countryname != '') ? $employer->employer_countryname : 'N/A';
                                                        $data .= getResumeField(Text::_('Country'), $countryname, $field->published);
                                                        break;
                                                }
                                                break;
                                            case "employer_position":
                                                $employer_position = ($employer->employer_position != '') ? $employer->employer_position : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_position, $field->published);
                                                break;
                                            case "employer_resp":
                                                $employer_resp = ($employer->employer_resp != '') ? $employer->employer_resp : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_resp, $field->published);
                                                break;
                                            case "employer_pay_upon_leaving":
                                                $employer_pay_upon_leaving = ($employer->employer_pay_upon_leaving != '') ? $employer->employer_pay_upon_leaving : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_pay_upon_leaving, $field->published);
                                                break;
                                            case "employer_supervisor":
                                                $employer_supervisor = ($employer->employer_supervisor != '') ? $employer->employer_supervisor : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_supervisor, $field->published);
                                                break;
                                            case "employer_from_date":
                                                if($employer->employer_from_date == '0000-00-00 00:00:00' || $employer->employer_from_date == NULL)
                                                    $employer_fromdate = '';
                                                else
                                                    $employer_fromdate = HTMLHelper::_('date', $employer->employer_from_date, $this->config['date_format']);
                                                $data .= getResumeField($field->fieldtitle, $employer_fromdate, $field->published);
                                                break;
                                            case "employer_to_date":
                                                if($employer->employer_to_date == '0000-00-00 00:00:00' || $employer->employer_to_date == NULL)
                                                    $employer_todate = '';
                                                else
                                                    $employer_todate = HTMLHelper::_('date', $employer->employer_to_date, $this->config['date_format']);
                                                $data .= getResumeField($field->fieldtitle, $employer_todate, $field->published);
                                                break;
                                            case "employer_leave_reason":
                                                $employer_leavereason = ($employer->employer_leave_reason != '') ? $employer->employer_leave_reason : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_leavereason, $field->published);
                                                break;
                                            case "employer_zip":
                                                $employer_zipcode = ($employer->employer_zip != '') ? $employer->employer_zip : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_zipcode, $field->published);
                                                break;
                                            case "employer_phone":
                                                $employer_phone = ($employer->employer_phone != '') ? $employer->employer_phone : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_phone, $field->published);
                                                break;
                                            case "employer_address":
                                                $employer_address = ($employer->employer_address != '') ? $employer->employer_address : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $employer_address, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField( $customfieldobj , $employer, $field,$employer->id);
                                                break;
                                        }
                                    }
                                    $data .= '  </div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_skills = 0;
                        if (isset($this->fieldsordering['skills'][0]) && $this->fieldsordering['skills'][0]->field == 'section_skills') {
                            $section_skills = $this->fieldsordering['skills'][0]->published;
                            unset($this->fieldsordering['skills'][0]);
                        }
                        if ($section_skills == 1) {
                            $data .= '
                                        <a id="skillsSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title">
                                                    <span>' . Text::_("Skills") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if ($this->resume->skills == '') {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="skillsFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . Text::_("No record found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="skillsFormContainer" class="js-resume-section-body skills-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-skills-section-view js-row no-margin">';
                                // resume skills starts here
                                $data .= '<div id="skills" class="js-resume-data-section-view js-row no-margin">';
                                foreach ($this->fieldsordering['skills'] as $field) {
                                    switch ($field->field) {
                                        case "skills":
                                            $data .= getResumeField($field->fieldtitle, $this->resume->skills, $field->published, 1);
                                            break;
                                        default:
                                            $data .= getResumeUserField( $customfieldobj , $this->resume, $field);
                                            break;
                                    }
                                }
                                $data .= '</div>';
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        $section_resume = 0;
                        if (isset($this->fieldsordering['resume'][0]) && $this->fieldsordering['resume'][0]->field == 'section_resume') {
                            $section_resume = $this->fieldsordering['resume'][0]->published;
                            unset($this->fieldsordering['resume'][0]);
                        }
                        if ($section_resume == 1) {
                            $data .= '
                                        <a id="editorSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title">
                                                    <span>' . Text::_("Resume") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if ($this->resume->resume == '') {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="editorFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . Text::_("No record found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="editorFormContainer" class="js-resume-section-body editor-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-resume-editor-section-view js-row no-margin">';
                                // resume skills starts here
                                $data .= '<div id="editorView" class="js-resume-data-section-view js-resume-address-section-view js-row no-margin">';
                                foreach ($this->fieldsordering['resume'] as $field) {
                                    switch ($field->field) {
                                        case "resume":
                                            if ($field->published == 1) {
                                                $data .= '
                                                            <div class="js-row no-margin js-full-width-row">
                                                                <div class="js-resume-data-title js-col-lg-12 js-col-md-12 no-padding">
                                                                    
                                                                </div>
                                                                <div class="js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                                                    <span class="addressDetails">' . $this->resume->resume . '</span>
                                                                </div>
                                                            </div>';
                                            }
                                            break;
                                        default:
                                            $data .= getResumeUserField( $customfieldobj , $this->resume, $field);
                                            break;
                                    }
                                }
                                $data .= '</div>
                                        </div>
                                    </div>';
                            }
                        }
                        $section_references = 0;
                        if (isset($this->fieldsordering['reference'][0]) && $this->fieldsordering['reference'][0]->field == 'section_reference') {
                            $section_references = $this->fieldsordering['reference'][0]->published;
                            unset($this->fieldsordering['reference'][0]);
                        }
                        if ($section_references == 1) {
                            $data .= '
                                        <a id="referenceSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title">
                                                    <span>' . Text::_("References") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->references)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="referenceFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . Text::_("No record found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="referenceFormContainer" class="js-resume-section-body reference-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-reference-data-section-view" class="js-row no-margin">';
                                // resume employers starts here
                                foreach ($this->references as $reference) {
                                    if(!($reference instanceof Object)){
                                        $reference = (Object) $reference;
                                    }
                                    $data .= '<div id="reference_' . $reference->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['reference'] as $field) {
                                        switch ($field->field) {
                                            case "reference":
                                                if ($field->published == 1) {
                                                    $reference_reference = ($reference->reference != '') ? $reference->reference : 'N/A';
                                                    $data .= '
                                                                    <div class="js-resume-data-head js-row no-margin">
                                                                        <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                            <span class="data-head-name">' . $reference_reference . '</span>
                                                                        </div>
                                                                    </div>';
                                                }
                                                break;
                                            case "reference_city":
                                                $comma = '';
                                                $addresslayouttype = $this->config['defaultaddressdisplaytype'];
                                                $cityname = ($reference->reference_cityname != '') ? $reference->reference_cityname : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $cityname, $field->published);
                                                switch ($addresslayouttype) {
                                                    case 'csc':
                                                        $statename = ($reference->reference_statename != '') ? $reference->reference_statename : 'N/A';
                                                        $data .= getResumeField(Text::_('State'), $statename, $field->published);
                                                        $countryname = ($reference->reference_countryname != '') ? $reference->reference_countryname : 'N/A';
                                                        $data .= getResumeField(Text::_('Country'), $countryname, $field->published);
                                                        break;
                                                    case 'cs':
                                                        $statename = ($reference->reference_statename != '') ? $reference->reference_statename : 'N/A';
                                                        $data .= getResumeField(Text::_('State'), $statename, $field->published);
                                                        break;
                                                    case 'cc':
                                                        $countryname = ($reference->reference_countryname != '') ? $reference->reference_countryname : 'N/A';
                                                        $data .= getResumeField(Text::_('Country'), $countryname, $field->published);
                                                        break;
                                                }
                                                break;
                                            case "reference_name":
                                                $reference_name = ($reference->reference_name != '') ? $reference->reference_name : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $reference_name, $field->published);
                                                break;
                                            case "reference_years":
                                                $reference_year = ($reference->reference_years != '') ? $reference->reference_years : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $reference_year, $field->published);
                                                break;
                                            case "reference_relation":
                                                $reference_relation = ($reference->reference_relation != '') ? $reference->reference_relation : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $reference_relation, $field->published);
                                                break;
                                            case "reference_zipcode":
                                                $reference_zipcode = ($reference->reference_zipcode != '') ? $reference->reference_zipcode : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $reference_zipcode, $field->published);
                                                break;
                                            case "reference_phone":
                                                $reference_phone = ($reference->reference_phone != '') ? $reference->reference_phone : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $reference_phone, $field->published);
                                                break;
                                            case "reference_address":
                                                $reference_address = ($reference->reference_address != '') ? $reference->reference_address : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $reference_address, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField( $customfieldobj , $reference, $field,$reference->id);
                                                break;
                                        }
                                    }
                                    $data .= '  </div>';
                                }
                                $data .= '</div>
                                        </div>';
                            }
                        }
                        $section_language = 0;
                        if (isset($this->fieldsordering['language'][0]) && $this->fieldsordering['language'][0]->field == 'section_language') {
                            $section_language = $this->fieldsordering['language'][0]->published;
                            unset($this->fieldsordering['language'][0]);
                        }
                        if ($section_language == 1) {
                            $data .= '
                                        <a id="languageSectionAnchor" alt="">
                                            <div class="js-row no-margin">
                                                <div class="js-resume-section-title">
                                                    <span>' . Text::_("Language") . '</span>
                                                </div>
                                            </div>
                                        </a>
                                        ';
                            if (empty($this->languages)) {
                                if ($this->show_only_section_that_have_value != 1) {
                                    $data .= '
                                        <div id="languageFormContainer" class="js-resume-section-body address-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                            <div id="js-resume-address-section-view" class="js-row no-margin">
                                                <div class="section-error js-col-lg-12 js-col-md-12">' . Text::_("No record found") . '</div>
                                            </div>
                                        </div>';
                                }
                            } else {
                                $data .= '
                                    <div id="languageFormContainer" class="js-resume-section-body language-section js-col-lg-12 js-col-md-12 js-col-xs-12 no-padding">
                                        <div id="js-language-data-section-view" class="js-row no-margin">';
                                // resume languages starts here
                                foreach ($this->languages as $language) {
                                    if(!($language instanceof Object)){
                                        $language = (Object) $language;
                                    }
                                    $data .= '<div id="language_' . $language->id . '" class="js-resume-data-section-view js-row no-margin">';
                                    foreach ($this->fieldsordering['language'] as $field) {
                                        switch ($field->field) {
                                            case "language":
                                                if ($field->published == 1) {
                                                    $language_language = ($language->language != '') ? $language->language : 'N/A';
                                                    $data .= '                                                                
                                                                    <div class="js-resume-data-head js-row no-margin">
                                                                        <div class="js-col-lg-10 js-col-md-10 no-padding">
                                                                            <span>' . $language->language . '</span>
                                                                        </div>
                                                                    </div>';
                                                }
                                                break;
                                            case "language_reading":
                                                $language_reading = ($language->language_reading != '') ? $language->language_reading : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $language_reading, $field->published);
                                                break;
                                            case "language_writing":
                                                $language_writing = ($language->language_writing != '') ? $language->language_writing : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $language_writing, $field->published);
                                                break;
                                            case "language_understanding":
                                                $language_understanding = ($language->language_understanding != '') ? $language->language_understanding : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $language_understanding, $field->published);
                                                break;
                                            case "language_where_learned":
                                                $language_where_learned = ($language->language_where_learned != '') ? $language->language_where_learned : 'N/A';
                                                $data .= getResumeField($field->fieldtitle, $language_where_learned, $field->published);
                                                break;
                                            default:
                                                $data .= getResumeUserField( $customfieldobj , $language, $field,$language->id);
                                                break;
                                        }
                                    }
                                    $data .= '  </div>';
                                }
                                $data .= '
                                        </div>
                                    </div>';
                            }
                        }
                        echo $data;
                        ?>
                        <?php
                        if (isset($this->resume)) {
                            if (($this->resume->created == '0000-00-00 00:00:00') || ($this->resume->created == '')) {
                                $curdate = date('Y-m-d H:i:s');
                            } else {
                                $curdate = $this->resume->created;
                            }
                        } else {
                            $curdate = date('Y-m-d H:i:s');
                        }
                        ?>
                    </div>
                </div>
            <?php } else {
                $this->jsjobsmessages->getAccessDeniedMsg('Could not find any matching results', 'Could not find any matching results', 0);
            }
        } else { // not allowed job posting 
            $this->jsjobsmessages->getAccessDeniedMsg('You are not allowed', 'You are not allowed to view this page', 0);
        }
}//ol
?>
<div id="jsjobsfooter">
    <table width="100%" style="table-layout:fixed;">
        <tr><td height="15"></td></tr>
        <tr>
            <td style="vertical-align:top;" align="center">
                <a class="img" target="_blank" href="https://www.joomsky.com"><img src="https://www.joomsky.com/logo/jsjobscrlogo.png"></a>
                <br>
                Copyright &copy; 2008 - <?php echo  date('Y') ?> ,
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions</a></span>
            </td>
        </tr>
    </table>
</div>
<?php 
if (isset($this->printresume) AND $this->printresume != 1) {
    echo '</div>';
}
?>

 
<script language="javascript">
    var printpage = '<?php if (isset($this->printresume))
    echo $this->printresume;
else
    echo 0;
?>';
    if (printpage == 1) {
        window.onload = function () {
            window.print();
            window.close();
        }
    }
    jQuery(document).ready(function () {
        <?php if(!isset($this->resumefiles)) { ?>
            getResumeFiles(<?php echo $this->resume->id ?>, 'resumeFilesList', 'view');
        <?php } ?>
    });


    function getResumeFiles(resumeid, src, filesfor) {
        var printResume = '<?php
                                if (isset($this->printresume) && !empty($this->printresume)) {
                                    echo $this->printresume;
                                } else {
                                    echo 0;
                                }
                            ?>';
        jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=getresumefiles', {resumeid: resumeid, filesfor: filesfor, printresume: printResume}, function (data) {

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
                alert("<?php echo Text::_('An error occurred while getting resume uploaded resume files'); ?>");
            }
        });
    }
    function getAllResumeFiles(resumeid) {
        jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=resume&task=getallresumefiles', {resumeid: resumeid}, function (data) {
            if (!data) {
                alert("<?php echo Text::_('An error occurred while getting resume uploaded resume files'); ?>");
            }
        });
    }
</script>
