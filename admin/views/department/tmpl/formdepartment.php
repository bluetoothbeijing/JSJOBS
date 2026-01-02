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
            if (task == 'department.savedepatrment') {
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
                            <a href="index.php?option=com_jsjobs&c=department&view=department&layout=departments" title="<?php echo Text::_('Departments'); ?>">
                                <?php echo Text::_('Departments'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                            if (isset($this->application->id)){
                                echo Text::_('Edit Department');
                            }else{
                                echo Text::_('Add Department');
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
                if (isset($this->department->id)){
                    echo Text::_('Edit Department');
                }else{
                    echo Text::_('Add Department');
                } ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
            <form action="index.php" method="post" name="adminForm" id="adminForm" >
            <div class="js-form-area">
                
                <input type="hidden" name="check" value="post"/>
                    <div class="js-form-wrapper">
                        <label class="jsjobs-title" for="companyid"><?php echo Text::_('Company'); ?>&nbsp;<font color="red">*</font></label>
                        <div class="jsjobs-value"><?php echo $this->lists['companies']; ?></div>
                    </div>

                    
                    <div class="js-form-wrapper">
                        <label class="jsjobs-title" for="name"><?php echo Text::_('Department Name'); ?>&nbsp;<font color="red">*</font></label>
                        <div class="jsjobs-value"><input class="inputbox required" type="text" name="name" id="name" size="20"  value="<?php if (isset($this->department)) echo $this->department->name; ?>" /></div>
                    </div>
                    
                    <div class="js-form-wrapper full-width">
                        <label class="jsjobs-title" for="name"><?php echo Text::_('Description'); ?>&nbsp;<font color="red">*</font></label>
                        <div class="jsjobs-value"> <?php
                                $conf   = Factory::getConfig();
                                $editor = Editor::getInstance($conf->get('editor'));

                                if (isset($this->department))
                                    echo $editor->display('description', $this->department->description, '550', '300', '60', '20', false);
                                else
                                    echo $editor->display('description', '', '550', '300', '60', '20', false);
                                ?></div>
                    </div>

                    <div class="js-form-wrapper">
                        <label class="jsjobs-title" for="status"><?php echo Text::_('Status'); ?></label>
                        <div class="jsjobs-value"><?php echo $this->lists['status']; ?></div>
                    </div>

                    <input type="hidden" name="id" value="<?php if (isset($this->department)) echo $this->department->id; ?>" />
                    <input type="hidden" name="task" value="department.savedepatrment" />
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="uid" value="<?php if (isset($this->department))
                                    echo $this->department->uid;
                                else
                                    echo $this->uid;
                                ?>" />

                                <?php
                // admin side forms dont have itemid was showing error for unfined variable
                /*
                    <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />

                    */
                        $md = Factory::getApplication()->input->get('md');
                        echo '<input type="hidden" name="md" value="'.$md.'" />';
                    ?>
            </div>
            <div class="js-buttons-area">
                <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save Department'); ?>" />
                <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=department&view=department&layout=departments"><?php echo Text::_('Cancel'); ?></a>
            </div>
            <?php echo HTMLHelper::_( 'form.token' ); ?>        
            </form> 
        </div>   
    </div>
</div>
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
