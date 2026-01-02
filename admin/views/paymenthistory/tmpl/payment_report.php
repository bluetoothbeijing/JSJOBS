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
                            <?php echo Text::_('Payment Report'); ?>
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
                <?php echo Text::_('Payment Report'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
      <form action="index.php" method="post" name="adminForm" id="adminForm">
          <div id="jsjobs_filter_wrapper">
                  <span class="jsjobs-filter"> <?php echo $this->lists['paymentfor']; ?></span>                   
                  <span class="jsjobs-filter"><?php echo $this->lists['paymentstatus']; ?></span>                   
                  <span class="jsjobs-filter"><?php echo HTMLHelper::_('calendar', $this->lists['searchstartdate'], 'prsearchstartdate', 'prsearchstartdate', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19' , 'placeholder' =>'Date From')); ?></span>
                  <span class="jsjobs-filter"> <?php echo HTMLHelper::_('calendar', $this->lists['searchenddate'], 'prsearchenddate', 'prsearchenddate', $js_dateformat, array('class' => 'inputbox', 'size' => '10', 'maxlength' => '19', 'placeholder' =>'Date To' )); ?></span>
                  <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-search" onclick="this.form.submit();"><?php echo Text::_('Search'); ?></button></span>
                  <span class="jsjobs-filter jsjobs-filter-btn-wrp"><button class="js-button js-form-reset" id="js-reset"><?php echo Text::_('Reset'); ?></button></span>
          </div>
          <script type="text/javascript">
                jQuery(document).ready(function(){
                    // reset form
                    jQuery('#js-reset').click(function(e){
                        jQuery('#prsearchstartdate').val('');
                        jQuery('#prsearchenddate').val('');
                        jQuery('#paymentfor').val('both');

                        e.preventDefault();
                        jQuery('#adminForm').submit();
                    });
                });

          </script>
            <?php if(!empty($this->items)){ ?>      
                <table class="adminlist" border="0" id="js-table">
                    <thead>
                        <tr>
                            <th class="title"><?php echo Text::_('Package'); ?></th>
                            <th class="title"><?php echo Text::_('Package For'); ?></th>
                            <th class="title"><?php echo Text::_('Name'); ?></th>
                            <th class="title"><?php echo Text::_('Payer Name'); ?></th>
                            <th class="title"><?php echo Text::_('Paid Amount'); ?></th>
                            <th class="center"><?php echo Text::_('Payment Status'); ?></th>
                            <th class="center"><?php echo Text::_('Created'); ?></th>
                        </tr>
                    </thead><tbody>
                    <?php
                    jimport('joomla.filter.output');
                    $k = 0;
                    for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                        $row = $this->items[$i];
                        ?>
                        <tr valign="top" class="<?php echo "row$k"; ?>">
                            <td aligh="left"><?php echo $row->packagetitle; ?></td>
                            <td aligh="left"><?php echo $row->packagefor; ?></td>
                            <td aligh="left">
                                <?php if ($row->packagefor == 'Employer') { ?>
                                    <a href="index.php?option=com_jsjobs&c=user&view=user&layout=userstate_companies&md=<?php echo $row->uid; ?>"><?php echo $row->buyername; ?></a>
                                <?php } else if ($row->packagefor == 'Job Seeker') { ?>
                                    <a href="index.php?option=com_jsjobs&c=user&view=user&layout=userstate_resumes&ruid=<?php echo $row->uid; ?>"><?php echo $row->buyername; ?></a>
                                <?php } ?>
                            </td>
                            <td aligh="left"><?php echo $row->payer_firstname; ?></td>
                            <td aligh="center"><?php if ($row->paidamount) echo $row->symbol . $row->paidamount; ?></td>
                            <td align="center"><?php if ($row->transactionverified == 1)
                                echo '<strong style="color:green;">'.Text::_('Verified').'</strong>';
                            else
                                echo '<strong style="color:red;">'.Text::_('Not Verified').'</strong>';
                            ?></td>
                            <td align="center"><?php echo HTMLHelper::_('date', $row->created, $this->config['date_format']); ?></td>

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
                <input type="hidden" name="c" value="paymenthistory" />
                <input type="hidden" name="view" value="paymenthistory" />
                <input type="hidden" name="layout" value="payment_report" />
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


