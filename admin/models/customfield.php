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
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

jimport('joomla.application.component.model');
jimport('joomla.html.html');

class JSJobsModelCustomfield extends JSModel {

    var $_config = null;
    var $_defaultcurrency = null;
    var $_client_auth_key = null;
    var $_siteurl = null;
    var $_application = null;

    function __construct() {
        parent::__construct();
        $this->_client_auth_key = $this->getJSModel('jobsharing')->getClientAuthenticationKey();
        $this->_siteurl = Uri::root();
        $this->_defaultcurrency = $this->getJSModel('currency')->getDefaultCurrency();
        $user = Factory::getUser();
        $this->_uid = $user->id;
    }

    function getFieldsOrdering($fieldfor, $visitor = false) {
        if (is_numeric($fieldfor) === false)
            return false;

        $db = $this->getDBO();
        if ($fieldfor == 16 ) { // resume visitor case
            $fieldfor = 3;
            $query = "SELECT *,isvisitorpublished AS published
                        FROM `#__js_job_fieldsordering`
                        WHERE isvisitorpublished = 1 AND fieldfor =  " . $fieldfor
                    ." ORDER BY";
        } else {
            $published_field = "published = 1";
            if ($visitor == true) {
                $published_field = "isvisitorpublished = 1";
            }
            $query = "SELECT * FROM `#__js_job_fieldsordering`
                        WHERE " . $published_field . " AND fieldfor =  " . $fieldfor
                    . " ORDER BY";
        }
        if ($fieldfor == 3) // fields for resume
            $query.=" section ,";
        $query.=" ordering";


        $db->setQuery($query);
        $fields = $db->loadObjectList();
        return $fields;
    }

    function getFieldTitleByFieldAndFieldfor($field,$fieldfor){
        if(!is_numeric($fieldfor)) return false;
        $db = Factory::getDBO();
        $query = "SELECT fieldtitle FROM `#__js_job_fieldsordering` WHERE field = ".$db->Quote($field)." AND fieldfor = ".$fieldfor;
        $db->setQuery($query);
        $title = $db->loadResult();
        return $title;
    }
    
}
?>
