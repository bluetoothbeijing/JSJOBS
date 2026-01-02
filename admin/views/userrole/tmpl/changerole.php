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

HTMLHelper::_('behavior.formvalidator');
?>

<script type="text/javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'userrole.save') {
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
            alert("Some values are not acceptable.  Please retry.");
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
                            <a href="index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users" title="<?php echo Text::_('Users'); ?>">
                                <?php echo Text::_('Users'); ?>
                            </a>
                        </li>
                        <li>
                            <?php echo Text::_('Change Role'); ?>
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
                <?php echo Text::_('Change Role'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">

<?php if(!empty($this->role)){ ?>
       <form action="index.php" method="POST" name="adminForm" id="adminForm" >
                <div class="js-usercontent">
                         
                    <div class="js-lineusercontent bg">
                        <span class="js-spansize1"><label id="titlemsg" for="title"><?php echo Text::_('Name'); ?> : </label></span>
                        <span class="js-spansize2"><?php if (isset($this->role)) echo $this->role->name; ?></span>
                    </div> 
                    <div class="js-lineusercontent">
                        <span class="js-spansize1"><label><?php echo Text::_('Username'); ?> : </label></span>
                        <span class="js-spansize2"><?php if (isset($this->role)) echo $this->role->username; ?></span>
                    </div> 
                    <div class="js-lineusercontent bg">
                        <?php
                            $img = $this->role->block ? 'cross.png' : 'tick.png';
                            $task = $this->role->block ? 'unblock' : 'block';
                            $alt = $this->role->block ? Text::_('Enabled') : Text::_('Blocked');
                            ?>
                        <span class="js-spansize1"><label><?php echo Text::_('Enabled'); ?> : </label></span>
                        <span class="js-spansize2"><img src="components/com_jsjobs/include/images/<?php echo $img; ?>" /></span>
                    </div> 
                    <div class="js-lineusercontent">
                        <span class="js-spansize1"><label><?php echo Text::_('Group'); ?> : </label></span>
                        <span class="js-spansize2"><?php if (isset($this->role)) echo $this->role->groupname; ?></span>
                    </div> 
                    <div class="js-lineusercontent bg">
                        <span class="js-spansize1"><label><?php echo Text::_('Id'); ?> : </label></span>
                        <span class="js-spansize2"><?php if (isset($this->role)) echo $this->role->id; ?></span>
                    </div> 
                    <div class="js-lineusercontent">
                        <span class="js-spansize1"><label><?php echo Text::_('Role'); ?> : </label></span>
                        <span class="js-spansize2"><?php echo $this->lists['roles']; ?></span>
                    </div> 
                </div>
                <?php
                if (isset($this->role)) {
                    if (($this->role->dated == '0000-00-00 00:00:00') || ($this->role->dated == ''))
                        $curdate = date('Y-m-d H:i:s');
                    else
                        $curdate = $this->role->dated;
                }else {
                    $curdate = date('Y-m-d H:i:s');
                }
                ?>
                <input type="hidden" name="dated" value="<?php echo $curdate; ?>" />
                <input type="hidden" name="id" value="<?php if (isset($this->role)) echo $this->role->userroleid; ?>" />
                <input type="hidden" name="uid" value="<?php if (isset($this->role)) echo $this->role->id; ?>" />
                <input type="hidden" name="check" value="" />
                <input type="hidden" name="task" value="userrole.saveuserrole" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />



            <div class="js-buttons-area">
                <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save User Role'); ?>" />
                <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users"><?php echo Text::_('Cancel'); ?></a>
            </div>
            </form>
        </div>

<?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
    </div>    
</div>

<div id="jsjobs-footer">
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




