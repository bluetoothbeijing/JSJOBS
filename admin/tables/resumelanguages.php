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

class TableResumeLanguages extends Table {

    var $id = null;
    var $resumeid = null;
    var $language = null;
    var $language_reading = null;
    var $language_writing = null;
    var $language_understanding = null;
    var $language_where_learned = null;
    var $created = null;
    var $last_modified = null;
    var $params = null;
    

    function __construct(&$db) {
        parent::__construct('#__js_job_resumelanguages', 'id', $db);
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
