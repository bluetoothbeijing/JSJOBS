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
$document->addStyleSheet('components/com_jsjobs/include/installer.css');
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidator');
?>
<script type="text/javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'startinstallation') {
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
            alert("Some values are not acceptable.  Please retry.");
            return false;
        }
        opendiv();
        return true;
    }
</script>

<?php
    $isgotocp = ($this->isgotocp > 0) ? true : false;
?>

<form action="index.php" method="POST" name="adminForm" id="adminForm" >
    <div class="js_installer_wrapper">
        <div class="js_header_bar"><?php echo Text::_('Js Jobs Installation'); ?></div>
        <img class="js_progress" src="components/com_jsjobs/include/images/p4.png" />
        <div class="js_message_wrapper">
            <?php
            if (!empty($this->data))
                foreach ($this->data AS $data) {
                    ?>
                    <div class="js_final_step <?php echo ($data[1] == 1) ? 'green' : 'red'; ?>"><img src="components/com_jsjobs/include/images/<?php echo ($data[1] == 1) ? 'tick.png' : 'cross.png'; ?>"/><?php echo $data[0]; ?></div>
                <?php }
            ?>
        </div>
        <div class="js_button_wrapper">
            <input class="js_next_button" type="submit" value="<?php echo Text::_('Finish'); ?>" onclick="return validate_form(document.adminForm);" />
        </div>
    </div>
    <input type="hidden" name="check" value="" />
    <?php 
    if($isgotocp){ ?>
        <input type="hidden" name="view" value="jsjobs" />
        <input type="hidden" name="layout" value="controlpanel" />
    <?php
    }else{ ?>
        <input type="hidden" name="view" value="installer" />
        <input type="hidden" name="layout" value="sampledata" />
    <?php
    }
    ?>
    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
</form>
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
