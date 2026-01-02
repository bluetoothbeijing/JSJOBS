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

$document = Factory::getDocument();
Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');

$status = array(
    '1' => Text::_('Approved'),
    '-1' => Text::_('Rejected'),
    '0' => Text::_('Pending'));
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
                            <a href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=payment_report" title="<?php echo Text::_('Payment Report'); ?>">
                                <?php echo Text::_('Payment Report'); ?>
                            </a>
                        </li>
                         <li>
                            <?php echo Text::_('Job Seeker Stats'); ?>
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
                <?php echo Text::_('Job Seeker Stats'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
     <form action="index.php" method="post" name="adminForm" id="adminForm">

    <?php if(!empty($this->items)){ ?>

                <table width="100%">
    <tr>
        
        <td width="100%" valign="top">

            <form action="index.php" method="post" name="adminForm" id="adminForm">

                <table class="adminlist" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th width="2%" class="title">
                                <?php echo Text::_('Num'); ?>
                            </th>
                            <th class="title"><?php echo Text::_('Name'); ?></th>
                            <th  class="title" ><?php echo Text::_('Application Title'); ?></th>
                            <th  class="title" ><?php echo Text::_('Category'); ?></th>
                            <th  class="center" ><?php echo Text::_('Created'); ?></th>
                            <th  class="center" ><?php echo Text::_('Status'); ?></th>                      </tr>
                    </thead>
                    <tbody>
                        <?php
                        $k = 0;
                        for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                            $row = $this->items[$i];
                            ?>
                            <tr class="<?php echo "row$k"; ?>">
                                <td>
                                    <?php echo $i + 1 + $this->pagination->limitstart; ?>
                                </td>
                                <td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
                                <td><?php echo $row->application_title; ?>  </td>
                                <td><?php echo $row->cat_title; ?>  </td>
                                <td align="center"><?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?></td>
                                <td class="center">
                                    <?php
                                    if ($row->status == 1)
                                        echo "<font color='green'>" . $status[$row->status] . "</font>";
                                    elseif ($row->status == -1)
                                        echo "<font color='red'>" . $status[$row->status] . "</font>";
                                    elseif ($row->status == 0)
                                        echo "<font color='blue'>" . $status[$row->status] . "</font>";
                                    ?>
                                </td>
                            </tr>
                            <?php
                            $k = 1 - $k;
                        }
                        ?>
                    </tbody>
                </table>
            </form>
        </td>
    </tr>
</table>                                        

                <div id="jsjobs-pagination-wrapper">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
        <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="c" value="user" />
                <input type="hidden" name="view" value="user" />
                <input type="hidden" name="layout" value="userstate_resumes" />
                <input type="hidden" name="ruid" value="<?php if($this->resumeuid) echo $this->resumeuid; else echo ''; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
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
