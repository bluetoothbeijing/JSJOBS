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
use Joomla\CMS\Table\Table;

// our table class for the application data
class TableJobSharingServicLog extends Table {

    var $id = null;
    var $uid = null;
    var $referenceid = null;
    var $event = null;
    var $eventtype = null;
    var $message = null;
    var $messagetype = null;
    var $datetime = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_sharing_service_log', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        return true;
    }

}

?>
