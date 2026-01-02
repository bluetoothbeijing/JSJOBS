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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;

class TableResumeFiles extends Table {

    var $id = null;
    var $resumeid = null;
    var $filename = null;
    var $filetype = null;
    var $filesize = null;
    var $filecontent = null;
    var $created = null;
    var $last_modified = null;

    function __construct(&$db) {
        parent::__construct('#__js_job_resumefiles', 'id', $db);
    }

    /**
     * Validation
     * 
     * @return boolean True if buffer is valid
     * 
     */
    function check() {
        if (trim($this->filename) == '') {
            $this->_error = Text::_("Filename can not be empty");
            return false;
        } else if (trim($this->filetype) == '') {
            $this->_error = Text::_("File type cannot be empty");
            return false;
        } else if (trim($this->filesize) == '') {
            $this->_error = Text::_("File size cannot be empty");
            return false;
        }

        return true;
    }

}

?>
