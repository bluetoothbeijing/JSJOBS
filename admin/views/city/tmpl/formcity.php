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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

jimport('joomla.html.pane');
HTMLHelper::_('behavior.formvalidator');

?>

<script type="text/javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'city.save') {
                returnvalue = validate_form(document.adminForm);
            } else
                returnvalue = true;
            if (returnvalue) {
                Joomla.submitform(task);
                return true;
            } else
                return false;
        }
    }
    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php echo Factory::getSession()->getFormToken(); ?>';//send token
        }
        else {
            alert("<?php echo Text::_("Some values are not acceptable").'. '.Text::_("Please retry"); ?>");
            return false;
        }
        return true;
    }
</script>

<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>    
    <div id="jsjobs-content">
        <div class="dashboard">
            <div id="jsjobs-wrapper-top-left">
                <div id="jsjobs-breadcrunbs">
                    <ul>
                        <li>
                            <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel" title="<?php echo Text::_('Dashboard'); ?>">
                                <?php echo Text::_('Dashboard'); ?>
                            </a>
                        </li>
                        <li>
                            <a href="index.php?option=com_jsjobs&c=city&view=city&layout=cities&sd=<?php echo $this->stateid; ?>&ct=<?php echo $this->countryid; ?>" title="<?php echo Text::_('Cities'); ?>">
                                <?php echo Text::_('Cities'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                            if (isset($this->city->id)){
                                echo Text::_('Edit City');
                            }else{
                                echo Text::_('Add City');
                            } ?>
                        </li>
                    </ul>
                    </ul>
                </div>
            </div>
            <div id="jsjobs-wrapper-top-right">
                <div id="jsjobs-config-btn">
                    <a href="index.php?option=com_jsjobs&c=configuration&view=configuration&layout=configurations" title="<?php echo Text::_('Configuration'); ?>">
                        <img alt="<?php echo Text::_('Configuration'); ?>" src="components/com_jsjobs/include/images/icon/config.png" />
                    </a>
                </div>
                <div id="jsjobs-help-btn" class="jsjobs-help-btn">
                    <a href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=help" title="<?php echo Text::_('Help'); ?>">
                        <img alt="<?php echo Text::_('Help'); ?>" src="components/com_jsjobs/include/images/help-page/help.png" />
                    </a>
                </div>
                <div id="jsjobs-vers-txt">
                    <?php echo Text::_("Version").' :'; ?>
                    <span class="jsjobs-ver">
                        <?php
                        $version1 = $this->getJSModel('configuration')->getConfigByFor('default');
                        $version = str_split($version1['version']);
                        $version = implode('', $version);
                        echo $version?>
                    </span>
                </div>
            </div>
        </div>
        <div id="jsjobs-head">
            <h1 class="jsjobs-head-text">
                <?php
                if (isset($this->city->id)){
                    echo Text::_('Edit City');
                }else{
                    echo Text::_('Add City');
                } ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
        <form action="index.php" method="POST" name="adminForm" id="adminForm" >
            <div class="js-form-area">
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="name"><?php echo Text::_('Title'); ?></label>
                    <div class="jsjobs-value"><?php echo $this->list['states']; ?></div>
                </div>

                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="name"><?php echo Text::_('Name'); ?></label>
                    <div class="jsjobs-value"><input class="inputbox required" type="text" name="name" id="name" size="40" maxlength="255" value="<?php if (isset($this->city)) echo $this->city->name; ?>" />
                        <small><a href="#" onclick="loadLatLng();"><?php echo Text::_("Show Map"); ?></a></small>
                    </div>
                </div>
                <div id="js-form-wrapper">
                    <label class="jsjobs-title" for="name"></label>
                    <div class="jsjobs-value">
                        <div id="googlemapcontainer"></div>
                    </div>
                </div>
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="latitude"><?php echo Text::_('Latitude'); ?></label>
                    <div class="jsjobs-value"><input class="inputbox" type="text" name="latitude" id="latitude" size="40" maxlength="255" value="<?php if (isset($this->city)) echo $this->city->latitude; ?>" /></div>
                </div>
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="longitude"><?php echo Text::_('Longitude'); ?></label>
                    <div class="jsjobs-value"><input class="inputbox" type="text" name="longitude" id="longitude" size="40" maxlength="255" value="<?php if (isset($this->city)) echo $this->city->longitude; ?>" /></div>
                </div>
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="status"><?php echo Text::_('Status'); ?></label>
                    <div class="jsjobs-value"><div class="div-white"><span class="js-cross"><input type="checkbox" name="enabled" id="status" value="1" <?php
                                                     if (isset($this->city)) {
                                                         if ($this->city->enabled == '1')
                                                             echo 'checked';
                                                     }
?>/></span> <label class="js-publish" for="status" ><?php echo Text::_('Publish'); ?></label>
                                                     </div>
                    </div>
                </div>

               <input type="hidden" name="id" value="<?php if (isset($this->city)) echo $this->city->id; ?>" />
<?php if (isset($this->city->id) AND ( $this->city->id != 0)) { ?>
                    <input type="hidden" name="isedit" value="1" />
<?php } ?>
                <input type="hidden" name="check" value="" />
                <input type="hidden" name="task" value="city.savecity" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" id="countryname" value="<?php echo $this->getJSModel('country')->getCountryById($this->countryid)->name; ?>" />
                <input type="hidden" id="edit_latitude" value="<?php if (isset($this->city)) echo $this->city->latitude; ?>" />
                <input type="hidden" id="edit_longitude" value="<?php if (isset($this->city)) echo $this->city->longitude; ?>" />
                <?php echo HTMLHelper::_( 'form.token' ); ?>        
            </div>
            <div class="js-buttons-area">
                <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save City'); ?>" />
                <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=city&view=city&layout=cities&ct=97"><?php echo Text::_('Cancel'); ?></a>
            </div>
        </form>
    </div>
    </div>
</div>
<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
?>    
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<script type="text/javascript">

var map,marker;

function loadLatLng(){

    if(!map){        

        jQuery("#js-form-wrapper").addClass("js-form-wrapper");
        jQuery("#googlemapcontainer").css("height","200px");

        var edit_latitude = jQuery("#edit_latitude").val();
        var edit_longitude = jQuery("#edit_longitude").val();
        var cityname = jQuery("#name").val().trim();

        if(edit_latitude != '' && edit_longitude != ''){
            renderMap(new google.maps.LatLng(edit_latitude,edit_longitude));
            addMarker(new google.maps.LatLng(edit_latitude,edit_longitude));
        }else if(cityname != ''){
            if(jQuery("#stateid").val() != '')
                cityname += ', '+jQuery("#stateid option:selected").text();
            cityname += ', '+jQuery("#countryname").val();

            var geocoder =  new google.maps.Geocoder();
            geocoder.geocode( { 'address': cityname}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var n_latitude = results[0].geometry.location.lat();
                    var n_longitude = results[0].geometry.location.lng();
                    renderMap(new google.maps.LatLng(n_latitude,n_longitude));
                } else {
                    renderMap();
                }
            });
        }else{
            renderMap();
        }
    }
}

function renderMap(latlng){
    if(!latlng)
        latlng = new google.maps.LatLng("<?php echo $this->config['default_latitude'] ?>","<?php echo $this->config['default_longitude'] ?>");
    var myOptions = {
        zoom: 9,
        center: latlng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("googlemapcontainer"), myOptions);
    google.maps.event.addListener(map, "click", function (e) {
        var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({'latLng': latLng}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                addMarker(results[0].geometry.location);
            } else {
                alert("Geocode was not successful for the following reason: " + status);
                jQuery("#latitude").val('');
                jQuery("#longitude").val('');
            }
        });
    });
}

function addMarker(latlang){
    if(marker){
        marker.setMap(null);
        marker = null;
    }
    marker = new google.maps.Marker({
        position: latlang,
        map: map
    });
    marker.setMap(map);
    map.setCenter(latlang);
    jQuery("#latitude").val(latlang.lat());
    jQuery("#longitude").val(latlang.lng());
}

</script>
<div id="jsjobsfooter">
    <table width="100%" style="table-layout:fixed;">
        <tr><td height="15"></td></tr>
        <tr>
            <td style="vertical-align:top;" align="center">
                <a class="img" target="_blank" href="https://www.joomsky.com"><img src="https://www.joomsky.com/logo/jsjobscrlogo.png"></a>
                <br>
                Copyright &copy; 2008 - <?php echo  date('Y') ?> ,
                <span id="themeanchor"> <a class="anchor"target="_blank" href="https://www.burujsolutions.com">Buruj Solutions </a></span>
            </td>
        </tr>
    </table>
</div>



