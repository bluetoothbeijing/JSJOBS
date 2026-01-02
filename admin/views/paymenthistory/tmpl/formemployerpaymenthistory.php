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
use Joomla\CMS\Editor\Editor;

jimport('joomla.html.pane');


//HTMLHelper::_('behavior.calendar');
HTMLHelper::_('behavior.formvalidator');
?>

<script language="javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'paymenthistory.save') {
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
            f.check.value = '<?php if (JVERSION < 3)
    echo JUtility::getToken();
else
    echo Factory::getSession()->getFormToken();
?>';//send token
        }
        else {
            alert("<?php echo Text::_("Some values are not acceptable").'. '.Text::_("Please retry"); ?>");
            return false;
        }
        return true;
    }
</script>

<table width="100%" >
    <tr>
        <td align="left" width="175"  valign="top">
            <table width="100%" ><tr><td style="vertical-align:top;">
<?php
include_once('components/com_jsjobs/views/menu.php');
?>
                    </td>
                </tr></table>
        </td>
        <td width="100%" valign="top" align="left">


            <form action="index.php" method="post" name="adminForm" id="adminForm"  >
                <input type="hidden" name="check" value="post"/>
                <table cellpadding="5" cellspacing="0" border="0" width="100%" class="adminform">
<?php if ($this->msg != '') { ?>
                        <tr>
                            <td colspan="2" align="center"><font color="red"><strong><?php echo Text::_($this->msg); ?></strong></font></td>
                        </tr>
                        <tr><td colspan="2" height="10"></td></tr>	
<?php } ?>
                    <tr class="row0">
                        <td valign="top" align="right"><label id="packagetitlemsg" for="packagetitle"><?php echo Text::_('Title'); ?></label>&nbsp;<font color="red">*</font></td>
                        <td><input class="inputbox required" type="text" name="packagetitle" id="packagetitle" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->packagetitle; ?>" /></td>
                    </tr>
                    <tr class="row1">
                        <td valign="top" align="right"><label id="packagepricemsg" for="packageprice"><?php echo Text::_('Price'); ?></label>&nbsp;<font color="red">*</font></td>
                        <td><input class="inputbox required validate-numeric" type="text" name="packageprice" id="packageprice" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->packageprice; ?>" /></td>
                    </tr>
                    <tr class="row0">
                        <td valign="top" align="right"><label id="discountamountmsg" for="discountamount"><?php echo Text::_('Discount'); ?></label>&nbsp;<font color="red">*</font></td>
                        <td><input class="inputbox required validate-numeric" type="text" name="discountamount" id="discountamount" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->discountamount; ?>" /></td>
                    </tr>
                    <tr class="row1">
                        <td valign="top" align="right"><label id="discountstartdate_msg" for="discountstartdate"><?php echo Text::_('Discount Start Date'); ?></label>&nbsp;</td>
                        <td><input class="inputbox " type="text" name="discountstartdate" id="discountstartdate" readonly size="10" maxlength="10" value="<?php if (isset($this->package)) echo $this->package->discountstartdate; ?>" />
                            <input type="reset" class="button" value="..." onclick="return showCalendar('discountstartdate', '%Y-%m-%d');"  /></td>

                    </tr>
                    <tr class="row0">
                        <td valign="top" align="right"><label id="discountenddate_msg" for="discountenddate"><?php echo Text::_('Discount End Date'); ?></label>&nbsp;</td>
                        <td><input class="inputbox " type="text" name="discountenddate" id="discountenddate" readonly size="10" maxlength="10" value="<?php if (isset($this->package)) echo $this->package->discountenddate; ?>" />
                            <input type="reset" class="button" value="..." onclick="return showCalendar('discountenddate', '%Y-%m-%d');"  /></td>
                    </tr>
                    <tr class="row1">
                        <td valign="top" align="right"><label id="discountmessage_msg" for="discountmessage"><?php echo Text::_('Discount Message'); ?></label>&nbsp;<font color="red">*</font></td>
                        <td><input class="inputbox required" type="text" name="discountmessage" id="discountmessage" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->discountmessage; ?>" /></td>
                    </tr>
                    <tr class="row0">
                        <td align="right"><label id="discounttypemsg" for="discounttype"><?php echo Text::_('Discount Type'); ?></label></td>
                        <td><?php echo $this->lists['type']; ?>
                        </td>

                    </tr>

                    <tr class="row1">
                        <td colspan="2" valign="top" align="center"><label id="descriptionmsg" for="description"><strong><?php echo Text::_('Description'); ?></strong></label>&nbsp;<font color="red">*</font></td>
                    </tr>

                    <tr class="row0">
                        <td colspan="2" align="center">
                            <?php
                            $conf   = Factory::getConfig();
                            $editor = Editor::getInstance($conf->get('editor'));

                            if (isset($this->package))
                                echo $editor->display('description', $this->package->description, '550', '300', '60', '20', false);
                            else
                                echo $editor->display('description', '', '550', '300', '60', '20', false);
                            ?>	
                        </td>
                    </tr>
                    <tr class="row1">
                        <td valign="top" align="right"><label id="resumeallowmsg" for="resumeallow"><?php echo Text::_('Companies Allow'); ?></label>&nbsp;</td>
                        <td><input class="inputbox " type="text" name="resumeallow" id="resumeallow" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->resumeallow; ?>" /></td>
                    </tr>
                    <tr class="row0">
                        <td valign="top" align="right"><label id="coverlettersallowmsg" for="coverlettersallow"><?php echo Text::_('Jobs Allow'); ?></label>&nbsp;</td>
                        <td><input class="inputbox " type="text" name="coverlettersallow" id="coverlettersallow" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->coverlettersallow; ?>" /></td>
                    </tr>
                    <tr class="row1">
                        <td valign="top" align="right"><label id="featuredresumemsg" for="featuredresume"><?php echo Text::_('Featured Companies'); ?></label>&nbsp;</td>
                        <td><input class="inputbox " type="text" name="featuredresume" id="featuredresume" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->featuredresume; ?>" /></td>
                    </tr>
                    <tr class="row0">
                        <td valign="top" align="right"><label id="goldresumemsg" for="goldresume"><?php echo Text::_('Gold Companies'); ?></label>&nbsp;</td>
                        <td><input class="inputbox " type="text" name="goldresume" id="goldresume" size="40" maxlength="255" value="<?php if (isset($this->package)) echo $this->package->goldresume; ?>" /></td>
                    </tr>
                    <tr class="row1">
                        <td align="right"><label id="jobsearchmsg" for="jobsearch"><?php echo Text::_('Resume Search	'); ?></label></td>
                        <td><?php echo $this->lists['yesNo']; ?></td>
                    </tr>
                    <tr class="row0">
                        <td align="right"><label id="savejobsearchmsg" for="savejobsearch"><?php echo Text::_('Save Resume Search'); ?></label></td>
                        <td><?php echo $this->lists['yesNo']; ?></td>
                    </tr>


                    <tr class="row1">
                        <td align="right"><label id="statusmsg" for="status"><?php echo Text::_('Status'); ?></label></td>
                        <td><?php echo $this->lists['status']; ?>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="2" height="5"></td>
                    <tr>
                    <tr>
                        <td colspan="2" align="center">
                            <input class="button" type="submit" onclick="return validate_form(document.adminForm)" name="submit_app" onClick="return myValidate();" value="<?php echo Text::_('Save Employer Package'); ?>" />
                        </td>
                    </tr>
                </table>


                <input type="hidden" name="id" value="<?php if (isset($this->package)) echo $this->package->id; ?>" />
                <input type="hidden" name="task" value="paymenthistory.saveemployerpaymenthistory" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <?php
                /*
                // uid was showing error for unfined variable
                <input type="hidden" name="uid" value="<?php echo $uid; ?>" />
            // admin side forms dont have itemid was showing error for unfined variable
                <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                */?>
                <?php echo HTMLHelper::_( 'form.token' ); ?>        
            </form>

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
