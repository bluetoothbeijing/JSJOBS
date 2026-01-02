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
use Joomla\CMS\Language\Text;


$document = Factory::getDocument();
//Old css
$document->addStyleSheet('components/com_jsjobs/css/style.css', 'text/css');
require_once(JPATH_COMPONENT.'/css/style_color.php');

$language = Factory::getLanguage();
if($language->isRtl()){
    $document->addStyleSheet('components/com_jsjobs/css/style_rtl.css', 'text/css');
}

?>

<div id="js_jobs_main_wrapper">
	<div id="jsjobs_r_p_notfound">
		<div class="js_job_messages_image-wrp">
            <img class="js_job_messages_image" src="<?php echo Uri::root();?>components/com_jsjobs/images/error-icon/page-not-exist.png"/>
        </div>
		<div class="jstitle"><?php echo Text::_('Requested page not found').'...!';?></div>
		<div class="jsjob_button_cp">
			<a class="jsjob_anchor_em" href="index.php?option=com_jsjobs&view=employer&layout=controlpanel"><?php echo Text::_('Employer Control Panel');?></a>
			<a class="jsjob_anchor_js" href="index.php?option=com_jsjobs&view=jobseeker&layout=controlpanel"><?php echo Text::_('Jobseeker Control Panel');?></a>
		</div>
	</div>
</div>
