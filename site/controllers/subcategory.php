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
use Joomla\CMS\Language\Text;

jimport('joomla.application.component.controller');

class JSJobsControllerSubCategory extends JSController {


    function __construct() {
        $app = Factory::getApplication();
        $user = Factory::getUser();
        if ($user->guest) { // redirect user if not login
            $link = 'index.php?option=com_user';
            $this->setRedirect($link);
        }

        parent::__construct();
    }

    function listsubcategoriesforresume() {
        $val = Factory::getApplication()->input->get('categoryid');
        if(!$val) $val = Factory::getApplication()->input->get('val');
        $subcategory = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory->listSubCategoriesForResume($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function listsubcategories() {
        $val = Factory::getApplication()->input->get('categoryid');
        if(!$val) $val = Factory::getApplication()->input->get('val');
        $subcategory = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory->listSubCategories($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function listfiltersubcategories() {
        $val = Factory::getApplication()->input->get('val');
        $subcategory = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory->listFilterSubCategories($val);
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function listsubcategoriesforsearch() {
        $val = Factory::getApplication()->input->get('val');
        $modulecall = Factory::getApplication()->input->get('md');
        $subcategory = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory->listSubCategoriesForSearch($val);
        if ($modulecall) {
            if ($modulecall == 1) {
                //$return = Text::_('Sub Category') . "<br>" . $returnvalue;
                //$returnvalue = $return;
            }
        }
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function subcategoriesforsearch() {
        $val = Factory::getApplication()->input->get('val');
        $modulecall = Factory::getApplication()->input->get('md');
        $subcategory = $this->getmodel('Subcategory', 'JSJobsModel');
        $returnvalue = $subcategory->SubCategoriesForSearch($val);
        if ($modulecall) {
            if ($modulecall == 1) {
                $return = Text::_('Sub Category') . "<br>" . $returnvalue;
                $returnvalue = $return;
            }
        }
        echo $returnvalue;
        Factory::getApplication()->close();
    }

    function display($cachable = false, $urlparams = false) { // correct employer controller display function manually.
        $document = Factory::getDocument();
        $viewName = Factory::getApplication()->input->get('view', 'default');
        $layoutName = Factory::getApplication()->input->get('layout', 'default');
        $viewType = $document->getType();
        $view = $this->getView($viewName, $viewType);
        $view->setLayout($layoutName);
        $view->display();
    }

}

?>
    
