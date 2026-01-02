<?php


/**
 * @Copyright Copyright (C) 2009-2010 Ahmad Bilal
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Company:     Buruj Solutions
 + Contact:     www.burujsolutions.com , info@burujsolutions.com
 * Created on:  Nov 22, 2010
 ^
 + Project:     JS Jobs
 ^ 
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;



jimport('joomla.application.component.view');

class JSJobsViewReport extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
        if ($layoutName == 'resume1') {
            require_once(JPATH_ROOT . '/components/com_jsjobs/models/resume.php');
            if (isset($user->groups[8]) OR isset($user->groups[7])) {
                $isadmin = 1;
            }
            $resumeid = Factory::getApplication()->input->get('rd', '');
            $myresume = Factory::getApplication()->input->get('nav', '');
            $folderid = Factory::getApplication()->input->get('fd', '');
            $sortvalue = $sort = Factory::getApplication()->input->get('sortby', false);
            $tabaction = Factory::getApplication()->input->get('ta', false);
            $show_only_section_that_have_value = $this->getJSModel('configuration')->getConfigValue('show_only_section_that_have_value');
            $resumeid = Factory::getApplication()->input->get('rd');
            if (is_numeric($resumeid) == true) {
                $resume_object = new JSJobsModelResume();
                $result = $resume_object->getResumeViewbyId($uid, false, $resumeid, $myresume, $sort, $tabaction);
            }
            $this->resume = $result['personal'];
            $this->addresses = $result['addresses'];
            $this->institutes = $result['institutes'];
            $this->employers = $result['employers'];
            $this->skills = $result['personal']->skills;
            $this->editor = $result['personal']->resume;
            $this->references = $result['references'];
            $this->languages = $result['languages'];
            $this->fieldsordering = $result['fieldsordering'];
            $this->userfields = $result['userfields'];
            $user = Factory::getUser();
            $this->isadmin = $isadmin;
        }
        $this->config = $config;
        $document = Factory::getDocument();
        $document->setTitle('Resume');
        parent::display();
    }

}

?>
