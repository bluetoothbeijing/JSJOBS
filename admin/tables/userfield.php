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
class TableUserField extends Table {

    var $id = null;
    var $name = null;
    var $title = null;
    var $description = null;
    var $type = null;
    var $maxlength = null;
    var $size = null;
    var $required = null;
    var $ordering = null;
    var $cols = null;
    var $rows = null;
    var $value = null;
    var $default = null;
    var $published = null;
    var $fieldfor = null;
    var $readonly = null;
    var $calculated = null;
    var $sys = null;
    var $params = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_userfields', 'id', $db);
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
