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
class TableJobApply extends Table {

    /** @var int Primary key */
    var $id = null;
    var $uid = null;
    var $jobid = null;
    var $cvid = null;
    var $coverletterid = null;
    var $apply_date = null;
    var $resumeview = null;
    var $comments = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_jobapply', 'id', $db);
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
