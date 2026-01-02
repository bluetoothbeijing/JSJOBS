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

$document = Factory::getDocument();
use Joomla\CMS\Language\Text;


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
            <div id="jsjobs_info_heading"><?php echo Text::_('User Stats'); ?></div>

            <form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
                <div id="js_job_filter">
                    <span class="js_job_Filter_title"><?php echo Text::_('Filter'); ?></span>
                    <div class="js_job_filter_field_wrapper">
                        <span class="title"><?php echo Text::_('Name'); ?></span>
                        <input type="text" name="searchname" id="searchname" value="<?php if (isset($this->lists['searchname'])) echo $this->lists['searchname']; ?>" class="text_area" onchange="document.adminForm.submit();" />
                    </div>
                    <div class="js_job_filter_field_wrapper">
                        <span class="title"><?php echo Text::_('Username'); ?></span>
                        <input type="text" name="searchusername" id="searchusername" size="15" value="<?php if (isset($this->lists['searchusername'])) echo $this->lists['searchusername']; ?>" class="text_area" onchange="document.adminForm.submit();" />
                    </div>
                    <div class="js_job_filter_field_wrapper">
                        <button onclick="this.form.submit();"><?php echo Text::_('Go'); ?></button>
                        <button onclick="document.getElementById('searchname').value = '';
                                document.getElementById('searchusername').value = '';
                                this.form.submit();"><?php echo Text::_('Reset'); ?></button>
                    </div>
                </div>
                <table class="adminlist" cellpadding="1">
                    <thead>
                        <tr>
                            <th width="2%" class="title">
                                <?php echo Text::_('Num'); ?>
                            </th>
                            <th class="title"><?php echo Text::_('Name'); ?></th>
                            <th  class="title" ><?php echo Text::_('Username'); ?></th>
                            <th  class="title" ><?php echo Text::_('Company'); ?></th>
                            <th  class="title" ><?php echo Text::_('Resume'); ?></th>
                            <th  class="title"><?php echo Text::_('Companies'); ?></th>
                            <th  class="title" nowrap="nowrap"><?php echo Text::_('Jobs'); ?></th>
                            <th  class="title" nowrap="nowrap"><?php echo Text::_('Resumes'); ?></th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                <?php echo $this->pagination->getListFooter(); ?>
                            </td>
                        </tr>
                    </tfoot>
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
                                <td><?php echo $row->name; ?></td>
                                <td><?php echo $row->username; ?>	</td>
                                <td><?php echo $row->companyname; ?>	</td>
                                <td><?php echo $row->resumename; ?>	</td>

                                <?php if ($row->rolefor == 1) { // employer  ?>
                                    <td align="center"><a href="index.php?option=com_jsjobs&c=user&view=user&layout=userstate_companies&md=<?php echo $row->id; ?>"><strong><?php echo $row->companies; ?></strong></a></td>
                                    <td align="center"><a href="index.php?option=com_jsjobs&c=user&view=user&layout=userstate_jobs&bd=<?php echo $row->id; ?>"><strong><?php echo $row->jobs; ?></a></strong></td>
                                    <td align="center"><strong>-</strong></td>
                                <?php } elseif ($row->rolefor == 2) { //jobseeker  ?>
                                    <td align="center"><strong>-</strong></td>
                                    <td align="center"><strong>-</strong></td>
                                    <td align="center"><a href="index.php?option=com_jsjobs&c=user&view=user&layout=userstate_resumes&ruid=<?php echo $row->id; ?>"><strong><?php echo $row->resumes; ?></a></strong></td>
                                <?php } else { ?>
                                    <td align="center"><strong>-</strong></td>
                                    <td align="center"><strong>-</strong></td>
                                    <td align="center"><strong>-</strong></td>
                                <?php } ?>

                            </tr>
                            <?php
                            $k = 1 - $k;
                        }
                        ?>
                    </tbody>
                </table>

                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="view" value="user" />
                <input type="hidden" name="layout" value="userstats" />
                <input type="hidden" name="task" value="view" />
                <input type="hidden" name="boxchecked" value="0" />
                <?php echo HTMLHelper::_('form.token'); ?>
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
