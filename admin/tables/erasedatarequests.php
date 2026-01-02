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

class TableErasedatarequests extends Table {

	var $id = null;
	var $uid = null;
	var $subject = null;
	var $message = null;
	var $status = null;
	var $created = null;


	function __construct(&$db) {
        parent::__construct('#__js_job_erasedatarequests', 'id', $db);
    }

    /**
     * Validation
     *
     * @return boolean true if buffer is valid
     *
     */
    function check() {
        if (trim($this->message) == '') {
            $this->_error = "Message cannot be empty.";
            return false;
        }

        return true;
    }
}

?>
