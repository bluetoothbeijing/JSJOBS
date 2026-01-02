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
class TableFieldsOrdering extends Table {

    var $id = null;
    var $field = null;
    var $fieldtitle = null;
    var $ordering = null;
    var $search_ordering = null;
    var $section = null;
    var $fieldfor = null;
    var $published = null;
    var $sys = null;
    var $cannotunpublish = null;
    var $cannotshowonlisting = null;
    var $required = null;
    var $cannotsearch = null;
    var $isuserfield = null;
    var $userfieldtype = null;
    var $userfieldparams = null;
    var $search_user = null;
    var $search_visitor = null;
    var $showonlisting = null;
    var $depandant_field = null;
    var $j_script = null;
    var $size = null;
    var $maxlength = null;
    var $cols = null;
    var $rows = null;
    var $readonly = null;


    function __construct(&$db) {
        parent::__construct('#__js_job_fieldsordering', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        if (trim( $this->fieldtitle) == '') {
          return false;
        }
        if (trim($this->size) == '') {
            $this->size = NULL;
        }
        return true;
    }

}

?>
