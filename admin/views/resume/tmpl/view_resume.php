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
global $mainframe;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

$document = Factory::getDocument();

$document->addStyleSheet(Uri::root() . 'components/com_jsjobs/css/token-input-jsjobs.css');
$document->addStyleSheet(Uri::root() . '/components/com_jsjobs/css/style.css');
$document->addStyleSheet(Uri::root() . '/components/com_jsjobs/css/style_color.php');
?>

<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>    
    <div id="jsjobs-content">
        <div id="jsjobs-heading"><a id="backimage" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel"><img src="components/com_jsjobs/include/images/back-icon.png" alt="<?php echo Text::_('Back');?>" ></a><span id="heading-text"><?php echo Text::_('View Resume'); ?></span></div>
            
            <?php
            $printform = 1;
            if ((isset($this->resume)) && ($this->resume->id != 0)) { // not new form
                if ($this->resume->status == 1) { // Employment Application is actve
                    $printform = 1;
                }
            }
            if ($printform == 1) {
                ?>

                <table cellpadding="5" cellspacing="0" border="0" width="100%" class="admintable" >
                    <tr><td class="<?php echo $this->theme['heading']; ?>" align="center">
                            <?php echo Text::_('View Resume'); ?>
                        </td></tr>
                    <tr><td height="3"></td></tr>
                    <tr>
                        <td align="right" height="15"><a href="index.php?option=com_jsjobs&c=resume&view=report&layout=resume1&format=pdf&rd=<?php echo $this->resumeid; ?> " target="_blank">
                                <img src="../components/com_jsjobs/images/pdf.png" width="36" height="36" /></a></td>
                    </tr>
                    <?php if (isset($_GET['vm'])) if (($_GET['vm'] == '2') || ($_GET['vm'] == '3')) { ?>
                            <?php
                            $jobid = Factory::getApplication()->input->get('jobid','');
                            $clink = 'index.php?option=com_jsjobs&c=jsjobs&view=jobseeker&layout=view_coverletters&vts=1&clu=' . $this->resume->uid . '&rd=' . $this->resumeid . '&jobid=' . $jobid . '&Itemid=' . $this->Itemid;
                            ?>
                            <tr><td align="right"><a href="<?php echo $clink; ?>" class="pageLink"><?php echo Text::_('View Cover Letters') ?></a></td></tr>
                    <?php } ?>
                </table>

                <?php
                    require_once(JPATH_ROOT . '/components/com_jsjobs/views/resume/tmpl/view_resume.php');
                ?>
                <?php
                if (isset($this->resume)) {
                    if (($this->resume->created == '0000-00-00 00:00:00') || ($this->resume->created == ''))
                        $curdate = date('Y-m-d H:i:s');
                    else
                        $curdate = $this->resume->created;
                } else
                    $curdate = date('Y-m-d H:i:s');
                ?>
                <?php
            } ?>    
    
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="oi" value="<?php echo $this->jobid; ?>" />
        <input type="hidden" name="fd" value="<?php echo $this->fd; ?>" />
        <input type="hidden" name="boxchecked" value="0" />
    </form>
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
