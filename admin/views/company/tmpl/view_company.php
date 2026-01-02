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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

?>

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
                <input type="hidden" name="c" value="company" />
                <input type="hidden" name="view" value="company" />
                <input type="hidden" name="layout" value="view_company" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
            </form>
            <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform" >
                <?php
                $trclass = array("row0", "row1");
                $i = 0;
                $isodd = 1;
                ?>
                <tr class="row1" height="25"><td width="5">&nbsp;</td>
                    <td colspan="2" align="center" class="maintext"><font size="+1"><strong><?php echo $this->company->name; ?></strong></font></td>
                </tr>
                <tr> <td colspan="3" height="1"></td> </tr>
                <tr height="200" class="row1"><td ></td>
                    <td width="200" valign="top">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="admintable">
                            <tr class="row1">
                                <td height="210" style="max-width:200px;max-height:20px;overflow:hidden;text-overflow:ellipsis" align="center">
                                    <div style="max-width: 200px;max-height:200px;">

                                        <?php if ($this->company->logoisfile != -1) { ?>
                                            <img  width="200"  src="../<?php echo $this->config['data_directory']; ?>/data/employer/comp_<?php echo $this->company->id; ?>/logo/<?php echo $this->company->logofilename; ?>" />
                                        <?php } else { ?>
                                            <img width="200" height="54" src="../components/com_jsjobs/images/blank_logo.png" />
                                        <?php } ?>
                                    </div>

                                </td>
                            </tr>
                        </table>	
                    </td>
                    <td valign="top">
                        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="adminform">
                            <tr> <td colspan="3" height="1"></td> </tr>
                            <?php if ($this->company->url) { ?>
                                <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td width="7"></td>
                                <td class="maintext"><b><?php echo Text::_('Website'); ?></b></td>
                                <td class="maintext"><?php echo $this->company->url; ?></td>
                    </tr>
                <?php } ?>
                <?php if ($this->company->contactname) { ?>
                    <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
                    <td class="maintext"><b><?php echo Text::_('Contact Name'); ?></b></td>
                    <td class="maintext"><?php echo $this->company->contactname; ?></td>
        </tr>
    <?php } ?>
    <?php if ($this->company->contactemail) { ?>
        <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
        <td class="maintext"><b><?php echo Text::_('Contact Email'); ?></b></td>
        <td class="maintext"><?php echo $this->company->contactemail; ?></td>
    </tr>
<?php } ?>
<tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
<td class="maintext"><b><?php echo Text::_('Contact Phone'); ?></b></td>
<td class="maintext"><?php echo $this->company->contactphone; ?></td>
</tr>
<?php if ($this->company->address1) { ?>
    <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
    <td class="maintext"><b><?php echo Text::_('Address 1'); ?></b></td>
    <td class="maintext"><?php echo $this->company->address1; ?></td>
    </tr>
<?php } ?>
<?php if ($this->company->address2) { ?>
    <tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
    <td class="maintext"><b><?php echo Text::_('Address 2'); ?></b></td>
    <td class="maintext"><?php echo $this->company->address2; ?></td>
    </tr>
<?php } ?>
<tr class="<?php echo $trclass[$isodd]; ?>"></tr><td></td>
<td class="maintext"><b><?php echo Text::_('Location'); ?></b></td>
<td class="maintext">
    <?php
    if ($this->company->multicity != '')
        echo $this->company->multicity;$comma = 1;
    if ($this->company->zipcode) {
        if ($comma)
            echo ', ';
        else
            $comma = 1;
        echo $this->company->zipcode;
    }
    ?>
</td>
</tr>
<tr> <td colspan="3" height="1"></td> </tr>
</table>
</td>
</tr>
<tr> <td colspan="3" height="1"></td> </tr>
<?php
$trclass = array("row0", "row1");
$i = 0;
$isodd = 0;
foreach ($this->fieldsordering as $field) {
    switch ($field->field) {
        case "jobcategory": $isodd = 1 - $isodd;
            ?>
            <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                <td class="maintext"><b><?php echo Text::_('Categories'); ?></b></td>
                <td class="maintext"><?php echo $this->company->cat_title; ?></td>
            </tr>
            <tr> <td colspan="3" height="1"></td> </tr>
            <?php
            break;
        case "contactphone": $isodd = 1 - $isodd;
            ?>
            <?php if ($field->published == 1) { ?>
                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                    <td class="maintext"><b><?php echo Text::_('Contact Phone'); ?></b></td>
                    <td class="maintext"><?php echo $this->company->contactphone; ?></td>
                </tr>
                <tr> <td colspan="3" height="1"></td> </tr>
            <?php } ?>
            <?php
            break;
        case "contactfax": $isodd = 1 - $isodd;
            ?>
            <?php if ($field->published == 1) { ?>
                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                    <td class="maintext"><b><?php echo Text::_('Contact Fax'); ?></b></td>
                    <td class="maintext"><?php echo $this->company->companyfax; ?></td>
                </tr>
                <tr> <td colspan="3" height="1"></td> </tr>
            <?php } ?>
            <?php
            break;
        case "since": $isodd = 1 - $isodd;
            ?>
            <?php if ($field->published == 1) { ?>
                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                    <td class="maintext"><b><?php echo Text::_('Since'); ?></b></td>
                    <?php
                    if(strpos($this->company->since , '1970') !== false){
                        $since = "";
                    } else {
                        $since = HTMLHelper::_('date', $this->company->since, $this->config['date_format']);
                    }
                    ?>
                    <td class="maintext"><?php echo $since; ?></td>
                </tr>
                <tr> <td colspan="3" height="1"></td> </tr>
            <?php } ?>
            <?php
            break;
        case "companysize": $isodd = 1 - $isodd;
            ?>
            <?php if ($field->published == 1) { ?>
                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                    <td class="maintext"><b><?php echo Text::_('Company Size'); ?></b></td>
                    <td class="maintext"><?php echo $this->company->companysize; ?></td>
                </tr>
                <tr> <td colspan="3" height="1"></td> </tr>
            <?php } ?>
            <?php
            break;
        case "income": $isodd = 1 - $isodd;
            ?>
            <?php if ($field->published == 1) { ?>
                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                    <td class="maintext"><b><?php echo Text::_('Income'); ?></b></td>
                    <td class="maintext"><?php echo $this->company->income; ?></td>
                </tr>
                <tr> <td colspan="3" height="1"></td> </tr>
            <?php } ?>
            <?php
            break;
        case "description": $isodd = 1 - $isodd;
            ?>
            <?php if ($field->published == 1) { ?>
                <tr class="<?php echo $trclass[$isodd]; ?>"><td></td>
                    <td class="maintext"><b><?php echo Text::_('Description'); ?></b></td>
                    <td class="maintext"><?php echo $this->company->description; ?></td>
                </tr>

            <?php } ?>
    <?php } ?>	
<?php } ?>	

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
