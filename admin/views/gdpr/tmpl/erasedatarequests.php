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
use Joomla\CMS\Language\Text;


Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');
?>

<script language=Javascript>
    function confirmdelete() {
        if (confirm("<?php echo Text::_('Are you sure to delete'); ?>") == true) {
            return true;
        } else
            return false;
    }
    jQuery(document).ready(function(){
       // jQuery('a.js-tk-button').tooltip();
    });
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
                            <?php echo Text::_('Erase Data Request'); ?>
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
                <?php echo Text::_('Erase Data Request'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
      <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="jsjobs_filter_wrapper">           
            <span class="jsjobs-filter"><input type="text" name="filter_email" id="filter_email" placeholder="<?php echo Text::_('Useremail') ?>" value="<?php if (isset($this->searchemail)) echo $this->searchemail; ?>" class="text_area"/></span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" onclick="document.getElementById('filter_email').value = ''; this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
        </div>
        <?php if (!(empty($this->result)) && is_array($this->result)) {  ?>
                <table class="adminlist" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th class="title"><?php echo Text::_("Subject"); ?></th>
                            <th class="title"><?php echo Text::_("Message"); ?></th>
                            <th class="center"><?php echo Text::_("Email"); ?></th>
                            <th class="center"><?php echo Text::_("User Role"); ?></th>
                            <th class="center"><?php echo Text::_("Request Status"); ?></th>
                            <th class="center"><?php echo Text::_("Created"); ?></th>
                            <th class="center"><?php echo Text::_("Action"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($this->result AS $request) { ?>
                            <tr>
                                <td><?php echo Text::_($request->subject); ?></td>
                                <td><?php echo Text::_($request->message); ?></td>
                                <td class="center"><?php echo $request->email; ?></td>
                                <?php 
                                    if($request->role == 1){ // employeer
                                        $userrole = "Employer";
                                    }else{
                                        $userrole = "Jobseeker";
                                    }
                                ?>
                                <td class="center"><?php echo Text::_($userrole); ?></td>
                                <td class="center">
                                  <?php if($request->status == 1){
                                      echo Text::_('Awaiting response');
                                  }elseif($request->status == 2){
                                    echo Text::_('Erased identifying data');
                                  }else{
                                    echo  Text::_('Deleted');
                                  }?>
                                </td>
                                <td class="center"><?php echo date($this->config['date_format'], strtotime($request->created)); ?></td>
                                <td class="center">
                                    <a class="js-tk-button" onclick="return confirmdelete()" href="index.php?option=com_jsjobs&c=gdpr&task=eraseidentifyinguserdata&id=<?php echo $request->uid; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1" data-toggle="tooltip" title="<?php echo Text::_("All the data belongs to this user will replace with dummy text"); ?>">
                                      <?php echo Text::_('Erase identifying data');?>
                                    </a>&nbsp;
                                    <a class="js-tk-button" onclick="return confirmdelete()" href="index.php?option=com_jsjobs&c=gdpr&task=deleteuserdata&id=<?php echo $request->uid; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1" data-toggle="tooltip" title="<?php echo Text::_("All the data belongs to this user will be deleted"); ?>">
                                      <?php echo Text::_('Delete data');?>
                                    </a>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        } ?>
                    </tbody>                    
                </table>
                <div id="jsjobs-pagination-wrapper">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
        <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
        <input type="hidden" name="c" value="gdpr" />
        <input type="hidden" name="layout" value="erasedatarequests" />
        <input type="hidden" name="view" value="gdpr" />
    </form>
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
