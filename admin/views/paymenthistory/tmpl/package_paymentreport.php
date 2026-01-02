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
use Joomla\CMS\Filter\OutputFilter;

//HTMLHelper::_('behavior.calendar');
?>
<table width="100%">
    <tr>
        <td align="left" width="175" valign="top">
            <table width="100%"><tr><td style="vertical-align:top;">
                        <?php
                        include_once('components/com_jsjobs/views/menu.php');
                        ?>
                    </td>
                </tr></table>
        </td>
        <td width="100%" valign="top">
            <form action="index.php" method="post" name="adminForm" id="adminForm">
                <table>
                    <tr>
                        <td width="100%">
                            <strong><?php echo Text::_('Filter'); ?></strong>
                        </td>
                        <td>
                            <?php echo $this->lists['paymentstatus']; ?>
                        </td>&nbsp;
                        <td nowrap>
                            <?php echo Text::_('Start'); ?>:
                            <input type="text" name="searchstartdate" id="searchstartdate" value="<?php if (isset($this->lists['searchstartdate'])) echo $this->lists['searchstartdate']; ?>" class="text_area" onchange="document.adminForm.submit();" />
                            <input class="button" value="..." onclick="return showCalendar('searchstartdate', '%Y-%m-%d');" type="reset">
                        </td>&nbsp;
                        </td>
                        <td nowrap>
                            <?php echo Text::_('End'); ?>:
                            <input type="text" name="searchenddate" id="searchenddate" value="<?php if (isset($this->lists['searchenddate'])) echo $this->lists['searchenddate']; ?>" class="text_area" onchange="document.adminForm.submit();" />
                            <input class="button" value="..." onclick="return showCalendar('searchenddate', '%Y-%m-%d');" type="reset">					
                        </td>&nbsp;
                        </td>
                        <td>
                            <button onclick="this.form.submit();"><?php echo Text::_('Go'); ?></button>
                        </td>&nbsp;

                        <td>
                            <button onclick="this.form.getElementById('searchpaymentstatus').value = '';
                                    document.getElementById('searchstartdate').value = '';
                                    document.getElementById('searchenddate').value = '';
                                    this.form.submit();"><?php echo Text::_('Reset'); ?></button>
                        </td>
                    </tr>
                </table>


                <table class="adminlist" border="0">
                    <thead>
                        <tr>
                            <th><?php echo Text::_('Title'); ?></th>
                            <th><?php echo Text::_('Package For'); ?></th>
                            <th><?php echo Text::_('Name'); ?></th>
                            <th><?php echo Text::_('Payer Name'); ?></th>
                            <th><?php echo Text::_('Paid Amount'); ?></th>
                            <th><?php echo Text::_('Payment Status'); ?></th>
                            <th ><?php echo Text::_('Created'); ?></th>



                        </tr>
                    </thead>
                    <?php
                    jimport('joomla.filter.output');
                    $k = 0;


                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = HTMLHelper::_('grid.id', $i, $row->id);
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=paymenthistory.edit&cid[]=' . $row->id);
                        ?>
                        <tr valign="top" class="<?php echo "row$k"; ?>">

                            <td><?php echo $row->packagetitle; ?></td>
                            <td align="center"><?php echo $row->packagefor; ?></td>
                            <td align="center"><?php echo $row->buyername; ?></td>
                            <td align="center"><?php echo $row->payer_firstname; ?></td>
                            <td align="center"><?php if ($row->paidamount) echo $this->config['currency'] . $row->paidamount; ?></td>
                            <td align="center"><?php if ($row->transactionverified == 1)
                        echo Text::_('Verified');
                    else
                        echo Text::_('Not Verified');
                    ?></td>
                            <td align="center"><?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?></td>

                        </tr>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>
                    <tr>
                        <td colspan="9">
<?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="c" value="paymenthistory" />
                <input type="hidden" name="view" value="paymenthistory" />
                <input type="hidden" name="layout" value="package_paymentreport" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
            </form>
        </td>
    </tr>
</table>							
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
