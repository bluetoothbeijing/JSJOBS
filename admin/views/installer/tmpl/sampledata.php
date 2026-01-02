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
    jQuery(document).ready(function () {
        jQuery(".cb-enable").click(function () {
            var parent = jQuery(this).parents('.switch');
            jQuery('.cb-disable', parent).removeClass('selected');
            jQuery(this).addClass('selected');
            jQuery('.checkbox', parent).attr('checked', true);
        });
        jQuery(".cb-disable").click(function () {
            var parent = jQuery(this).parents('.switch');
            jQuery('.cb-enable', parent).removeClass('selected');
            jQuery(this).addClass('selected');
            jQuery('.checkbox', parent).attr('checked', false);
        });
        jQuery('label[data-id="sampledata"]').click(function (e) {
            e.preventDefault();
            jQuery('div.ahmed_users').show();
        });
        jQuery('label[data-id="sampledata1"]').click(function (e) {
            e.preventDefault();
            jQuery('div.ahmed_users').hide();
        });
    });
</script>
<style>
    p.field.switch{display: inline-block;float:right;margin:0px;margin-top:-6px;}
    .cb-enable, .cb-disable, .cb-enable span, .cb-disable span { background: url(components/com_jsjobs/views/installer/tmpl/switch.gif) repeat-x; display: block; float: left; }
    .cb-enable span, .cb-disable span { line-height: 30px; display: block; background-repeat: no-repeat; font-weight: bold; }
    .cb-enable span { background-position: left -90px; padding: 0 10px; }
    .cb-disable span { background-position: right -180px;padding: 0 10px; }
    .cb-disable.selected { background-position: 0 -30px; }
    .cb-disable.selected span { background-position: right -210px; color: #fff; }
    .cb-enable.selected { background-position: 0 -60px; }
    .cb-enable.selected span { background-position: left -150px; color: #fff; }
    .switch label { cursor: pointer; }
    .switch input { display: none; }    
</style>
<form action="index.php" method="POST" name="adminForm" id="adminForm" >
    <div class="js_installer_wrapper">
        <div class="js_header_bar"><?php echo Text::_('JS Jobs Installation'); ?></div>
        <img class="js_progress" src="components/com_jsjobs/include/images/p4.png" />
        <div class="js_message_wrapper">
            <h1><?php echo Text::_('Quick Configurations'); ?></h1>
            <div class="js_final_step">
<?php echo Text::_('Enable Js Jobs'); ?>
                <p class="field switch">
                    <input type="radio" id="offline" name="offline" value="0" checked />
                    <input type="radio" id="offline" name="offline" value="1" />
                    <label for="radio1" class="cb-enable selected"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
            </div>        
            <div class="js_final_step">
<?php echo Text::_('Employer Can Register'); ?>
                <p class="field switch">
                    <input type="radio" id="showemployerlink" name="showemployerlink" value="1" checked />
                    <input type="radio" id="showemployerlink" name="showemployerlink" value="0" />
                    <label for="radio1" class="cb-enable selected"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
            </div>        
            <div class="js_final_step">
<?php echo Text::_('Employer Package Required'); ?>
                <p class="field switch">
                    <input type="radio" id="newlisting_requiredpackage" name="newlisting_requiredpackage" value="1" checked />
                    <input type="radio" id="newlisting_requiredpackage" name="newlisting_requiredpackage" value="0" />
                    <label for="radio1" class="cb-enable selected"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
            </div>        
            <div class="js_final_step">
<?php echo Text::_('Only Employer Can Post Jobs'); ?>
                <p class="field switch">
                    <input type="radio" id="visitor_can_post_job" name="visitor_can_post_job" value="0" checked />
                    <input type="radio" id="visitor_can_post_job" name="visitor_can_post_job" value="1" />
                    <label for="radio1" class="cb-enable selected"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
            </div>        
            <div class="js_final_step">
<?php echo Text::_('Job Seeker Package Required'); ?>
                <p class="field switch">
                    <input type="radio" id="js_newlisting_requiredpackage" name="js_newlisting_requiredpackage" value="1" checked />
                    <input type="radio" id="js_newlisting_requiredpackage" name="js_newlisting_requiredpackage" value="0" />
                    <label for="radio1" class="cb-enable selected"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
            </div>        
            <div class="js_final_step">
<?php echo Text::_('Only jobseeker can apply for job'); ?>
                <p class="field switch">
                    <input type="radio" id="visitor_can_apply_to_job" name="visitor_can_apply_to_job" value="0" checked />
                    <input type="radio" id="visitor_can_apply_to_job" name="visitor_can_apply_to_job" value="1" />
                    <label for="radio1" class="cb-enable selected"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
            </div>        
            <h1><?php echo Text::_('Sample Data'); ?></h1>
            <div class="js_final_step">
<?php echo Text::_('Install Sample Data'); ?>
                <p class="field switch">
                    <input type="radio" id="install_sample_data" name="install_sample_data" value="1" checked />
                    <input type="radio" id="install_sample_data" name="install_sample_data" value="0" />
                    <label for="radio1" class="cb-enable selected" data-id="sampledata"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable" data-id="sampledata1"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
                <div class="ahmed_users">
                    <span class="ahmed_users_title"><?php echo Text::_('Sample Data Default User Information'); ?></span>
                    <div class="ahmed_user_wrapper">
                        <div class="ahmed_user_title">
                            <span class="col1"><?php echo Text::_('Username'); ?></span>
                            <span class="col1"><?php echo Text::_('Password'); ?></span>
                        </div>
                        <div class="ahmed_user_data">
                            <span class="col1">jsjobs_jobseeker</span>
                            <span class="col1">demo</span>
                        </div>
                        <div class="ahmed_user_data">
                            <span class="col1">jsjobs_employer</span>
                            <span class="col1">demo</span>
                        </div>
                    </div>
                </div>
            </div>        
            <div class="js_final_step">
<?php echo Text::_('Create Menu For JS Jobs'); ?>
                <p class="field switch">
                    <input type="radio" id="create_menu_link" name="create_menu_link" value="1" checked />
                    <input type="radio" id="create_menu_link" name="create_menu_link" value="0" />
                    <label for="radio1" class="cb-enable selected"><span><?php echo Text::_('JYES'); ?></span></label>
                    <label for="radio2" class="cb-disable"><span><?php echo Text::_('JNO'); ?></span></label>
                </p>
            </div>        
            <div class="js_button_wrapper">
                <input class="js_next_button" type="submit" value="<?php echo Text::_('Finish'); ?>" onclick="return validate_form(document.adminForm);" />
            </div>
        </div>
        <input type="hidden" name="check" value="" />
        <input type="hidden" name="c" value="installer" />
        <input type="hidden" name="task" value="completeinstallation" />
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
