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
class TableDepartment extends Table {

    /** @var int Primary key */
    var $id = null;
    var $uid = null;
    var $companyid = null;
    var $name = null;
    var $alias = null;
    var $description = null;
    var $status = null;
    var $created = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_departments', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
      if (trim( $this->name ) == '') {
          return false;
      }
      if (trim( $this->description ) == '') {
          return false;
      }
        return true;
    }

}

?>
