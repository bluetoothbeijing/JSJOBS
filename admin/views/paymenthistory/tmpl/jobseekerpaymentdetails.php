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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

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
                            <a href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=jobseekerpaymenthistory" title="<?php echo Text::_('Payment History'); ?>">
                                <?php echo Text::_('Job Seeker Payment History'); ?>
                            </a>
                        </li>
                        <li>
                            <?php echo Text::_('Payment History Details'); ?>
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
                <?php echo Text::_('Payment History Details');?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp">
        <div class="js-phd-head"><?php echo $this->items->packagetitle; ?></div>
        <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="js-phd-data" class="js-phd-data">
            <div class="js-phd-row bg">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Payer Name');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php echo $this->items->payer_firstname . ' ' . $this->items->payer_lastname;?></div>
            </div>
            <div class="js-phd-row">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Payer E-mail');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php echo $this->items->payer_email;?></div>
            </div>
            <div class="js-phd-row bg">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Payer Amount');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php echo $this->items->payer_amount;?></div>
            </div>
            <div class="js-phd-row">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Payer Item Name');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php echo $this->items->payer_itemname;?></div>
            </div>
            <div class="js-phd-row bg">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Payer Item Name2');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php echo $this->items->payer_itemname1;?></div>
            </div>
            <div class="js-phd-row">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Transaction Verified');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php if ($this->items->transactionverified == 1) echo Text::_('Approve'); else echo Text::_('Reject'); ?></div>
            </div>
            <div class="js-phd-row bg">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Transaction Auto Verified');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php if ($this->items->transactionautoverified == 1) echo Text::_('Auto Approve'); else echo Text::_('Manual Approved'); ?></div>
            </div>
            <div class="js-phd-row">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Verify Date');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value">
                    <?php
                    if($this->items->verifieddate != ''){
                        echo HTMLHelper::_('date', $this->items->verifieddate, $this->config['date_format']);
                    }
                    ?>
                </div>
            </div>
            <div class="js-phd-row bg">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Paid Amount');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php echo $this->items->paidamount; ?></div>
            </div>
            <div class="js-phd-row">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Created');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php echo HTMLHelper::_('date', $this->items->created, $this->config['date_format']); ?></div>
            </div>
            <div class="js-phd-row bg">
                <div class="js-col-xs-12 js-col-md-3 js-phd-title"><?php echo Text::_('Status');?></div>
                <div class="js-col-xs-12 js-col-md-3 js-phd-value"><?php if ($this->items->status == 1) echo Text::_('Approved'); else echo Text::_('Rejected'); ?></div>
            </div>
        </div>
            <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="c" value="paymenthistory" />
            <input type="hidden" name="view" value="paymenthistory" />
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
