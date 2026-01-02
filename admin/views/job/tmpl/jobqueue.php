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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filter\OutputFilter;

use Joomla\CMS\Session\Session;

$document = Factory::getDocument();


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
$sort_combo = array(
    '0' => array('value' => 0, 'text' => Text::_('Sort By')),
    '1' => array('value' => 1, 'text' => Text::_('Job Title')),
    '2' => array('value' => 2, 'text' => Text::_('Job Type')),
    '3' => array('value' => 3, 'text' => Text::_('Company Name')),
    '4' => array('value' => 4, 'text' => Text::_('Category')),
    '5' => array('value' => 5, 'text' => Text::_('Location')),
    '6' => array('value' => 6, 'text' => Text::_('Status')),
    '7' => array('value' => 7, 'text' => Text::_('Created')),);
$sort_combo = HTMLHelper::_('select.genericList', $sort_combo, 'js_sortby', 'class="inputbox" onchange="js_Ordering();"'.'', 'value', 'text',$this->js_sortby);
?>

<script type="text/javascript">
    function js_Ordering(){
        var val = jQuery('#js_sortby option:selected').val();
        jQuery('form#adminForm').submit();
    }

    function js_imgSort(){
        var val = jQuery('#js_sortby option:selected').val();
        if(val!=0){
            jQuery('#my_click').val('1');
            jQuery('form#adminForm').submit();
        }
    }
</script>


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
                            <a href="index.php?option=com_jsjobs&c=job&view=job&layout=jobs" title="<?php echo Text::_('Jobs'); ?>">
                                <?php echo Text::_('Jobs'); ?>
                            </a>
                        </li>
                         <li>
                            <?php echo Text::_('Jobs Approval Queue'); ?>
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
                <?php echo Text::_('Jobs Approval Queue'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <form action="index.php" method="post" name="adminForm" id="adminForm">
        <div id="jsjobs_filter_wrapper">    
            <div id="jsjobs-filter-main">
                <div id="checkallbox-main">
                  <div id="checkallbox">
                    <?php if (JVERSION < '3') { ?> 
                        <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>); highlightAll();" />
                    <?php } else { ?>
                        <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this); highlightAll();" />
                    <?php } ?>
                  <span class="selectallbox-text"><?php echo Text::_("Select All"); ?></span>
                </div>
              </div> 
                <span class="jsjobs-filter"><input type="text" name="companyname" placeholder="<?php echo Text::_('Company Name'); ?>" id="companyname" value="<?php if (isset($this->lists['companyname'])) echo $this->lists['companyname']; ?>"  /></span>
                <span class="jsjobs-filter"><input type="text" name="jobtitle" placeholder="<?php echo Text::_('Job Title'); ?>" id="jobtitle" value="<?php if (isset($this->lists['jobtitle'])) echo $this->lists['jobtitle']; ?>"  /></span>
                <span class="jsjobs-filter"><input type="text" name="location" placeholder="<?php echo Text::_('Location'); ?>" id="location" value="<?php if (isset($this->lists['location'])) echo $this->lists['location']; ?>"  /></span>
                <span class="jsjobs-filter default-hidden"><?php echo $this->lists['jobcategory']; ?></span>
                <span class="jsjobs-filter default-hidden"><?php echo $this->lists['jobtype']; ?></span>
                <span class="jsjobs-filter default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists["datefrom"], 'datefrom','datefrom' , $js_dateformat, array('placeholder' => Text::_('Date From'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span>
                <span class="jsjobs-filter default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists["dateto"], 'dateto','dateto' , $js_dateformat, array('placeholder' => Text::_('Date To'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span>
                <span class="jsjobs-filter jsjobs-filter-btn-wrp"><span id="showhidefilter"><span><?php echo Text::_('Show More')?></span></span></span>
                <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" id="js-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" id="js-reset"><?php echo Text::_('Reset'); ?></button></span>
            </div>    
        </div>
        <div id="jsjobs-admin-heading">
            <div class="js-admin-head-txt"><?php echo Text::_('All Jobs') ?></div>
            <span id="sorting-wrapper">
                <span id="sorting-bar1">
                    <?php echo $sort_combo; ?>
                </span>
                <span id="sortimage">
                    <?php if ($this->sort == 'asc') {$img = "components/com_jsjobs/include/images/up.png"; }else{$img = "components/com_jsjobs/include/images/down.png"; } ?>
                    <a href="javascript: void(0);"onclick="js_imgSort();"><img src="<?php echo $img ?>"></a>
                </span>
            </span>
        </div>
        <?php if(!empty($this->items)){ ?>
                 <?php
                    jimport('joomla.filter.output');
                    $k = 0;
                    $jobdeletetask = 'jobenforcedelete';
                    $deleteimg = 'publish_x.png';
                    $Deletealt = Text::_('Delete');
                    $common = $this->getJSModel('common');
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = '<input type="checkbox" onclick="single_highlight('.$row->id.');" id="cb'.$i.'" name="cid[]" value="'.$row->id.'" />';
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=job.edit&cid[]=' . $row->id.'&callfrom=jobqueue');
                        $comp_path = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=company.edit&cid[]=' . $row->companyid);
                        ?>

                <div id="js-jobs-comp-listwrapper" class="jsjobs-check-container select_<?php echo $row->id;?>">
                  <div class="jsjobs-top-comp-wrp">
                      <div id="jsjobs-top-comp-left">
                         <?php 
                          $path = $common->getCompanyLogo($row->companyid, $row->companylogo , $this->config);
                         ?>
                          <a class="jsjobs-img" href="<?php echo $comp_path;?>"><img class="myfilelogoimg" src="<?php echo $path;?>"/></a>
                          <div id="jsjobslist-comp-body">
                            <div class="datablock com-name"><a href="<?php echo $comp_path;?>"><?php echo $row->companyname; ?></a></div>
                            <div class="datablock job-subject"><a href="<?php echo $link; ?>"><?php echo $row->title; ?></a></div>
                            <span class="datablock"><span class="title"><?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('jobcategory', 2 )); ?> :</span><span class="notbold color"><?php echo Text::_($row->cat_title); ?></span></span>
                            <span class="datablock last-child"><span class="title"><?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('city', 2 )); ?> :</span><span class="notbold color"><?php echo $row->location; ?></span></span>
                          </div>
                      </div>
                      <div id="jsjobs-top-comp-right">
                          <div id="jsjobslist-comp-header" class="jsjobslist-rightalign">
                              <span id="js-queues-statuses"><?php
                                  $class_color = '';
                                  $arr = array();
                                  if($row->status==0){
                                    ?>
                                    <span class="q-self forapproval"><?php echo Text::_('Job'); ?></span><?php
                                    $class_color = 'q-self';
                                      $arr['self'] = 1;
                                    } ?>
                                    <span class="<?php echo $class_color; ?> waiting-text"><?php echo Text::_('Waiting for approval'); ?></span>
                                </span>
                            </div>
                            <div class="js-cp-job-salary padding-right">
                              <?php 
                                $salaryrange = '';
                                if ($row->salaryfrom)
                                    $salaryrange =  $row->salaryfrom;
                                if ($row->salaryto)
                                    $salaryrange .=  ' - ' . $row->salaryto;
                                if($this->config['currency_align'] == 1){
                                    $salaryrange = $row->symbol . ' '.$salaryrange;
                                }elseif($this->config['currency_align'] == 2){
                                    $salaryrange .= ' '.$row->symbol;
                                }
                                echo $salaryrange.'/';
                                if ($row->salarytype){
                                ?>
                                <span class="per-month"><?php echo Text::_($row->salarytype);?></span>
                              <?php 
                              }
                              ?>
                            </div>
                            <div class="js-cp-job-salary padding-right">
                              <span class="js-cp-job-info-label">
                                <?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?>
                              </span>
                            </div>
                      </div>
                  </div>
                    <div id="jsjobs-bottom-comp">
                        <div id="bottomleftnew-job">
                            <span class="bottomcheckbox ss_<?php echo $row->id;?>"><?php echo $checked; ?></span>
                        </div>
                        <div id="bottomrightnew"> <?php
                          $total = count($arr);
                          if($total==3){
                            $objid = 4; //for all
                          }
                          if($total==1){
                            if(isset($arr['self'])){
                              echo '<a class="js-bottomspan" href="index.php?option=com_jsjobs&c=job&task=jobapprove&id='.$row->id.'">'.Text::_('Approve').'</a>';
                            }
                          }else{ ?>
                            <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="approveActionPopup('<?php echo $row->id; ?>');"><?php echo Text::_('Approve');?>
                              <div id="jsjobs-queue-actionsbtn" class="jobsqueueapprove_<?php echo $row->id;?>">
                                <?php
                                  if(isset($arr['self'])){
                                    echo '<a id="jsjobs-act-row" class="jsjobs-act-row" href="index.php?option=com_jsjobs&c=job&task=jobapprove&id='.$row->id.'">'.Text::_("Job Approve").'</a>';
                                  }
                                ?>
                              </div>
                            </div>
                          <?php
                          } // End approve
                          if($total==1){
                            if(isset($arr['self'])){
                              echo '<a class="js-bottomspan" href="index.php?option=com_jsjobs&c=job&task=jobreject&id='.$row->id.'">'.Text::_('Reject').'</a>';
                            }
                          }else{ ?>
                            <div class="js-bottomspan jobsqueue-approvalqueue" onmouseout="hideThis(this);" onmouseover="rejectActionPopup('<?php echo $row->id; ?>');"><?php echo Text::_('Reject');?>
                              <div id="jsjobs-queue-actionsbtn" class="jobsqueuereject_<?php echo $row->id;?>">
                                <?php
                                  if(isset($arr['self'])){
                                    echo '<a id="jsjobs-act-row" class="jsjobs-act-row" href="index.php?option=com_jsjobs&c=job&task=jobreject&id='.$row->id.'">'.Text::_("Job Reject").'</a>';
                                  }
                                ?>
                              </div>
                            </div>
                          <?php
                          }//End Reject ?>
                               <?php 
                                    if(JVERSION < 4){
                                        $token = Factory::getSession()->getFormToken();
                                    }else{
                                        $token = Session::getFormToken();
                                    }
                                ?>
                              <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=job.remove&cid[]='.$row->id.'&'.$token.'=1&callfrom=jobqueue'); ?>
                              <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                  <?php echo Text::_('Delete'); ?> 
                              </a>
                              <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=job.jobenforcedelete&cid[]='.$row->id.'&'.$token.'=1&callfrom=jobqueue'); ?>
                              <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                  <?php echo Text::_('Force Delete'); ?> 
                              </a>
                          
                        </div>                        
                    </div>  
                </div>
                        <?php
                        $k = 1 - $k;
                    }
                    ?>

             
                <div id="jsjobs-pagination-wrapper">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>

<?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>                
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="c" value="job" />
                <input type="hidden" name="view" value="job" />
                <input type="hidden" name="callfrom" value="jobqueue" />
                <input type="hidden" name="layout" value="jobqueue" />
                <input type="hidden" name="sortby" value="<?php echo $this->sort;?>"/>
                <input type="hidden" id="my_click" name="my_click" value=""/>
                <input type="hidden" name="boxchecked" value="0" />
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
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
<script type="text/javascript">
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
            var height = jQuery(this).parent().height();
            var imgheight = jQuery(this).height();
            var currenttop = (height-imgheight) / 2;
            jQuery(this).css('top',currenttop);
        });

        jQuery('#js-reset').click(function(e){
            jQuery('#companyname').val('');
            jQuery('#jobtitle').val('');
            jQuery('#location').val('');
            jQuery('#jobcategory').prop('selectedIndex', 0);
            jQuery('#jobtype').prop('selectedIndex', 0);
            jQuery('#dateto').val('');
            jQuery('#datefrom').val('');
            jQuery('#isgfcombo').prop('selectedIndex', 0);
            e.preventDefault();
            jQuery('#adminForm').submit();
        });
    });

    function approveActionPopup(id){
      var cname = '.jobsqueueapprove_'+id;
      jQuery(cname).show();
      jQuery(cname).mouseout(function(){
        jQuery(cname).hide();
      });
    }

    function rejectActionPopup(id){
      var cname = '.jobsqueuereject_'+id;
      jQuery(cname).show();
      jQuery(cname).mouseout(function(){
        jQuery(cname).hide();
      });
    }

    function hideThis(obj){
      jQuery(obj).find('div#jsjobs-queue-actionsbtn').hide();
    }
  function single_highlight(id){
    if (jQuery("div.select_"+id+" span input:checked").length > 0){
      showBorder(id);
    }else{
      hideBorder(id);
    }
  }

  function showBorder(id){
    jQuery("div.select_"+id).addClass('ck_blue');
    jQuery("span.ss_"+id).addClass('ck_blue');
  }

  function hideBorder(id){
    jQuery("div.select_"+id).removeClass('ck_blue');
    jQuery("span.ss_"+id).removeClass('ck_blue');
  }

  function highlightAll(){
    if (jQuery("span.bottomcheckbox input").is(':checked') == false){
      jQuery("div.jsjobs-check-container").removeClass('ck_blue');
      jQuery("span.bottomcheckbox").removeClass('ck_blue');
    }
    if (jQuery("span.bottomcheckbox input").is(':checked') == true){
      jQuery("div.jsjobs-check-container").addClass('ck_blue');
      jQuery("span.bottomcheckbox").addClass('ck_blue');
    }
  }    

</script> 
