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


jimport('joomla.application.component.view');

class JSJobsViewOutput extends JSView {

    function display($tpl = NULL) {
        require_once(JPATH_COMPONENT . '/views/common.php');
        $viewtype = 'pdf';
        if ($layout == 'resumepdf') {
            $resumeid = $this->getJSModel('common')->parseId(Factory::getApplication()->input->get('rd', ''));
            $myresume = 6; // hard code b/c here we go for the pdf output
            $folderid = Factory::getApplication()->input->get('fd', '');
            $sortvalue = $sort = Factory::getApplication()->input->get('sortby', false);
            $tabaction = Factory::getApplication()->input->get('ta', false);
            $show_only_section_that_have_value = $this->getJSModel('configurations')->getConfigValue('show_only_section_that_have_value');
            if (is_numeric($resumeid) == true) {
                $resume_model = $this->getJSModel('resume');
                $result = $resume_model->getResumeViewbyId($uid, false, $resumeid, $myresume, $sort, $tabaction);
            }
            $this->resume = isset($result['personal']) ? $result['personal'] : '';
            $this->addresses = isset($result['addresses']) ? $result['addresses'] : '';
            $this->institutes = isset($result['institutes']) ? $result['institutes'] : '';
            $this->employers = isset($result['employers']) ? $result['employers'] : '';
            $this->skills = isset($result['skills']) ? $result['skills'] : '';
            $this->editor = isset($result['editor']) ? $result['editor'] : '';
            $this->references = isset($result['references']) ? $result['references'] : '';
            $this->languages = isset($result['languages']) ? $result['languages'] : '';
            $this->fieldsordering = isset($result['fieldsordering']) ? $result['fieldsordering'] : '';
            $this->userfields = isset($result['userfields']) ? $result['userfields'] : '';
        }
        $this->config = $config;
        $document = Factory::getDocument();
        $document->setTitle('Resume');
        parent::display();
    }

}

?>
