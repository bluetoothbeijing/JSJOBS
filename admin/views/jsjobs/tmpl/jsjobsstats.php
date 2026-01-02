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
use Joomla\CMS\Language\Text;

$document = Factory::getDocument();

?>
<div id="jsjobs-wrapper">
    <div id="jsjobs-menu">
        <?php include_once('components/com_jsjobs/views/menu.php'); ?>
    </div>    
    <div id="jsjobs-content">
        <div id="jsjobs-heading"><span id="heading-text"><?php echo Text::_('Control Panel'); ?></span></div>

<!--  -->
<table width="100%">
    <tr>
        <td width="100%" valign="top" cellpadding="0" cellspacing="0" >
            <div id="jsjobs_info_heading"><?php echo Text::_('JS Jobs Stats'); ?></div> 
            <table id="jsjobsstats_data_table" class="adminlist" cellpadding="0" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <th width="50%"></th>
                        <th width="25%"><?php echo Text::_('Total'); ?></th>
                        <th width="25%"><?php echo Text::_('Active'); ?></th>
                    </tr>
                </thead>
                <tr class="row0">
                    <td><strong><?php echo Text::_('Companies'); ?></strong></td>
                    <td><strong><?php if (isset($this->companies->totalcompanies)) echo $this->companies->totalcompanies; ?></strong></td>
                    <td><strong><?php if (isset($this->companies->activecompanies)) echo $this->companies->activecompanies; ?></strong></td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo Text::_('Jobs'); ?></strong></td>
                    <td><strong><?php if (isset($this->jobs->totaljobs)) echo $this->jobs->totaljobs; ?></strong></td>
                    <td><strong><?php if (isset($this->jobs->activejobs)) echo $this->jobs->activejobs; ?></strong></td>
                </tr>
                <tr class="row0">
                    <td><strong><?php echo Text::_('Resumes'); ?></strong></td>
                    <td><strong><?php if (isset($this->resumes->totalresumes)) echo $this->resumes->totalresumes; ?></strong></td>
                    <td><strong><?php if (isset($this->resumes->activeresumes)) echo $this->resumes->activeresumes; ?></strong></td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo Text::_('Gold Companies'); ?></strong></td>
                    <td><strong><?php if (isset($this->goldcompanies->totalgoldcompanies)) echo $this->goldcompanies->totalgoldcompanies; ?></strong></td>
                    <td><strong><?php if (isset($this->goldcompanies->activegoldcompanies)) echo $this->goldcompanies->activegoldcompanies; ?></strong></td>
                </tr>
                <tr class="row0">
                    <td><strong><?php echo Text::_('Featured Companies'); ?></strong></td>
                    <td><strong><?php if (isset($this->featuredcompanies->totalfeaturedcompanies)) echo $this->featuredcompanies->totalfeaturedcompanies; ?></strong></td>
                    <td><strong><?php if (isset($this->featuredcompanies->activefeaturedcompanies)) echo $this->featuredcompanies->activefeaturedcompanies; ?></strong></td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo Text::_('Gold Jobs'); ?></strong></td>
                    <td><strong><?php if (isset($this->goldjobs->totalgoldjobs)) echo $this->goldjobs->totalgoldjobs; ?></strong></td>
                    <td><strong><?php if (isset($this->goldjobs->activegoldjobs)) echo $this->goldjobs->activegoldjobs; ?></strong></td>
                </tr>
                <tr class="row0">
                    <td><strong><?php echo Text::_('Featured Jobs'); ?></strong></td>
                    <td><strong><?php if (isset($this->featuredjobs->totalfeaturedjobs)) echo $this->featuredjobs->totalfeaturedjobs; ?></strong></td>
                    <td><strong><?php if (isset($this->featuredjobs->activefeaturedjobs)) echo $this->featuredjobs->activefeaturedjobs; ?></strong></td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo Text::_('Gold Resumes'); ?></strong></td>
                    <td><strong><?php if (isset($this->goldresumes->totalgoldresumes)) echo $this->goldresumes->totalgoldresumes; ?></strong></td>
                    <td><strong><?php if (isset($this->goldresumes->activegoldresumes)) echo $this->goldresumes->activegoldresumes; ?></strong></td>
                </tr>
                <tr class="row0">
                    <td><strong><?php echo Text::_('Featured Resumes'); ?></strong></td>
                    <td><strong><?php if (isset($this->featuredresumes->totalfeaturedresumes)) echo $this->featuredresumes->totalfeaturedresumes; ?></strong></td>
                    <td><strong><?php if (isset($this->featuredresumes->activefeaturedresumes)) echo $this->featuredresumes->activefeaturedresumes; ?></strong></td>
                </tr>
                <tr class="row1">
                    <td><strong><?php echo Text::_('Employer'); ?></strong></td>
                    <td><strong><?php if (isset($this->totalemployer->totalemployer)) echo $this->totalemployer->totalemployer; ?></strong></td>
                    <td><strong><?php echo '-'; ?></strong></td>
                </tr>
                <tr class="row0">
                    <td><strong><?php echo Text::_('Job Seeker'); ?></strong></td>
                    <td><strong><?php if (isset($this->totaljobseeker->totaljobseeker)) echo $this->totaljobseeker->totaljobseeker; ?></strong></td>
                    <td><strong><?php echo '-'; ?></strong></td>
                </tr>

            </table>
        </td>
    </tr>
</table>                            
<!--  -->

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
