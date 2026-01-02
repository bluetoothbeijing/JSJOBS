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
                            <?php echo Text::_('Help'); ?>
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
                <?php echo Text::_('Help'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n jsstadmin-data-wrp">
            <!-- help page  -->
            <div class="jsjobs-help-top">
                <div class="jsjobs-help-top-left">
                    <div class="jsjobs-help-top-left-cnt-img">
                        <img alt="<?php echo Text::_('Configuration'); ?>" src="components/com_jsjobs/include/images/help-page/support-icon.jpg" />
                    </div>
                    <div class="jsjobs-help-top-left-cnt-info">
                        <h2><?php echo Text::_('We are here to help you'); ?></h2>
                        <p><?php echo Text::_('JS Jobs is a simple yet powerful Jobs board extension that provides a Step-by-step Youtube guide for its ease of use.'); ?></p>
                        <a href="https://www.youtube.com/channel/UCd2mQkY0Q3UZGpn4ROsf_hg/videos" target="_blank" class="jsjobs-help-top-middle-action" title="<?php echo Text::_('View all videos'); ?>">
                            <img alt="<?php echo Text::_('Video icon'); ?>" src="components/com_jsjobs/include/images/help-page/play-icon.jpg" />
                            <?php echo Text::_('View All Videos'); ?>
                        </a>
                    </div>
                </div>
                <div class="jsjobs-help-top-right">
                    <div class="jsjobs-help-top-right-cnt-img">
                        <img alt="<?php echo Text::_('Configuration'); ?>" src="components/com_jsjobs/include/images/help-page/support.png" />
                    </div>
                    <div class="jsjobs-help-top-right-cnt-info">
                        <h2><?php echo Text::_('JS Jobs Support','js-support-ticket'); ?></h2>
                        <p><?php echo Text::_("JS Jobs delivers timely customer support if you have any query then we're here to show you the way."); ?></p>
                        <a target="_blank" href="https://joomsky.com/support/" class="jsjobs-help-top-middle-action second" title="<?php echo Text::_('Submit ticket'); ?>">
                            <img alt="<?php echo Text::_('Video icon'); ?>" src="components/com_jsjobs/include/images/help-page/ticket.png" />
                            <?php echo Text::_('Submit Ticket'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="jsjobs-help-btm">
                <!-- js jobs installations -->
                <div class="jsjobs-help-btm-wrp">
                    <h2 class="jsjobs-help-btm-title"><?php echo Text::_('Installation'); ?></h2>
                    <?php
                        $title = Text::_('Installation of JS Jobs Free');
                        $url = '4F7Skm3I_Z8';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Installation of JS Jobs Pro');
                        $url = '273JEfzSDkQ';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('js jobs free to pro update');
                        $url = 'W0QqeTq2fDk';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('js jobs pro to pro update');
                        $url = 's9mY9LxslbE';
                        printVideoPlaylist($title, $url);
                    ?>
                </div>
                <!-- js jobs resume -->
                <div class="jsjobs-help-btm-wrp">
                    <h2 class="jsjobs-help-btm-title"><?php echo Text::_('resume'); ?></h2>
                    <?php
                        $title = Text::_('Featured resume in JS Jobs');
                        $url = 'UtJAgEsrp_A';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Add resume in JS Jobs');
                        $url = 'Q9yn3HgAYyM';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Gold resume in JS Jobs');
                        $url = 'JrZGOC3t1m0';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Resume PDF in JS Jobs');
                        $url = '3fy7CWiAGa4';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Resume search in JS Jobs');
                        $url = '9qgjVWLPxGY';
                        printVideoPlaylist($title, $url);
                    ?>
                </div>
                <!-- js jobs company -->
                <div class="jsjobs-help-btm-wrp">
                    <h2 class="jsjobs-help-btm-title"><?php echo Text::_('company'); ?></h2>
                    <?php
                        $title = Text::_('Gold company in JS Jobs');
                        $url = '2llzbZtxogE';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Create company in JS Jobs');
                        $url = '5bm6Sdn1AKA';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Featured company in JS Jobs');
                        $url = '2c3q17oHxxc';
                        printVideoPlaylist($title, $url);
                    ?>
                </div>
                <!-- js jobs job -->
                <div class="jsjobs-help-btm-wrp">
                    <h2 class="jsjobs-help-btm-title"><?php echo Text::_('Jobs'); ?></h2>
                    <?php
                        $title = Text::_('Job Search in JS Jobs');
                        $url = 'bkAVC72rkf4';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Create job in JS Jobs');
                        $url = '6DCdLa2Ty7o';
                        printVideoPlaylist($title, $url);
                    ?>
                    <?php
                        $title = Text::_('Featured job in JS Jobs');
                        $url = 'zf8LzfyVtZ0';
                        printVideoPlaylist($title, $url);
                    ?>
                </div>
            </div>
        </div>
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
</div>

<?php
    function printVideoPlaylist($video_title,$video_url){
        $html = '
        <div class="jsjobs-help-btm-cnt">
            <a href="https://www.youtube.com/watch?v='.$video_url.'" class="jsjobs-help-btm-link"  target="_blank" title="'.$video_title.'">
                <div class="jsjobs-help-btm-cnt-img">
                    <img alt="'.$video_title.'" src="components/com_jsjobs/include/images/help-page/video_image.png" />
                </div>
                <div class="jsjobs-help-btm-cnt-title">
                    <span>'.$video_title.'</span>
                </div>
            </a>
        </div>
        ';
        echo ($html);
    }
      
?>
