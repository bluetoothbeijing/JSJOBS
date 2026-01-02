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
jimport('joomla.html.pane');
HTMLHelper::_('behavior.formvalidator');
if (JVERSION < 4){
	HTMLHelper::_('behavior.modal');
}

?>


<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("div#full_background , img#popup_cross").click(function () {
            closePopup();
        });
        jQuery("div#popup_main").on(  "click" , "a.jsjobs_js-userpopup-link" ,function () {
            var uid = jQuery(this).attr('data-id');
            var username = jQuery(this).attr('data-username');
            jQuery('span#employername').html(name);
            jQuery('input#username').val(username);
            closePopup();
        });        

    });

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
    function getUserList(user_limit = 0){
        var searchname = jQuery('#searchname').val();
        var searchusername = jQuery('#searchusername').val();
        var searchrole = jQuery('#searchrole').prop('selectedIndex');
        
        jQuery.post("index.php?option=com_jsjobs&task=user.getuserlistajax", {searchname: searchname , searchusername, searchusername ,searchrole : searchrole, userlimit : user_limit }, function (data) {
            if (data) {
                jQuery('div#popup-record-data').html(data);
                showPopup();
            }else{
                jQuery('div#popup-record-data').html('');
            }
        });
    }

    function showPopup() {
        jQuery("div#full_background").css("display", "block");
        jQuery("div#popup_main").slideDown('slow');
    }

    function closePopup() {
        jQuery("div#popup_main").slideUp('slow');
        setTimeout(function () {
            jQuery("div#full_background").hide();
            jQuery('div#popup-record-data').html('');
        }, 700);
    }
</script>
<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>
    <div style="display:none;" id="full_background"></div>
    <div style="display:none;" id="popup_main">
        <span class="popup-top"><span id="popup_title"><?php echo Text::_('Select User'); ?></span><img id="popup_cross" src="components/com_jsjobs/include/images/popup-close.png"></span>
        <div class="jobs-comp-employer-popupwrapper">
            <div class="search-center">
                <div class="jsjobs-searchpopupwrapper">
                    <div class="search-value">
                       <input placeholder="<?php echo Text::_('Name'); ?>" type="text" name="searchname" id="searchname" class="text_area" />
                    </div>
                    <div class="search-value">
                        <input placeholder="<?php echo Text::_('User Name'); ?>" type="text" name="searchusername" id="searchusername" size="15" class="text_area" />
                    </div>
                    <div class="search-value">
                        <?php echo HTMLHelper::_('select.genericList', $this->getJSModel('common')->getUserRole('Select User Role'), 'searchrole', 'class="inputbox" ', 'value', 'text',''); ?>
                    </div>
                    <div class="search-value-button search-uservalue-button">
                        <div class="js-button ">
                            <input type="submit" class="submit-button employer-popup-search-btn" onclick="getUserList();" value="<?php echo Text::_('Search');?>" />
                        </div>
                        <div class="js-button">
                            <input type="submit" class="employer-popup-reset-btn" onclick="document.getElementById('searchusername').value = ''; document.getElementById('searchname').value = ''; document.getElementById('searchrole').value = ''; getUserList();" value="<?php echo Text::_('Reset');?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="popup-record-data" style="display:inline-block;width:100%;"></div>
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
                            <a href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory" title="<?php echo Text::_('Payment History'); ?>">
                                <?php echo Text::_('Payment History'); ?>
                            </a>
                        </li>
                        <li>
                            <?php
                                echo Text::_('Payment History Details');
                            ?>
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
                    echo Text::_('Payment History Details');
                ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
        <form action="index.php" method="POST" name="adminForm" id="adminForm" >
            <div class="jsjobs-content-area js-form-area">
                
                    <?php
                    $td = array('row0', 'row1');
                    $k = 0;
                    //$td=array('','');
                    $userlink = 'index.php?option=com_jsjobs&c=user&view=user&layout=users&tmpl=component&task=preview';
                    ?>
                    <div class="js-form-wrapper <?php echo $td[$k];
                    $k = 1 - $k;
                    ?>">
                        <label class="jsjobs-title" id="usernamemsg" for="username"><?php echo Text::_('User Name'); ?><font color="red">*</font> </label>
                        <div class="jsjobs-value">
                            <input  class="inputbox required" type="text" name="username" id="username" value="<?php
                            if (isset($this->user)) {
                                echo $this->user->username;
                            } else {
                                echo "";
                            }
                    ?>" />
                            <a onclick="getUserList();" class="modal" rel="{handler: 'iframe', size: {x: 870, y: 350}}" id="user-popup" href="javascript:void(0);"><?php echo Text::_('Select User') ?></a>
                        </div>
                       
                    </div>
                    <div class="js-form-wrapper <?php echo $td[$k];
                        $k = 1 - $k;
                    ?>">
                        <label id="userpackagemsg" for="packageid"><?php echo Text::_('Package'); ?><font color="red">*</font></label>
                        <div class="jsjobs-value">
                            <div id="package"></div>
                        </div>
                    </div>
                    <div><div></div></div>
            </div>
                    <div class="js-buttons-area">
                        <input type="submit" class="js-btn-save" name="submit_app" onclick="return validate_form(document.adminForm)" value="<?php echo Text::_('Save'); ?>" />
                        <a class="js-btn-cancel" href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=employerpaymenthistory"><?php echo Text::_('Cancel'); ?></a>
                    </div> 
                <input type="hidden" name="nisactive" value="1" />
                <input type="hidden" name="check" value="" />
                <input type="hidden" name="task" value="paymenthistory.saveuserpackage" />
                <input type="hidden" name="userrole" id="userrole" value="" />
                <input type="hidden" name="userid" id="userid" value="" />
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <?php echo HTMLHelper::_( 'form.token' ); ?>        
        </form>
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
</div>

<script language="javascript">
    function setuser(username, userid) {
        jQuery.post("index.php?option=com_jsjobs&task=userrole.listuserdataforpackage", {val: userid}, function (data) {
            if (data) {
                if (data != false) {
                    var obj = eval("(" + data + ")");//return Value
                    document.getElementById('package').innerHTML = obj.list;
                    document.getElementById('username').value = username;
                    document.getElementById('userrole').value = obj.userrole;
                    document.getElementById('userid').value = userid;
                } else {
                    alert("<?php echo Text::_("Selected user is not js jobs system user, please asign role to user user roles > Users > Change Role") ?>");
                }
            }
        });
    }
    function closeme() {
        parent.SqueezeBox.close();
    }
</script>
