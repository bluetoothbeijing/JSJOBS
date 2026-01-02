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

//HTMLHelper::_('behavior.calendar');
HTMLHelper::_('behavior.formvalidator');

?>

<script language="javascript">

    jQuery(document).ready(function ($) {
        jQuery('#termsandconditions_linktype').on('change', function() {
            if(this.value == 1){
                jQuery('.for-terms-condtions-linktype1').slideDown();
                jQuery('.for-terms-condtions-linktype2').hide();
            }else{
                jQuery('.for-terms-condtions-linktype1').hide();
                jQuery('.for-terms-condtions-linktype2').slideDown();
            }
        });
        <?php if(isset($this->userfield->id)){ ?>
            var intial_val = jQuery('#termsandconditions_linktype').val();
            if(intial_val == 1){
                jQuery('.for-terms-condtions-linktype1').slideDown();
                jQuery('.for-terms-condtions-linktype2').hide();
            }else{
                jQuery('.for-terms-condtions-linktype1').hide();
                jQuery('.for-terms-condtions-linktype2').slideDown();
            }
        <?php } ?>
    });

    // for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'gdpr.savegdprfield') {
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
                            <a href="index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=gdprfields" title="<?php echo Text::_('Employer Package'); ?>">
                                <?php echo Text::_('GDPR Fields'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                            if (isset($this->userfieldparams)){
                                echo Text::_('Edit Fields');
                            }else{
                                echo Text::_('Add').' '.Text::_('Fields');
                            } ?>
                        </li>
                    </ul>
                    </ul>
                </div>
            </div>
            <div id="jsjobs-wrapper-top-right">
                <div id="jsjobs-config-btn">
                    <a href="index.php?option=com_jsjobs&c=employerpackages&view=employerpackages&layout=employerpackages" title="<?php echo Text::_('Configuration'); ?>">
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
                if (isset($this->userfieldparams)){
                    echo Text::_('Edit Fields');
                }else{
                    echo Text::_('Add').' '.Text::_('Fields');
                } ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
        <form action="index.php" method="post" name="adminForm" id="adminForm" >
            <div class="js-form-area">
                <?php
                $termsandconditions_text = '';
                $termsandconditions_linktype = '';
                $termsandconditions_link = '';
                $termsandconditions_page = '';
                if( isset($this->userfieldparams) && $this->userfieldparams != '' && is_array($this->userfieldparams) && !empty($this->userfieldparams)){
                    $termsandconditions_text = isset($this->userfieldparams['termsandconditions_text']) ? $this->userfieldparams['termsandconditions_text'] :'' ;
                    $termsandconditions_linktype = isset($this->userfieldparams['termsandconditions_linktype']) ? $this->userfieldparams['termsandconditions_linktype'] :'' ;
                    $termsandconditions_link = isset($this->userfieldparams['termsandconditions_link']) ? $this->userfieldparams['termsandconditions_link'] :'' ;
                    $termsandconditions_page = isset($this->userfieldparams['termsandconditions_page']) ? $this->userfieldparams['termsandconditions_page'] :'' ;
                } ?>
                <input type="hidden" name="check" value="post"/>
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="fieldtitle"><?php echo Text::_('Field Title'); ?>&nbsp;<font color="red">*</font></label>
                    <div class="jsjobs-value"><input class="inputbox required" type="text" name="fieldtitle" id="fieldtitle" value="<?php echo isset($this->userfield->fieldtitle) ? $this->userfield->fieldtitle : ''; ?>" /></div>
                </div>

                    <div class="js-form-wrapper">
                        <label class="jsjobs-title" for="termsandconditions_text"><?php echo Text::_('Field Text'); ?>&nbsp;<font color="red">*</font></label>
                        <div class="jsjobs-value"><input class="inputbox required" type="text" name="termsandconditions_text" id="termsandconditions_text" value="<?php echo $termsandconditions_text; ?>" /></div>
                        <div class="js-gdpr-field-desc">
                            <label class=" stylelabelbottom labelcolorbottom"><?php echo Text::_('e.g').' '.Text::_('I have read and agree to the').' ['.Text::_('link').'] '.Text::_('Terms and Conditions').'[/'.Text::_('link').'].' .Text::_('The text between').' ['.Text::_('link').'] '.Text::_('and').' [/'.Text::_('link').'] '.Text::_('will be linked to provided url or Joomla page'); ?></label>     
                        </div>
                    </div>
                    <?php
                    $linktype = array(
                    '0' => array('value' => 0,'text' => Text::_('Select link type')),
                    '1' => array('value' => 1,'text' => Text::_('Direct Link')),
                    '2' => array('value' => 2, 'text' => Text::_('Joomla Article')));

                    ?>
                    <div class="js-form-wrapper">
                        <label class="jsjobs-title" for="termsandconditions_linktype"><?php echo Text::_('Link').' '.Text::_('Type'); ?>&nbsp;<font color="red">*</font></label>
                        <div class="jsjobs-value"><?php echo HTMLHelper::_('select.genericList', $linktype, 'termsandconditions_linktype', 'class="inputbox required" ' . '', 'value', 'text', $termsandconditions_linktype);?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-linktype1" style="display: none;">
                        <label class="jsjobs-title" for="termsandconditions_link"><?php echo Text::_('URL'); ?>&nbsp;<font color="red">*</font></label>
                        <div class="jsjobs-value"><input class="inputbox" type="text" name="termsandconditions_link" id="termsandconditions_link" value="<?php echo isset($termsandconditions_link) ? $termsandconditions_link : ''; ?>" /></div>
                    </div> 
                    <div class="js-form-wrapper for-terms-condtions-linktype2" style="display: none;">
                        <label class="jsjobs-title" for="termsandconditions_page"><?php echo Text::_('Joomla Article'); ?>&nbsp;<font color="red">*</font></label>
                        <div class="jsjobs-value"><?php echo HTMLHelper::_('select.genericList', $this->articles, 'termsandconditions_page', 'class="inputbox" ' . '', 'value', 'text', $termsandconditions_page);?></div>
                    </div>               

                    <input type="hidden" name="id" value="<?php echo isset($this->userfield->id) ? $this->userfield->id : '';?>" />
                    <input type="hidden" name="task" value="gdpr.savegdprfield" />
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <?php
                    // admin side forms dont have itemid was showing error for unfined variable
                    /*
                    <input type="hidden" name="Itemid" id="Itemid" value="<?php echo $this->Itemid; ?>" />
                    */
                    ?>
                    <input type="hidden" name="userfieldtype" id="userfieldtype" value="termsandconditions" />
                    <input type="hidden" name="isuserfield" id="isuserfield" value="1" />
                    <input type="hidden" name="fieldfor" id="fieldfor" value="14" />
                    <input type="hidden" name="published" id="published" value="1" />
                    <input type="hidden" name="required" id="required" value="1" />
                    <input type="hidden" name="isvisitorpublished" id="isvisitorpublished" value="1" />
                    <input type="hidden" name="ordering" id="ordering" value="<?php echo isset($this->userfield->ordering) ? $this->userfield->ordering : '';?>" />
                    <input type="hidden" name="created" id="created" value="<?php echo isset($this->userfield->created) ? $this->userfield->created : '';?>" />
            </div>
            <div class="js-buttons-area">
                <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save').' '.Text::_('Fields'); ?>" />
                <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=gdpr&view=gdpr&layout=gdprfields"><?php echo Text::_('Cancel'); ?></a>
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
