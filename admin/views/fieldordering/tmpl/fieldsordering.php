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
$socialEnabled = JSModel::getJSModel('configuration')->getConfigValue('isenabled_jomsocial');

?>
<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>    

    <div id="full_background" style="display:none;"></div>
    <div id="popup_main" style="display:none;">

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
                            <?php echo Text::_('Field Ordering'); ?>
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
                <?php echo Text::_('Field Ordering'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('fieldordering.editjobuserfield');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Add').' ',Text::_('Field'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Add').' ',Text::_('Field'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
        <form action="index.php?option=com_jsjobs" method="post" name="adminForm" id="adminForm">
            <div id="jsjobs_filter_wrapper">           
                    <span class="jsjobs-filter"><input type="text" name="fieldtitle" placeholder="<?php echo Text::_('Title'); ?>" id="fieldtitle" value="<?php if (isset($this->lists['fieldtitle'])) echo $this->lists['fieldtitle']; ?>"  /></span>
                    <span class="jsjobs-filter"><?php echo $this->lists['userpublish']; ?></span>
                    <span class="jsjobs-filter"><?php echo $this->lists['visitorpublish']; ?></span>
                    <span class="jsjobs-filter"><?php echo $this->lists['fieldrequired']; ?></span>
                    <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                    <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" onclick="document.getElementById('fieldtitle').value = '';document.getElementById('userpublish').value = '';document.getElementById('visitorpublish').value = '';document.getElementById('fieldrequired').value = ''; this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
            </div>
            <?php if(!empty($this->items)){ ?>      
                <table class="adminlist" cellpadding="1" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th width="2%" class="title">
                                <?php echo Text::_('Num'); ?>
                            </th>
                            <th width="3%"  class="center">
                                <?php if (JVERSION < '3') { ?> 
                                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
                                <?php } else { ?>
                                    <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
                                <?php } ?>
                            </th>
                            <th width="25%" class="title bold" > <span class="blackcolor"><?php echo Text::_('Field Title'); ?></span></th>
                            <th width="5%" class="center bold" nowrap="nowrap"><span class="blackcolor"><?php echo Text::_('Section'); ?></span></th>
                            <th width="5%" class="center bold"><span class="blackcolor"><?php echo Text::_('User Published'); ?></span></th>
                            <th width="5%" class="center bold"><span class="blackcolor"><?php echo Text::_('Visitor Published'); ?> </span>    </th>
                            <?php if($socialEnabled){ ?>
                            <th width="5%" class="center bold"><span class="blackcolor"><?php echo Text::_('Social Published'); ?> </span></th>
                            <?php } ?>
                            <th width="5%" class="center bold"><span class="blackcolor"><?php echo Text::_('Required'); ?>  </span> </th>
                            <th width="10%" class="center bold" nowrap="nowrap"><span class="blackcolor"><?php echo Text::_('Ordering'); ?></span></th>
                            <th width="10%" class="center bold" nowrap="nowrap"><span class="blackcolor"><?php echo Text::_('Action'); ?></span></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="10">
                                <?php //echo $this->pagination->getListFooter();  ?>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $k = 0;
                        for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                            $row = $this->items[$i];
                            if (isset($this->items[$i + 1]))
                                $row1 = $this->items[$i + 1];
                            else
                                $row1 = $this->items[$i];
                            $uptask = 'fieldorderingup';
                            $upimg = 'uparrow.png';
                            $downtask = 'fieldorderingdown';
                            $downimg = 'downarrow.png';

                            $pubtask = $row->published ? 'fieldunpublished' : 'fieldpublished';
                            $pubimg = $row->published ? 'tick.png' : 'cross.png';
                            $alt = $row->published ? Text::_('Published') : Text::_('Unpublished');

                            $visitorpubtask = $row->isvisitorpublished ? 'visitorfieldunpublished' : 'visitorfieldpublished';
                            $visitorpubimg = $row->isvisitorpublished ? 'tick.png' : 'cross.png';
                            $visitoralt = $row->isvisitorpublished ? Text::_('Published') : Text::_('Unpublished');

                            $requiredtask = $row->required ? 'fieldnotrequired' : 'fieldrequired';
                            $requiredpubimg = $row->required ? 'tick.png' : 'cross.png';
                            $requiredalt = $row->required ? Text::_('Required') : Text::_('Not required');

                            $socialpubtask = $row->issocialpublished ? 'socialfieldunpublished' : 'socialfieldpublished';
                            $socialpubimg = $row->issocialpublished ? 'tick.png' : 'cross.png';
                            $socialalt = $row->issocialpublished ? Text::_('Published') : Text::_('Unpublished');


                            $checked = HTMLHelper::_('grid.id', $i, $row->id);
                            $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=fieldordering.edit&cid[]=' . $row->id);
                            ?>
                            <tr class="<?php echo "row$k"; ?>">
                                <td>
                                    <?php echo $i + 1 + $this->pagination->limitstart; ?>
                                </td>
                                <td align="center">
                                    <?php echo HTMLHelper::_('grid.id', $i, $row->id); ?>
                                </td>
                                <?php
                                $sec = substr($row->field, 0, 8); //get section_
                                $newsection = 0;
                                if ($sec == 'section_') {
                                    $newsection = 1;
                                    $subsec = substr($row->field, 0, 12);
                                    if ($subsec == 'section_sub_') {
                                        ?>
                                        <td colspan="2" align="center"><strong><?php echo $row->fieldtitle; ?></strong></td>
                                    <?php } else { ?>
                                        <td colspan="2" align="center"><strong><font size="2"><?php echo $row->fieldtitle; ?></font></strong></td>
                                    <?php } ?>

                                    <td align="center">
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="components/com_jsjobs/include/images/<?php echo $pubimg; ?>" alt="<?php echo Text::_('Can Not Unpublished'); ?>" />
                                        <?php } else { ?>
                                            <?php
                                            $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.'.$pubtask.'&cid[]='.$row->id);
                                            ?>
                                            <a href="<?php echo $status_link;?>">
                                                <img src="components/com_jsjobs/include/images/<?php echo $pubimg; ?>"  />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td align="center" colspan="1">
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="components/com_jsjobs/include/images/<?php echo $visitorpubimg; ?>" alt="<?php echo Text::_('Can Not Unpublished'); ?>" />
                                        <?php } else { ?>
                                            <?php
                                            $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.'.$visitorpubtask.'&cid[]='.$row->id);
                                            ?>
                                            <a href="<?php echo $status_link;?>">
                                                <img src="components/com_jsjobs/include/images/<?php echo $visitorpubimg; ?>" alt="<?php echo $visitoralt; ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td align="center" colspan="1">
                                            <?php
                                            $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.'.$socialpubtask.'&cid[]='.$row->id);
                                            ?>
                                            <a href="<?php echo $status_link;?>">
                                                <img src="components/com_jsjobs/include/images/<?php echo $socialpubimg; ?>" alt="<?php echo $socialalt; ?>" />
                                            </a>
                                    </td>
                                    <td colspan="3"></td>
                                <?php } else { ?>
                            <?php /*    <td ><?php //echo $row->name;   ?></td> */ ?>
                                    <td><?php if ($row->fieldtitle)
                                echo Text::_($row->fieldtitle);
                            else
                                echo $row->userfieldtitle;
                            ?></td>
                                    <td><?php echo $row->section; ?></td>
                                    <td align="center">
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="components/com_jsjobs/include/images/<?php echo $pubimg; ?>" alt="<?php echo Text::_('Can Not Unpublished'); ?>" />
                                        <?php } else { ?>
                                            <?php
                                            $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.'.$pubtask.'&cid[]='.$row->id);
                                            ?>
                                            <a href="<?php echo $status_link;?>">
                                                <img src="../components/com_jsjobs/images/<?php echo $pubimg; ?>" alt="<?php echo $alt; ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td align="center">
                                        <?php if ($row->cannotunpublish == 1) { ?>
                                            <img src="components/com_jsjobs/include/images/<?php echo $visitorpubimg; ?>" alt="<?php echo Text::_('Can Not Unpublished'); ?>" />
                                        <?php } else { ?>
                                            <?php
                                            $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.'.$visitorpubtask.'&cid[]='.$row->id);
                                            ?>
                                            <a href="<?php echo $status_link;?>">
                                                <img src="../components/com_jsjobs/images/<?php echo $visitorpubimg; ?>" alt="<?php echo $visitoralt; ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <?php if($socialEnabled){ ?>
                                    <td align="center">
                                            <?php
                                            $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.'.$socialpubtask.'&cid[]='.$row->id);
                                            ?>
                                            <a href="<?php echo $status_link;?>">
                                                <img src="../components/com_jsjobs/images/<?php echo $socialpubimg; ?>" alt="<?php echo $socialalt; ?>" />
                                            </a>
                                    </td>
                                    <?php } ?>
                                    <td align="center">
                                        <?php if ($row->sys == 1 || ($row->isuserfield == 1 && $row->userfieldtype == 'checkbox')) { ?>
                                            <img src="components/com_jsjobs/include/images/<?php echo $requiredpubimg; ?>" alt="<?php echo $requiredalt; ?>" />
                                        <?php } elseif ($row->sys == 1 || ($row->isuserfield == 1 && $row->userfieldtype == 'termsandconditions')) { ?>
                                            <img src="components/com_jsjobs/include/images/tick.png" alt="<?php echo Text::_('Required'); ?>" />
                                        <?php } else { ?>
                                            <?php
                                            $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.'.$requiredtask.'&cid[]='.$row->id);
                                            ?>
                                            <a href="<?php echo $status_link;?>">
                                                <img src="../components/com_jsjobs/images/<?php echo $requiredpubimg; ?>" alt="<?php echo $requiredalt; ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td class="center">
                                        <?php
                                        if ($row->ordering != 1) {
                                            if ($newsection != 1) {
                                                ?>      
                                                <a href="index.php?option=com_jsjobs&c=common&task=fieldordering.<?php echo $downtask; ?>&cid[]=<?php echo $row->id; ?>">
                                                    <img src="components/com_jsjobs/include/images/<?php echo $upimg; ?>" alt="<?php echo Text::_('Order Up');?>" /></a>
                                            <?php
                                            } else
                                                echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                        } else
                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                                        ?>  
                                        &nbsp;&nbsp;<?php echo $row->ordering; ?>&nbsp;&nbsp;
                                        <?php
                                        //if ($i < $n-1) { 
                                        if ($row->section == $row1->section) {
                                            ?>
                                            <a href="index.php?option=com_jsjobs&c=common&task=fieldordering.<?php echo $uptask; ?>&cid[]=<?php echo $row->id; ?>">
                                                <img src="components/com_jsjobs/include/images/<?php echo $downimg; ?>" alt="<?php echo Text::_('Order Down');?>" /></a>
                                    <?php
                                    }
                                    //} 
                                    ?>  
                                    </td>
                                    <td class="center">
                                        <a class="action-btn" href="javascript:void(0);" onclick="showPopupAndSetValues(<?php echo $row->id; ?>)" ><img src="components/com_jsjobs/include/images/edit.png" title="<?php echo Text::_('Edit'); ?>"></a>
                                        <?php if ($row->isuserfield == 1) { ?>
                                            <?php
                                                $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=fieldordering.deleteuserfield&cid[]='.$row->id);
                                            ?>
                                            <a class="action-btn" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                                <img src="components/com_jsjobs/include/images/remove.png" title="<?php echo Text::_('Delete'); ?>" />
                                            </a>
                                        <?php } ?>
                                    </td>
                                <?php
                                $newsection = 0;
                            }
                            ?>
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

                <?php
                    $session = Factory::getApplication()->getSession();
                    $session->set('fieldfor',$this->ff);
                ?>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="ff" value="<?php echo $this->ff; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="c"  value="fieldordering" />
                <input type="hidden" name="view"  value="fieldordering" />
                <input type="hidden" name="layout"  value="fieldsordering" />
                <input type="hidden" name="filter_order" value="<?php echo isset($this->lists['order'])? $this->lists['order']:''; ?>" />
                <input type="hidden" name="filter_order_Dir" value="<?php echo isset($this->lists['order_Dir'])?$this->lists['order_Dir']:''; ?>" />
                <?php echo HTMLHelper::_('form.token'); ?>
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

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery("div#full_background").click(function () {
                closePopup();
            });
        });

        function resetFrom() {
            jQuery("input#title").val('');
            jQuery("select#ustatus").val('');
            jQuery("select#vstatus").val('');
            jQuery("select#required").val('');
            jQuery("form#jsjobsform").submit();
        }

        function showPopupAndSetValues(id) {
            jQuery.post("index.php?option=com_jsjobs&task=fieldordering.getOptionsForFieldEdit", {field: id}, function (data) {
                if (data) {
                    var d = jQuery.parseJSON(data);
                    jQuery("div#full_background").css("display", "block");
                    jQuery("div#popup_main").html(d);
                    jQuery("div#popup_main").slideDown('slow');
                }
            });
        }

        function closePopup() {
            jQuery("div#popup_main").slideUp('slow');
            setTimeout(function () {
                jQuery("div#full_background").hide();
                jQuery("div#popup_main").html('');
            }, 700);
        }
    </script>
