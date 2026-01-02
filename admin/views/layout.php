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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

class JSJOBSlayout {

    static function getNoRecordFound() {
        $link = "index.php?option=com_jsjobs";
        $html = '
				<div class="js_job_error_messages_wrapper">
                    <div class="message1">
                    	<span>
                    		<img class="js_job_messages_image" src="'.Uri::root().'components/com_jsjobs/images/error-icon/no-record.png"/>
                    	</span>
                    </div>    
                    <div class="message2">
                     	 <span class="img">
                     	'. Text::_('Sorry') .'
                     	 </span> 
                     	 <span class="message-text">
                     	 	'. Text::_('Record not found') .'
                     	 </span>
                    </div>
				</div>
		';
        echo $html;
    }
}
?>
