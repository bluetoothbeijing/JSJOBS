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
            if (task == 'category.savecategory') {
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
                            <a href="index.php?option=com_jsjobs&c=category&view=category&layout=categories" title="<?php echo Text::_('Categories'); ?>">
                                <?php echo Text::_('Categories'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                            if (isset($this->application->id)){
                                echo Text::_('Edit Category');
                            }else{
                                echo Text::_('Add').' '.Text::_('Category');
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
                if (isset($this->application->id)){
                    echo Text::_('Edit Category');
                }else{
                    echo Text::_('Add').' '.Text::_('Category');
                } ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
        <form action="index.php" method="POST" name="adminForm" id="adminForm" >
            <div class="js-form-area">
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="cat_title"><?php echo Text::_('Category Title'); ?>:&nbsp;</label>
                    <div class="jsjobs-value"><input class="inputbox required" type="text" name="cat_title" id="cat_title" size="40" maxlength="255" value="<?php if (isset($this->application)) echo $this->application->cat_title; ?>" /></div>
                </div>
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="status"><?php echo Text::_('Status'); ?></label>
                    <div class="jsjobs-value"><div class="div-white"><span class="js-cross"><input type="checkbox" name="isactive" id="status" value="1" <?php
                                                     if (isset($this->application)) {
                                                         if ($this->application->isactive == '1')
                                                             echo 'checked';
                                                     }else{
                                                        echo 'checked';
                                                     }
?>/></span> <label class="js-publish" for="status" ><?php echo Text::_('Publish'); ?></label>
                                                     </div>
                    </div>
                </div>

              <input type="hidden" name="id" value="<?php if (isset($this->application)) echo $this->application->id; ?>" />
                <input type="hidden" name="isdefault" value="<?php if (isset($this->application)) echo $this->application->isdefault; ?>" />
                <input type="hidden" name="ordering" value="<?php if (isset($this->application)) echo $this->application->ordering; ?>" />

                <input type="hidden" name="nisactive" value="1" />
                <input type="hidden" name="check" value="" />
                <input type="hidden" name="task" value="category.savecategory" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <?php echo HTMLHelper::_( 'form.token' ); ?>        
            </div>
            <div class="js-buttons-area">
                <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save Category'); ?>" />
                <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=category&view=category&layout=categories"><?php echo Text::_('Cancel'); ?></a>
            </div>
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




