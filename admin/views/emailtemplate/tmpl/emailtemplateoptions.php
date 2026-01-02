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

jimport('joomla.html.pane');


//HTMLHelper::_('behavior.calendar');
HTMLHelper::_('behavior.formvalidator');
$document = Factory::getDocument();

?>


<script language="javascript">
// for joomla 1.6
    Joomla.submitbutton = function (task) {
        if (task == '') {
            return false;
        } else {
            if (task == 'emailtemplate.save') {
                returnvalue = validate_form(document.adminForm);
            } else
                returnvalue = true;
            if (returnvalue) {
                Joomla.submitform(task);
                return true;
            } else
                return false;
        }
    }
    function validate_form(f)
    {
        if (document.formvalidator.isValid(f)) {
            f.check.value = '<?php echo Factory::getSession()->getFormToken(); ?>';//send token
        }
        else {
            alert("<?php echo Text::_("Some values are not acceptable.  Please retry."); ?>");
            return false;
        }
        return true;
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
                            <?php echo Text::_('Email Template').' '.Text::_('Options'); ?>
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
                <?php echo Text::_('Email Template').' '.Text::_('Options'); ?>
            </h1>
        </div>
        <div id="jsstadmin-data-wrp" class="p0 bg-n bs-n">
            <table class="adminlist" border="0" id="js-table">
                <thead>
                    <tr>
                        <th width="30%" class="title"><?php echo Text::_('Title'); ?></th>
                        <th width="14%" class="center"><?php echo Text::_('Employer'); ?></th>
                        <th width="14%" class="center"><?php echo Text::_('Job Seeker'); ?></th>
                        <th width="14%" class="center"><?php echo Text::_('Admin'); ?></th>
                        <th width="14%" class="center"><?php echo Text::_('Job Seeker Visitor'); ?></th>
                        <th width="14%" class="center"><?php echo Text::_('Employer Visitor'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr valign="top" class="">
                        <td class="emailtemp_heading" colspan="6"> <?php echo Text::_('Company'); ?> </td>
                    </tr>
                    <?php
                    $img_yes = "tick.png";
                    $img_no = "cross.png";
                    $method = 'updateemailoption';
                    ?>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Add New Company'); ?> </td>
                        <?php
                            $data = $this->options['add_new_company'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=3"><img src="components/com_jsjobs/include/images/<?php if($data->admin == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Company Status'); ?> </td>
                        <?php 
                        $data = $this->options['company_status'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Company').' '.Text::_('Delete'); ?> </td>
                        <?php 
                        $data = $this->options['company_delete'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td class="emailtemp_heading" colspan="6"> <?php echo Text::_('Job'); ?> </td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Add New Job'); ?> </td>
                        <?php 
                        $data = $this->options['add_new_job'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=3"><img src="components/com_jsjobs/include/images/<?php if($data->admin == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">PRO</td>

                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Job Status'); ?> </td>
                        <?php 
                        $data = $this->options['job_status'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">PRO</td>

                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Job Delete'); ?> </td>
                        <?php 
                        $data = $this->options['job_delete'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td class="emailtemp_heading" colspan="6"> <?php echo Text::_('Resume'); ?> </td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Add New Resume'); ?> </td>
                        <?php 
                        $data = $this->options['add_new_resume'];
                        ?>

                        <td align="center">-</td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=2"><img src="components/com_jsjobs/include/images/<?php if($data->jobseeker == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=3"><img src="components/com_jsjobs/include/images/<?php if($data->admin == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">PRO</td>
                        <td class="center">-</td>

                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Resume Status'); ?> </td>
                        <?php 
                        $data = $this->options['resume_status'];
                        ?>                    
                        <td align="center">-</td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=2"><img src="components/com_jsjobs/include/images/<?php if($data->jobseeker == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">PRO</td>
                        <td class="center">-</td>

                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Resume').' '.Text::_('Delete'); ?> </td>
                        <?php 
                        $data = $this->options['resume_delete'];
                        ?>                    
                        <td align="center">-</td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=2"><img src="components/com_jsjobs/include/images/<?php if($data->jobseeker == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td class="emailtemp_heading" colspan="6"> <?php echo Text::_('Miscellaneous'); ?> </td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Job Apply'); ?> </td>
                        <?php 
                        $data = $this->options['jobapply_jobapply'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=2"><img src="components/com_jsjobs/include/images/<?php if($data->jobseeker == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=3"><img src="components/com_jsjobs/include/images/<?php if($data->admin == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Applied Resume Status Change'); ?> </td>
                        <?php 
                        $data = $this->options['applied_resume_status'];
                        ?>
                        <td align="center">-</td>
                        <td class="center">PRO</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Employer Purchase Package'); ?> </td>
                        <?php 
                        $data = $this->options['employer_purchase_package'];
                        ?>
                        <td align="center">PRO</td>
                        <td class="center">-</td>
                        <td class="center">PRO</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Jobseeker Purchase Package'); ?> </td>
                        <?php 
                        $data = $this->options['jobseeker_purchase_package'];
                        ?>
                        <td align="center">-</td>
                        <td class="center">PRO</td>
                        <td class="center">PRO</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Add New Department'); ?> </td>
                        <?php 
                        $data = $this->options['add_new_department'];
                        ?>
                        <td align="center">-</td>
                        <td class="center">-</td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=3"><img src="components/com_jsjobs/include/images/<?php if($data->admin == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <!-- GDPR EMAIL OPTIONS -->
                    <tr valign="top" class="">
                        <td class="emailtemp_heading" colspan="6"> <?php echo Text::_('GDPR'); ?> </td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Erase').'/'.Text::_('Delete user data'); ?> </td>
                        <?php 
                        $data = $this->options['erase_data'];
                        ?>
                        <td align="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=1"><img src="components/com_jsjobs/include/images/<?php if($data->employer == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=2"><img src="components/com_jsjobs/include/images/<?php if($data->jobseeker == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                    <tr valign="top" class="">
                        <td> <?php echo Text::_('Receive user erase data request'); ?> </td>
                        <?php 
                        $data = $this->options['receive_erase_data_request'];
                        ?>
                        <td align="center">-</td>
                        <td class="center">-</td>
                        <td class="center"><a href="index.php?option=com_jsjobs&c=emailtemplate&task=<?php echo $method; ?>&emailfor=<?php echo $data->emailfor; ?>&for=3"><img src="components/com_jsjobs/include/images/<?php if($data->admin == 1) echo $img_yes; else echo $img_no; ?>" /></a></td>
                        <td class="center">-</td>
                        <td class="center">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div><!-- email main content wrapper closed -->

    </div><!-- content closed -->
</div><!-- main wrapper closed -->
				
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
