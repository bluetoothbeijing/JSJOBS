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
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;


jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class JSJobsViewEmailTemplate extends JSView {

    function display($tpl = null) {
        require_once JPATH_COMPONENT_ADMINISTRATOR . '/views/common.php';
//        layout start
        if ($layoutName == 'emailtemplate') {          // email template
            $templatefor = Factory::getApplication()->input->get('tf');
            $fieldfor = 0;
            switch ($templatefor) {
                case 'ew-cm' : $text = Text::_('New Company'); $fieldfor = 1;
                    break;
                case 'cm-ap' : $text = Text::_('Company Approval'); $fieldfor = 1;
                    break;
                case 'cm-rj' : $text = Text::_('Company Rejection'); $fieldfor = 1;
                    break;
                case 'ew-ob' : $text = Text::_('New Job').' ('.Text::_('Admin').')'; $fieldfor = 2;
                    break;
                case 'ew-ob-em' : $text = Text::_('New Job').' ('.Text::_('Employer').')'; $fieldfor = 2;
                    break;
                case 'ob-ap' : $text = Text::_('Job Approval'); $fieldfor = 2;
                    break;
                case 'ob-rj' : $text = Text::_('Job Rejecting'); $fieldfor = 2;
                    break;
                case 'ap-rs' : $text = Text::_('Applied Resume Status');
                    break;
                case 'ew-rm' : $text = Text::_('New Resume'); $fieldfor = 3;
                    break;
                case 'rm-ap' : $text = Text::_('Resume Approval'); $fieldfor = 3;
                    break;
                case 'rm-rj' : $text = Text::_('Resume Rejecting'); $fieldfor = 3;
                    break;
                case 'ba-ja' : $text = Text::_('Job Apply'); $fieldfor = 3;
                    break;
                case 'ew-md' : $text = Text::_('New Department');
                    break;
                case 'ew-rp' : $text = Text::_('Employer Buy Package');
                    break;
                case 'ew-js' : $text = Text::_('Job Seeker Buy Package');
                    break;
                case 'ms-sy' : $text = Text::_('Message');
                    break;
                case 'jb-at' : $text = Text::_('Job Alert');
                    break;
                case 'jb-at-vis' : $text = Text::_('Employer Visitor Job'); $fieldfor = 2;
                    break;
                case 'jb-to-fri' : $text = Text::_('Job To Friend'); $fieldfor = 2;
                    break;
                case 'jb-pkg-pur' : $text = Text::_('Job Seeker Package Purchased');
                    break;
                case 'emp-pkg-pur' : $text = Text::_('Employer Package Purchased');
                    break;
                case 'ew-rm-vis' : $text = Text::_('New Resume Visitor'); $fieldfor = 3;
                    break;
                case 'cm-dl' : $text = Text::_('Company Delete');
                    break;
                case 'ob-dl' : $text = Text::_('Job Delete');
                    break;
                case 'rm-dl' : $text = Text::_('Resume Delete');
                    break;
                case 'js-ja' : $text = Text::_('Job Apply for jobseeker'); $fieldfor = 2;
                    break;
                case 'u-da-de' : $text = Text::_('User data deleted'); break;
                case 'u-da-re' : $text = Text::_('User data erase request'); break;
            }
            ToolbarHelper::title(Text::_('Email Templates') . ' <small><small>[' . $text . '] </small></small>');
            ToolbarHelper::save('emailtemplate.saveemailtemplate');
            $template = $this->getJSModel('emailtemplate')->getTemplate($templatefor);
            $customfields = null;
            if($fieldfor){
                $customfields = $this->getJSModel('fieldordering')->getUserfieldsfor($fieldfor);
            }
            $this->template = $template;
            $this->templatefor = $templatefor;
            $this->customfields = $customfields;
        }elseif($layoutName == 'emailtemplateoptions'){
            ToolbarHelper::title(Text::_('Email Template Options'));
            $options = $this->getJSModel('emailtemplate')->getEmailTemplateOptionsForView();
            $this->options = $options;
        }
//        layout end

        $this->config = $config;
        //$this->application = $application;
        $this->theme = $theme;
        $this->option = $option;
        $this->uid = $uid;
        $this->msg = $msg;
        $this->isjobsharing = $_client_auth_key;

        parent::display($tpl);
    }

}

?>
