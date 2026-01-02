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
class TableSubCategory extends Table {

    var $id = null;
    var $categoryid = null;
    var $title = null;
    var $status = 0;
    var $ordering = null;
    var $isdefault = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_subcategories', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        $var = trim($this->title);
        if(empty($var)){
            return false;
        }
        return true;
    }

}

?>
