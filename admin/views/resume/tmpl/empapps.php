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

$document = Factory::getDocument();
use Joomla\CMS\Session\Session;

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
    '1' => array('value' => 1, 'text' => Text::_('Application Title')),
    '2' => array('value' => 2, 'text' => Text::_('First Name')),
    '3' => array('value' => 3, 'text' => Text::_('Category')),
    '4' => array('value' => 4, 'text' => Text::_('Job Type')),
    '5' => array('value' => 5, 'text' => Text::_('Location')),
    '6' => array('value' => 6, 'text' => Text::_('Status')),
    '7' => array('value' => 7, 'text' => Text::_('Created')),
    '8' => array('value' => 8, 'text' => Text::_('Viewed')),);
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

<script type="text/javascript">
    function confirmdeleteresume(id, task) {
        if (confirm("<?php echo Text::_('Are You Sure')."?"; ?>") == true) {
            return listItemTask(id, task);
        } else
            return false;
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
                            <?php echo Text::_('Resume'); ?>
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
                <?php echo Text::_('Resume'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
           <form action="index.php" method="post" name="adminForm" id="adminForm">
                <div id="jsjobs_filter_wrapper">
                    <div id="checkallbox-main">
                        <div id="checkallbox">
                            <span class="selectallbox"><span class="selectallbox-onhover" style="display:none"><?php echo Text::_("Select All"); ?><img src="components/com_jsjobs/include/images/bottom-tool-tip.png" alt="downhover-part"></span></span>
                            <?php if (JVERSION < '3') { ?> 
                                <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>); highlightAll();" />
                            <?php } else { ?>
                                <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this); highlightAll();" />
                            <?php } ?>
                            <span class="selectallbox-text"><?php echo Text::_("Select All"); ?></span>
                        </div>
                        <div id="checkallbox">
                            <span class="selectallbox"><span class="selectallbox-onhover" style="display:none"><?php echo Text::_("Select All"); ?><img src="components/com_jsjobs/include/images/bottom-tool-tip.png" alt="downhover-part"></span></span>
                            <img class="del-icon" src="components/com_jsjobs/include/images/delete-icon-new.png">
                            <span class="selectallbox-text"><a onclick="if (document.adminForm.boxchecked.value == 0) { alert(Joomla.Text._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')); } else { if (confirm('Are You Sure?')) { Joomla.submitbutton('resume.remove'); } }"><?php echo Text::_("Delete"); ?></a></span>
                        </div>
                    </div>    
                    <div id="jsjobs-filter-main">
                        <span class="jsjobs-filter"><input type="text" name="resumetitle" placeholder="<?php echo Text::_('Resume Title'); ?>" id="resumetitle" value="<?php if (isset($this->lists['resumetitle'])) echo $this->lists['resumetitle']; ?>"  /></span>
                        <span class="jsjobs-filter"><input type="text" name="resumename" placeholder="<?php echo Text::_('Name'); ?>" id="resumename" value="<?php if (isset($this->lists['resumename'])) echo $this->lists['resumename']; ?>"  /></span>
                        <span class="jsjobs-filter"><?php echo $this->lists['desiredsalary']; ?></span>
                        <span class="jsjobs-filter"><span class="default-hidden"><input type="text" name="location" placeholder="<?php echo Text::_('Location'); ?>" id="location" value="<?php if (isset($this->lists['location'])) echo $this->lists['location']; ?>"  /></span></span>
                        <span class="jsjobs-filter"><span class="default-hidden"><?php echo $this->lists['resumecategory']; ?></span></span>
                        <span class="jsjobs-filter"><span class="default-hidden"><?php echo $this->lists['resumetype']; ?></span></span>
                        <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists['datefrom'], 'datefrom', 'datefrom', $js_dateformat, array('placeholder' => Text::_('Date From'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
                        <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists['dateto'], 'dateto', 'dateto', $js_dateformat, array('placeholder' => Text::_('Date to'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
                        <span class="jsjobs-filter"><span class="default-hidden"><?php echo $this->lists['status']; ?></span></span>
                        <span class="jsjobs-filter jsjobs-filter-btn-wrp"><span id="showhidefilter"><span><?php echo Text::_('Show More')?></span></span></span>
                        <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" id="js-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                        <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" id="js-reset"><?php echo Text::_('Reset'); ?></button></span>
                    </div>    
                </div>
                <div id="jsjobs-admin-heading">
                    <div class="js-admin-head-txt"><?php echo Text::_('All').' '.Text::_('Resume') ?></div>
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

                    $resumedeletetask = 'resumeenforcedelete';
                    $deleteimg = 'publish_x.png';
                    $Deletealt = Text::_('Delete');


                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = HTMLHelper::_('grid.id', $i, $row->id);
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=resume.edit&cid[]=' . $row->id);
                        
                        if($row->photo==""){
                          $logo_path = "components/com_jsjobs/include/images/Users.png";
                        }else{
                          $logo_path = "../".$this->config['data_directory']."/data/jobseeker/resume_".$row->id."/photo/".$row->photo;
                        }

                        ?>

                <div id="js-jobs-comp-listwrapper" class="jsjobs-check-container select_<?php echo $row->id;?>">
                    <div id="jsjobs-top-comp-left">
                        <span class="outer-circle"><a href="<?php echo $link; ?>"> <img class="circle" src="<?php echo $logo_path; ?>"/> </a></span> 
                    </div>
                    <div id="jsjobs-top-comp-right_resume">
                        <div id="jsjobslist-comp-header" class="jsjobslist-comp-header-wrp">
                            <div id="innerheaderlefti">
                               <span id="jsjobs-resumetitle" class="datablockhead-left">
                                  <?php if($row->status==1){?>
                                    <span class="rightcorner"><span class="listheadrightcorner-green"><?php echo Text::_('Approved'); ?></span></span>
                                    <?php }else{ ?>
                                    <span class="rightcorner_red"><span class="listheadrightcorner-red"><?php echo Text::_('Rejected'); ?></span></span>
                                    <?php } ?>
                               </span>
                            </div>
                            <div class="js-cp-job-type">
                                <?php echo Text::_($row->jobtypetitle); ?>
                            </div>
                            <div id="bottomleftnew-resume" class="mg-top">
                                <span class="js-created"><?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?></span>
                            </div>

                        </div>
                        <div id="jsjobslist-comp-body">
                            <span class="datablock app-title"><?php echo $row->application_title; ?></span>
                            <span class="datablock name" ><a href="<?php echo $link; ?>"><?php  echo $row->first_name . ' ' . $row->last_name; ?></a></span>
                            <span class="datablock last-child" ><span class="title"><?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('desired_salary', 3 )); ?> :</span><span class="notbold color"><?php echo $row->salary; ?></span></span>
                            <span class="datablock last-child" ><span class="title"><?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('address', 3 )); ?> :</span><span class="notbold color"><?php echo $row->location; ?></span></span>
                            <span class="datablock last-child" ><span class="title"><?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('job_category', 3 )); ?> :</span><span class="notbold color"><?php echo Text::_($row->cat_title); ?></span></span>   
                        </div>
                    </div>
                    <div id="jsjobs-bottom-comp">
                        <div id="bottomleftnew-resume-wrp">
                            <span class="datablockhead-left"><?php echo $checked; ?></span>
                        </div>
                        <div id="bottomrightnew-resume">
                             <?php if( $this->getJSModel(VIRTUEMARTJSJOBS)->{VIRTUEMARTJSJOBSFUN}("UGF0dFlJam9tc29jaWFsS1ducDhi") ){ ?>
                             <a class="js-bottomspan" href="index.php?option=com_jsjobs&c=resume&task=postresumeonjomsocial&id=<?php echo $row->id; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1"><?php echo Text::_('Post On JomSocial');?></a>
                              <?php } ?>
                             <?php
                                 if(JVERSION < 4){
                                     $token = Factory::getSession()->getFormToken();
                                 }else{
                                     $token = Session::getFormToken();
                                 }
                             ?>
                             <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=resume.remove&cid[]='.$row->id.'&'.$token.'=1&callfrom=empapps'); ?>
                             <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                 <?php echo Text::_('Delete');?>
                             </a>
                             <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=resume.resumeenforcedelete&cid[]='.$row->id.'&'.$token.'=1&callfrom=empapps'); ?>
                             <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                 <?php echo Text::_('Force Delete');?>
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
                <input type="hidden" name="c" value="resume" />
                <input type="hidden" name="view" value="resume" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="layout" value="empapps" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="sortby" value="<?php echo $this->sort;?>"/>
                <input type="hidden" id="my_click" name="my_click" value=""/>
                <?php echo HTMLHelper::_('form.token'); ?>
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
        // reset form
        jQuery('#js-reset').click(function(e){
            jQuery('#resumetitle').val('');
            jQuery('#resumename').val('');
            jQuery('#desiredsalary').prop('selectedIndex', 0);
            jQuery('#location').val('');
            jQuery('#resumecategory').prop('selectedIndex', 0);
            jQuery('#resumetype').prop('selectedIndex', 0);
            jQuery('#dateto').val('');
            jQuery('#datefrom').val('');
            jQuery('#status').prop('selectedIndex', 0);
            jQuery('#isgfcombo').prop('selectedIndex', 0);
            e.preventDefault();
            jQuery('#adminForm').submit();
        });
    });

    jQuery(document).ready(function(){
      jQuery('input[id^="cb"]').click(function(){
        var checkbox = jQuery(this);
        var value = jQuery(checkbox).val();
        single_highlight(value);
      });
    });


  function single_highlight(id){
    if (jQuery("div.select_"+id+" span input:checked").length > 0){
      showBorder(id);
    }else{
      hideBorder(id);
    }
  }

  function showBorder(id){
    jQuery("div.select_"+id).addClass('ck_blue');
  }

  function hideBorder(id){
    jQuery("div.select_"+id).removeClass('ck_blue');
  }

  function highlightAll(){
    if (jQuery("span.datablockhead-left input").is(':checked') == false){
      jQuery("div.jsjobs-check-container").removeClass('ck_blue');
    }
    if (jQuery("span.datablockhead-left input").is(':checked') == true){
      jQuery("div.jsjobs-check-container").addClass('ck_blue');
      
    }
  }
</script>               

