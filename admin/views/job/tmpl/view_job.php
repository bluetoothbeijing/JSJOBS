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

$document = Factory::getDocument();
?>

<style type="text/css">
    div#map_container{width:100%;height:350px;}
</style>


<table width="100%">
    <tr>
        <td align="left" width="175" valign="top">
            <table width="100%"><tr><td style="vertical-align:top;">
                        <?php
                        include_once('components/com_jsjobs/views/menu.php');
                        ?>
                    </td>
                </tr></table>
        </td>
        <td width="100%" valign="top">
            <form action="index.php" method="POST" name="adminForm" id="adminForm">
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
            </form>
            <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform" >
                <?php
                $trclass = array("row0", "row1");
                $i = 0;
                $isodd = 1;

                foreach ($this->fieldsordering as $field) {
                    switch ($field->field) {
                        case "jobtitle": $isodd = 1 - $isodd;
                            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td width="5%">&nbsp;</td>
                                <td width="30%"><b><?php echo Text::_('Title'); ?></b></td>
                                <td><?php echo $this->job->title; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
                            <?php
                            break;
                        case "company": $isodd = 1 - $isodd;
                            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Company'); ?></b></td>
                                <td><?php echo $this->job->companyname; ?></td>
                            </tr>
                            <tr> <td colspan="3" height="1"></td> </tr>
                            <?php
                            break;
                        case "department": $isodd = 1 - $isodd;
                            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_(' Department'); ?></b></td>
                                <td>
            <?php echo $this->job->departmentname; ?>
                                </td>
                            </tr>
                            <tr> <td colspan="3" height="1"></td> </tr>
                            <?php
                            break;

                        case "video": $isodd = 1 - $isodd;
                            ?>
            <?php if ($this->job->video == 1) { ?>
                                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                    <td><b><?php echo Text::_('Video'); ?></b></td>
                                    <td>
                                        <iframe title="YouTube video player" width="480" height="390" 
                                                src="http://www.youtube.com/embed/<?php echo $this->job->video; ?>" frameborder="0" allowfullscreen>
                                        </iframe>

                                    </td>
                                </tr>
                                <tr> <td colspan="3" height="1"></td> </tr>
                            <?php } ?>
                            <?php
                            break;
                        case "map": $isodd = 1 - $isodd;
                            ?>
            <?php //if ($this->job->map == 1) {   ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2">
                                    <div id="map"><div id="map_container"></div></div>
                                </td>
                            </tr>
                            <input type="hidden" id="longitude" name="longitude" value="<?php if (isset($this->job)) echo $this->job->longitude; ?>"/>
                            <input type="hidden" id="latitude" name="latitude" value="<?php if (isset($this->job)) echo $this->job->latitude; ?>"/>
                            <tr> <td colspan="3" height="1"></td> </tr>
                            <?php //}  ?>

            <?php
            break;
        case "jobcategory": $isodd = 1 - $isodd;
            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Category'); ?></b></td>
                                <td><?php echo $this->job->cat_title; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
            <?php
            break;
        case "jobtype": $isodd = 1 - $isodd;
            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Job Type'); ?></b></td>
                                <td><?php echo $this->job->jobtypetitle; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td> </tr>
            <?php
            break;
        case "jobstatus": $isodd = 1 - $isodd;
            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Job Status'); ?></b></td>
                                <td><?php echo $this->job->jobstatustitle; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td> </tr>
            <?php
            break;
        case "jobshift": $isodd = 1 - $isodd;
            ?>
                            <?php if ($field->published == 1) { ?>
                                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                    <td><b><?php echo Text::_('Shift'); ?></b></td>
                                    <td><?php echo $this->job->shifttitle; ?></td>
                                </tr>
                                <tr><td colspan="3" height="1"></td> </tr>
            <?php } ?>
            <?php
            break;
        case "jobsalaryrange":
            ?>
                                    <?php if ($field->published == 1) { ?>
                                        <?php
                                        if ($this->job->hidesalaryrange != 1) { // show salary 
                                            $isodd = 1 - $isodd;
                                            ?>
                                    <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                        <td><b><?php echo Text::_('Salary Range'); ?></b></td>
                                        <td><?php
                                                $salaryrange = '';
                                                if ($this->job->salaryfrom)
                                                    $salaryrange =  $this->job->salaryfrom;
                                                if ($this->job->salaryto)
                                                    $salaryrange .=  ' - ' . $this->job->salaryto;
                                                if($this->config['currency_align'] == 1){
                                                    $salaryrange = $this->job->symbol . ' '.$salaryrange;
                                                }elseif($this->config['currency_align'] == 2){
                                                    $salaryrange .= ' '.$this->job->symbol;
                                                }
                                                if ($this->job->salarytype)
                                                    $salaryrange .= ' ' . $this->job->salarytype;
                                                echo $salaryrange; 
                                    ?></td>
                                    </tr>
                                    <tr><td colspan="3" height="1"></td></tr>
                                    <?php } ?>
                                <?php } ?>
                                <?php
                                break;
                            case "heighesteducation": $isodd = 1 - $isodd;
                                ?>
                                <?php if ($field->published == 1) { ?>
                                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                    <?php
                                    if ($this->job->iseducationminimax == 1) {
                                        if ($this->job->educationminimax == 1)
                                            $title = Text::_('Minimum Education');
                                        else
                                            $title = Text::_('Maximum Education');
                                        $educationtitle = $this->job->educationtitle;
                                    }else {
                                        $title = Text::_('Education');
                                        $educationtitle = $this->job->mineducationtitle . ' - ' . $this->job->maxeducationtitle;
                                    }
                                    ?>
                                    <td><b><?php echo $title; ?></b></td>
                                    <td><?php echo $educationtitle; ?></td>
                                </tr>
                                <tr><td colspan="3" height="1"></td> </tr>
                <?php $isodd = 1 - $isodd; ?>
                                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                    <td><b><?php echo Text::_('Degree Title'); ?></b></td>
                                    <td><?php echo $this->job->degreetitle; ?></td>
                                </tr>
                                <tr><td colspan="3" height="1"></td> </tr>
                            <?php } ?>
                            <?php
                            break;
                        case "noofjobs": $isodd = 1 - $isodd;
                            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Number Of Jobs'); ?></b></td>
                                <td><?php echo $this->job->noofjobs; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
                                <?php
                                break;
                            case "experience": $isodd = 1 - $isodd;
                                ?>
                                <?php if ($field->published == 1) { ?>
                                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                    <?php
                                    if ($this->job->isexperienceminimax == 1) {
                                        if ($this->job->experienceminimax == 1)
                                            $title = Text::_('Minimum Experience');
                                        else
                                            $title = Text::_('Maximum Experience');
                                        $experiencetitle = $this->job->experiencetitle;
                                    }else {
                                        $title = Text::_('Experience');
                                        $experiencetitle = $this->job->minexperiencetitle . ' - ' . $this->job->maxexperiencetitle;
                                    }
                                    if ($this->job->experiencetext)
                                        $experiencetitle .= ' (' . $this->job->experiencetext . ')';
                                    ?>
                                    <td><b><?php echo $title; ?></b></td>
                                    <td><?php echo $experiencetitle; ?>
                                    </td>
                                </tr>
                                <tr><td colspan="3" height="1"></td></tr>
                            <?php } ?>
                            <?php
                            break;
                        case "duration": $isodd = 1 - $isodd;
                            ?>
            <?php if ($field->published == 1) { ?>
                                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                    <td><b><?php echo Text::_('Duration'); ?></b></td>
                                    <td><?php echo $this->job->duration; ?>
                                    </td>
                                </tr>
                                <tr><td colspan="3" height="1"></td></tr>
                            <?php } ?>
            <?php
            break;
        case "startpublishing": $isodd = 1 - $isodd;
            ?>
                            <?php //if ($vj == '1'){ //my jobs  ?> 
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Start Publishing'); ?></b></td>
                                <td><?php echo HTMLHelper::_('date', $this->job->startpublishing, $this->config['date_format']); ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
            <?php //}   ?>
            <?php
            break;
        case "stoppublishing": $isodd = 1 - $isodd;
            ?>
                            <?php //if ($vj == '1'){ //my jobs  ?> 
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Stop Publishing'); ?></b></td>
                                <td><?php echo HTMLHelper::_('date', $this->job->stoppublishing, $this->config['date_format']); ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
            <?php //}   ?>
            <?php
            break;
        case "agreement": $isodd = 1 - $isodd;
            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><b><?php echo Text::_('Agreement'); ?></b></td>
                            </tr>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><?php echo $this->job->agreement; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
            <?php
            break;
        case "qualifications": $isodd = 1 - $isodd;
            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><b><?php echo Text::_('Qualifications'); ?></b></td>
                            </tr>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><?php echo $this->job->qualifications; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
            <?php
            break;
        case "description": $isodd = 1 - $isodd;
            ?>
                            <tr  class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><b><?php echo Text::_('Description'); ?></b></td>
                            </tr>
                            <tr  class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><?php echo $this->job->description; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
                            <?php
                            break;
                        case "prefferdskills": $isodd = 1 - $isodd;
                            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><b><?php echo Text::_('Preferred Skills'); ?></b></td>
                            </tr>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td colspan="2"><?php echo $this->job->prefferdskills; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>
                            <?php
                            break;
                        case "city": $isodd = 1 - $isodd;
                            ?>
                            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                                <td><b><?php echo Text::_('Location'); ?></b></td>
                                <td><?php if ($this->job->multicity != '') echo $this->job->multicity; ?></td>
                            </tr>
                            <tr><td colspan="3" height="1"></td></tr>


                    <?php } ?>	
                <?php } ?>	


                <?php
                if (isset($this->userfields)) {
                    foreach ($this->userfields as $ufield) {
                        if ($ufield[0]->published == 1) {
                            $isodd = 1 - $isodd;
                            $userfield = $ufield[0];
                            echo '<tr class="' . $trclass[$isodd] . '">';
                            echo '<td></td>';
                            echo '<td >' . $userfield->title . '</td>';
                            if ($userfield->type == "checkbox") {
                                if (isset($ufield[1])) {
                                    $fvalue = $ufield[1]->data;
                                    $userdataid = $ufield[1]->id;
                                } else {
                                    $fvalue = "";
                                    $userdataid = "";
                                }
                                if ($fvalue == '1')
                                    $fvalue = "True";
                                else
                                    $fvalue = "false";
                            }elseif ($userfield->type == "select") {
                                if (isset($ufield[2])) {
                                    $fvalue = $ufield[2]->fieldtitle;
                                    $userdataid = $ufield[2]->id;
                                } else {
                                    $fvalue = "";
                                    $userdataid = "";
                                }
                            } else {
                                if (isset($ufield[1])) {
                                    $fvalue = $ufield[1]->data;
                                    $userdataid = $ufield[1]->id;
                                } else {
                                    $fvalue = "";
                                    $userdataid = "";
                                }
                            }
                            //								echo '<td>'.$fvalue.'</td>';	
                            echo '<td >' . $fvalue . '</td>';
                            echo '</tr>';
                        }
                    }
                }
                ?>
            </table>	
        </td>
    </tr>
</table>							
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
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; ?>
<script type="text/javascript" src="<?php echo $protocol; ?>maps.googleapis.com/maps/api/js?key=<?php echo $this->config['google_map_api_key']; ?>"></script>
<script type="text/javascript">
    window.onload = loadMap();
    function loadMap() {
        var latedit = [];
        var longedit = [];
        var longitude = document.getElementById('longitude').value;
        var latitude = document.getElementById('latitude').value;
        latedit = latitude.split(",");
        longedit = longitude.split(",");
        if (latedit != '' && longedit != '') {
            for (var i = 0; i < latedit.length; i++) {
                var latlng = new google.maps.LatLng(latedit[i], longedit[i]);
                zoom = 4;
                var myOptions = {
                    zoom: zoom,
                    center: latlng,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                if (i == 0)
                    var map = new google.maps.Map(document.getElementById("map_container"), myOptions);
                /*var lastmarker = new google.maps.Marker({
                 postiion:latlng,
                 map:map,
                 });*/
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    visible: true,
                });
                marker.setMap(map);
            }
        }
    }
</script>

