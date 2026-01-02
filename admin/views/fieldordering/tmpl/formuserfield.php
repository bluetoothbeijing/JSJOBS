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
            if (task == 'fieldordering.savejobuserfieldsave' || task == 'fieldordering.savejobuserfieldandnew' || task == 'fieldordering.savejobuserfield') {
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
        }else {
            alert("<?php echo Text::_("Some values are not acceptable").'. '.Text::_("Please retry"); ?>");
            return false;
        }
        
        var terms = document.getElementById('userfieldtype');
        if(terms.value == 'termsandconditions'){
            if(document.getElementById("termsandconditions_text").value === null){
                alert("<?php echo Text::_("Terms and condition text is missing"); ?>");
                jQuery("#termsandconditions_text").addClass("required invalid");
                return false;
            }
            if(document.getElementById("termsandconditions_linktype").value == 1){
                if(document.getElementById("termsandconditions_link").value == ""){
                    alert("<?php echo Text::_("Terms and condition link is missing"); ?>");
                    jQuery("#termsandconditions_link").addClass("required invalid");
                    return false;
                }
            }else if(document.getElementById("termsandconditions_linktype").value == 2){
                if(document.getElementById("termsandconditions_page").value == ""){
                    alert("<?php echo Text::_("Terms and condition article is missing"); ?>");
                    jQuery("#termsandconditions_page").addClass("required invalid");
                    return false;
                }
            }else{
                alert("<?php echo Text::_("Terms and condition link type is missing"); ?>");
                return false;
            }
        }
        return true;
    }
</script>
<?php
    $yesno = array();
    $yesno[] = (object) array('id' => 1, 'text' => Text::_('JYES'));
    $yesno[] = (object) array('id' => 0, 'text' => Text::_('JNO'));
    $sectionarray = array();
    $sectionarray[] = (object) array('id' => 1, 'text' => Text::_('Personal Information'));
    $sectionarray[] = (object) array('id' => 2, 'text' => Text::_('Addresses'));
    $sectionarray[] = (object) array('id' => 3, 'text' => Text::_('Education'));
    $sectionarray[] = (object) array('id' => 4, 'text' => Text::_('Employer'));
    $sectionarray[] = (object) array('id' => 5, 'text' => Text::_('Skills'));
    $sectionarray[] = (object) array('id' => 6, 'text' => Text::_('Resume'));
    $sectionarray[] = (object) array('id' => 7, 'text' => Text::_('References'));
    $sectionarray[] = (object) array('id' => 8, 'text' => Text::_('Languages'));
    $fieldtypes = array();
    $fieldtypes[] = (object) array('id' => 'text', 'text' => Text::_('Text Field'));
    $fieldtypes[] = (object) array('id' => 'checkbox', 'text' => Text::_('Check Box'));
    $fieldtypes[] = (object) array('id' => 'date', 'text' => Text::_('Date'));
    $fieldtypes[] = (object) array('id' => 'combo', 'text' => Text::_('Drop Down'));
    $fieldtypes[] = (object) array('id' => 'email', 'text' => Text::_('Email Address'));
    $fieldtypes[] = (object) array('id' => 'textarea', 'text' => Text::_('Text Area'));
    $fieldtypes[] = (object) array('id' => 'radio', 'text' => Text::_('Radio Button'));
    $fieldtypes[] = (object) array('id' => 'depandant_field', 'text' => Text::_('Depandent Field'));
    $fieldtypes[] = (object) array('id' => 'file', 'text' => Text::_('Upload File'));
    $fieldtypes[] = (object) array('id' => 'multiple', 'text' => Text::_('Multi Select'));
    $fieldtypes[] = (object) array('id' => 'termsandconditions', 'text' => Text::_('Terms and Conditions'));
?>

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
                            <a href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=fieldsordering&ff=3" title="<?php echo Text::_('Field Ordering'); ?>">
                                <?php echo Text::_('Field Ordering'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                            if (isset($this->application->id)){
                                echo Text::_('Edit User Field');
                            }else{
                                echo Text::_('Form User Field');
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
                    echo Text::_('Edit User Field');
                }else{
                    echo Text::_('Form User Field');
                } ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
            <form action="index.php" method="POST" name="adminForm" id="adminForm">
                <div class="js-form-area">
                    <div class="js-form-wrapper">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Field Type'); ?><font color="red">*</font></label>
                        <div class="jsjobs-value"><?php echo customfields::select('userfieldtype', $fieldtypes, isset($this->application['userfield']->userfieldtype) ? $this->application['userfield']->userfieldtype : 'text', '', array('class' => 'inputbox required', 'data-validation' => '', 'onchange' => 'toggleType(this.options[this.selectedIndex].value);')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide" id="for-combo-wrapper" style="display:none;">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Select','js-jobs') .'&nbsp;'. Text::_('Parent Field'); ?><font color="red">*</font></label>
                        <div class="jsjobs-value" id="for-combo"></div>      
                    </div>
                    <div class="js-form-wrapper">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Field Title'); ?><font color="red">*</font></label>
                        <div class="jsjobs-value"><?php echo customfields::text('fieldtitle', isset($this->application['userfield']->fieldtitle) ? $this->application['userfield']->fieldtitle : '', array('class' => 'inputbox required', 'data-validation' => 'required')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Show on listing'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::select('showonlisting', $yesno, isset($this->application['userfield']->showonlisting) ? $this->application['userfield']->showonlisting : 0, '', array('class' => 'inputbox one')); ?></div>
                    </div>

                    <?php if ($this->ff == 3) { 
                        // section field disabled was not working in edit case
                        if(isset($this->application['userfield']) && $this->application['userfield'])
                            $disabled = '1';
                        else
                            $disabled = '';
                        ?>
                        <div class="js-form-wrapper">
                            <label class="jsjobs-title" for=""><?php echo Text::_('Resume Section'); ?><font color="red">*</font></label>
                            <div class="jsjobs-value">
                                <?php 
                                    echo customfields::select('section', $sectionarray, isset($this->application['userfield']->section) ? $this->application['userfield']->section : '', '', array('class' => 'inputbox one required', 'data-validation' => 'required', 'onchange' => 'resumesectionchange(this.value);'), $disabled); 
                                ?> 
                                <div id="js_section_msg">
                                    <?php echo Text::_('Section Will Not Change In Edit Case'); ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('User Published'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::select('published', $yesno, isset($this->application['userfield']->published) ? $this->application['userfield']->published : 1, '', array('class' => 'inputbox one')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Visitor Published'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::select('isvisitorpublished', $yesno, isset($this->application['userfield']->isvisitorpublished) ? $this->application['userfield']->isvisitorpublished : 1, '', array('class' => 'inputbox one')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('User Search'); ?></label>
                        <div class="jsjobs-value">
                            <?php 
                                $defaultvalue = isset($this->application['userfield']->search_user) ? $this->application['userfield']->search_user : 1;
                                $attrarray = array('class' => 'inputbox one');
                                if($this->ff == 1){ // In case of company this field should be disabled
                                    $attrarray['disabled'] = true;
                                    $defaultvalue = 0;
                                }
                                echo customfields::select('search_user', $yesno, $defaultvalue, '', $attrarray);
                            ?>                        
                        </div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Visitor Search'); ?></label>
                        <div class="jsjobs-value">
                            <?php 
                                $defaultvalue = isset($this->application['userfield']->search_visitor) ? $this->application['userfield']->search_visitor : 1;
                                $attrarray = array('class' => 'inputbox one');
                                if($this->ff == 1){ // In case of company this field should be disabled
                                    $attrarray['disabled'] = true;
                                    $defaultvalue = 0;
                                }
                                echo customfields::select('search_visitor', $yesno, $defaultvalue, '', $attrarray);
                            ?>
                        </div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Required'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::select('required', $yesno, isset($this->application['userfield']->required) ? $this->application['userfield']->required : 0, '', array('class' => 'inputbox one')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Field Size'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::text('size', isset($this->application['userfield']->size) ? $this->application['userfield']->size : '', array('class' => 'inputbox one')); ?></div>
                    </div>
                    <?php /*
                    <div class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Java Script'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::textarea('j_script', isset($this->application['userfield']->j_script) ? $this->application['userfield']->j_script : '', array('class' => 'inputbox one')); ?></div>
                    </div>
                    */ ?>

                    <div id="for-combo-options-head" >
                        <span class="js-admin-title"><?php echo Text::_('Use The Table Below To Add New Values'); ?></span>
                    </div>
                    <div id="for-combo-options">
                        <?php
                        $arraynames = '';
                        $comma = '';
                        if (isset($this->application['userfieldparams']) && $this->application['userfield']->userfieldtype == 'depandant_field') {
                            foreach ($this->application['userfieldparams'] as $key => $val) {
                                $textvar = $key;
                                $textvar .='[]';
                                $arraynames .= $comma . "$key";
                                $comma = ',';
                                ?>
                                <div class="js-field-wrapper">
                                    <div class="js-field-title">
                                        <?php echo $key; ?>
                                    </div>
                                    <div class="jsjobs-value combo-options-fields" id="<?php echo $key; ?>">
                                        <?php
                                        if (!empty($val)) {
                                            foreach ($val as $each) {
                                                ?>
                                                <span class="input-field-wrapper">
                                                    <input name="<?php echo $textvar; ?>" id="<?php echo $key; ?>" value="<?php echo $each; ?>" class="inputbox one user-field" type="text">
                                                    <img class="input-field-remove-img" src="components/com_jsjobs/include/images/remove.png">
                                                </span><?php
                                            }
                                        }
                                        ?>
                                        <input id="depandant-field-button" onclick="getNextField(&quot;<?php echo $key; ?>&quot;,this );" value="Add More" type="button">
                                    </div>
                                </div><?php
                            }
                        }
                        ?>
                    </div>

                    <div id="divText" class="js-form-wrapper for-terms-condtions-hide">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Max Length'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::text('maxlength', isset($this->application['userfield']->maxlength) ? $this->application['userfield']->maxlength : '', array('class' => 'inputbox one')); ?></div>
                    </div>

                    <div class="js-form-wrapper for-terms-condtions-hide divColsRows">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Columns'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::text('cols', isset($this->application['userfield']->cols) ? $this->application['userfield']->cols : '', array('class' => 'inputbox one')); ?></div>
                    </div>
                    <div class="js-form-wrapper for-terms-condtions-hide divColsRows">
                        <label class="jsjobs-title" for=""><?php echo Text::_('Rows'); ?></label>
                        <div class="jsjobs-value"><?php echo customfields::text('rows', isset($this->application['userfield']->rows) ? $this->application['userfield']->rows : '', array('class' => 'inputbox one')); ?></div>
                    </div>
                    <div id="divValues" class="js-form-wrapper for-terms-condtions-hide divColsRows">
                        <span class="js-admin-title"><?php echo Text::_('Use The Table Below To Add New Values'); ?></span>
                        <div class="page-actions">
                            <div id="user-field-values" class="no-padding">
                                <?php
                                if (isset($this->application['userfield']) && $this->application['userfield']->userfieldtype != 'depandant_field') {
                                    if (isset($this->application['userfieldparams'])) {
                                        if( ! empty($this->application['userfieldparams']))
                                        foreach ($this->application['userfieldparams'] as $key => $val) {
                                            ?>
                                            <span class="input-field-wrapper">
                                                <?php echo customfields::text('values[]', isset($val) ? $val : '', array('class' => 'inputbox one user-field')); ?>
                                                <img class="input-field-remove-img" src="components/com_jsjobs/include/images/remove.png" />
                                            </span>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <span class="input-field-wrapper">
                                        <?php echo customfields::text('values[]', isset($val) ? $val : '', array('class' => 'inputbox one user-field')); ?>
                                            <img class="input-field-remove-img" src="components/com_jsjobs/include/images/remove.png" />
                                        </span>
                                    <?php
                                    }
                                }
                                ?>
                                <a class="js-button-link button user-field-val-button" id="user-field-val-button" onclick="insertNewRow();"><?php echo Text::_('Add Value') ?></a>
                            </div>  
                        </div>
                    </div>

                    <div class="for-terms-condtions-show" >
                        <?php
                        $termsandconditions_text = '';
                        $termsandconditions_linktype = '';
                        $termsandconditions_link = '';
                        $termsandconditions_page = '';
                        if( isset($this->application['userfieldparams']) && $this->application['userfieldparams'] != '' && is_array($this->application['userfieldparams']) && !empty($this->application['userfieldparams'])){
                            $termsandconditions_text = isset($this->application['userfieldparams']['termsandconditions_text']) ? $this->application['userfieldparams']['termsandconditions_text'] :'' ;
                            $termsandconditions_linktype = isset($this->application['userfieldparams']['termsandconditions_linktype']) ? $this->application['userfieldparams']['termsandconditions_linktype'] :'' ;
                            $termsandconditions_link = isset($this->application['userfieldparams']['termsandconditions_link']) ? $this->application['userfieldparams']['termsandconditions_link'] :'' ;
                            $termsandconditions_page = isset($this->application['userfieldparams']['termsandconditions_page']) ? $this->application['userfieldparams']['termsandconditions_page'] :'' ;
                        } ?>
                        <div class="js-form-wrapper ">
                            <label class="jsjobs-title" for=""><?php echo Text::_('Terms and Conditions Link Type'); ?><font color="red">*</font></label>
                            <?php
                            $linktype = array(
                                '0' => array('value' => 0,'text' => Text::_('Select link type')),
                                '1' => array('value' => 1,'text' => Text::_('Direct Link')),
                                '2' => array('value' => 2, 'text' => Text::_('Joomla Article'))
                            );
                            ?>
                            <div class="jsjobs-value"><?php echo HTMLHelper::_('select.genericList', $linktype, 'termsandconditions_linktype', 'class="inputbox required" ' . '', 'value', 'text', $termsandconditions_linktype);?></div>
                        </div>
                        <div class="js-form-wrapper">
                            <label class="jsjobs-title" for=""><?php echo Text::_('Terms and Conditions Text'); ?><font color="red">*</font></label>
                            <div class="jsjobs-value"><?php echo customfields::text('termsandconditions_text', $termsandconditions_text, array('class' => 'inputbox')); ?>
                                <div class="js-form-desc">
                                <?php echo Text::_('e.g').' '.Text::_('I have read and agree to the').' ['.Text::_('link').'] '.Text::_('Terms and Conditions').'[/'.Text::_('link').'] '.Text::_('The text between').' ['.Text::_('link').'] '.Text::_('and').' [/'.Text::_('link').'] '.Text::_('will be linked to provided url or Joomla page'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="js-form-wrapper for-terms-condtions-linktype1" style="display: none;">
                            <label class="jsjobs-title" for=""><?php echo Text::_('Terms and Conditions Link'); ?></label>
                            <div class="jsjobs-value"><input class="inputbox" type="text" name="termsandconditions_link" id="termsandconditions_link" value="<?php echo isset($termsandconditions_link) ? $termsandconditions_link : ''; ?>" /></div>
                        </div>
                        <div class="js-form-wrapper for-terms-condtions-linktype2" style="display: none;">
                            <label class="jsjobs-title" for=""><?php echo Text::_('Terms and Conditions Page'); ?></label>
                            <div class="jsjobs-value"><?php echo HTMLHelper::_('select.genericList', $this->articles, 'termsandconditions_page', 'class="inputbox" ' . '', 'value', 'text', $termsandconditions_page);?></div>
                        </div>
                    
                    
                    <?php echo customfields::hidden('id', isset($this->application['userfield']->id) ? $this->application['userfield']->id : ''); ?>
                    <?php 
                        $cannotsearch = 0;
                        if($this->ff == 1){ // for company field are not available for search
                            $cannotsearch = 1;
                        }
                        echo customfields::hidden('cannotsearch', $cannotsearch); 
                    ?>
                    <?php echo customfields::hidden('cannotshowonlisting', 0); ?>
                    <?php echo customfields::hidden('isuserfield', 1); ?>
                    <?php echo customfields::hidden('fieldfor', $this->ff); ?>
                    <?php echo customfields::hidden('ordering', isset($this->application['userfield']->ordering) ? $this->application['userfield']->ordering : '' ); ?>
                    <?php echo customfields::hidden('fieldname', isset($this->application['userfield']->field) ? $this->application['userfield']->field : '' ); ?>
                    <?php echo customfields::hidden('field', isset($this->application['userfield']->field) ? $this->application['userfield']->field : '' ); ?>
                    <?php echo customfields::hidden('arraynames2', $arraynames); ?>
                    <?php // echo customfields::hidden('parentfield', isset($this->application['userfield']->parentfield) ? $this->application['userfield']->parentfield : '' ); ?>
                    <input type="hidden" name="task" value="fieldordering.savejobuserfield" />
                    <input type="hidden" name="check" value="" />
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                </div>

                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="title"></label>
                    <div class="jsjobs-value"></div>
                </div>

                <div class="js-buttons-area">
                    <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save user field'); ?>" />
                    <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=fieldordering&view=fieldordering&layout=fieldsordering"><?php echo Text::_('Cancel'); ?></a>
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

<script type="text/javascript">
    jQuery(document).ready(function () {
        toggleType(jQuery('#userfieldtype').val());
        resumesectionchange(jQuery('select#section').val());
        jQuery('#termsandconditions_linktype').on('change', function() {
            if(this.value == 1){
                jQuery('.for-terms-condtions-linktype1').slideDown();
                jQuery('.for-terms-condtions-linktype2').hide();
            }else if(this.value == 2){
                jQuery('.for-terms-condtions-linktype1').hide();
                jQuery('.for-terms-condtions-linktype2').slideDown();
            }
        });

        var intial_val = jQuery('#termsandconditions_linktype').val();
        if(intial_val == 1){
            jQuery('.for-terms-condtions-linktype1').slideDown();
            jQuery('.for-terms-condtions-linktype2').hide();
        }else if(intial_val == 2){
            jQuery('.for-terms-condtions-linktype1').hide();
            jQuery('.for-terms-condtions-linktype2').slideDown();
        }
    });
    function resumesectionchange(value){
        var ff = jQuery('input#fieldfor').val();
        if(ff == 3){
            if(value != 1){
                jQuery("select#search_user").val(0).parent().parent().hide();
                jQuery("select#search_visitor").val(0).parent().parent().hide();
                jQuery("select#showonlisting").val(0).parent().parent().hide();
                jQuery("input#cannotshowonlisting").val(1);
                jQuery("input#cannotsearch").val(1);
            }else{
                jQuery("select#search_user").parent().parent().show();
                jQuery("select#search_visitor").parent().parent().show();
                jQuery("select#showonlisting").parent().parent().show();
            }
        }
    }
    function disableAll() {
        jQuery("#divValues").slideUp();
        jQuery(".divColsRows").slideUp();
        jQuery("#divText").slideUp();
    }
    function toggleType(type) {
        disableAll();
        //prep4SQL(document.forms['adminForm'].elements['field']);
        selType(type);

        // Set the option for custom fields
        if(type == 'checkbox'){
            jQuery("select#required").val(0).parent().parent().hide();
        } else if (type == 'termsandconditions'){
            jQuery("select#required").val(1).parent().parent().hide();
        }else{
            jQuery("select#required").parent().parent().show();
        }
    }

    // function prep4SQL(field) {
    //     if (field.value != '') {
    //         field.value = field.value.replace('js_', '');
    //         field.value = 'js_' + field.value.replace(/[^a-zA-Z_0-9]+/g, '');
    //     }
    // }

    function selType(sType) {
        var elem;
        /*
         text
         checkbox
         date
         combo
         email
         textarea
         radio
         editor
         depandant_field
         multiple*/

        switch (sType) {
            case 'editor':
                jQuery("div.for-terms-condtions-hide").show();
                jQuery("#divText").slideUp();
                jQuery("#divValues").slideUp();
                jQuery(".divColsRows").slideUp();
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();

                jQuery("div.for-terms-condtions-show").slideUp();
                break;
            case 'textarea':
                jQuery("div.for-terms-condtions-hide").show();
                jQuery("#divText").slideUp();
                jQuery(".divColsRows").slideDown();
                jQuery(".divColsRows").css('display','inline-block');
                jQuery("#divValues").slideUp();
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();
                jQuery("div.for-terms-condtions-show").slideUp();
                break;
            case 'email':
            case 'password':
            case 'text':
                jQuery("div.for-terms-condtions-hide").show();
                jQuery("#divText").slideDown();
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();
                jQuery("div.for-terms-condtions-show").slideUp();
                jQuery(".divColsRows").slideUp();
                break;
            case 'combo':
            case 'multiple':
                // jQuery("div.for-terms-condtions-hide").show();
                jQuery("#divValues").slideDown();
                jQuery("#divValues").css('display','inline-block');
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();
                // jQuery("div.for-terms-condtions-show").slideUp();
                break;
            case 'depandant_field':
                comboOfFields();
                jQuery("div.for-terms-condtions-show").slideUp();
                break;
            case 'radio':
            case 'checkbox':
                //jQuery(".divColsRows").slideDown();
                jQuery("#divValues").slideDown();
                jQuery("#divValues").css('display','inline-block');
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();
                /*
                 if (elem=getObject('jsNames[0]')) {
                 elem.setAttribute('mosReq',1);
                 }
                 */
                jQuery("div.for-terms-condtions-show").slideUp();
                break;
            case 'file':
                jQuery("div.for-terms-condtions-hide").show();
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();            
                jQuery("div.for-terms-condtions-show").slideUp();
                jQuery("#divValues").slideUp();
                jQuery(".divColsRows").slideUp();
                break;
            case 'termsandconditions':
                jQuery("div.for-terms-condtions-hide").hide();
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();
                jQuery("#divText").slideUp();
                jQuery(".divColsRows").slideUp();
                jQuery("#divValues").slideUp();
                jQuery("div#for-combo-wrapper").hide();
                jQuery("div#for-combo-options").hide();
                jQuery("div#for-combo-options-head").hide();
                jQuery("div.for-terms-condtions-show").slideDown();
                break;
            case 'delimiter':
            default:
        }
    }

    function comboOfFields() {
        var ff = jQuery("input#fieldfor").val();
        var pf = jQuery("input#fieldname").val();
        jQuery.post("index.php?option=com_jsjobs&task=fieldordering.getfieldsforcombobyfieldfor", {fieldfor: ff, parentfield: pf}, function (data) {
            if (data) {
                console.log(data);
                var d = jQuery.parseJSON(data);
                jQuery("div#for-combo").html(d);
                jQuery("div#for-combo-wrapper").show();
            }
        });
    }

    function getDataOfSelectedField() {
        var field = jQuery("select#parentfield").val();
        jQuery.post("index.php?option=com_jsjobs&task=fieldordering.getSectionToFillValues", {pfield: field}, function (data) {
            if (data) {
                console.log(data);
                var d = jQuery.parseJSON(data);
                jQuery("div#for-combo-options-head").css('display','inline-block');
                jQuery("div#for-combo-options").html(d);
                jQuery("div#for-combo-options").css('display','inline-block');
            }
        });
    }

    function getNextField(divid,object) {
        var textvar = divid + '[]';
        var fieldhtml = "<span class='input-field-wrapper' ><input type='text' name='" + textvar + "' class='inputbox one user-field'  /><img class='input-field-remove-img' src='components/com_jsjobs/include/images/remove.png' /></span>";
        jQuery(object).before(fieldhtml);
    }

    function getObject(obj) {
        var strObj;
        if (document.all) {
            strObj = document.all.item(obj);
        } else if (document.getElementById) {
            strObj = document.getElementById(obj);
        }
        return strObj;
    }

    function insertNewRow() {
        var fieldhtml = '<span class="input-field-wrapper" ><input name="values[]" id="values" value="" class="inputbox one user-field" type="text" /><img class="input-field-remove-img" src="components/com_jsjobs/include/images/remove.png" /></span>';
        jQuery("#user-field-val-button").before(fieldhtml);
    }
    jQuery(document).ready(function () {
        jQuery("body").delegate("img.input-field-remove-img", "click", function () {
            jQuery(this).parent().remove();
        });
    });
</script>
