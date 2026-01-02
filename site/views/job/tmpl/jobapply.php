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

?>

<script type="text/javascript">
  function getApplyNowByJobid(id){
    jQuery.post("<?php echo Uri::root(); ?>index.php?option=com_jsjobs&task=jobapply.applyjob",{jobid : id} , function(data){
        if(data){
            var response = jQuery.parseJSON(data);
            if (typeof response == 'object'){
                if (response[0] === 'popup'){
                    jQuery("div#jspopup_work_area").html(response[1]);
                    jQuery("div#js_jobs_main_popup_back").show();
                    jQuery("div#waiting-wrapper").hide();
                    jQuery("div#jspopup_title").html("<?php echo Text::_('Apply Now');?>");
                    jQuery("div#js_jobs_main_popup_area").slideDown('slow');
                }else{
                    window.location = response[1];
                }
            }else{
                if (response === false){
                    //the response was a string "false", parseJSON will convert it to boolean false
                }else{
                    //the response was something else
                }
            }
        }
    });
    jQuery("img#jspopup_image_close").click(function(e){
        jQuery("div#js_jobs_main_popup_area").slideUp("slow");
        setTimeout(function () {
            jQuery("div#js_jobs_main_popup_back").hide();
            jQuery("div#jspopup_work_area").html("");
        }, 700);
    });  
  }
</script>