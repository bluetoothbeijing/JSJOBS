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

use Joomla\CMS\Session\Session;


Factory::getDocument()->addScript(Uri::root().'administrator/components/com_jsjobs/include/js/responsivetable.js');
//HTMLHelper::_('behavior.calendar');
if ($this->config['date_format'] == 'm/d/Y')
    $dash = '/';
else
    $dash = '-';

$dateformat = $this->config['date_format'];
$firstdash = strpos($dateformat, $dash, 0);
$firstvalue = substr($dateformat, 0, $firstdash);
$firstdash = $firstdash + 1;
$seconddash = strpos($dateformat, $dash, $firstdash);
$secondvalue = substr($dateformat, $firstdash, $seconddash - $firstdash);
$seconddash = $seconddash + 1;
$thirdvalue = substr($dateformat, $seconddash, strlen($dateformat) - $seconddash);
$js_dateformat = '%' . $firstvalue . $dash . '%' . $secondvalue . $dash . '%' . $thirdvalue;

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
                            <?php echo Text::_('Job Seeker Payment History'); ?>
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
                <?php echo Text::_('Job Seeker Payment History'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('paymenthistory.edit');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Add New').' '.Text::_('Payment History'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Add New').' '.Text::_('Payment History'); ?>
            </a>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
        <script type="text/javascript">
            jQuery(document).ready(function(){
                // reset form
                jQuery('.js-form-reset').click(function(e){
                    document.getElementById('searchtitle').value = '';
                    document.getElementById('searchempname').value = '';
                    document.getElementById('searchprice').value = '';
                    document.getElementById('searchpaymentstatus').value = '';
                    document.getElementById('searchdatestart').value = '';
                    document.getElementById('searchdateend').value = '';
                    e.preventDefault();
                    jQuery('#adminForm').submit();
                });
            });
        </script>

     <form action="index.php" method="post" name="adminForm" id="adminForm">
         <div id="jsjobs_filter_wrapper" class="jsjobs_filter_wrapper_class">
             <span class="jsjobs-filter"><input type="text" name="searchtitle"  placeholder="<?php echo Text::_('Title'); ?>" id="searchtitle" size="15" value="<?php if (isset($this->lists['searchtitle'])) echo $this->lists['searchtitle']; ?>"  /></span>                   
             <span class="jsjobs-filter"><input type="text" name="searchempname"  placeholder="<?php echo Text::_('Employee Name'); ?>" id="searchempname" size="15" value="<?php if (isset($this->lists['searchempname'])) echo $this->lists['searchempname']; ?>"  /></span>                   
             <span class="jsjobs-filter"><input type="text" name="searchprice"  placeholder="<?php echo Text::_('Price'); ?>" id="searchprice" size="15" value="<?php if (isset($this->lists['searchprice'])) echo $this->lists['searchprice']; ?>" /></span>                   
             <span class="jsjobs-filter"><span class="default-hidden"><?php echo $this->lists['paymentstatus']; ?></span></span>
             <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists['searchdatestart'], 'searchdatestart', 'searchdatestart', $js_dateformat, array('placeholder' => Text::_('Date Start'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
             <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists['searchdateend'], 'searchdateend', 'searchdateend', $js_dateformat, array('placeholder' => Text::_('Date End'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
             <span class="jsjobs-filter jsjobs-filter-btn-wrp"><span id="showhidefilter"><span><?php echo Text::_('Show More')?></span></span></span>
             <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
             <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" ><?php echo Text::_('Reset'); ?></button></span>
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
                            <th class="left"><?php echo Text::_('Title'); ?></th>
                            <th class="left"><?php echo Text::_('Job Seeker Name'); ?></th>
                            <th class="center"><?php echo Text::_('Price'); ?></th>
                            <th class="center"><?php echo Text::_('Discount Amount'); ?></th>
                            <th class="center"><?php echo Text::_('Created'); ?></th>
                            <th class="center"><?php echo Text::_('Payment Status'); ?></th>
                            <th class="center"><?php echo Text::_('Purchase Status'); ?></th>
                            <th class="center"><?php echo Text::_('Verify Payment'); ?></th>
                        </tr>
                    </thead><tbody>
                    <?php
                    jimport('joomla.filter.output');
                    $k = 0;
                    $approvetask = 'jobseekerpaymentapprove';
                    $approveimg = 'tick.png';
                    $rejecttask = 'jobseekerpaymentereject';
                    $rejectimg = 'cross.png';
                    $approvealt = Text::_('Approve');// case sestive variable
                    $rejectalt = Text::_('Reject');// case sestive variable

                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = HTMLHelper::_('grid.id', $i, $row->id);
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=paymenthistory.edit&cid[]=' . $row->id);
                        ?>
                        <tr valign="top" class="<?php echo "row$k"; ?>">
                            <td>
                                <?php echo $checked; ?>
                            </td>


                            <td ><a href="index.php?option=com_jsjobs&c=paymenthistory&view=paymenthistory&layout=jobseekerpaymentdetails&pk=<?php echo $row->id; ?>"><?php echo $row->packagetitle; ?></a></td>
                            <td align="left"><?php echo $row->jobseekername; ?></td>
                            <td align="center"><?php echo $row->symbol . $row->packageprice; ?></td>
                            <td align="center"><?php if ($row->discountamount) echo $row->symbol . $row->discountamount; ?></td>
                            <td align="center"><?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?></td>
                            <td align="center"><?php if ($row->transactionverified == 1)
                                echo '<strong style="color:green;">'.Text::_('Verified').'</strong>';
                            else
                                echo '<strong style="color:red;">'.Text::_('Not Verified').'</strong>';
                            ?></td>
                            <td align="center"><?php if ($row->status == 1)
                                echo '<strong style="color:green;">'.Text::_('Verified').'</strong>';
                            else
                                echo '<strong style="color:red;">'.Text::_('Not Verified').'</strong>';
                            ?></td>
                            <td class="center">
                                <?php 
                                    if(JVERSION < 4){
                                        $token = Factory::getSession()->getFormToken();
                                    }else{
                                        $token = Session::getFormToken();
                                    }
                                ?>
                                <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=paymenthistory.'.$approvetask.'&cid[]='.$row->id.'&'.$token.'=1'); ?>
                                <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                    <img class="width-auto" src="components/com_jsjobs/include/images/<?php echo $approveimg; ?>" alt="<?php echo $approvealt; ?>" />
                                </a>
                                <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=paymenthistory.'.$rejecttask.'&cid[]='.$row->id.'&'.$token.'=1'); ?>
                                &nbsp;&nbsp; - &nbsp;&nbsp
                                <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                    <img class="width-auto" src="components/com_jsjobs/include/images/<?php echo $rejectimg; ?>" alt="<?php echo $rejectalt; ?>" />
                                </a>
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
                <input type="hidden" name="c" value="paymenthistory" />
                <input type="hidden" name="view" value="paymenthistory" />
                <input type="hidden" name="layout" value="jobseekerpaymenthistory" />
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

<script>
    jQuery(document).ready(function(){
      

            jQuery("span#showhidefilter").click(function(e){
            e.preventDefault();
            var text2 = "<?php echo Text::_('Show More')?>";
            var text1 = "<?php echo Text::_('Show Less')?>";
            if(jQuery('.default-hidden').is(':visible')){
            jQuery('#showhidefilter span').text(text2);
            }else{
            jQuery('#showhidefilter span').text(text1) ;
            }
            jQuery(".default-hidden").toggle();
            var height = jQuery(this).height();
            var imgheight = jQuery(this).find('img').height();
            var currenttop = (height-imgheight) / 2;
            jQuery(this).find('img').css('top',currenttop);
        });

    });
    


</script>
