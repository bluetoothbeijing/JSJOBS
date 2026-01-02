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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Editor\Editor;

jimport('joomla.html.pane');

$conf   = Factory::getConfig();
$editor = Editor::getInstance($conf->get('editor'));
//HTMLHelper::_('behavior.calendar');
HTMLHelper::_('behavior.formvalidator');
$document = Factory::getDocument();
$document->addStyleSheet('../components/com_jsjobs/css/token-input-jsjobs.css');
$document->addScript('../components/com_jsjobs/js/jquery.tokeninput.js');

if ($this->config['date_format'] == 'm/d/Y')
    $dash = '/';
else
    $dash = '-';
$dateformat = $this->config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;
$js_scriptdateformat = $firstvalue . $dash . $secondvalue . $dash . $thirdvalue;
?>


<script language="javascript">
    function checkUrl(obj) {
        if (!obj.value.match(/^http[s]?\:\/\//))
            obj.value = 'http://' + obj.value;
    }
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'company.savecompany') {
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
        var msg = new Array();

        var since_required = document.getElementById('company-since-required').value;
        if (since_required != '') {
            var since_value = document.getElementById('since').value;
            if (since_value == '') {
                jQuery("#since").addClass("invalid");
                msg.push('<?php echo Text::_('Please enter company since the date'); ?>');
                alert(msg.join('\n'));
                return false;
            }
        }

        var desc_required = document.getElementById("company-description-required").value;
        if (typeof desc_required !== 'undefined' && desc_required !== null) {
            if (desc_required != '') {
                var comdescription = tinyMCE.get('description').getContent();
                if (comdescription == '') {
                    msg.push('<?php echo Text::_('Please enter company description'); ?>');
                    alert(msg.join('\n'));
                    return false;
                }
            }
        }

        var logo_required = document.getElementById('company-logo-required').value;
        if (logo_required != '') {
            var logo_value = document.getElementById('logo').value;
            if (logo_value == '') {
                var logofile_value = document.getElementById('company-logofilename').value;
                if (logofile_value == '') {
                    msg.push('<?php echo Text::_('Please select the company logo'); ?>');
                    alert(msg.join('\n'));
                    return false;
                }
            }
        }

        var url_return = validate_url();
        if (url_return == false)
            return false;
        var call_since = jQuery("#since").val();
        if (typeof call_since != 'undefined') {
            var since_return = validate_since();
            if (since_return == false)
                return false;
        }

        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php echo Factory::getSession()->getFormToken(); ?>';//send token

        }
        else {
            msg.push('<?php echo Text::_('Some values are not acceptable, please retry'); ?>');
            alert(msg.join('\n'));
            return false;
        }
        return true;
    }

    function hasClass(el, selector) {
        var className = " " + selector + " ";

        if ((" " + el.className + " ").replace(/[\n\t]/g, " ").indexOf(className) > -1) {
            return true;
        }
        return false;
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
                            <a href="index.php?option=com_jsjobs&c=company&view=company&layout=companies" title="<?php echo Text::_('Companies'); ?>">
                                <?php echo Text::_('Companies'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                            if (isset($this->application->id)){
                                echo Text::_('Edit Company');
                            }else{
                                echo Text::_('Add Company');
                            } ?>
                        </li>
                    </ul>
                    </ul>
                </div>
            </div>
            <div id="jsjobs-wrapper-top-right">
                <div id="jsjobs-config-btn">
                    <a href="index.php?option=com_jsjobs&c=company&view=company&layout=companies" title="<?php echo Text::_('Configuration'); ?>">
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
                    echo Text::_('Edit Company');
                }else{
                    echo Text::_('Add Company');
                } ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
        <form action="index.php" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data"  >
            <div class="js-form-area">
                <input type="hidden" name="check" value="post"/>
                  <?php
                    function getRow($title,$value,$labelfor,$fullwidth='',$wrp=''){
                        $html = '<div class="js-form-wrapper '.$fullwidth.'">
                                    <label class="jsjobs-title" for="'.$labelfor.'">'.$title.'</label>
                                    <div class="jsjobs-value '.$wrp.'">'.$value.'</div>
                                </div>';
                        return $html;
                    }
                    $i = 0;
                    $customfieldobj = getCustomFieldClass();
                    foreach ($this->fieldsordering as $field) {
                        //echo '<br> uf'.$field->field;
                    switch ($field->field) {
                    
                    case "user":
                            // $labelfor = "uid";
                            // $title = Text::_($field->fieldtitle).': '; 
                            // if ($field->required == 1) { 
                            //         $title .= '&nbsp;<font color="red">*</font>'; 
                            //     } 
                            // $value = $this->lists['uid'];
                            // echo getRow($title,$value,$labelfor);
                        break;
                    case "jobcategory":
                            $labelfor = "category";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $value = $this->lists['category'];
                            echo getRow($title,$value,$labelfor);
                        break;
                    case "name":
                            $name_required = ($field->required ? 'required' : '');
                            $labelfor = "name";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->name : '';
                            $value = '<input class="inputbox "'.$name_required.'" type="text" name="name" id="name" size="40" maxlength="255" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;
                    case "url":
                            $url_required = ($field->required ? 'required' : '');
                            $labelfor = "validateurl";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->url : '';
                            $value = '<input class="inputbox validate-url '.$url_required.' " type="text"   id="validateurl" name="url" onblur="checkUrl(this);" size="40" maxlength="100" value="'.$fieldvalue.'"/>';
                            echo getRow($title,$value,$labelfor);
                        break;
                    case "contactname":
                            $contactname_required = ($field->required ? 'required' : '');
                            $labelfor = "contactname";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->contactname : '';
                            $value = '<input class="inputbox '.$contactname_required.'" type="text" name="contactname" id="contactname" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;  
                    case "contactphone":
                            $contactphone_required = ($field->required ? 'required' : '');
                            $labelfor = "contactphone";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->contactphone : '';
                            $value = '<input class="inputbox '.$contactphone_required.'" type="text" name="contactphone" id="contactphone" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;  
                    case "contactfax":
                            $fax_required = ($field->required ? 'required' : '');
                            $labelfor = "companyfax";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->companyfax : '';
                            $value = '<input class="inputbox '.$fax_required.'" type="text" name="companyfax" id="companyfax"  size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;
                    case "contactemail":
                            $email_required = ($field->required ? 'required' : '');
                            $labelfor = "contactemail";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->contactemail : '';
                            $value = '<input class="inputbox '.$email_required.'" type="text" name="contactemail" id="contactemail" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);

                        break; 
                    case "since":
                            $since_required = ($field->required ? 'required' : '');
                            if (isset($this->job))
                            $labelfor = "since";                                            
                            $title = Text::_($field->fieldtitle).': ';
                            if ($field->required == 1) { 
                                $title .= '&nbsp;:<font color="red">*</font>'; 
                            }
                            if (isset($this->company) && $this->company->since != '' && strpos($this->company->since , '1970') === false) {
                                $value = HTMLHelper::_('calendar', HTMLHelper::_('date', $this->company->since, $this->config['date_format']), 'since', 'since', $js_dateformat, array('class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19'));
                            } else {
                                $value = HTMLHelper::_('calendar', '', 'since', 'since', $js_dateformat, array('class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19'));
                            }
                            $value .= '<input type="hidden" id="company-since-required" name="company-since-required" for="since" value="'.$since_required.'" />';
                            echo getRow($title,$value,$labelfor); 
                        break; 
                    case "companysize":
                            $companysize_required = ($field->required ? 'required' : '');
                            $labelfor = "companysize";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->companysize : '';
                            $value = '<input class="inputbox '.$companysize_required.'" type="text" name="companysize" class="validate-numeric" id="companysize" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;
                    case "income":
                            $income_required = ($field->required ? 'required' : '');
                            $labelfor = "income";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->income : '';
                            $value = '<input class="inputbox '.$income_required.'" type="text" name="income" id="income" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;  
                    case "description":
                                $description_required = ($field->required ? 'required' : '');
                                $labelfor = "description";
                                $title = Text::_($field->fieldtitle);
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                                          
                                    if (isset($this->company))
                                        $value = $editor->display('description', $this->company->description, '550', '300', '60', '20', false);
                                    else
                                        $value = $editor->display('description', '', '550', '300', '60', '20', false);
                                    $value .='<input type=\'hidden\' id=\'company-description-required\' name="company-description-required" value="'.$description_required.'">';
                               
                                echo getRow($title,$value,$labelfor,'full-width');
                            break;  
                    case "city":
                                $city_required = ($field->required ? 'required' : '');
                                $labelfor = "city";
                                $title = Text::_($field->fieldtitle);
                                if ($field->required == 1) { 
                                    $title .= '&nbsp;:<font color="red">*</font>'; 
                                }
                                $fieldvalue = (isset($this->multiselectedit)) ? $this->multiselectedit : '';
                                $value = '<input class="inputbox "'.$city_required.'" type="text" name="city" id="city" size="40" maxlength="100" value="" />';
                                $value .='<input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                                echo getRow($title,$value,$labelfor);
                    
                            // $city_required = ($field->required ? 'required' : '');
                            // $labelfor = "city";
                            // $title = Text::_('City').': '; 
                            // if ($field->required == 1) { 
                            //         $title .= '&nbsp;<font color="red">*</font>'; 
                            //     } 
                            // $fieldvalue= (isset($this->multiselectedit)) ? trim($this->multiselectedit) : '';
                            // $value = '<input class="inputbox '.$city_required.'" type="text" name="city" id="city" size="40" maxlength="100" value="" />';
                            // $value .= '<input class="inputbox" type="hidden" name="citynameforedit" id="citynameforedit" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            // echo getRow($title,$value,$labelfor);
                        break;
                    case "zipcode":
                            $zipcode_required = ($field->required ? 'required' : '');
                            $labelfor = "zipcode";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->zipcode : '';
                            $value = '<input class="inputbox '.$zipcode_required.'" maxlength="25" type="text" name="zipcode" id="zipcode" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;
                    case "address1":
                            $address1_required = ($field->required ? 'required' : '');
                            $labelfor = "address1";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->address1 : '';
                            $value = '<input class="inputbox '.$address1_required.'" type="text" name="address1" id="address1" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;
                    case "address2":
                            $address2_required = ($field->required ? 'required' : '');
                            $labelfor = "address2";
                            $title = Text::_($field->fieldtitle).': '; 
                            if ($field->required == 1) { 
                                    $title .= '&nbsp;<font color="red">*</font>'; 
                                } 
                            $fieldvalue= (isset($this->company)) ? $this->company->address2 : '';
                            $value = '<input class="inputbox '.$address2_required.'" type="text" name="address2" id="address2" size="40" maxlength="100" value="'.$fieldvalue.'" />';
                            echo getRow($title,$value,$labelfor);
                        break;  
                    case "logo":
                            $logo_required = ($field->required ? 'required' : '');
                            $labelfor = "logo";
                            $title = Text::_($field->fieldtitle).': ';
                            $value = '';
                            if (isset($this->company))
                            if ($this->company->logofilename != '') {
                                $value = '<input type="checkbox" id="deletelogo" name="deletelogo" value="1">';
                                $value .= Text::_('Delete Logo File') . '[' . $this->company->logofilename . ']';
                                $filepath = Uri::root().$this->config['data_directory'].'/data/employer/comp_'.$this->company->id.'/logo/'.$this->company->logofilename;
                                $value .= '<img width="100px" src="'.$filepath.'" />';
                            } 
                            if ($field->required == 1) {
                                $title .= '&nbsp;<font color="red">*</font>'; 
                            }

                            $fieldvalue = (isset($this->company)) ? $this->company->logofilename : '';
                            $value .= Text::_('Upload Company Logo').'<br><input type="file" class="inputbox" name="logo" id="logo" size="20" maxlenght="30" />';     
                            $value .='<br><small>'.Text::_('Max File Size').' '.$this->config['company_logofilezize'].' - KB</small><br>';
                            
                            echo getRow($title,$value,$labelfor); ?>
                            <input type='hidden' id='company-logo-required' name="company-logo-required" value="<?php echo $logo_required; ?>">
                            <input type='hidden' id='company-logofilename' value="<?php echo $fieldvalue;?>">
                        <?php
                        break;
                    case "smalllogo":
                            $slogo_required = ($field->required ? 'required' : ''); 
                            $labelfor = "smalllogo";
                            $title = Text::_($field->fieldtitle).': ';
                            $value = '';
                            if (isset($this->company))
                            if ($this->company->smalllogofilename != '') {
                                $value = '<input type="checkbox" id="deletesmalllogo" name="deletesmalllogo" value="1">';
                                $value .= Text::_('Delete Small Logo') . '[' . $this->company->smalllogofilename . ']';
                            } 
                            if ($field->required == 1) {
                                $title .= '&nbsp;<font color="red">*</font>'; 
                            }

                            //$fieldvalue = (isset($this->company)) ? $this->company->logofilename : '';
                            $value .='<input type="file" class="inputbox "'.$slogo_required.'" name="smalllogo" size="20" maxlenght="30"/>';
                            
                            echo getRow($title,$value,$labelfor);
                        break;
                     case "aboutcompany":
                            $abcom_required = ($field->required ? 'required' : '');
                            $labelfor = "aboutcompany";
                            $title = Text::_($field->fieldtitle).': ';
                            $value = '';
                            if (isset($this->company))
                            if ($this->company->aboutcompanyfilename != '') {
                                $value = '<input type="checkbox" id="deleteaboutcompany" name="deleteaboutcompany" value="1">';
                                $value .= Text::_('Delete About Company') . '[' . $this->company->aboutcompanyfilename . ']';
                            } 
                            if ($field->required == 1) {
                                $title .= '&nbsp;<font color="red">*</font>'; 
                            }
                            $value .='<input type="file" class="inputbox "'.$abcom_required.'" name="aboutcompany" size="20" maxlenght="30"/>';
                            
                            echo getRow($title,$value,$labelfor);
                        break;

                    default:
                        $params = NULL;
                        $id = NULL;
                        if(isset($this->company)){
                            $id = $this->company->id; 
                            $params = $this->company->params; 
                        }
                        $array = $customfieldobj->formCustomFields($field , $id , $params , 'admin');
                        if(!empty($array)) {
                            $array['wrp']='';
                            if ($field->required == 1) { 
                                $array['title'] .= '&nbsp;:<font color="red">*</font>'; 
                            }
                            if ($field->isuserfield == 1 &&($field->userfieldtype =='checkbox' || $field->userfieldtype =='radio')) { 
                                $array['wrp'] = 'js-jobs-main-wrp'; 
                            } elseif ($field->isuserfield == 1 && $field->userfieldtype =='file') { 
                                $array['wrp'] = 'js-jobs-main-wrp js-jobs-file-wrp '; 
                            }
                            echo getRow($array['title'],$array['value'],$array['lable'],'',$array['wrp']);
                        }

                    break;
                }
            }
                   echo '<input type="hidden" id="userfields_total" name="userfields_total"  value="' . $i . '"  />';
 ?>
                <div class="js-form-wrapper">
                    <label class="jsjobs-title" for="status"><?php echo Text::_('Status'); ?>:&nbsp;</label>
                    <div class="jsjobs-value"><?php echo $this->lists['status']; ?></div>
                </div>
               <?php
if (isset($this->company)) {
    if (($this->company->created == '0000-00-00 00:00:00') || ($this->company->created == ''))
        $curdate = date('Y-m-d H:i:s');
    else
        $curdate = $this->company->created;
}else {
    $curdate = date('Y-m-d H:i:s');
}
?>              <?php if (isset($this->company->uid) && $this->company->uid != ''){ $uid = $this->company->uid;}else{ $uid = 0; } ?>
                <input type="hidden" name="uid" id="uid" value="<?php if (isset($this->company)) echo $uid; ?>" />
                <input type="hidden" name="created" value="<?php echo $curdate; ?>" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="callfrom" value="<?php echo $this->callfrom; ?>" />
                <input type="hidden" name="task" value="company.savecompany" />
                <input type="hidden" name="j_dateformat" id="j_dateformat" value="<?php echo $js_scriptdateformat; ?>" />
                <input type="hidden" name="id" value="<?php if (isset($this->company)) echo $this->company->id; ?>" />
                <?php echo HTMLHelper::_( 'form.token' ); ?>        
                </div>
                
            <div class="js-buttons-area">
                <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save Company'); ?>" />
                <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=company&view=company&layout=companies"><?php echo Text::_('Cancel'); ?></a>
            </div>
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

<script type="text/javascript">
    jQuery(document).ready(function () {
        var cityname = jQuery("#citynameforedit").val();
        if (cityname != "") {
            jQuery("#city").tokenInput("<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo Text::_('Type In A Search'); ?>",
                noResultsText: "<?php echo Text::_('No Results'); ?>",
                searchingText: "<?php echo Text::_('Searching...'); ?>",
                //tokenLimit: 1,
                prePopulate: <?php if (isset($this->multiselectedit))
                    echo $this->multiselectedit;
                else
                    echo "''";
                ?>
            });
        } else {
            jQuery("#city").tokenInput("<?php echo Uri::root() . "index.php?option=com_jsjobs&c=cities&task=getaddressdatabycityname"; ?>", {
                theme: "jsjobs",
                preventDuplicates: true,
                hintText: "<?php echo Text::_('Type In A Search'); ?>",
                noResultsText: "<?php echo Text::_('No Results'); ?>",
                searchingText: "<?php echo Text::_('Searching...'); ?>",
                //tokenLimit: 1
            });
        }

        jQuery('input.cf_uploadfile').change(function(){
            companyCustomUploadFileSelection(this);
        });
    });

    function validate_url() {
        var value = jQuery("#validateurl").val();
        if (typeof value != 'undefined') {
            if (value != '') {
                if (value.match(/^(http|https|ftp)\:\/\/\w+([\.\-]\w+)*\.\w{2,4}(\:\d+)*([\/\.\-\?\&\%\#]\w+)*\/?$/i) ||
                        value.match(/^mailto\:\w+([\.\-]\w+)*\@\w+([\.\-]\w+)*\.\w{2,4}$/i))
                {
                    return true;
                }
                else
                {
                    jQuery("#validateurl").addClass("invalid");
                    alert("<?php echo Text::_("Please enter the correct company address"); ?>");
                    return false;
                }
            }
        }
        return true;
    }

    function validate_since() {
        var date_since_make = new Array();
        var split_since_value = new Array();

        f = document.adminForm;
        var returnvalue = true;
        var today = new Date()
        today.setHours(0, 0, 0, 0);

        var since_string = document.getElementById("since").value;
        var format_type = document.getElementById("j_dateformat").value;
        if (format_type == 'd-m-Y') {
            split_since_value = since_string.split('-');

            date_since_make['year'] = split_since_value[2];
            date_since_make['month'] = split_since_value[1];
            date_since_make['day'] = split_since_value[0];


        } else if (format_type == 'm/d/Y') {
            split_since_value = since_string.split('/');
            date_since_make['year'] = split_since_value[2];
            date_since_make['month'] = split_since_value[0];
            date_since_make['day'] = split_since_value[1];


        } else if (format_type == 'Y-m-d') {

            split_since_value = since_string.split('-');

            date_since_make['year'] = split_since_value[0];
            date_since_make['month'] = split_since_value[1];
            date_since_make['day'] = split_since_value[2];
        }
        var sincedate = new Date(date_since_make['year'], date_since_make['month'] - 1, date_since_make['day']);

        if (sincedate > today) {
            jQuery("#since").addClass("invalid");
            alert("<?php echo Text::_("Start date must be less than today"); ?>");
            returnvalue = false;
        }
        return returnvalue;
    }

    function companyCustomUploadFileSelection(input ) {
        var photoValidated = 1;
        var photo = input.files[0];

        var filesize = <?php echo $this->config['document_file_size']; ?>;

        var fileTypes_image = "<?php echo strtolower($this->config['image_file_type']); ?>";
        var fileTypes_file = "<?php echo strtolower($this->config['document_file_type']); ?>";
        var fileTypes = fileTypes_image + ','+fileTypes_file;
        var fileTypesArray = fileTypes.split(",");

        var photoExt = photo.name.split(".").pop();
        var photoSize = (photo.size / 1024);

        if (jQuery.inArray(photoExt, fileTypesArray) < 0) {
            alert("<?php echo Text::_('File extension mismatched'); ?>");
            jQuery(input).val("");
        }
        if (photoSize > filesize) {
            alert("<?php echo Text::_('File size exceeded'); ?>");
            jQuery(input).val("");
        }
    }
</script>
