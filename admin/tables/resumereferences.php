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

class TableResumeReferences extends Table {

    var $id = null;
    var $resumeid = null;
    var $reference = null;
    var $reference_name = null;
    var $reference_country = null;
    var $reference_state = null;
    var $reference_city = null;
    var $reference_zipcode = null;
    var $reference_address = null;
    var $reference_phone = null;
    var $reference_relation = null;
    var $reference_years = null;
    var $created = null;
    var $last_modified = null;
    var $params = null;
    

    function __construct(&$db) {
        parent::__construct('#__js_job_resumereferences', 'id', $db);
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
