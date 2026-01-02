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
use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Router\Route;    

jimport('joomla.html.pane');

$conf   = Factory::getConfig();
$editor = Editor::getInstance($conf->get('editor'));


$document = Factory::getDocument();
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet('components/com_jsjobs/js/chosen/chosen.min.css');
$document->addStyleSheet('components/com_jsjobs/css/token-input-jsjobs.css');
if (JVERSION < 3) {
    HTMLHelper::_('behavior.mootools');
    $document->addScript('components/com_jsjobs/js/jquery.js');
} else {
    HTMLHelper::_('bootstrap.framework');
    HTMLHelper::_('jquery.framework');
}
$document->addScript('components/com_jsjobs/js/chosen/chosen.jquery.min.js');
$document->addScript('components/com_jsjobs/js/jquery.tokeninput.js');

//HTMLHelper::_('behavior.calendar'); 
$width_big = 40;
$width_med = 25;
$width_sml = 15;

if ($this->config['date_format'] == 'm/d/Y')
    $dash = '/';
else
    $dash = '-';
$dateformat = $this->config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
?>
<div id="js_jobs_main_wrapper">
<div id="js_menu_wrapper">
    <?php
    if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
        foreach ($this->jobseekerlinks as $lnk) {
            ?>                     
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    if (is_array($this->employerlinks) && sizeof($this->employerlinks) != 0) {
        foreach ($this->employerlinks as $lnk) {
            ?>
            <a class="js_menu_link <?php if ($lnk[2] == 'login' || $lnk[2] == 'logout') echo 'js_menu_right_link';  if ($lnk[2] == 'job') echo 'selected'; ?>" href="<?php echo $lnk[0]; ?>"><?php echo $lnk[1]; ?></a>
            <?php
        }
    }
    ?>
</div>
<?php

$hidemapscript = false;
if ($this->config['offline'] == '1') {
    $this->jsjobsmessages->getSystemOfflineMsg($this->config);
} else {
    ?>
    <div id="jsjobs-main-wrapper">
        <span class="jsjobs-main-page-title"><?php echo Text::_('Search Job'); ?></span>
        <?php if ($this->config['cur_location'] == '1') { ?>
            <div class="jsjobs-breadcrunbs-wrp">
                <div id="jsjobs-breadcrunbs">
                    <ul>
                        <li>
                            <?php
                                if (is_array($this->jobseekerlinks) && sizeof($this->jobseekerlinks) != 0) {
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
                            <?php echo Text::_('Search Job'); ?>
                        </li>
                    </ul>
                </div>
            </div>
        <?php } ?>
    <?php
    if ($this->myjobsearch_allowed == VALIDATE) {
        $defaultradius = $this->config['defaultradius'];
        ?>

            <form action="<?php echo Route::_('index.php?option=com_jsjobs&c=job&view=job&layout=jobs&Itemid=' . $this->Itemid ,false); ?>" method="post" name="adminForm" id="adminForm" class="jsautoz_form" >

                <div class="jsjobs-field-main-wrapper">
                <input type="hidden" name="issearchform" value="1" />
                <?php
                $customfieldobj = getCustomFieldClass();
                $fieldsordering = $this->getJSModel('fieldsordering')->getFieldsOrderingForSearchByFieldFor(2);
                foreach ($fieldsordering as $field) {
                    switch ($field->field) {
                        case 'metakeywords': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>

                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="metakeywords" size="40" maxlength="100"  />
                                    </div>
                                </div>                
                            <?php 
                            break;
                        case 'jobtitle': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="jobtitle" size="40" maxlength="255"  />
                                    </div>
                                </div>
                            <?php                                
                            break;
                        case 'company': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['company']; ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'jobcategory': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['category']; ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'subcategory': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue" id="fj_subcategory">
                                        <?php echo  $this->searchoptions['jobsubcategory']; ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'careerlevel': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['careerlevel']; ?>
                                    </div>
                                </div>
                            <?php 
                            break;
                        case 'jobshift': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['shift']; ?>
                                    </div>
                                </div>
                            <?php 
                            break;
                        case 'gender': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['gender']; ?>
                                    </div>
                                </div>
                            <?php  
                            break;
                        case 'jobtype': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['jobtype']; ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'jobstatus': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['jobstatus']; ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'zipcode': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="zipcode" size="40" maxlength="100"  />
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'startpublishing': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo HTMLHelper::_('calendar', '', 'startpublishing', 'startpublishing', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19')); ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'stoppublishing': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo HTMLHelper::_('calendar', '', 'stoppublishing', 'stoppublishing', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlenght' => '19')); ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'jobsalaryrange': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <span class="jsjobs-salaryrange-value"><?php echo $this->searchoptions['currencyid']; ?></span>
                                        <span class="jsjobs-salaryrange-value"><?php echo $this->searchoptions['srangestart']; ?></span>
                                        <span class="jsjobs-salaryrange-value"><?php echo $this->searchoptions['srangeend']; ?></span>
                                        <span class="jsjobs-salaryrange-value"><?php echo $this->searchoptions['srangetype']; ?></span>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'workpermit': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['workpermit']; ?>
                                    </div>
                                </div>
                            <?php                             
                            break;
                        case 'heighesteducation': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['education']; ?>
                                    </div>
                                </div>
                            <?php                             
                            break;
                        case 'experience': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['experiencemin'].' '.$this->searchoptions['experiencemax']; ?>
                                    </div>
                                </div>
                            <?php 
                            break;
                        case 'requiredtravel': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['requiredtravel']; ?>
                                    </div>
                                </div>
                            <?php 
                            break;
                        case 'city': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue" id="city">
                                        <input type="text" name="city" size="40" id="searchcity"  value="" />
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'map':
                            $hidemapscript = true;
                             ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue search_map">
                                        <div id="map">
                                            <div id="map_container"></div>
                                        </div>
                                        <div class="jsjobs-fieldwrapper">
                                        
                                        <span class="jsjobs-longitude">
                                            <span class="jsjobs-longitude-title"><?php echo Text::_('Latitude'); ?></span>
                                            <input type="text" id="latitude" name="latitude" value=""/>
                                        </span>
                                        <span class="jsjobs-longitude">
                                            <span class="jsjobs-longitude-title"><?php echo Text::_('Longitude'); ?></span>
                                            <input type="text" id="longitude" name="longitude" value=""/>
                                        </span>
                                        </div>
                                    </div>
                                </div>                      
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_('Coordinates Radius'); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input type="text" id="radius" name="radius" value=""/>
                                    </div>
                                </div>                      
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_('Radius Length Type'); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <?php echo $this->searchoptions['radiuslengthtype']; ?>
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        case 'duration': ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($field->fieldtitle); ?>
                                    </div>
                                    <div class="jsjobs-fieldvalue">
                                        <input class="inputbox" type="text" name="duration" size="10" maxlength="15"  />
                                    </div>
                                </div>                      
                            <?php 
                            break;
                        default:
                            $params = null;
                            $style='';
                            if($field->userfieldtype == 'checkbox' || $field->userfieldtype == 'radio'){
                                $style='js-searchform-customfield-wrp'; 
                            }
                            if($field->userfieldtype == 'multiple'){
                                $style='js-searchform-multiselect'; 
                            }
                            $k = 1;
                            $f_res = $customfieldobj->formCustomFieldsForSearch($field, $k, $params , 1);
                            if($f_res){ ?>
                                <div class="jsjobs-fieldwrapper">
                                    <div class="jsjobs-fieldtitle">
                                        <?php echo Text::_($f_res['title']); ?>
                                    </div>
                                    <?php 
                                        if($field->userfieldtype == 'checkbox' || $field->userfieldtype == 'radio'){ ?>
                                            <div class="jsjobs-fieldvalue js-searchform-customfield-wrp">
                                        <?php }
                                        else if($field->userfieldtype == 'multiple'){ ?>
                                            <div class="jsjobs-fieldvalue  js-searchform-multiselect"> 
                                        <?php }
                                        else{ ?>
                                            <div class="jsjobs-fieldvalue">
                                        <?php }
                                    ?>
                                    
                                        <?php echo $f_res['field']; ?>
                                    </div>
                                </div>
                                <?php
                            } 
                            break;
                    }
                }
                 ?>
                <div class="fieldwrapper-btn">  
                    <input type="submit" id="button" class="button jsjobs_button" name="submit_app" onClick="return checkmapcooridnate(); " value="<?php echo Text::_('Search Job'); ?>"/>
                </div>
                <input type="hidden" name="c" value="job" />
                <input type="hidden" name="view" value="job" />
                <input type="hidden" name="layout" value="jobs" />
                <input type="hidden" name="uid" value="<?php echo $this->uid; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task11" value="view" />
                <input type="hidden" id="default_longitude" name="default_longitude" value="<?php echo $this->config['default_longitude']; ?>"/>
                <input type="hidden" id="default_latitude" name="default_latitude" value="<?php echo $this->config['default_latitude']; ?>"/>
                 
             </div>
            </form>
        <?php
    } else {
        switch ($this->myjobsearch_allowed) {
            case JOB_SEARCH_NOT_ALLOWED_IN_PACKAGE:
                $link = "index.php?option=com_jsjobs&c=jobseekerpackages&view=jobseekerpackages&layout=packages&Itemid=".$this->Itemid;
                $this->jsjobsmessages->getPackageExpireMsg('Job search not allowed in the package', 'Job search not allowed in your package please purchase a package which has this option', $link);
                break;
            case EMPLOYER_NOT_ALLOWED_JOBSEEKER_PRIVATE_AREA:
                $this->jsjobsmessages->getAccessDeniedMsg('Employer not allowed', 'Employer is not allowed in jobseeker private area', 0);
                break;
            case EMPLOYER_NOT_ALLOWED_JOBSEARCH:
                $this->jsjobsmessages->getAccessDeniedMsg('Job search is not allowed to the employer', 'Job search is not allowed to the employer', 0);
                break;
            case USER_ROLE_NOT_SELECTED:
                $link = "index.php?option=com_jsjobs&c=common&view=common&layout=new_injsjobs&Itemid=".$this->Itemid;
                $vartext = Text::_('You do not select your role').','.Text::_('Please select your role');
                $this->jsjobsmessages->getUserNotSelectedMsg('You do not select your role',$vartext, $link);
                break;
            case VISITOR_NOT_ALLOWED_JOBSEARCH:
                $this->jsjobsmessages->getAccessDeniedMsg('You are not logged in', 'Visitor is not allowed in the employer private area', 1);
                break;
        }
    } ?>
    </div>
    <?php
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


</div>
<style type="text/css">
    div#map_container{
        background:#000;
        width:100%;
        height:100%;
    }
    div#map{
        height: 300px;
        width: 100%;
    }
</style>

<?php 
if($hidemapscript){ 
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
 ?>
    <script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<?php
} ?>


<script type="text/javascript">
                function fj_getsubcategories(src, val) {
                    jQuery("#" + src).html("Loading ...");
                    jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=listsubcategoriesForSearch', {val: val}, function (data) {
                        if (data) {
                            jQuery("#" + src).html(data);
                            jQuery("#" + src + " select.jsjobs-cbo").chosen();
                        } else {
                            jQuery("#" + src).html('<?php echo '<input type="text" name="jobsubcategory" value="">'; ?>');
                        }
                    });
                }

                function loadMap() {
                    var default_latitude = document.getElementById('default_latitude').value;
                    var default_longitude = document.getElementById('default_longitude').value;

                    var latitude = document.getElementById('latitude').value;
                    var longitude = document.getElementById('longitude').value;

                    if (latitude != '' && longitude != '') {
                        default_latitude = latitude;
                        default_longitude = longitude;
                    }
                    var latlng = new google.maps.LatLng(default_latitude, default_longitude);
                    zoom = 10;
                    var myOptions = {
                        zoom: zoom,
                        center: latlng,
                        scrollwheel: false,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    };
                    var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                    var lastmarker = new google.maps.Marker({
                        postiion: latlng,
                        map: map,
                    });
                   /* var marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                    });
                    marker.setMap(map);
                    lastmarker = marker;
                    document.getElementById('latitude').value = marker.position.lat();
                    document.getElementById('longitude').value = marker.position.lng();
                    */
                    lastmarker = '';

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
                                document.getElementById('latitude').value = marker.position.lat();
                                document.getElementById('longitude').value = marker.position.lng();

                            } else {
                                alert("Geocode was not successful for the following reason: " + status);
                            }
                        });
                    });
                //document.getElementById('map_container').innerHTML += "<a href='Javascript hidediv();'><?php echo Text::_('Close Map'); ?></a>";
                }
                function showdiv() {
                    document.getElementById('map').style.display = 'block';
                }
                function hidediv() {
                    document.getElementById('map').style.display = 'none';
                }
                function checkmapcooridnate() {
                    var latitude = document.getElementById('latitude').value;
                    var longitude = document.getElementById('longitude').value;
                    var radius = document.getElementById('radius').value;
                    if (latitude != '' && longitude != '') {
                        if (radius != '') {
                            this.form.submit();
                        } else {
                            alert("<?php echo Text::_("Please Enter The Coordinate Radius"); ?>");
                            return false;
                        }
                    }

                }
                jQuery(document).ready(function ($) {
                    $(".jsjob-multiselect").chosen({
                        placeholder_text_multiple : "<?php echo Text::_('Select Some Options'); ?>"
                    });
        
                    jQuery("select.jsjobs-cbo").chosen();
                    jQuery("input.jsjobs-inputbox")
                            .css({
                                'width': '192px',
                                'border': '1px solid #A9ABAE',
                                'cursor': 'text',
                                'margin': '0',
                                'padding': '4px'
                            });
                    jQuery("#searchcity").tokenInput("<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                        theme: "jsjobs",
                        preventDuplicates: true,
                        hintText: "<?php echo Text::_('Type In A Search'); ?>",
                        noResultsText: "<?php echo Text::_('No Results'); ?>",
                        searchingText: "<?php echo Text::_('Searching...'); ?>",
                    });

                    $("select#category").chosen().change(function (){
                        var category = $("select#category").val();
                        if(category){
                            jQuery.post('<?php echo Uri::root(); ?>index.php?option=com_jsjobs&c=subcategory&task=subcategoriesForSearch', {val: category}, function (data) {
                                if (data) {
                                    jQuery("select#jobsubcategory").html(data);
                                    $("select#jobsubcategory").trigger("chosen:updated");
                                }
                            });
                        }
                    });

                    <?php 
                    if($hidemapscript){  ?>
                        loadMap();
                        <?php
                     } ?>

                });
</script>
