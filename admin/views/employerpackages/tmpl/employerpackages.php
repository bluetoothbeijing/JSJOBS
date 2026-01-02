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
                            <?php echo Text::_('Employer Packages'); ?>
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
                <?php echo Text::_('Employer Packages'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('employerpackages.add');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Add New').' '.Text::_('Employer Package'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Add New').' '.Text::_('Employer Package'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <form action="index.php" method="post" name="adminForm" id="adminForm">
                 <div id="jsjobs_filter_wrapper">
                        <span class="jsjobs-filter"><input type="text" name="searchtitle" placeholder="<?php echo Text::_('Title'); ?>" id="searchtitle" size="15" value="<?php if (isset($this->lists['searchtitle'])) echo $this->lists['searchtitle']; ?>"  /></span>                   
                        <span class="jsjobs-filter"><input type="text" name="searchprice" placeholder="<?php echo Text::_('Price'); ?>" id="searchprice" size="15" value="<?php if (isset($this->lists['searchprice'])) echo $this->lists['searchprice']; ?>"/></span>
                        <span class="jsjobs-filter"><?php echo $this->lists['searchstatus']; ?></span>
                        <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                        <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" onclick="document.getElementById('searchtitle').value = '';
                                document.getElementById('searchprice').value = '';document.getElementById('searchstatus').value = '';
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
                                    <th  width="50%" class="title"><?php echo Text::_('Title'); ?></th>
                                    <th  width="15%" class="center"><?php echo Text::_('Price'); ?></th>
                                    <th  width="15%" class="center"><?php echo Text::_('Discount'); ?></th>
                                    <th  width="15%" class="center"><?php echo Text::_('Published'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            jimport('joomla.filter.output');
                            $k = 0;
                            $currency_align = $this->config['currency_align'];
                            for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                                $row = $this->items[$i];
                                $checked = HTMLHelper::_('grid.id', $i, $row->id);
                                if($row->discounttype == 1){
                                    $type = $row->symbol;
                                }elseif($row->discounttype == 2){
                                    $type = '%';
                                }
                                $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=employerpackages.edit&cid[]=' . $row->id);
                                ?>
                                <tr valign="top" class="<?php echo "row$k"; ?>">
                                    <td>
                                        <?php echo $checked; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo $link; ?>">
                                            <?php echo $row->title; ?></a>
                                    </td>
                                    <td align="center"><?php if($row->price == 0){ echo Text::_('Free');} else{ if($currency_align == 1) echo $row->symbol.' '.$row->price; else echo $row->price.' '.$row->symbol; }  ?></td>
                                    <td align="center"><?php if($row->discount == 0){ echo '-';
                                        } else{ 
                                            if($currency_align == 1)
                                                echo $type.' '.$row->discount;
                                            else
                                                echo $row->discount.' '.$type;
                                        }
                                        ?></td>
                                    <td align="center">
                                        <?php
                                        $img = $row->status ? 'tick.png' : 'publish_x.png';
                                        ?>
                                        <img class="width-auto" src="../components/com_jsjobs/images/<?php echo $img; ?>" />
                                    </td>
                                </tr>
                                <?php
                                $k = 1 - $k;
                            }
                            ?>
                <tbody>
                        </table>
                        <div id="jsjobs-pagination-wrapper">
                            <?php echo $this->pagination->getLimitBox(); ?>
                            <?php echo $this->pagination->getListFooter(); ?>
                        </div>
                <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
                    <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                    <input type="hidden" name="c" value="employerpackages" />
                    <input type="hidden" name="view" value="employerpackages" />
                    <input type="hidden" name="layout" value="employerpackages" />
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


