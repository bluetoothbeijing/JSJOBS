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
use Joomla\CMS\Language\Text;



$document = Factory::getDocument();

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
                            <?php echo Text::_('Information'); ?>
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
                <?php echo Text::_('Information'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n">
<!--  -->
        <div id="top_bluebar"><label><?php echo Text::_('Component Details'); ?></label></div><!--Top Blue Bar closed-->
        <div id="info_main_content">
            <div id="info_det">
                <label></label>
                <div class="jssst-author-info">
                    <span class="left_info"><?php echo Text::_('Created By'); ?></span>
                    <span class="right_info"><?php echo 'Ahmed Bilal'; ?></span>
                </div>
                <div class="jssst-author-info">
                    <span class="left_info"><?php echo Text::_('Company'); ?></span>
                    <span class="right_info"><?php echo Text::_('Joom Sky'); ?></span>
                </div>
                <div class="jssst-author-info">
                    <span class="left_info"><?php echo Text::_('Version'); ?></span>
                    <span class="right_info"><?php echo '1.3.6 - r'; ?></span>
                </div>
            </div><!--info det closed-->
            <div id="info_desc">
                    <img class="info_img_logo" src="components/com_jsjobs/include/images/logo-new.png">
                    <span class="info_line"></span>
                    <label><?php echo Text::_('About Joomsky'); ?></label>
                    <span class="info_text"><?php echo Text::_('Our philosophy on project development is quite simple. We deliver exactly what you need to ensure the growth and effective running of your business. To do this we undertake a complete analysis of your business needs with you, then conduct thorough research and use our knowledge and expertise of software development programs to identify the products that are most beneficial to your business projects.'); ?></span>
                    <a class="info_link" href="https://www.joomsky.com" target="_blank" ><?php echo 'www.joomsky.com'; ?></a>
            </div><!--info desc closed-->  
            
            <div id="info_datablock">
                <div class="info_block2">
                 <div class="info_block1_img">
                    <img class="img1" src="components/com_jsjobs/include/images/joomla-new.png">
                    <span class="title"><?php echo Text::_('JS Jobs'); ?></span>
                    <span class="type"><?php echo Text::_('Joomla'); ?></span>
                    <span class="detail"><?php echo Text::_('JS Jobs for any business, industry body or staffing company wishing to establish a presence on the internet where job seekers can come to view the latest jobs and apply to them.JS Jobs allows you to run your own, unique jobs classifieds service where you or employer can advertise their jobs and jobseekers can upload their Resumes.'); ?></span>
                    <span class="detail">
                        <a class="bottom_links_info pro_color1" href="https://www.joomsky.com/products/js-jobs-pro.html" target="_blank"><?php echo Text::_('Pro Download'); ?></a>
                        <a class="bottom_links_info" href="https://www.joomsky.com/products/js-jobs.html" target="_blank"><?php echo Text::_('Free Download'); ?></a>
                    </span> 
                 </div>
                </div>   
            </div> <!--data block closed-->

              <div id="info_datablock">
                <div class="info_block4">
                 <div class="info_block7_img">
                    <img class="img1" src="components/com_jsjobs/include/images/wordpress-new.png">
                    <span class="title"><?php echo Text::_('JS JOBS'); ?></span>
                    <span class="type"><?php echo Text::_('WordPress'); ?></span>
                    <span class="detail"><?php echo Text::_('JS Jobs for any business, industry body or staffing company wishing to establish a presence on the internet where job seekers can come to view the latest jobs and apply to them.JS Jobs allows you to run your own, unique jobs classifieds service where you or employer can advertise their jobs and jobseekers can upload their Resumes.'); ?></span>
                    <span class="detail">
                        <a class="bottom_links_info pro_color4" href="https://www.joomsky.com/products/js-jobs-pro-wp.html" target="_blank"><?php echo Text::_('Pro Download'); ?></a>
                        <a class="bottom_links_info" href="https://www.joomsky.com/products/js-jobs-wp.html" target="_blank"><?php echo Text::_('Free Download'); ?></a>
                    </span> 
                 </div>
                </div>   
            </div> <!--data block closed-->

              <div id="info_datablock">
                <div class="info_block4">
                 <div class="info_block4_img">
                    <img class="img1" src="components/com_jsjobs/include/images/wordpress-new.png">
                    <span class="title"><?php echo Text::_('JS Support Ticket'); ?></span>
                    <span class="type"><?php echo Text::_('WordPress'); ?></span>
                    <span class="detail"><?php echo Text::_('JS Support Ticket is a trusted open source ticket system. JS Support ticket is a simple, easy to use, web-based customer support system. User can create ticket from front-end. JS support ticket comes packed with lot features than most of the expensive').'('.Text::_('and complex').') '.Text::_('support ticket system on market.'); ?></span>
                    <span class="detail">
                        <a class="bottom_links_info pro_color4" href="https://jshelpdesk.com/" target="_blank"><?php echo Text::_('Pro Download'); ?></a>
                        <a class="bottom_links_info" href="https://jshelpdesk.com/" target="_blank"><?php echo Text::_('Free Download'); ?></a>
                    </span> 
                 </div>
                </div>   
            </div> <!--data block closed-->
              

            <div id="info_datablock">
                <div class="info_block6">
                 <div class="info_block6_img">
                    <img class="img1" src="components/com_jsjobs/include/images/joomla-new.png">
                    <span class="title"><?php echo Text::_('JS Support Ticket'); ?></span>
                    <span class="type"><?php echo Text::_('Joomla'); ?></span>
                    <span class="detail"><?php echo Text::_('JS Support Ticket is a trusted open source ticket system. JS Support ticket is a simple, easy to use, web-based customer support system. User can create ticket from front-end. JS support ticket comes packed with lot features than most of the expensive').'('.Text::_('and complex').') '.Text::_('support ticket system on market.'); ?></span>
                    <span class="detail">
                        <a class="bottom_links_info pro_color3" href="https://www.joomsky.com/products/js-support-ticket-pro-joomla.html" target="_blank"><?php echo Text::_('Pro Download'); ?></a>
                        <a class="bottom_links_info" href="https://www.joomsky.com/products/js-ticket-joomla.html" target="_blank"><?php echo Text::_('Free Download'); ?></a>
                    </span> 
                 </div>
                </div>   
            </div> <!--data block closed-->
              
           
          
        </div><!--info main content closed-->
    </div>
<div id="jsjobs-footer" class="jsjobs-inner-footer">
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
