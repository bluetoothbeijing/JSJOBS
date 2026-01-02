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
$session = Factory::getApplication()->getSession();
//$session->clear('versiondata');
$virtuemarterror = $session->get('virtuemarterror');
if(!empty($virtuemarterror)){
    $session->clear('virtuemarterror');
}
$jomsocialerror = $session->get('jomsocialerror');
if(!empty($jomsocialerror)){
    $session->clear('jomsocialerror');
}
?>
<script language=Javascript>
function opendiv(elm,event){
    event.preventDefault();
    var form = jQuery(elm).parent(); 
    var key = form.find('#licensekey').val().trim();
    if(key == ''){
        jQuery(form).find('#licensekey').css('border','1px solid red');
        return false;
    }else{
        jQuery(form).find('#licensekey').removeAttr('style');
        document.getElementById('jsjob_installer_waiting_div').style.display='block';
        document.getElementById('jsjob_installer_waiting_span').style.display='block';
        form.submit();
    }
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
                            <?php echo Text::_('Premium Plugins'); ?>
                        </li>
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
                <?php echo Text::_('Premium Plugins'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
        <div style="display:none;" id="jsjob_installer_waiting_div" class="jsjob_premium_waiting_div"></div>
        <span style="display:none;" id="jsjob_installer_waiting_span"><?php echo Text::_("Please wait for installation in progress"); ?></span>
        <div id="jsjob-main-wrapper" >
            <div class="jsjob_premium_wrapper">
                <!-- virtuemart -->
                <?php
                    if(!empty($virtuemarterror)){
                        $class = 'jsjobs-error';
                    } else if(isset($this->virtuemart->vmVerified)){
                        $class = 'jsjobs-success';
                    } else {
                        $class = '';
                    }
                ?>
                <div class="jsjobs-plugin-wrap <?php echo $class; ?>">
                    <h1 class="jsjobs-plugin-heading"><?php echo Text::_("JS Jobs").' '.Text::_("for").' '.Text::_("VirtueMart"); ?></h1>
                    <h4 class="jsjobs-plugin-desc"><?php echo Text::_("Enables you to sell JS Jobs employer/jobseeker packages as virtuemart products"); ?></h4>
                    <div class="jsjobs-plugin-content">
                        <div class="jsjobs-pro-plugin"><?php echo Text::_("Pro Plugin"); ?></div>
                        <div class="alert alert-info"><?php echo Text::_("This plugin is only available for JS Jobs Pro"); ?></div>
                    </div>
                </div>
                <!-- virtuemart end -->

                <!-- JomSocial -->
                <div class="jsjobs-plugin-wrap <?php if(!empty($jomsocialerror)) echo 'jsjobs-error'; else if($this->jomsocial->jmVerified) echo 'jsjobs-success'; ?>">
                    <h1 class="jsjobs-plugin-heading"><?php echo Text::_("JS Jobs for JomSocial"); ?></h1>
                    <h4 class="jsjobs-plugin-desc"><?php echo Text::_("Shows JS Jobs data in JomSocial profile and enables users to post company/job/resume on JomSocial stream"); ?></h4>
                    <div class="jsjobs-plugin-content">
                    <?php
                    if( $this->jomsocial->jmEnabled ){
                        if( $this->jomsocial->jmVerified ){
                            ?>
                            <div class="alert alert-success"><?php echo Text::_("JS Jobs plugin for JomSocial is active"); ?></div>
                            <?php
                        }
                        ?>
                        <form action="index.php" method="post">
                            <?php
                            if( !$this->jomsocial->jmVerified ){
                                if( $this->jomsocial->jsjobsJmEnabled ){
                                    ?>
                                    <div class="alert alert-info"><?php echo Text::_("JS Jobs plugin for JomSocial is installed"); ?></div>
                                    <?php
                                }else{
                                    ?>
                                    <div class="alert alert-danger"><?php echo Text::_("JS Jobs plugin for JomSocial not installed"); ?></div>
                                    <?php
                                    if( !$this->writeable ){
                                        ?>
                                        <div class="alert alert-danger"><?php echo JPATH_ROOT.'/tmp/ '.Text::_("is not writeable"); ?></div>
                                        <?php
                                    }
                                }
                            }
                            if(!empty($jomsocialerror)){
                                ?>
                                <div class="alert alert-danger"><?php echo Text::_($jomsocialerror); ?></div>
                                <?php
                            }
                            ?>
                            <input type="text" id="licensekey" name="licensekey" autocomplete="off" placeholder="<?php echo Text::_("Enter license key"); ?>" class="jsjobs-inputbox">
                            <div class="jsjobs-lic-link">
                                <?php echo Text::_("Do not have license key"); ?>
                                <a href="https://www.joomsky.com/products/js-jobs/jomsocial.html" target="_blank"><?php echo Text::_("Purchase it here") ?></a>
                            </div>
                            <?php
                            if( $this->jomsocial->jsjobsJmEnabled ){
                                ?>
                                <button class="jsjobs-button" onclick="return opendiv(this,event);"><?php echo Text::_("Activate"); ?></button>
                                <?php
                            }else{
                                ?>
                                <button class="jsjobs-button" onclick="return opendiv(this,event);" <?php if(!$this->writeable) echo 'disabled'; ?>><?php echo Text::_("Install And Activate"); ?></button>
                                <?php
                            }
                            ?>
                            <input type="hidden" name="domain" id="domain" value="<?php echo Uri::root(); ?>" />
                            <input type="hidden" name="producttype" id="producttype" value="<?php echo $this->versiontype->configvalue;?>" />
                            <input type="hidden" name="productcode" id="productcode" value="jsjobs" />
                            <input type="hidden" name="productversion" id="productversion" value="<?php echo str_replace('.','',$this->versioncode->configvalue);?>" />
                            <input type="hidden" name="JVERSION" id="JVERSION" value="<?php echo JVERSION;?>" />
                            <input type="hidden" name="installerversion" id="installerversion" value="1.1" />
                            <input type="hidden" name="pluginfor" value="jomsocial" />
                            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                            <input type="hidden" name="callfrom" value="<?php echo $this->callfrom; ?>" />
                            <input type="hidden" name="task" value="premiumplugin.activatepremiumplugin" />
                            <?php echo HTMLHelper::_( 'form.token' ); ?>     
                        </form>
                        <?php
                    }else{
                        ?>
                        <div class="alert alert-danger"><?php echo Text::_("JomSocial not installed"); ?></div>
                        <?php
                    }
                    ?>
                    </div>
                </div>
                <!-- JomSocial end -->
            </div>
        </div>
        </div>
        

        
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
