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


Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');
?>
<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>    
    <div id="jsjobs-content">
        <div id="jsjobs-heading"><a id="backimage" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel"><img src="components/com_jsjobs/include/images/back-icon.png" alt="<?php echo Text::_('Back');?>" ></a><span id="heading-text"><?php echo Text::_('User Fields'); ?></span></div>
       <form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
                <div id="jsjobs_filter_wrapper">           
                    <span class="jsjobs-filter"><input type="text" name="searchtitle" placeholder="<?php echo Text::_('Title'); ?>" id="searchtitle" value="<?php if (isset($this->lists['searchtitle'])) echo $this->lists['searchtitle']; ?>"  /></span>
                    <span class="jsjobs-filter"><?php echo $this->lists['searchtype']; ?></span>
                    <span class="jsjobs-filter"><?php echo $this->lists['searchrequired']; ?></span>
                
                    <span class="jsjobs-filter"><button class="js-button" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                    <span class="jsjobs-filter"><button class="js-button" onclick="document.getElementById('searchtitle').value = '';document.getElementById('searchtype').value = '';document.getElementById('searchrequired').value = ''; this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
                </div>               
      <?php  if(!empty($this->items)){ ?>
             
                <table class="adminlist" cellpadding="1" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th width="2%" class="title">
                                <?php echo Text::_('Num'); ?>
                            </th>
                            <th width="3%" class="center">
                                <?php if (JVERSION < '3') { ?> 
                                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                                <?php } else { ?>
                                    <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                                <?php } ?>
                            </th>
                            <th class="title">
                                <span class="blackcolor"><?php echo HTMLHelper::_('grid.sort', 'Field name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?></span> 

                            </th>
                            <th width="15%" class="title" >
                               <span class="blackcolor"> <?php echo HTMLHelper::_('grid.sort', 'Field title', 'a.username', @$this->lists['order_Dir'], @$this->lists['order']); ?></span>
                            </th>
                            <th width="10%" class="center" nowrap="nowrap">
                               <span class="blackcolor"> <?php echo HTMLHelper::_('grid.sort', 'Field type', 'a.block', @$this->lists['order_Dir'], @$this->lists['order']); ?></span>
                            </th>
                            <th width="10%" class="center" nowrap="nowrap">
                               <span class="blackcolor"> <?php echo HTMLHelper::_('grid.sort', 'Read only', 'a.id', @$this->lists['order_Dir'], @$this->lists['order']); ?></span>
                            </th>
                            <th width="10%" class="center" nowrap="nowrap">
                               <span class="blackcolor"> <?php echo HTMLHelper::_('grid.sort', 'Required', 'a.required', @$this->lists['order_Dir'], @$this->lists['order']); ?></span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($this->fieldfor))
                            $fieldfor = $this->fieldfor;
                        else
                            $fieldfor = $this->ff;
                        $k = 0;
                        for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                            $row = $this->items[$i];
                            $link = 'index.php?option=com_jsjobs&amp;view=customfield&amp;task=customfield.edit&amp;cid[]=' . $row->id . '&ff=' . $fieldfor;
                            ?>
                            <tr class="<?php echo "row$k"; ?>">
                                <td>
                                    <?php   echo $i + 1 + $this->pagination->limitstart; ?>
                                </td>
                                <td>
                                    <?php echo HTMLHelper::_('grid.id', $i, $row->id); ?>
                                </td>
                                <td><a href="<?php echo $link; ?>"><?php echo $row->name; ?></a></td>
                                <td><?php echo $row->title; ?></td>
                                <td class="center"><?php echo $row->type; ?></td>
                                <td class="center"><?php if ($row->readonly == 1)
                                    echo Text::_('JYES');
                                else
                                    echo Text::_('JNO');
                                ?></td>
                                <td class="center"><?php if ($row->required == 1)
                                    echo Text::_('JYES');
                                else
                                    echo Text::_('JNO');
                                ?></td>

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
                <input type="hidden" name="c" value="customfield" />
                <input type="hidden" name="view" value="customfield" />
                <input type="hidden" name="layout" value="userfields" />
                <input type="hidden" name="fieldfor" value="<?php echo $this->fieldfor; ?>" />
                <input type="hidden" name="ff" value="<?php echo $this->fieldfor; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="filter_order" value="<?php if (isset($this->listss)) echo $this->lists['order']; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php if (isset($this->listss)) echo $this->lists['order_Dir']; ?>" />
<?php echo HTMLHelper::_('form.token'); ?>
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




