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
                            <?php echo Text::_('Users'); ?>
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
                <?php echo Text::_('Users'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">

      <form action="index.php?option=com_jsjobs&c=userrole&view=userrole&layout=users" method="post" name="adminForm" id="adminForm">
            <div id="jsjobs_filter_wrapper">                    
             <div id="jsjobs-filter-main" class="width100">
                 <span class="jsjobs-filter"><input type="text" name="searchname"  placeholder="<?php echo Text::_('Name'); ?>" id="searchname" size="15" value="<?php if (isset($this->lists['searchname'])) echo $this->lists['searchname']; ?>"  /></span>                
                 <span class="jsjobs-filter"><input type="text" name="searchusername"  placeholder="<?php echo Text::_('Username'); ?>" id="searchusername" size="15" value="<?php if (isset($this->lists['searchusername'])) echo $this->lists['searchusername']; ?>"  /></span>                   
                 <span class="jsjobs-filter"><input type="text" name="searchcompany"  placeholder="<?php echo Text::_('Company'); ?>" id="searchcompany" size="15" value="<?php if (isset($this->lists['searchcompany'])) echo $this->lists['searchcompany']; ?>" /></span>                   
                 <span class="jsjobs-filter"><span class="default-hidden"><input type="text" name="searchresume"  placeholder="<?php echo Text::_('Resume Title'); ?>" id="searchresume" size="15" value="<?php if (isset($this->lists['searchresume'])) echo $this->lists['searchresume']; ?>" /></span></span>
                 <span class="jsjobs-filter"><span class="default-hidden"><?php echo $this->lists['usergroup']; ?></span></span>
                 <span class="jsjobs-filter"><span class="default-hidden"><?php echo $this->lists['searchrole']; ?></span></span>
                 <span class="jsjobs-filter"><span class="default-hidden"><?php echo $this->lists['status']; ?></span></span>
                 <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar',  $this->lists['datestart'], 'datestart', 'datestart', $js_dateformat, array('placeholder' => Text::_('Date Start'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
                 <span class="jsjobs-filter"><span class="default-hidden"><?php echo HTMLHelper::_('calendar', $this->lists['dateend'], 'dateend', 'dateend', $js_dateformat, array('placeholder' => Text::_('Date End'),'class' => 'inputbox validate-since', 'size' => '10', 'maxlength' => '19')); ?></span></span>
                        
                 <span class="jsjobs-filter jsjobs-filter-btn-wrp"><span id="showhidefilter"><span><?php echo Text::_('Show More')?></span></span></span>
                 <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" id="js-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                 <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" id="js-reset" onclick="document.getElementById('searchname').value = '';
                 document.getElementById('searchusername').value = '';
                 document.getElementById('searchcompany').value = '';
                 document.getElementById('searchresume').value = '';
                 document.getElementById('usergroup').value = '';
                 document.getElementById('searchrole').value = '';
                 document.getElementById('status').value = '';
                 document.getElementById('datestart').value = '';
                 document.getElementById('dateend').value = '';
                 this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
             </div>    
            </div>
            <div id="jsjobs-admin-heading">
                <div class="js-admin-head-txt"><?php echo Text::_('Users'); ?></div>
            </div>
<?php if(!empty($this->items)){ ?>
               <?php
                        $k = 0;
                        $common = $this->getJSModel('common');
                        for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                            $row = $this->items[$i];
                            $task = $row->block ? 'unblock' : 'block';
                            $link = 'index.php?option=com_jsjobs&c=userrole&view=userrole&layout=changerole&cid[]=' . $row->id . '';
                            ?>
                <div id="js-jobs-listwrapper">
                    <div id="jsjobs-top-left">
                    <?php if($row->roletitle=="employer"){  
                        $logo_path = $common->getCompanyLogo($row->companyid, $row->companylogo , $this->config);
                        }else{
                            if ($row->photo != '') {
                                $logo_path = "../".$this->config['data_directory'] . "/data/jobseeker/resume_" . $row->resumeid . "/photo/" . $row->photo;
                            } else {
                                $logo_path = "components/com_jsjobs/include/images/Users.png";
                            }
                        }
                    ?>
                    <span class="outer-circle"><img class="circle" src="<?php echo $logo_path;?>"/></span>
                    </div>
                    <div id="jsjobs-top-right" class="min-height-box">
                        <div id="jsjobslist-header" class="min-heigth-title">
                             <span class="listheadleft"><?php echo $row->name; ?></span>
                        </div>
                        <span class="js-innerspanleft txt">
                            <span class="title">
                                <?php if($row->roletitle == "employer") {?>
                                    <?php echo $row->companyname; ?>
                                <?php }elseif($row->roletitle == "jobseeker"){?>
                                    <?php echo $row->first_name . ' ' . $row->last_name; ?>
                                <?php } ?>
                            </span>
                        </span>
                        <span class="js-innerspanleft">
                            <span class="title"><?php echo Text::_('Username'); ?> :</span>&nbsp;<span class="value"><?php echo $row->username; ?></span>
                            <span class="title"><?php echo Text::_('Group'); ?> :</span>&nbsp;<span class="value"><?php echo Text::_($row->groupname); ?></span>
                        </span>
                        <span class="js-idbottom-top">
                            <?php if($row->roletitle == "employer"){?>
                             <span class="listheadright"><?php echo Text::_($row->roletitle); ?></span>
                             <?php }elseif($row->roletitle == "jobseeker"){ ?><span class="listheadright2"><?php echo Text::_($row->roletitle); ?></span><?php } ?> 
                            <span class="listheadright3">
                                 <?php if($row->block==0){?>
                                    <span class="listheadrightcorner-green"><?php echo Text::_('Enabled'); ?></span>
                                <?php }else{ ?>
                                    <span class="listheadrightcorner-red"><?php echo Text::_('Disabled'); ?></span>
                                <?php } ?>
                            </span>
                        </span>
                        <span class="js-idbottom"><span class="title"><?php echo Text::_('Id'); ?> :</span>&nbsp;<?php echo $row->id; ?></span>
                    </div>
                    <div id="jsjobs-bottom">
                        <a id="js-bottombutton" href="<?php echo $link; ?>"><img src="components/com_jsjobs/include/images/change-role-icon-2.png" alt="img">&nbsp;<?php echo Text::_('Change Role');?></a>
                    </div>
                </div>  
                <?php }
                        ?>
     
                <div id="jsjobs-pagination-wrapper">
                    <?php echo $this->pagination->getLimitBox(); ?>
                    <?php echo $this->pagination->getListFooter(); ?>
                </div>
            <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>

                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="task" value="view" />
                <input type="hidden" name="boxchecked" value="0" />
                <?php echo HTMLHelper::_('form.token'); ?>
            </form>
        </div>
    </div>    
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


