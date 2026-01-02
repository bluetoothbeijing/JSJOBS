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


Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');


?>
<?php
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
                            <?php echo Text::_('Ages'); ?>
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
                <?php echo Text::_('Ages'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('age.editjobage');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Add New').' '.Text::_('Age'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Add New').' '.Text::_('Age'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <form action="index.php" method="post" name="adminForm" id="adminForm">
               <div id="jsjobs_filter_wrapper">           
                    <span class="jsjobs-filter"><input type="text" name="searchtitle" placeholder="<?php echo Text::_('Title'); ?>" id="searchtitle" value="<?php if (isset($this->lists['searchtitle'])) echo $this->lists['searchtitle']; ?>"  /></span>
                    <span class="jsjobs-filter"><?php echo $this->lists['searchstatus']; ?></span>
                    <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                    <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" onclick="document.getElementById('searchstatus').value = '';document.getElementById('searchtitle').value = ''; this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
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
                                    <th  width="60%" class="title"><?php echo Text::_('Title'); ?></th>
                                    <th class="center"><?php echo Text::_('Default'); ?></th>
                                    <th class="center"><?php echo Text::_('Published'); ?></th>
                                    <th class="center"><?php echo Text::_('Ordering'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            jimport('joomla.filter.output');
                            $k = 0;
                            for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                                $row = $this->items[$i];
                                $upimg = 'uparrow.png';
                                $downimg = 'downarrow.png';
                                $checked = HTMLHelper::_('grid.id', $i, $row->id);
                                $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=age.editjobage&cid[]=' . $row->id);
                                ?>
                                <tr valign="top">
                                    <td>
                                        <?php echo $checked; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo $link; ?>">
                                            <?php echo Text::_($row->title); ?></a>
                                    </td>
                                    <td align="center">
                                        <?php if ($row->isdefault == 1) { ?> 
                                            <img src="../components/com_jsjobs/images/default.png" alt="<?php echo Text::_('Default');?>" />
                                        <?php } else { ?>
                                            <a href="index.php?option=com_jsjobs&c=common&task=makedefault&for=ages&id=<?php echo $row->id; ?>">
                                                <img src="../components/com_jsjobs/images/notdefault.png" alt="<?php echo Text::_('Not Default');?>" /></a>
                                        <?php } ?>  
                                    </td>   

                                    <td align="center">
                                        <?php
                                        $img = ($row->status) ? 'tick.png' : 'publish_x.png';
                                        $status_value = ($row->status) ? 0 : 1;
                                        $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=common.makePublishUnpublish&for=ages&status='.$status_value.'&cid[]='.$row->id);
                                        ?>
                                        <a href="<?php echo $status_link;?>">
                                            <img src="../components/com_jsjobs/images/<?php echo $img; ?>"  />
                                        </a>
                                    </td>
                                    <td align="center">
                                        <?php if ($i != 0 || $this->pagination->pagesCurrent > 1) { ?>
                                            <a href="index.php?option=com_jsjobs&c=common&task=defaultorderingdown&for=ages&id=<?php echo $row->id; ?>">
                                                <img src="../components/com_jsjobs/images/<?php echo $upimg; ?>" alt="<?php echo Text::_('Order Up');?>" /></a>
                                        <?php } else echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; ?>  
                                        <?php echo $row->ordering; ?>
                                        <?php if ($i < $n - 1 || $this->pagination->pagesCurrent < $this->pagination->pagesTotal) { ?>
                                            <a href="index.php?option=com_jsjobs&c=common&task=defaultorderingup&for=ages&id=<?php echo $row->id; ?>">
                                                <img src="../components/com_jsjobs/images/<?php echo $downimg; ?>" alt="<?php echo Text::_('Order Down');?>" /></a>
                                        <?php } ?>
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
                        <input type="hidden" name="c" value="age" />
                        <input type="hidden" name="view" value="age" />
                        <input type="hidden" name="layout" value="ages" />
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
