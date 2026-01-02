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
use Joomla\CMS\Filter\OutputFilter;

$document = Factory::getDocument();
Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');
$status = array(
    '1' => Text::_('Approved'),
    '-1' => Text::_('Rejected'));

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
                            <?php echo Text::_('Department Approval Queue'); ?>
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
                <?php echo Text::_('Department Approval Queue'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
     <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="jsjobs_filter_wrapper">
            <span class="jsjobs-filter"><input type="text" name="searchdepartment" placeholder="<?php echo Text::_('Department Name'); ?>" id="searchdepartment" size="15" value="<?php if (isset($this->lists['searchdepartment'])) echo $this->lists['searchdepartment']; ?>" /></span>                   
            <span class="jsjobs-filter"><input type="text" name="searchcompany" placeholder="<?php echo Text::_('Company Name'); ?>"  id="searchcompany" size="15" value="<?php if (isset($this->lists['searchcompany'])) echo $this->lists['searchcompany']; ?>"  /></span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" onclick="document.getElementById('searchdepartment').value = '';
                            document.getElementById('searchcompany').value = '';
                            this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
        </div>
        <?php if(!empty($this->items)){ ?>

                <table class="adminlist" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th width="20">
                                <?php if (JVERSION < '3') { ?> 
                                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                                <?php } else { ?>
                                    <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                                <?php } ?>
                            </th>
                            <th class="title"><?php echo Text::_('Department'); ?></th>
                            <th class="title"><?php echo Text::_('Company'); ?></th>
                            <th class="center"  width="15%"><?php echo Text::_('Created'); ?></th>
                            <th class="center"  width="15%"><?php echo Text::_('Status'); ?></th>
                        </tr>
                    </thead><tbody>
                    <?php
                    jimport('joomla.filter.output');
                    $k = 0;
                    $approvetask = 'departmentapprove';
                    $approveimg = 'tick.png';
                    $rejecttask = 'departmentreject';
                    $rejectimg = 'cross.png';
                    $approvealt = Text::_('Approve');
                    $rejectalt = Text::_('Reject');
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = HTMLHelper::_('grid.id', $i, $row->id);
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=department.edit&cid[]=' . $row->id);
                        ?>
                        <tr valign="top" class="<?php echo "row$k"; ?>">
                            <td>
                                <?php echo $checked; ?>
                            </td>
                            <td class="title">
                                <a href="<?php echo $link; ?>">
                                    <?php echo $row->name; ?></a>
                            </td>
                            <td class="title">
                                <?php echo $row->companyname; ?>
                            </td>
                            <td class="center">
                                <?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?>
                            </td>
                            <td class="center">
                                <a href="index.php?option=com_jsjobs&c=common&task=department.<?php echo $approvetask; ?>&cid[]=<?php echo $row->id; ?>">
                                    <img src="components/com_jsjobs/include/images/<?php echo $approveimg; ?>"  alt="<?php echo $approvealt; ?>" title="<?php echo Text::_('Approve') ;?>"/></a>

                                &nbsp;&nbsp; - &nbsp;&nbsp
                                <a href="index.php?option=com_jsjobs&c=common&task=department.<?php echo $rejecttask; ?>&cid[]=<?php echo $row->id; ?>">
                                    <img src="components/com_jsjobs/include/images/<?php echo $rejectimg; ?>" alt="<?php echo $rejectalt; ?>" title="<?php echo Text::_('Reject') ;?>" /></a>

                            </td>
                        </tr>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>
                    </tbody>
                </table>
                <div id="jsjobs-pagination-wrapper">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
                <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="c" value="department" />
                <input type="hidden" name="view" value="department" />
                <input type="hidden" name="layout" value="departmentqueue" />
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
