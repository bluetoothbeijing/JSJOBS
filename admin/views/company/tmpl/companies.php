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


/*if (JVERSION < 3) {
    HTMLHelper::_('behavior.mootools');
    $document->addScript('../components/com_jsjobs/js/jquery.js');
} else {
    HTMLHelper::_('bootstrap.framework');
    
}
*/
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
    '1' => array('value' => 1, 'text' => Text::_('Category')),
    '2' => array('value' => 2, 'text' => Text::_('Created')),
    '3' => array('value' => 3, 'text' => Text::_('Status')),
    '4' => array('value' => 4, 'text' => Text::_('Location')),
    '5' => array('value' => 5, 'text' => Text::_('Company Name')),
    '6' => array('value' => 6, 'text' => Text::_('Viewed')),);
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
                            <?php echo Text::_('Companies'); ?>
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
                <?php echo Text::_('Companies'); ?>
            </h1>
            <a onclick="Joomla.submitbutton('company.add');" class="jsjobs-add-link green-bg button" title="<?php echo Text::_('Add New').' '.Text::_('Company'); ?>">
                <img alt="<?php echo Text::_('Plus Icon'); ?>" src="components/com_jsjobs/include/images/plus.png"/>
                <?php echo Text::_('Add New').' '.Text::_('Company'); ?>
            </a>
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
                    <span class="selectallbox-text"><a onclick="if (document.adminForm.boxchecked.value == 0) { alert(Joomla.Text._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST')); } else { if (confirm('Are You Sure?')) { Joomla.submitbutton('company.remove'); } }"><?php echo Text::_("Delete"); ?></a></span>
                </div>
            </div>
            <span class="jsjobs-filter"><input type="text" name="companyname" placeholder="<?php echo Text::_('Company Name'); ?>" id="companyname" value="<?php if (isset($this->lists['companyname'])) echo $this->lists['companyname']; ?>" /></span>
            <span class="jsjobs-filter"><?php echo $this->lists['jobcategory']; ?></span>
            <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists["datefrom"], 'datefrom', 'datefrom', $js_dateformat, array('placeholder' => Text::_('Date From'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
            <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists["dateto"], 'dateto', 'dateto', $js_dateformat, array('placeholder' => Text::_('Date To'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
            <span class="jsjobs-filter"><?php echo $this->lists['status']; ?></span>
            <div class="jsjobs-filter-btn-wrp">
            <span class="jsjobs-filter jsjobs-filter-btn-wrp"><span id="showhidefilter"><span><?php echo Text::_('Show More')?></span></span></span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp">
                <button class="js-button js-form-search" id="js-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button>
            </span>
            <span class="jsjobs-filter jsjobs-filter-btn-wrp">
                <button class="js-button js-form-reset" id="js-reset"><?php echo Text::_('Reset'); ?></button>
            </span>
            </div>   
        </div>
        <div id="jsjobs-admin-heading">
            <div class="js-admin-head-txt"><?php echo Text::_('All Companies') ?></div>
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
        <?php if(!empty($this->items)){
                    //jimport('joomla.filter.output');
                    $k = 0;

                    $companydeletetask = 'companyenforcedelete';
                    $deleteimg = 'publish_x.png';
                    $Deletealt = Text::_('Delete');
                    $common = $this->getJSModel('common');
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        $checked = HTMLHelper::_('grid.id', $i, $row->id);
                        $link = OutputFilter::ampReplace('index.php?option=' . $this->option . '&task=company.edit&cid[]=' . $row->id);
                        ?>

                <div id="js-jobs-comp-listwrapper" class="jsjobs-check-container select_<?php echo $row->id;?>">
                    <div class="jsjobs-top-comp-wrp">
                        <div id="jsjobs-top-comp-left">
                           <?php 
                            $path = $common->getCompanyLogo($row->id, $row->logofilename , $this->config);
                           ?>
                            <a class="jsjobs-leftimg" href="<?php echo $link; ?>" ><img class="myfilelogoimg" src="<?php echo $path;?>"/></a>
                            <div id="jsjobslist-comp-body" class="jsjobs-resume-body-wrp">
                                <div class="datablock url" >
                                   <?php /*  <span class="title">
                                        <?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('url', 1 )); ?> :
                                    </span> */ ?>
                                    <a class="notbold" target="_blank" href="<?php echo $row->url; ?>"><?php echo $row->url; ?></a>
                                </div>
                                <div class="datablock name" >
                                    <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
                                </div>
                                <span class="datablock" >
                                    <span class="title">
                                        <?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('jobcategory', 1 )); ?> :
                                    </span>
                                    <span class="notbold color">
                                        <?php echo Text::_($row->cat_title); ?>
                                    </span>
                                </span>
                                <span class="datablock location" ><span class="title"> <?php echo Text::_($this->getJSModel('fieldordering')->getFieldTitleByFieldAndFieldfor('city', 1 )); ?> :</span><span class="notbold color"><?php echo $row->location; ?></span>
                                </span>
                            </div>
                           
                        </div>
                        <div id="jsjobs-top-comp-right" class="jsjobs-resume-right-wrp">
                            <div id="jsjobslist-comp-header">
                                <span class="datablockhead-right jsjobs-gold-wrp">
                                 <?php if($row->status==1){?>
                                    <span class="rightcorner">
                                        <span class="listheadrightcorner-green">
                                            <?php echo Text::_('Approved'); ?>
                                        </span>
                                    </span>
                                    <?php }else{ ?>
                                    <span class="rightcorner_red">
                                        <span class="listheadrightcorner-red">
                                            <?php echo Text::_('Rejected'); ?>
                                        </span>
                                    </span>
                                 <?php } ?>

                                </span>
                                <div id="innerheaderlefti" class="jsjobs-date-wrp">
                                    <span class="datablockhead-date"><?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?></span>
                                    <span>
                                      
                                   </span>
                                </div>
                              </div>
                        </div>
                    </div>
                    <div id="jsjobs-bottom-comp">
                        <div id="bottomleftnew">
                            <span class="bottomcheckbox ss_<?php echo $row->id;?>"><?php echo $checked; ?></span>
                        </div>
                        <div id="bottomrightnew">
                            <a class="js-bottomspan" href="index.php?option=com_jsjobs&c=department&view=department&layout=departments&md=<?php echo $row->id; ?>">
                                <?php echo Text::_('Departments');?>
                            </a>
                            <?php if( $this->getJSModel(VIRTUEMARTJSJOBS)->{VIRTUEMARTJSJOBSFUN}("UDZiWndkam9tc29jaWFsZ3RDV3VQ") ){ ?>
                                <a class="js-bottomspan" href="index.php?option=com_jsjobs&c=company&task=postcompanyonjomsocial&id=<?php echo $row->id; ?>&<?php echo Factory::getSession()->getFormToken(); ?>=1">
                                    <?php echo Text::_('Post On JomSocial');?>
                                </a>
                            <?php } ?>
                            <?php 
                                if(JVERSION < 4){
                                    $token = Factory::getSession()->getFormToken();
                                }else{
                                    $token = Session::getFormToken();
                                }
                            ?>
                            <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=company.companyenforcedelete&cid[]='.$row->id.'&'.$token.'=1'); ?>
                            <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                <?php echo Text::_('Force Delete'); ?> 
                            </a>
                            <?php $status_link = OutputFilter::ampReplace('index.php?option='.$this->option.'&task=company.remove&cid[]='.$row->id.'&'.$token.'=1'); ?>
                            <a class="js-bottomspan" onclick='return confirm("<?php echo Text::_('Are You Sure')."?"; ?>");' href="<?php echo $status_link;?>">
                                <?php echo Text::_('Delete'); ?> 
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
                <input type="hidden" name="c" value="company" />
                <input type="hidden" name="view" value="company" />
                <input type="hidden" name="layout" value="companies" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <input type="hidden" name="sortby" value="<?php echo $this->sort;?>"/>
                <input type="hidden" id="my_click" name="my_click" value=""/>
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

<script>
    jQuery(document).ready(function(){
        jQuery(".goldnew").hover(function(){
            jQuery(this).find(".goldnew-onhover").show();
        },function() {
            jQuery(this).find('span.goldnew-onhover').fadeOut("slow");
        });    
        jQuery(".featurednew").hover(function(){            
            jQuery(this).find("span.featurednew-onhover").show();
        },function() {
            jQuery(this).find('.featurednew-onhover').fadeOut("slow");
        });

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

        jQuery('#js-reset').click(function(e){
            jQuery('#companyname').val('');
            jQuery('#jobcategory').prop('selectedIndex', 0);
            jQuery('#dateto').val('');
            jQuery('#datefrom').val('');
            jQuery('#jobcategory').prop('selectedIndex', 0);
            jQuery('#status').prop('selectedIndex', 0);
            jQuery('#isgfcombo').prop('selectedIndex', 0);
            // fix for emptiying date fields
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
