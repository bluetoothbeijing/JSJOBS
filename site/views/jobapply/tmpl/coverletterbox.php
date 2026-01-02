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

?>
<div id="black_wrapper_jobshortlist" style="display:none;"></div>
<div id="coverletterPopup" class="coverletterPopup">
</div>

<script type="text/javascript" src="<?php echo Uri::root(); ?>components/com_jsjobs/js/tinybox.js"></script>
<link media="screen" rel="stylesheet" href="<?php echo Uri::root(); ?>components/com_jsjobs/js/style.css" />
<script language="javascript">
    jQuery(document).ready(function () {
        jQuery("div#black_wrapper_jobshortlist").click(function () {
            jQuery("div#coverletterPopup").fadeOut(300, function () {
                jQuery('#black_wrapper_jobshortlist').fadeOut();
            });
        });
    });
</script>