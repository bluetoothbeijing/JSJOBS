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

$document = Factory::getDocument();

$status = array(
    '1' => Text::_('Approved'),
    '-1' => Text::_('Rejected'));
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
        <div id="jsjobs-heading"><a id="backimage" href="index.php?option=com_jsjobs&c=jsjobs&view=jsjobs&layout=controlpanel"><img src="components/com_jsjobs/include/images/back-icon.png" alt="<?php echo Text::_('Back');?>" ></a><span id="heading-text"><?php echo Text::_('Job Sharing Log'); ?></span></div>
            <form  method="post" name="adminForm" id="adminForm">
                 <div id="jsjobs_filter_wrapper" >           
                    <span class="jsjobs-filter"><input type="text" name="searchuid" placeholder="<?php echo Text::_('User Id'); ?>" id="searchuid" size="15" value="<?php if (isset($this->lists['uid'])) echo $this->lists['uid']; ?>" class="text_area"/></span>
                    <span class="jsjobs-filter"><input type="text" name="searchrefnumber" placeholder="<?php echo Text::_('Reference Number'); ?>" id="searchrefnumber" size="15" value="<?php if (isset($this->lists['refnumber'])) echo $this->lists['refnumber']; ?>" class="text_area"/></span>
                    <span class="jsjobs-filter"><input type="text" name="searchusername" placeholder="<?php echo Text::_('User Name'); ?>" id="searchusername" size="15" value="<?php if (isset($this->lists['username'])) echo $this->lists['username']; ?>" class="text_area"/></span>
                    <span class="jsjobs-filter" ><?php $startdate = !empty($this->lists['startdate']) ? date(str_replace('%', '', $js_dateformat), strtotime($this->lists['startdate'])) : '';  echo HTMLHelper::_('calendar', $startdate, 'searchstartdate', 'searchstartdate', $js_dateformat, array('class' => 'inputbox','placeholder' => 'Date Start', 'maxlength' => '19')); ?></span>
                    <span class="jsjobs-filter"><?php $enddate = !empty($this->lists['enddate']) ? date(str_replace('%', '', $js_dateformat), strtotime($this->lists['enddate'])) : '';  echo HTMLHelper::_('calendar', $enddate, 'searchenddate', 'searchenddate', $js_dateformat, array('class' => 'inputbox','placeholder' => 'Date End', 'maxlength' => '19')); ?></span>
                    
                    <span class="jsjobs-filter"><button class="js-button" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                    <span class="jsjobs-filter"><button class="js-button" onclick="document.getElementById('searchuid').value = '';document.getElementById('searchrefnumber').value = '';document.getElementById('searchusername').value = '';document.getElementById('searchstartdate').value = '';document.getElementById('searchenddate').value = ''; this.form.submit();"><?php echo Text::_('Reset'); ?></button></span>
                 </div>
                 <?php if(!empty($this->servicelog)){ ?>
                <div id="sharelog_wrapper">
                <?php
                    jimport('joomla.filter.output');
                    $k = 0;
                    for ($i = 0, $n = count($this->servicelog); $i < $n; $i++) {
                        $row = $this->servicelog[$i];
                        ?>
                    <div id="log_block_main">
                        <span id="left_blocks"><?php echo Text::_('User Id'); ?>:<span id="left_blocks_nested"><?php echo $row->uid; ?></span></span>
                        <span id="left_blocks"><?php echo Text::_('User Name'); ?>:<span id="left_blocks_nested"><?php echo $row->username; ?></span></span>
                        <span id="left_blocks"><?php echo Text::_('Ref Number'); ?>:<span id="left_blocks_nested"><?php echo $row->referenceid; ?></span></span>
                        <span id="right_blocks"><?php echo Text::_('Event'); ?>:<span id="right_blocks_nested"><?php echo $row->event; ?></span></span>
                        <span id="right_blocks"><?php echo Text::_('Event Type'); ?>:<span id="right_blocks_nested"><?php echo $row->eventtype; ?></span></span>
                        <span id="right_blocks"><?php echo Text::_('Date'); ?>:<span id="right_blocks_nested"><?php echo HTMLHelper::_('date', $row->datetime, str_replace('%', '', $js_dateformat)); ?></span></span>
                        <span id="mesg_block"><?php echo Text::_('Message'); ?>:</span><span id="mesg_block_next"><?php echo $row->message; ?></span>                                                
                    </div><!-- block main -->
                    <?php
                        $k = 1 - $k;
                    }
                    ?>
                </div><!-- share log wrapper -->    
                <div id="jsjobs-pagination-wrapper">
                <?php //echo $this->pagination->getListFooter(); ?>
                </div>
                <input type="hidden" name="option" value="<?php echo $this->option; ?>" />
                <input type="hidden" name="c" value="jobsharing" />
                <input type="hidden" name="view" value="jobsharing" />
                <input type="hidden" name="layout" value="jobsharelog" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="0" />
                <?php }else{ JSJOBSlayout::getNoRecordFound(); } ?>
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
